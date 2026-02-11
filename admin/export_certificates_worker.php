<?php
/**
 * Background CSV Export Worker
 *
 * Runs as a separate PHP CLI process, completely independent of PHP-FPM.
 * Called by: php export_certificates_worker.php <fileId>
 * Writes CSV to admin/exports/<fileId>
 * Writes progress to admin/exports/<fileId>.status (JSON)
 */

ini_set("memory_limit", "512M");
ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);
date_default_timezone_set("Asia/Kolkata");

// Get file ID from command line argument
$fileId = isset($argv[1]) ? $argv[1] : '';
$exportDir = __DIR__ . '/exports';
if (!is_dir($exportDir)) {
    mkdir($exportDir, 0755, true);
}

$logFile = $exportDir . '/export_error.log';
$statusFile = $exportDir . '/' . $fileId . '.status';

// Helper: write status file for AJAX polling
function writeStatus($statusFile, $state, $message, $rows = 0) {
    file_put_contents($statusFile, json_encode([
        'state' => $state,       // 'running', 'done', 'error'
        'message' => $message,
        'rows' => $rows,
        'time' => date('H:i:s'),
    ]));
}

if ($fileId == '') {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " No fileId provided\n", FILE_APPEND);
    exit(1);
}

writeStatus($statusFile, 'running', 'Starting export...', 0);

// Include database connection
try {
    include_once(__DIR__ . '/include/classes/config.php');
    include_once(__DIR__ . '/include/classes/database_results.class.php');
} catch (Throwable $e) {
    $msg = "Failed to include config: " . $e->getMessage();
    file_put_contents($logFile, date('Y-m-d H:i:s') . " $msg\n", FILE_APPEND);
    writeStatus($statusFile, 'error', $msg);
    exit(1);
}

writeStatus($statusFile, 'running', 'Connecting to database...', 0);

try {
    $db = new database_results();
    $conn1 = $db->mysqli;
    if (!$conn1 || $conn1->connect_error) {
        throw new Exception("DB conn1 failed: " . ($conn1 ? $conn1->connect_error : 'null'));
    }

    $conn2 = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    if ($conn2->connect_error) {
        throw new Exception("DB conn2 failed: " . $conn2->connect_error);
    }
    $conn2->set_charset("utf8");
} catch (Throwable $e) {
    $msg = "DB connection error: " . $e->getMessage();
    file_put_contents($logFile, date('Y-m-d H:i:s') . " $msg\n", FILE_APPEND);
    writeStatus($statusFile, 'error', $msg);
    exit(1);
}

$tmpFile = $exportDir . '/' . $fileId . '.tmp';
$finalFile = $exportDir . '/' . $fileId;

file_put_contents($logFile, date('Y-m-d H:i:s') . " START export: $fileId\n", FILE_APPEND);

$fp = fopen($tmpFile, 'w');
if (!$fp) {
    $msg = "Cannot create file $tmpFile";
    file_put_contents($logFile, date('Y-m-d H:i:s') . " ERROR: $msg\n", FILE_APPEND);
    writeStatus($statusFile, 'error', $msg);
    exit(1);
}

// UTF-8 BOM
fwrite($fp, "\xEF\xBB\xBF");

// CSV header
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

writeStatus($statusFile, 'running', 'Querying data...', 0);

// Cursor-based chunking
$chunkSize = 200;
$lastId = 0;
$totalRows = 0;
$chunkNum = 0;

while (true) {
    $chunkNum++;

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
    if ($res === false) {
        $msg = "Main query error (chunk $chunkNum, lastId=$lastId): " . $conn1->error;
        file_put_contents($logFile, date('Y-m-d H:i:s') . " $msg\n", FILE_APPEND);
        writeStatus($statusFile, 'error', $msg);
        fclose($fp);
        @unlink($tmpFile);
        exit(1);
    }

    if ($res->num_rows == 0) {
        $res->free();
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

    // Fetch multi-sub marksheet subjects for this chunk
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
        if ($msRes === false) {
            $msg = "Multi-sub query error (chunk $chunkNum): " . $conn2->error;
            file_put_contents($logFile, date('Y-m-d H:i:s') . " $msg\n", FILE_APPEND);
            writeStatus($statusFile, 'error', $msg);
            fclose($fp);
            @unlink($tmpFile);
            exit(1);
        }
        if ($msRes->num_rows > 0) {
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
        }
        $msRes->free();
    }

    // Fetch typing marksheet subjects for this chunk
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
        if ($tyRes === false) {
            $msg = "Typing query error (chunk $chunkNum): " . $conn2->error;
            file_put_contents($logFile, date('Y-m-d H:i:s') . " $msg\n", FILE_APPEND);
            writeStatus($statusFile, 'error', $msg);
            fclose($fp);
            @unlink($tmpFile);
            exit(1);
        }
        if ($tyRes->num_rows > 0) {
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
        }
        $tyRes->free();
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

    $totalRows += count($rows);
    unset($rows, $multiSubMarks, $typingMarks);

    writeStatus($statusFile, 'running', "Processed $totalRows rows (chunk $chunkNum)...", $totalRows);

    if ($chunkCount < $chunkSize) {
        break;
    }
}

$conn2->close();
fclose($fp);

// Rename .tmp to final filename (atomic — signals "ready")
rename($tmpFile, $finalFile);

writeStatus($statusFile, 'done', "Export complete: $totalRows rows", $totalRows);
file_put_contents($logFile, date('Y-m-d H:i:s') . " DONE export: $fileId ($totalRows rows)\n", FILE_APPEND);
exit(0);
