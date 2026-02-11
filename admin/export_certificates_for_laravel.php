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
// Handle AJAX status check
// ============================================================
if (isset($_GET['check'])) {
    $checkFile = $exportDir . '/' . basename($_GET['check']);
    header('Content-Type: application/json');
    if (file_exists($checkFile)) {
        echo json_encode(['ready' => true]);
    } else {
        // Check if .tmp exists (still generating)
        $tmpExists = file_exists($checkFile . '.tmp');
        echo json_encode(['ready' => false, 'generating' => $tmpExists]);
    }
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

// Find PHP binary
$phpBin = PHP_BINARY ?: '/usr/bin/php';

// Worker script path
$workerScript = __DIR__ . '/export_certificates_worker.php';

// Spawn worker as a completely separate process (nohup + & = detached from PHP-FPM)
$cmd = sprintf(
    'nohup %s %s %s > %s/export_worker.log 2>&1 &',
    escapeshellarg($phpBin),
    escapeshellarg($workerScript),
    escapeshellarg($fileId),
    escapeshellarg($exportDir)
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
            max-width: 500px; margin: 100px auto; background: #fff;
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
    </style>
</head>
<body>
<div class="export-box">
    <div id="spinnerDiv"><div class="spinner"></div></div>
    <h3 id="title">Generating CSV Export...</h3>
    <p id="status">Please wait. Do not close this page.</p>
    <p id="counter">Checking... 0s</p>
</div>
<script>
var fileId = <?php echo json_encode($fileId); ?>;
var seconds = 0;
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
                    window.location.href = 'export_certificates_for_laravel.php?download=' + encodeURIComponent(fileId);
                } else if (resp.generating) {
                    document.getElementById('status').textContent = 'Generating CSV file... please wait.';
                }
            } catch(e) {}
        }
    };
    xhr.send();
}, 3000);

// Timeout after 10 minutes
setTimeout(function() {
    clearInterval(checkInterval);
    document.getElementById('spinnerDiv').innerHTML = '<div style="font-size:50px" class="error">&#10007;</div>';
    document.getElementById('title').textContent = 'Export Timed Out';
    document.getElementById('title').className = 'error';
    document.getElementById('status').textContent = 'The export took too long. Please try again or check admin/exports/export_error.log';
}, 600000);
</script>
</body>
</html>
