<?php
/**
 * Import Certificates to laravel_certificates_export
 *
 * Spawns a background PHP CLI worker — completely outside nginx/PHP-FPM timeouts.
 * Browser polls for progress via AJAX every 3 seconds.
 *
 * Usage: open this page in the browser while logged in as admin.
 * Run directly from CLI: php admin/import_certificates_worker.php
 */

ini_set('display_errors', 0);
date_default_timezone_set("Asia/Kolkata");

session_start();

include_once('include/classes/config.php');
include_once('include/classes/database_results.class.php');

$db      = new database_results();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
if ($user_id == '') {
    die("Unauthorized. Please login first.");
}

$exportDir  = __DIR__ . '/exports';
if (!is_dir($exportDir)) {
    mkdir($exportDir, 0755, true);
}

$statusFile = $exportDir . '/import_certificates.status';
$logFile    = $exportDir . '/import_certificates_error.log';

// ── AJAX: status check ───────────────────────────────────────
if (isset($_GET['check'])) {
    header('Content-Type: application/json');

    if (file_exists($statusFile)) {
        $data = @json_decode(file_get_contents($statusFile), true);
        if ($data) {
            echo json_encode($data);
            exit;
        }
    }

    echo json_encode(['state' => 'waiting', 'message' => 'Waiting for worker to start...', 'rows' => 0]);
    exit;
}

// ── Spawn background worker ──────────────────────────────────

// Clear previous status/log
@file_put_contents($statusFile, '');
@file_put_contents($logFile, '');

// Find PHP CLI binary
$phpBin = '/usr/bin/php';
if (!file_exists($phpBin)) $phpBin = '/usr/bin/php8.3';
if (!file_exists($phpBin)) $phpBin = trim(shell_exec('which php 2>/dev/null') ?: '');
if (empty($phpBin) || !file_exists($phpBin)) {
    die("Cannot find PHP CLI binary.");
}

$workerScript = __DIR__ . '/import_certificates_worker.php';
$workerLog    = $exportDir . '/import_worker_stdout.log';

