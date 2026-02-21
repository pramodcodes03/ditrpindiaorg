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
        body { font-family: Arial, sans-serif; background: #f5f7ff; }
        .box {
            max-width: 560px; margin: 80px auto; background: #fff;
            border-radius: 8px; padding: 40px; text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
        }
        .spinner { display: inline-block; width: 44px; height: 44px;
            border: 4px solid #ddd; border-top: 4px solid #3f80ea;
            border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .done  { color: #4CAF50; font-size: 52px; }
        .error { color: #f44336; font-size: 52px; }
        #status   { font-size: 16px; color: #555; margin: 12px 0; }
        #progress { font-size: 14px; color: #2196F3; font-weight: bold; margin: 6px 0; }
        #timer    { font-size: 13px; color: #999; }
        #errBox   { font-size: 12px; color: #f44336; margin-top: 12px; text-align: left;
                    background: #fff5f5; padding: 10px; border-radius: 4px;
                    display: none; max-height: 200px; overflow-y: auto; word-break: break-all; }
    </style>
</head>
<body>
<div class="box">
    <div id="iconArea"><div class="spinner"></div></div>
    <h3 id="title">Importing Certificates to Laravel Table...</h3>
    <p id="status">Starting worker process...</p>
    <p id="progress"></p>
    <p id="timer">0s</p>
    <div id="errBox"></div>
</div>
<script>
var seconds = 0;
var timer = setInterval(function () {
    seconds += 3;
    document.getElementById('timer').textContent = seconds + 's elapsed';

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'import_certificates_for_laravel.php?check=1&_=' + Date.now());
    xhr.onload = function () {
        if (xhr.status !== 200) return;
        try {
            var r = JSON.parse(xhr.responseText);

            if (r.state === 'done') {
                clearInterval(timer);
                document.getElementById('iconArea').innerHTML = '<div class="done">&#10003;</div>';
                document.getElementById('title').textContent  = 'Import Complete!';
                document.getElementById('title').style.color  = '#4CAF50';
                document.getElementById('status').textContent = r.rows + ' rows imported successfully.';
                document.getElementById('progress').textContent = '';
                document.getElementById('timer').textContent  = 'Total time: ' + seconds + 's';
                return;
            }

            if (r.state === 'error') {
                clearInterval(timer);
                document.getElementById('iconArea').innerHTML = '<div class="error">&#10007;</div>';
                document.getElementById('title').textContent  = 'Import Failed';
                document.getElementById('title').style.color  = '#f44336';
                document.getElementById('status').textContent = 'Worker encountered an error:';
                document.getElementById('errBox').style.display = 'block';
                document.getElementById('errBox').textContent = r.message || 'Unknown error';
                return;
            }

            // running / waiting
            document.getElementById('status').textContent   = r.message || 'Processing...';
            document.getElementById('progress').textContent = r.rows > 0 ? r.rows + ' rows imported' : '';

        } catch (e) {}
    };
    xhr.send();
}, 3000);

// Safety timeout — 60 minutes
setTimeout(function () {
    clearInterval(timer);
    document.getElementById('iconArea').innerHTML = '<div class="error">&#10007;</div>';
    document.getElementById('title').textContent  = 'Timed Out';
    document.getElementById('status').textContent = 'Check admin/exports/import_certificates_error.log on the server.';
}, 3600000);
</script>
</body>
</html>
