<?php
/**
 * Export Certificate & Marksheet Data to Single Excel (CSV) for Laravel Migration
 *
 * This exports ALL data needed for the 4 Laravel views:
 *   1. List all certificates (admin view)
 *   2. View individual certificate (mPDF)
 *   3. View marksheet (mPDF)
 *   4. Student certificate verification using certificate number
 */

ini_set("memory_limit", "512M");
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
// MAIN QUERY: Get all certificate details with joined data
// ============================================================
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
    sd.SONOF,
    cd.ACTIVE,
    cd.DELETE_FLAG,
    DATE_FORMAT(cd.CREATED_ON, '%Y-%m-%d %H:%i:%s') AS CREATED_ON,
    cd.CREATED_BY,

    -- Institute details (for verification page)
    get_institute_name(cd.INSTITUTE_ID) AS INSTITUTE_NAME_LIVE,
    get_institute_owner_name(cd.INSTITUTE_ID) AS OWNER_NAME,
    get_institute_city(cd.INSTITUTE_ID) AS INSTITUTE_CITY,
    get_institute_email(cd.INSTITUTE_ID) AS INSTITUTE_EMAIL,
    get_institute_mobile(cd.INSTITUTE_ID) AS INSTITUTE_MOBILE,
    get_institute_address(cd.INSTITUTE_ID) AS INSTITUTE_ADDRESS,
    get_institute_state(cd.INSTITUTE_ID) AS INSTITUTE_STATE,
    get_institute_code(cd.INSTITUTE_ID) AS INSTITUTE_CODE,

    -- Student details
    get_student_code(cd.STUDENT_ID) AS STUDENT_CODE,
    get_stud_photo(cd.STUDENT_ID) AS STUDENT_PHOTO_LIVE,
    get_stud_sign(cd.STUDENT_ID) AS STUDENT_SIGN_LIVE,

    -- Course details
    get_course_title_modify(cd.COURSE_ID) AS COURSE_NAME_COMPUTED,
    get_course_multi_sub_title_modify(cd.MULTI_SUB_COURSE_ID) AS MULTI_SUB_COURSE_NAME,

    -- Course durations
    (SELECT UPPER(c.COURSE_DURATION) FROM courses c WHERE c.COURSE_ID = cd.COURSE_ID LIMIT 1) AS COURSE_DURATION,
    (SELECT UPPER(ms.MULTI_SUB_COURSE_DURATION) FROM multi_sub_courses ms WHERE ms.MULTI_SUB_COURSE_ID = cd.MULTI_SUB_COURSE_ID LIMIT 1) AS MULTI_SUB_COURSE_DURATION,
    (SELECT UPPER(ct.TYPING_COURSE_DURATION) FROM courses_typing ct WHERE ct.TYPING_COURSE_ID = cd.TYPING_COURSE_ID LIMIT 1) AS TYPING_COURSE_DURATION,

    -- Certificate request info
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
WHERE cd.DELETE_FLAG = 0
ORDER BY cd.CERTIFICATE_DETAILS_ID ASC";

$res = $db->execQuery($sql);

if (!$res || $res->num_rows == 0) {
    die("No certificate records found.");
}

// ============================================================
// Include exam classes for marksheet subject data
// ============================================================
include_once('include/classes/exam.class.php');
include_once('include/classes/exammultisub.class.php');
include_once('include/classes/coursetypingexam.class.php');

