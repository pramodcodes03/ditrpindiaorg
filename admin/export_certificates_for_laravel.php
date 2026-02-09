<?php
ini_set('display_errors', 1);

/**
 * Export Certificate & Marksheet Data to CSV for Laravel Migration
 *
 * Streams CSV directly to browser using fputcsv + flush per chunk.
 * This prevents nginx 504 gateway timeout by keeping data flowing.
 * All MySQL stored functions replaced with JOINs for performance.
 */

ini_set("memory_limit", "1024M");
set_time_limit(0);
date_default_timezone_set("Asia/Kolkata");

session_start();

// Include database connection
include_once('include/classes/config.php');
include_once('include/classes/database_results.class.php');
include_once('include/classes/access.class.php');

$db = new database_results();
$access = new access();

// Session authentication check
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
if ($user_id == '') {
    die("Unauthorized. Please login first.");
}

// ============================================================
// CSV COLUMN HEADERS
// ============================================================
$columns = [
    'certificate_details_id',
    'certificate_request_id',
    'certificate_request_master_id',
    'certificate_file',
    'certificate_serial_no',
    'certificate_prefix',
    'certificate_no',
    'issue_date',
    'issue_date_format',
    'qr_file',
    'student_id',
    'student_code',
    'student_name',
    'student_fname',
    'student_mname',
    'student_lname',
    'student_mother_name',
    'student_father_name',
    'son_of',
    'student_photo',
    'student_sign',
    'student_dob',
    'student_dob_format',
    'stud_id_proof_type',
    'stud_id_proof_number',
    'institute_id',
    'institute_code',
    'institute_name',
    'owner_name',
    'institute_city',
    'institute_state',
    'institute_address',
    'institute_email',
    'institute_mobile',
    'course_id',
    'multi_sub_course_id',
    'typing_course_id',
    'course_name',
    'course_name_computed',
    'multi_sub_course_name',
    'course_duration',
    'multi_sub_course_duration',
    'typing_course_duration',
    'exam_title',
    'exam_type',
    'exam_result_id',
    'exam_result_final_id',
    'subject',
    'objective_marks',
    'practical_marks',
    'marks_per',
    'grade',
    'result_status',
    'exam_fees',
    'request_status',
    'marksheet_subjects_json',
    'active',
    'delete_flag',
    'created_on',
    'created_by',
    'request_created_on',
];