$cmd = sprintf(
    'nohup %s %s > %s 2>&1 &',
    escapeshellarg($phpBin),
    escapeshellarg($workerScript),
    escapeshellarg($workerLog)
);
exec($cmd);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Importing Certificates...</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f2f8; margin: 0; padding: 30px 16px; }
        .box {
            max-width: 620px; margin: 40px auto; background: #fff;
            border-radius: 10px; padding: 36px 40px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.10);
        }
        .icon-area { text-align: center; margin-bottom: 10px; }
        .spinner { display: inline-block; width: 46px; height: 46px;
            border: 4px solid #dde; border-top: 4px solid #3f80ea;
            border-radius: 50%; animation: spin 0.9s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .icon-done  { color: #4CAF50; font-size: 54px; line-height: 1; }
        .icon-error { color: #f44336; font-size: 54px; line-height: 1; }

        h3 { text-align: center; margin: 10px 0 22px; font-size: 18px; color: #333; }

        /* ── stat cards ── */
        .stats { display: flex; gap: 12px; margin-bottom: 22px; }
        .stat {
            flex: 1; border-radius: 8px; padding: 14px 10px; text-align: center;
            background: #f5f7ff; border: 1px solid #e0e4f0;
        }
        .stat .label { font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
        .stat .value { font-size: 22px; font-weight: 700; color: #3f80ea; }
        .stat.done-card  .value { color: #4CAF50; }
        .stat.rem-card   .value { color: #FF9800; }
        .stat.total-card .value { color: #607D8B; }

        /* ── progress bar ── */
        .bar-wrap { background: #eee; border-radius: 20px; height: 18px; overflow: hidden; margin-bottom: 8px; }
        .bar-fill  { height: 100%; background: linear-gradient(90deg,#3f80ea,#6ab0ff);
                     border-radius: 20px; transition: width .5s ease; width: 0%; }
        .bar-label { text-align: center; font-size: 13px; color: #555; margin-bottom: 20px; }

        /* ── status / timer ── */
        #status { text-align: center; font-size: 14px; color: #666; margin: 0 0 6px; min-height: 20px; }
        #timer  { text-align: center; font-size: 12px; color: #aaa; margin: 0; }

        /* ── error box ── */
        #errBox { font-size: 12px; color: #f44336; margin-top: 16px; text-align: left;
                  background: #fff5f5; padding: 12px; border-radius: 6px;
                  display: none; max-height: 180px; overflow-y: auto; word-break: break-all; }
    </style>
</head>
<body>
<div class="box">

    <div class="icon-area" id="iconArea"><div class="spinner"></div></div>
    <h3 id="title">Importing Certificates to Laravel Table...</h3>

    <!-- Stat cards -->
    <div class="stats">
        <div class="stat total-card">
            <div class="label">Total</div>
            <div class="value" id="statTotal">—</div>
        </div>
        <div class="stat done-card">
            <div class="label">Imported</div>
            <div class="value" id="statDone">0</div>
        </div>
        <div class="stat rem-card">
            <div class="label">Remaining</div>
            <div class="value" id="statRem">—</div>
        </div>
    </div>

    <!-- Progress bar -->
    <div class="bar-wrap"><div class="bar-fill" id="barFill"></div></div>
    <div class="bar-label" id="barLabel">0%</div>

    <p id="status">Starting worker process...</p>
    <p id="timer">0s elapsed</p>
    <div id="errBox"></div>
</div>

<script>
var seconds = 0;

function fmt(n) { return Number(n).toLocaleString(); }

var timer = setInterval(function () {
    seconds += 3;
    document.getElementById('timer').textContent = seconds + 's elapsed';

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'import_certificates_for_laravel.php?check=1&_=' + Date.now());
    xhr.onload = function () {
        if (xhr.status !== 200) return;
        try {
            var r = JSON.parse(xhr.responseText);

            // ── Update stat cards & bar whenever we have data ──
            if (r.total > 0) {
                document.getElementById('statTotal').textContent = fmt(r.total);
                document.getElementById('statRem').textContent   = fmt(r.remaining);
            }
            if (r.rows >= 0) {
                document.getElementById('statDone').textContent  = fmt(r.rows);
            }
            var pct = r.percent || 0;
            document.getElementById('barFill').style.width  = pct + '%';
            document.getElementById('barLabel').textContent = pct + '%';

            // ── DONE ──
            if (r.state === 'done') {
                clearInterval(timer);
                document.getElementById('iconArea').innerHTML   = '<div class="icon-done">&#10003;</div>';
                document.getElementById('title').textContent    = 'Import Complete!';
                document.getElementById('title').style.color   = '#4CAF50';
                document.getElementById('statRem').textContent = '0';
                document.getElementById('barFill').style.width = '100%';
                document.getElementById('barLabel').textContent= '100%';
                document.getElementById('status').textContent  = fmt(r.rows) + ' rows imported successfully.';
                document.getElementById('timer').textContent   = 'Total time: ' + seconds + 's';
                return;
            }

            // ── ERROR ──
            if (r.state === 'error') {
                clearInterval(timer);
                document.getElementById('iconArea').innerHTML  = '<div class="icon-error">&#10007;</div>';
                document.getElementById('title').textContent   = 'Import Failed';
                document.getElementById('title').style.color  = '#f44336';
                document.getElementById('status').textContent = 'Worker encountered an error:';
                document.getElementById('errBox').style.display = 'block';
                document.getElementById('errBox').textContent = r.message || 'Unknown error';
                return;
            }

            // ── RUNNING / WAITING ──
            document.getElementById('status').textContent = r.message || 'Processing...';

        } catch (e) {}
    };
    xhr.send();
}, 3000);

// Safety timeout — 60 minutes
setTimeout(function () {
    clearInterval(timer);
    document.getElementById('iconArea').innerHTML  = '<div class="icon-error">&#10007;</div>';
    document.getElementById('title').textContent   = 'Timed Out';
    document.getElementById('status').textContent = 'Check admin/exports/import_certificates_error.log on the server.';
}, 3600000);
</script>
</body>
</html>
