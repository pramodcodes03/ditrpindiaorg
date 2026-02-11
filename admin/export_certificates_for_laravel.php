<?php
/**
 * Export Certificate & Marksheet Data to CSV for Laravel Migration
 *
 * Spawns a separate PHP CLI process to generate the CSV file.
 * CLI process is NOT bound by PHP-FPM or nginx timeouts.
 * Browser shows a "please wait" page and polls for completion.
 */

ini_set('display_errors', 0);
date_default_timezone_set("Asia/Kolkata");

session_start();

include_once('include/classes/config.php');
include_once('include/classes/database_results.class.php');
include_once('include/classes/access.class.php');

$db = new database_results();
$access = new access();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
if ($user_id == '') {
    die("Unauthorized. Please login first.");
}

// Export directory
$exportDir = __DIR__ . '/exports';
if (!is_dir($exportDir)) {
    mkdir($exportDir, 0755, true);
}

// ============================================================
// Handle AJAX status check — reads .status file for details
// ============================================================
if (isset($_GET['check'])) {
    $checkFile = $exportDir . '/' . basename($_GET['check']);
    $statusFile = $checkFile . '.status';
    header('Content-Type: application/json');

    // Check if final CSV file exists (done)
    if (file_exists($checkFile)) {
        echo json_encode(['ready' => true, 'state' => 'done', 'message' => 'Export complete!']);
        exit;
    }

    // Check status file from worker
    if (file_exists($statusFile)) {
        $statusData = @json_decode(file_get_contents($statusFile), true);
        if ($statusData) {
            // If worker reported error
            if ($statusData['state'] === 'error') {
                echo json_encode(['ready' => false, 'state' => 'error', 'message' => $statusData['message'], 'rows' => 0]);
                exit;
            }
            // Worker is running
            echo json_encode([
                'ready' => false,
                'state' => 'running',
                'message' => $statusData['message'],
                'rows' => isset($statusData['rows']) ? $statusData['rows'] : 0,
                'generating' => true,
            ]);
            exit;
        }
    }

    // Check if .tmp exists (worker started but no status yet)
    $tmpExists = file_exists($checkFile . '.tmp');

    // Check worker log for any output
    $logContent = '';
    $workerLog = $exportDir . '/export_worker.log';
    if (file_exists($workerLog)) {
        $logContent = trim(file_get_contents($workerLog));
        // Only get last 500 chars
        if (strlen($logContent) > 500) {
            $logContent = substr($logContent, -500);
        }
    }

    echo json_encode([
        'ready' => false,
        'state' => $tmpExists ? 'running' : 'waiting',
        'message' => $tmpExists ? 'Worker is running...' : 'Waiting for worker to start...',
        'generating' => $tmpExists,
        'log' => $logContent,
    ]);
    exit;
}

// ============================================================
// Handle file download
// ============================================================
if (isset($_GET['download'])) {
    $dlFile = $exportDir . '/' . basename($_GET['download']);
    if (file_exists($dlFile)) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . basename($dlFile) . '"');
        header('Content-Length: ' . filesize($dlFile));
        header('Cache-Control: no-cache');
        readfile($dlFile);
        // Clean up status file
        @unlink($dlFile . '.status');
        @unlink($dlFile);
    } else {
        die("File not found.");
    }
    exit;
}

// ============================================================
// Generate unique file ID and spawn background worker
// ============================================================
$fileId = 'certificates_export_' . date('Y-m-d_His') . '_' . substr(uniqid(), -6) . '.csv';

// Find PHP CLI binary (PHP_BINARY returns php-fpm when running under FPM)
$phpBin = '/usr/bin/php';
if (!file_exists($phpBin)) {
    // Try php8.3 explicitly
    $phpBin = '/usr/bin/php8.3';
}
if (!file_exists($phpBin)) {
    $phpBin = trim(shell_exec('which php 2>/dev/null') ?: '');
}
if (empty($phpBin) || !file_exists($phpBin)) {
    die("Cannot find PHP CLI binary.");
}

// Verify it's actually the CLI binary, not FPM
$phpVersion = trim(shell_exec(escapeshellarg($phpBin) . ' -r "echo PHP_SAPI;" 2>/dev/null') ?: '');
if ($phpVersion !== 'cli') {
    // Try alternative paths
    foreach (['/usr/bin/php8.3', '/usr/bin/php8.2', '/usr/bin/php8.1', '/usr/local/bin/php'] as $altPath) {
        if (file_exists($altPath)) {
            $altSapi = trim(shell_exec(escapeshellarg($altPath) . ' -r "echo PHP_SAPI;" 2>/dev/null') ?: '');
            if ($altSapi === 'cli') {
                $phpBin = $altPath;
                $phpVersion = 'cli';
                break;
            }
        }
    }
    if ($phpVersion !== 'cli') {
        die("Cannot find PHP CLI binary. Found '$phpBin' but it is '$phpVersion', not 'cli'.");
    }
}

// Worker script path
$workerScript = __DIR__ . '/export_certificates_worker.php';

