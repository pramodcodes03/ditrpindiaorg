<?php
/**
 * Export Certificate & Marksheet Data to CSV for Laravel Migration
 *
 * TWO-PHASE approach to avoid 504 Gateway Timeout:
 *   Phase 1: Responds INSTANTLY with a "please wait" page, then generates
 *            the CSV file in background (after fastcgi_finish_request).
 *   Phase 2: Browser auto-checks every 3 seconds. When file is ready,
 *            triggers download automatically.
 */

ini_set("memory_limit", "512M");
ini_set('display_errors', 0);
set_time_limit(0);
ignore_user_abort(true);
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
        echo json_encode(['ready' => true, 'url' => 'exports/' . basename($_GET['check'])]);
    } else {
        echo json_encode(['ready' => false]);
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
        // Delete file after download
        @unlink($dlFile);
    } else {
        die("File not found.");
    }
    exit;
}

// ============================================================
// PHASE 1: Send "please wait" page INSTANTLY, then generate CSV
// ============================================================
$fileId = 'certificates_export_' . date('Y-m-d_His') . '_' . substr(uniqid(), -6) . '.csv';

// Send the HTML response to the browser immediately
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
            var resp = JSON.parse(xhr.responseText);
            if (resp.ready) {
                clearInterval(checkInterval);
                document.getElementById('spinnerDiv').innerHTML = '<div style="font-size:50px" class="done">&#10003;</div>';
                document.getElementById('title').textContent = 'Export Complete!';
                document.getElementById('title').className = 'done';
                document.getElementById('status').innerHTML = 'Download starting...';
                document.getElementById('counter').textContent = 'Total time: ' + seconds + 's';
                // Trigger download
                window.location.href = 'export_certificates_for_laravel.php?download=' + encodeURIComponent(fileId);
            }
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
    document.getElementById('status').textContent = 'The export took too long. Please try again.';
}, 600000);
</script>
</body>
</html>
<?php

// ============================================================
// Flush the HTML page to the browser and close the connection
// PHP continues running in the background to generate the CSV
// ============================================================
if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
} else {
    // Fallback: flush everything and close connection manually
    header('Connection: close');
    header('Content-Length: ' . ob_get_length());
    ob_end_flush();
    flush();
}

// ============================================================
// PHASE 2: Generate CSV file in background (no timeout concern)
// ============================================================
$tmpFile = $exportDir . '/' . $fileId;
$fp = fopen($tmpFile . '.tmp', 'w');

// UTF-8 BOM
fwrite($fp, "\xEF\xBB\xBF");

// CSV header row
fputcsv($fp, [
    'certificate_details_id','certificate_request_id','certificate_request_master_id',
    'certificate_file','certificate_serial_no','certificate_prefix','certificate_no',
    'issue_date','issue_date_format','qr_file',
    'student_id','student_code','student_name','student_fname','student_mname','student_lname',
    'student_mother_name','student_father_name','son_of',
    'student_photo','student_sign',
    'student_dob','student_dob_format','stud_id_proof_type','stud_id_proof_number',
    'institute_id','institute_code','institute_name','owner_name',
    'institute_city','institute_state','institute_address','institute_email','institute_mobile',
    'course_id','multi_sub_course_id','typing_course_id',
    'course_name','course_name_computed','multi_sub_course_name',
    'course_duration','multi_sub_course_duration','typing_course_duration',
    'exam_title','exam_type','exam_result_id','exam_result_final_id',
    'subject','objective_marks','practical_marks','marks_per','grade',
    'result_status','exam_fees','request_status',
    'marksheet_subjects_json',
    'active','delete_flag','created_on','created_by','request_created_on',
]);

// Two DB connections
$conn1 = $db->mysqli;
$conn2 = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
$conn2->set_charset("utf8");

// Cursor-based chunking
$chunkSize = 200;
$lastId = 0;