// ============================================================
// Stream CSV directly to browser (prevents nginx 504 timeout)
// ============================================================
$filename = 'certificates_export_' . date('Y-m-d_His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// Open output stream
$output = fopen('php://output', 'w');

// Write BOM for Excel UTF-8 compatibility
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Write header row
fputcsv($output, $columns);

// Flush headers + first row immediately
ob_flush();
flush();

// ============================================================
// Pre-fetch all marksheet subjects in bulk (multi-sub courses)
// to avoid per-row queries
// ============================================================
$multiSubMarks = [];
$msBulkSql = "SELECT
    creq.CERTIFICATE_REQUEST_ID,
    er.STUDENT_ID,
    er.INSTITUTE_ID,
    mscs.COURSE_SUBJECT_NAME AS SUBJECT_NAME,
    er.EXAM_TITLE,
    er.MARKS_OBTAINED,
    er.PRACTICAL_MARKS,
    er.EXAM_RESULT_ID
    FROM certificate_requests creq
    INNER JOIN multi_sub_exam_result_final erf ON creq.EXAM_RESULT_FINAL_ID = erf.EXAM_RESULT_FINAL_ID
    INNER JOIN multi_sub_exam_result er ON er.STUDENT_ID = erf.STUDENT_ID AND er.INSTITUTE_ID = erf.INSTITUTE_ID AND er.STUD_COURSE_ID = erf.STUD_COURSE_ID
    LEFT JOIN multi_sub_courses_subjects mscs ON er.STUDENT_SUBJECT_ID = mscs.COURSE_SUBJECT_ID
    WHERE er.DELETE_FLAG = 0 AND er.ACTIVE = 1
    ORDER BY er.EXAM_RESULT_ID ASC";
$msBulkRes = $db->execQuery($msBulkSql);
if ($msBulkRes && $msBulkRes->num_rows > 0) {
    while ($row = $msBulkRes->fetch_assoc()) {
        $key = $row['CERTIFICATE_REQUEST_ID'] . '_' . $row['STUDENT_ID'] . '_' . $row['INSTITUTE_ID'];
        if (!isset($multiSubMarks[$key])) $multiSubMarks[$key] = [];
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
unset($msBulkRes);
ob_flush();
flush();

// ============================================================
// Pre-fetch all typing course marks in bulk
// ============================================================
$typingMarks = [];
$tyBulkSql = "SELECT
    creq.CERTIFICATE_REQUEST_ID,
    er.STUDENT_ID,
    er.INSTITUTE_ID,
    cts.TYPING_COURSE_SUBJECT_NAME AS SUBJECT_NAME,
    er.EXAM_TITLE,
    er.MARKS_OBTAINED,
    er.EXAM_TOTAL_MARKS,
    er.TOTAL_MARKS,
    er.MINIMUM_MARKS,
    cts.TYPING_COURSE_SPEED,
    er.EXAM_RESULT_ID
    FROM certificate_requests creq
    INNER JOIN course_typing_exam_result_final ert ON creq.EXAM_RESULT_TYPING_ID = ert.EXAM_RESULT_FINAL_ID
    INNER JOIN course_typing_exam_result er ON er.STUDENT_ID = ert.STUDENT_ID AND er.INSTITUTE_ID = ert.INSTITUTE_ID AND er.STUD_COURSE_ID = ert.STUD_COURSE_ID
    LEFT JOIN courses_typing_subjects cts ON er.STUDENT_SUBJECT_ID = cts.TYPING_COURSE_SUBJECT_ID
    WHERE er.DELETE_FLAG = 0
    ORDER BY er.EXAM_RESULT_ID ASC";
$tyBulkRes = $db->execQuery($tyBulkSql);
if ($tyBulkRes && $tyBulkRes->num_rows > 0) {
    while ($row = $tyBulkRes->fetch_assoc()) {
        $key = $row['CERTIFICATE_REQUEST_ID'] . '_' . $row['STUDENT_ID'] . '_' . $row['INSTITUTE_ID'];
        if (!isset($typingMarks[$key])) $typingMarks[$key] = [];
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
unset($tyBulkRes);
ob_flush();
flush();

// ============================================================
// Process certificates in chunks
// ============================================================
$chunkSize = 1000;
$offset = 0;
$hasMore = true;

while ($hasMore) {

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

        COALESCE(cd.STUDENT_PHOTO, (SELECT sf.FILE_NAME FROM student_files sf WHERE sf.STUDENT_ID = cd.STUDENT_ID AND sf.FILE_LABEL = 'profile_photo' AND sf.ACTIVE = 1 AND sf.DELETE_FLAG = 0 ORDER BY sf.FILE_ID DESC LIMIT 1)) AS STUDENT_PHOTO_LIVE,
        COALESCE(cd.STUDENT_SIGN, (SELECT sf2.FILE_NAME FROM student_files sf2 WHERE sf2.STUDENT_ID = cd.STUDENT_ID AND sf2.FILE_LABEL = 'student_sign' AND sf2.ACTIVE = 1 AND sf2.DELETE_FLAG = 0 ORDER BY sf2.FILE_ID DESC LIMIT 1)) AS STUDENT_SIGN_LIVE,

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
        msc.MULTI_SUB_COURSE_NAME AS MULTI_SUB_COURSE_NAME,
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
    WHERE cd.DELETE_FLAG = 0
    ORDER BY cd.CERTIFICATE_DETAILS_ID ASC
    LIMIT $offset, $chunkSize";

    $res = $db->execQuery($sql);

    if (!$res || $res->num_rows == 0) {
        $hasMore = false;
        break;
    }

    $rowCount = $res->num_rows;

    while ($data = $res->fetch_assoc()) {

        $marksheetData = [];
        $COURSE_ID = $data['COURSE_ID'];
        $MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
        $TYPING_COURSE_ID = $data['TYPING_COURSE_ID'];

        // Single subject course
        if ($COURSE_ID != '' && !empty($COURSE_ID) && $COURSE_ID != 0) {
            $marksheetData[] = [
                'type' => 'single',
                'subject_name' => $data['SUBJECT'],
                'objective_marks' => (float)$data['OBJECTIVE_MARKS'],
                'practical_marks' => (float)$data['PRACTICAL_MARKS'],
                'total_marks' => (float)($data['OBJECTIVE_MARKS'] + $data['PRACTICAL_MARKS']),
            ];
        }

        // Multi-subject course — use pre-fetched bulk data
        if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != 0) {
            $key = $data['CERTIFICATE_REQUEST_ID'] . '_' . $data['STUDENT_ID'] . '_' . $data['INSTITUTE_ID'];
            if (isset($multiSubMarks[$key])) {
                $marksheetData = array_merge($marksheetData, $multiSubMarks[$key]);
            }
        }

        // Typing course — use pre-fetched bulk data
        if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != 0) {
            $key = $data['CERTIFICATE_REQUEST_ID'] . '_' . $data['STUDENT_ID'] . '_' . $data['INSTITUTE_ID'];
            if (isset($typingMarks[$key])) {
                $marksheetData = array_merge($marksheetData, $typingMarks[$key]);
            }
        }

        $marksheetJson = !empty($marksheetData) ? json_encode($marksheetData, JSON_UNESCAPED_UNICODE) : '';

        $rowData = [
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
        ];

        fputcsv($output, $rowData);
    }

    // Flush each chunk to keep nginx connection alive
    ob_flush();
    flush();

    $offset += $chunkSize;

    if ($rowCount < $chunkSize) {
        $hasMore = false;
    }
}

// Free bulk data memory
unset($multiSubMarks);
unset($typingMarks);

fclose($output);
exit;