$exammultisub = new exammultisub();
$coursetypingexam = new coursetypingexam();

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
// Set headers for browser CSV download
// ============================================================
$filename = 'certificates_export_' . date('Y-m-d_His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

$fp = fopen('php://output', 'w');

// Write BOM for Excel UTF-8 compatibility
fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Write header row
fputcsv($fp, $columns);

// ============================================================
// Write data rows
// ============================================================
while ($data = $res->fetch_assoc()) {

    $marksheetData = [];

    $MULTI_SUB_COURSE_ID = $data['MULTI_SUB_COURSE_ID'];
    $TYPING_COURSE_ID = $data['TYPING_COURSE_ID'];
    $COURSE_ID = $data['COURSE_ID'];
    $CERTIFICATE_REQUEST_ID = $data['CERTIFICATE_REQUEST_ID'];
    $STUDENT_ID = $data['STUDENT_ID'];
    $INSTITUTE_ID = $data['INSTITUTE_ID'];

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

    // Multi-subject course
    if ($MULTI_SUB_COURSE_ID != '' && !empty($MULTI_SUB_COURSE_ID) && $MULTI_SUB_COURSE_ID != 0) {
        try {
            $res4 = $exammultisub->get_exam_final_id($CERTIFICATE_REQUEST_ID);
            if ($res4 && $res4->num_rows > 0) {
                $data4 = $res4->fetch_assoc();
                $EXAM_RESULT_FINAL_ID = $data4['EXAM_RESULT_FINAL_ID'];

                $examResults = $exammultisub->list_student_exam_results_multi_sub($EXAM_RESULT_FINAL_ID, $STUDENT_ID, $INSTITUTE_ID, '', '');
                if ($examResults && $examResults->num_rows > 0) {
                    $data3 = $examResults->fetch_assoc();
                    $STUD_COURSE_ID = $data3['STUD_COURSE_ID'];

                    $res2 = $exammultisub->list_student_exam_results_multi_sub_list('', $STUDENT_ID, $INSTITUTE_ID, $STUD_COURSE_ID, '');
                    if ($res2 != '') {
                        while ($subData = $res2->fetch_assoc()) {
                            $marksheetData[] = [
                                'type' => 'multi_sub',
                                'subject_name' => $subData['SUBJECT_NAME'],
                                'exam_title' => $subData['EXAM_TITLE'],
                                'objective_marks' => (float)$subData['MARKS_OBTAINED'],
                                'practical_marks' => (float)$subData['PRACTICAL_MARKS'],
                                'total_marks' => (float)($subData['MARKS_OBTAINED'] + $subData['PRACTICAL_MARKS']),
                            ];
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Skip if marksheet data not available
        }
    }

    // Typing course
    if ($TYPING_COURSE_ID != '' && !empty($TYPING_COURSE_ID) && $TYPING_COURSE_ID != 0) {
        try {
            $res4 = $coursetypingexam->get_exam_final_id($CERTIFICATE_REQUEST_ID);
            if ($res4 && $res4->num_rows > 0) {
                $data4 = $res4->fetch_assoc();
                $EXAM_RESULT_FINAL_ID = $data4['EXAM_RESULT_TYPING_ID'];

                $examResults = $coursetypingexam->list_student_exam_results_typing($EXAM_RESULT_FINAL_ID, $STUDENT_ID, $INSTITUTE_ID, '', '');
                if ($examResults && $examResults->num_rows > 0) {
                    $data3 = $examResults->fetch_assoc();
                    $STUD_COURSE_ID = $data3['STUD_COURSE_ID'];

                    $res2 = $coursetypingexam->list_student_exam_results_typing_list('', $STUDENT_ID, $INSTITUTE_ID, $STUD_COURSE_ID, '');
                    if ($res2 != '') {
                        while ($subData = $res2->fetch_assoc()) {
                            $marksheetData[] = [
                                'type' => 'typing',
                                'subject_name' => $subData['SUBJECT_NAME'],
                                'exam_title' => $subData['EXAM_TITLE'],
                                'speed_wpm' => (int)$subData['TYPING_COURSE_SPEED'],
                                'minimum_marks' => (float)$subData['MINIMUM_MARKS'],
                                'exam_total_marks' => (float)$subData['EXAM_TOTAL_MARKS'],
                                'marks_obtained' => (float)$subData['MARKS_OBTAINED'],
                                'total_marks' => (float)$subData['TOTAL_MARKS'],
                            ];
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Skip if marksheet data not available
        }
    }

    $marksheetJson = !empty($marksheetData) ? json_encode($marksheetData, JSON_UNESCAPED_UNICODE) : '';

    $row = [
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

    fputcsv($fp, $row);
}

fclose($fp);
exit;