while (true) {

    $sql = "SELECT
        cd.CERTIFICATE_DETAILS_ID,
        cd.CERTIFICATE_REQUEST_ID,
        cd.CERTIFICATE_FILE,
        cd.CERTIFICATE_SERIAL_NO,
        cd.CERTIFICATE_PREFIX,
        cd.CERTIFICATE_NO,
        DATE_FORMAT(cd.ISSUE_DATE, '%Y-%m-%d') AS ISSUE_DATE,
        DATE_FORMAT(cd.ISSUE_DATE, '%d-%m-%Y') AS ISSUE_DATE_FORMAT,
        cd.INSTITUTE_ID,
        cd.STUDENT_ID,
        cd.COURSE_ID,
        cd.MULTI_SUB_COURSE_ID,
        cd.TYPING_COURSE_ID,
        cd.INSTITUTE_NAME,
        cd.STUDENT_NAME,
        cd.STUDENT_FNAME,
        cd.STUDENT_MNAME,
        cd.STUDENT_LNAME,
        cd.STUDENT_MOTHER_NAME,
        cd.STUDENT_FATHER_NAME,
        cd.STUDENT_PHOTO,
        cd.STUDENT_SIGN,
        DATE_FORMAT(cd.STUDENT_DOB, '%Y-%m-%d') AS STUDENT_DOB,
        DATE_FORMAT(cd.STUDENT_DOB, '%d.%m.%Y') AS STUDENT_DOB_FORMAT,
        cd.STUD_ID_PROOF_TYPE,
        cd.STUD_ID_PROOF_NUMBER,
        cd.COURSE_NAME,
        cd.SUBJECT,
        cd.PRACTICAL_MARKS,
        cd.OBJECTIVE_MARKS,
        cd.GRADE,
        cd.MARKS_PER,
        cd.QRFILE,
        cd.ACTIVE,
        cd.DELETE_FLAG,
        DATE_FORMAT(cd.CREATED_ON, '%Y-%m-%d %H:%i:%s') AS CREATED_ON,
        cd.CREATED_BY,
        sd.STUDENT_CODE,
        sd.SONOF,
        cd.STUDENT_PHOTO AS STUDENT_PHOTO_LIVE,
        cd.STUDENT_SIGN AS STUDENT_SIGN_LIVE,
        inst.INSTITUTE_CODE,
        inst.INSTITUTE_NAME AS INSTITUTE_NAME_LIVE,
        inst.INSTITUTE_OWNER_NAME AS OWNER_NAME,
        inst.EMAIL AS INSTITUTE_EMAIL,
        inst.MOBILE AS INSTITUTE_MOBILE,
        inst.ADDRESS_LINE1 AS INSTITUTE_ADDRESS,
        cm.CITY_NAME AS INSTITUTE_CITY,
        sm.STATE_NAME AS INSTITUTE_STATE,
        co.COURSE_NAME AS COURSE_NAME_COMPUTED,
        UPPER(co.COURSE_DURATION) AS COURSE_DURATION,
        msc.MULTI_SUB_COURSE_NAME,
        UPPER(msc.MULTI_SUB_COURSE_DURATION) AS MULTI_SUB_COURSE_DURATION,
        UPPER(ct.TYPING_COURSE_DURATION) AS TYPING_COURSE_DURATION,
        cr.EXAM_TITLE,
        cr.EXAM_TYPE,
        cr.EXAM_FEES,
        cr.RESULT_STATUS,
        cr.REQUEST_STATUS,
        cr.EXAM_RESULT_ID,
        cr.EXAM_RESULT_FINAL_ID,
        cr.CERTIFICATE_REQUEST_MASTER_ID,
        DATE_FORMAT(cr.CREATED_ON, '%Y-%m-%d %H:%i:%s') AS REQUEST_CREATED_ON
    FROM certificates_details cd
    LEFT JOIN certificate_requests cr ON cd.CERTIFICATE_REQUEST_ID = cr.CERTIFICATE_REQUEST_ID
    LEFT JOIN student_details sd ON cd.STUDENT_ID = sd.STUDENT_ID
    LEFT JOIN institute_details inst ON cd.INSTITUTE_ID = inst.INSTITUTE_ID
    LEFT JOIN city_master cm ON inst.CITY = cm.CITY_ID
    LEFT JOIN states_master sm ON inst.STATE = sm.STATE_ID
    LEFT JOIN courses co ON cd.COURSE_ID = co.COURSE_ID
    LEFT JOIN multi_sub_courses msc ON cd.MULTI_SUB_COURSE_ID = msc.MULTI_SUB_COURSE_ID
    LEFT JOIN courses_typing ct ON cd.TYPING_COURSE_ID = ct.TYPING_COURSE_ID
    WHERE cd.DELETE_FLAG = 0 AND cd.CERTIFICATE_DETAILS_ID > $lastId
    ORDER BY cd.CERTIFICATE_DETAILS_ID ASC
    LIMIT $chunkSize";

    $res = $conn1->query($sql);
    if (!$res || $res->num_rows == 0) {
        break;
    }

    $rows = [];
    $msReqIds = [];
    $tyReqIds = [];

    while ($data = $res->fetch_assoc()) {
        $rows[] = $data;
        $lastId = $data['CERTIFICATE_DETAILS_ID'];

        if (!empty($data['MULTI_SUB_COURSE_ID']) && $data['MULTI_SUB_COURSE_ID'] != 0 && !empty($data['CERTIFICATE_REQUEST_ID'])) {
            $msReqIds[] = (int)$data['CERTIFICATE_REQUEST_ID'];
        }
        if (!empty($data['TYPING_COURSE_ID']) && $data['TYPING_COURSE_ID'] != 0 && !empty($data['CERTIFICATE_REQUEST_ID'])) {
            $tyReqIds[] = (int)$data['CERTIFICATE_REQUEST_ID'];
        }
    }
    $chunkCount = $res->num_rows;
    $res->free();

    // Fetch marksheet subjects for this chunk
    $multiSubMarks = [];
    $msReqIds = array_unique($msReqIds);
    if (!empty($msReqIds)) {
        $idList = implode(',', $msReqIds);
        $msSql = "SELECT
            creq.CERTIFICATE_REQUEST_ID,
            erf.STUDENT_ID,
            erf.INSTITUTE_ID,
            mscs.COURSE_SUBJECT_NAME AS SUBJECT_NAME,
            er.EXAM_TITLE,
            er.MARKS_OBTAINED,
            er.PRACTICAL_MARKS
        FROM certificate_requests creq
        INNER JOIN multi_sub_exam_result_final erf ON creq.EXAM_RESULT_FINAL_ID = erf.EXAM_RESULT_FINAL_ID
        INNER JOIN multi_sub_exam_result er ON er.STUDENT_ID = erf.STUDENT_ID AND er.INSTITUTE_ID = erf.INSTITUTE_ID AND er.STUD_COURSE_ID = erf.STUD_COURSE_ID
        LEFT JOIN multi_sub_courses_subjects mscs ON er.STUDENT_SUBJECT_ID = mscs.COURSE_SUBJECT_ID
        WHERE creq.CERTIFICATE_REQUEST_ID IN ($idList)
        AND er.DELETE_FLAG = 0 AND er.ACTIVE = 1
        ORDER BY er.EXAM_RESULT_ID ASC";
        $msRes = $conn2->query($msSql);
        if ($msRes && $msRes->num_rows > 0) {
            while ($row = $msRes->fetch_assoc()) {
                $key = $row['CERTIFICATE_REQUEST_ID'] . '_' . $row['STUDENT_ID'] . '_' . $row['INSTITUTE_ID'];
                $multiSubMarks[$key][] = [
                    'type' => 'multi_sub',
                    'subject_name' => $row['SUBJECT_NAME'],
                    'exam_title' => $row['EXAM_TITLE'],
                    'objective_marks' => (float)$row['MARKS_OBTAINED'],
                    'practical_marks' => (float)$row['PRACTICAL_MARKS'],
                    'total_marks' => (float)($row['MARKS_OBTAINED'] + $row['PRACTICAL_MARKS']),
                ];
            }
            $msRes->free();
        }
    }

    $typingMarks = [];
    $tyReqIds = array_unique($tyReqIds);
    if (!empty($tyReqIds)) {
        $idList = implode(',', $tyReqIds);
        $tySql = "SELECT
            creq.CERTIFICATE_REQUEST_ID,
            ert.STUDENT_ID,
            ert.INSTITUTE_ID,
            cts.TYPING_COURSE_SUBJECT_NAME AS SUBJECT_NAME,
            er.EXAM_TITLE,
            er.MARKS_OBTAINED,
            er.EXAM_TOTAL_MARKS,
            er.TOTAL_MARKS,
            er.MINIMUM_MARKS,
            cts.TYPING_COURSE_SPEED
        FROM certificate_requests creq
        INNER JOIN course_typing_exam_result_final ert ON creq.EXAM_RESULT_TYPING_ID = ert.EXAM_RESULT_FINAL_ID
        INNER JOIN course_typing_exam_result er ON er.STUDENT_ID = ert.STUDENT_ID AND er.INSTITUTE_ID = ert.INSTITUTE_ID AND er.STUD_COURSE_ID = ert.STUD_COURSE_ID
        LEFT JOIN courses_typing_subjects cts ON er.STUDENT_SUBJECT_ID = cts.TYPING_COURSE_SUBJECT_ID
        WHERE creq.CERTIFICATE_REQUEST_ID IN ($idList)
        AND er.DELETE_FLAG = 0
        ORDER BY er.EXAM_RESULT_ID ASC";
        $tyRes = $conn2->query($tySql);
        if ($tyRes && $tyRes->num_rows > 0) {
            while ($row = $tyRes->fetch_assoc()) {
                $key = $row['CERTIFICATE_REQUEST_ID'] . '_' . $row['STUDENT_ID'] . '_' . $row['INSTITUTE_ID'];
                $typingMarks[$key][] = [
                    'type' => 'typing',
                    'subject_name' => $row['SUBJECT_NAME'],
                    'exam_title' => $row['EXAM_TITLE'],
                    'speed_wpm' => (int)$row['TYPING_COURSE_SPEED'],
                    'minimum_marks' => (float)$row['MINIMUM_MARKS'],
                    'exam_total_marks' => (float)$row['EXAM_TOTAL_MARKS'],
                    'marks_obtained' => (float)$row['MARKS_OBTAINED'],
                    'total_marks' => (float)$row['TOTAL_MARKS'],
                ];
            }
            $tyRes->free();
        }
    }

    // Write CSV rows for this chunk
    foreach ($rows as $data) {
        $marksheetData = [];

        if (!empty($data['COURSE_ID']) && $data['COURSE_ID'] != 0 && !empty($data['SUBJECT'])) {
            $marksheetData[] = [
                'type' => 'single',
                'subject_name' => $data['SUBJECT'],
                'objective_marks' => (float)$data['OBJECTIVE_MARKS'],
                'practical_marks' => (float)$data['PRACTICAL_MARKS'],
                'total_marks' => (float)($data['OBJECTIVE_MARKS'] + $data['PRACTICAL_MARKS']),
            ];
        }

        if (!empty($data['MULTI_SUB_COURSE_ID']) && $data['MULTI_SUB_COURSE_ID'] != 0) {
            $key = $data['CERTIFICATE_REQUEST_ID'] . '_' . $data['STUDENT_ID'] . '_' . $data['INSTITUTE_ID'];
            if (isset($multiSubMarks[$key])) {
                $marksheetData = array_merge($marksheetData, $multiSubMarks[$key]);
            }
        }

        if (!empty($data['TYPING_COURSE_ID']) && $data['TYPING_COURSE_ID'] != 0) {
            $key = $data['CERTIFICATE_REQUEST_ID'] . '_' . $data['STUDENT_ID'] . '_' . $data['INSTITUTE_ID'];
            if (isset($typingMarks[$key])) {
                $marksheetData = array_merge($marksheetData, $typingMarks[$key]);
            }
        }

        $marksheetJson = !empty($marksheetData) ? json_encode($marksheetData, JSON_UNESCAPED_UNICODE) : '';

        fputcsv($fp, [
            $data['CERTIFICATE_DETAILS_ID'],
            $data['CERTIFICATE_REQUEST_ID'],
            $data['CERTIFICATE_REQUEST_MASTER_ID'],
            $data['CERTIFICATE_FILE'],
            $data['CERTIFICATE_SERIAL_NO'],
            $data['CERTIFICATE_PREFIX'],
            $data['CERTIFICATE_NO'],
            $data['ISSUE_DATE'],
            $data['ISSUE_DATE_FORMAT'],
            $data['QRFILE'],
            $data['STUDENT_ID'],
            $data['STUDENT_CODE'],
            $data['STUDENT_NAME'],
            $data['STUDENT_FNAME'],
            $data['STUDENT_MNAME'],
            $data['STUDENT_LNAME'],
            $data['STUDENT_MOTHER_NAME'],
            $data['STUDENT_FATHER_NAME'],
            $data['SONOF'],
            $data['STUDENT_PHOTO_LIVE'],
            $data['STUDENT_SIGN_LIVE'],
            $data['STUDENT_DOB'],
            $data['STUDENT_DOB_FORMAT'],
            $data['STUD_ID_PROOF_TYPE'],
            $data['STUD_ID_PROOF_NUMBER'],
            $data['INSTITUTE_ID'],
            $data['INSTITUTE_CODE'],
            $data['INSTITUTE_NAME'],
            $data['OWNER_NAME'],
            $data['INSTITUTE_CITY'],
            $data['INSTITUTE_STATE'],
            $data['INSTITUTE_ADDRESS'],
            $data['INSTITUTE_EMAIL'],
            $data['INSTITUTE_MOBILE'],
            $data['COURSE_ID'],
            $data['MULTI_SUB_COURSE_ID'],
            $data['TYPING_COURSE_ID'],
            $data['COURSE_NAME'],
            $data['COURSE_NAME_COMPUTED'],
            $data['MULTI_SUB_COURSE_NAME'],
            $data['COURSE_DURATION'],
            $data['MULTI_SUB_COURSE_DURATION'],
            $data['TYPING_COURSE_DURATION'],
            $data['EXAM_TITLE'],
            $data['EXAM_TYPE'],
            $data['EXAM_RESULT_ID'],
            $data['EXAM_RESULT_FINAL_ID'],
            $data['SUBJECT'],
            $data['OBJECTIVE_MARKS'],
            $data['PRACTICAL_MARKS'],
            $data['MARKS_PER'],
            $data['GRADE'],
            $data['RESULT_STATUS'],
            $data['EXAM_FEES'],
            $data['REQUEST_STATUS'],
            $marksheetJson,
            $data['ACTIVE'],
            $data['DELETE_FLAG'],
            $data['CREATED_ON'],
            $data['CREATED_BY'],
            $data['REQUEST_CREATED_ON'],
        ]);
    }

    unset($rows, $multiSubMarks, $typingMarks);

    if ($chunkCount < $chunkSize) {
        break;
    }
}

$conn2->close();
fclose($fp);

// Rename .tmp to final filename (atomic — signals "ready" to the status check)
rename($tmpFile . '.tmp', $tmpFile);
exit;