// Clear old worker log
$workerLog = $exportDir . '/export_worker.log';
@file_put_contents($workerLog, '');

// Spawn worker as a completely separate process (nohup + & = detached from PHP-FPM)
$cmd = sprintf(
    'nohup %s %s %s > %s 2>&1 &',
    escapeshellarg($phpBin),
    escapeshellarg($workerScript),
    escapeshellarg($fileId),
    escapeshellarg($workerLog)
);
exec($cmd);

// ============================================================
// Show "please wait" page — responds INSTANTLY
// ============================================================
?>
<!DOCTYPE html>
<html>
<head>
    <title>Exporting Certificates...</title>
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f7ff; }
        .export-box {
            max-width: 550px; margin: 80px auto; background: #fff;
            border-radius: 8px; padding: 40px; text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .spinner { display: inline-block; width: 40px; height: 40px;
            border: 4px solid #ddd; border-top: 4px solid #4CAF50;
            border-radius: 50%; animation: spin 1s linear infinite; margin-bottom: 20px; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .done { color: #4CAF50; }
        .error { color: #f44336; }
        #status { font-size: 16px; color: #555; margin: 15px 0; }
        #counter { font-size: 13px; color: #999; }
        #progress { font-size: 14px; color: #2196F3; margin: 10px 0; font-weight: bold; }
        #errorDetail { font-size: 12px; color: #f44336; margin: 10px 0; text-align: left;
            background: #fff5f5; padding: 10px; border-radius: 4px; display: none;
            max-height: 200px; overflow-y: auto; word-break: break-all; }
        #logOutput { font-size: 11px; color: #666; margin: 10px 0; text-align: left;
            background: #f5f5f5; padding: 10px; border-radius: 4px; display: none;
            max-height: 150px; overflow-y: auto; word-break: break-all; font-family: monospace; }
    </style>
</head>
<body>
<div class="export-box">
    <div id="spinnerDiv"><div class="spinner"></div></div>
    <h3 id="title">Generating CSV Export...</h3>
    <p id="status">Starting worker process...</p>
    <p id="progress"></p>
    <p id="counter">Checking... 0s</p>
    <div id="errorDetail"></div>
    <div id="logOutput"></div>
</div>
<script>
var fileId = <?php echo json_encode($fileId); ?>;
var seconds = 0;
var noProgressCount = 0;
var checkInterval = setInterval(function() {
    seconds += 3;
    document.getElementById('counter').textContent = 'Checking... ' + seconds + 's';

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'export_certificates_for_laravel.php?check=' + encodeURIComponent(fileId));
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var resp = JSON.parse(xhr.responseText);

                if (resp.ready) {
                    clearInterval(checkInterval);
                    document.getElementById('spinnerDiv').innerHTML = '<div style="font-size:50px" class="done">&#10003;</div>';
                    document.getElementById('title').textContent = 'Export Complete!';
                    document.getElementById('title').className = 'done';
                    document.getElementById('status').innerHTML = 'Download starting...';
                    document.getElementById('counter').textContent = 'Total time: ' + seconds + 's';
                    document.getElementById('progress').textContent = '';
                    window.location.href = 'export_certificates_for_laravel.php?download=' + encodeURIComponent(fileId);
                    return;
                }

                if (resp.state === 'error') {
                    clearInterval(checkInterval);
                    document.getElementById('spinnerDiv').innerHTML = '<div style="font-size:50px" class="error">&#10007;</div>';
                    document.getElementById('title').textContent = 'Export Failed';
                    document.getElementById('title').className = 'error';
                    document.getElementById('status').textContent = 'The worker encountered an error:';
                    document.getElementById('errorDetail').style.display = 'block';
                    document.getElementById('errorDetail').textContent = resp.message || 'Unknown error';
                    return;
                }

                if (resp.state === 'running') {
                    document.getElementById('status').textContent = resp.message || 'Processing...';
                    if (resp.rows > 0) {
                        document.getElementById('progress').textContent = resp.rows + ' rows exported';
                    }
                    noProgressCount = 0;
                } else if (resp.state === 'waiting') {
                    noProgressCount++;
                    document.getElementById('status').textContent = 'Waiting for worker to start...';
                    // Show log if worker hasn't started after 15s
                    if (resp.log && noProgressCount > 5) {
                        document.getElementById('logOutput').style.display = 'block';
                        document.getElementById('logOutput').textContent = 'Worker log: ' + resp.log;
                    }
                }
            } catch(e) {}
        }
    };
    xhr.send();
}, 3000);

// Timeout after 30 minutes (background worker has no timeout, but UI should eventually give up)
setTimeout(function() {
    clearInterval(checkInterval);
    document.getElementById('spinnerDiv').innerHTML = '<div style="font-size:50px" class="error">&#10007;</div>';
    document.getElementById('title').textContent = 'Export Timed Out';
    document.getElementById('title').className = 'error';
    document.getElementById('status').textContent = 'The export took too long. Check admin/exports/export_error.log and export_worker.log on the server.';
}, 1800000);
</script>
</body>
</html>
