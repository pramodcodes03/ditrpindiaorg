<?php
/**
 * Background Import Worker — laravel_certificates_export
 *
 * Runs as a separate PHP CLI process, completely independent of PHP-FPM/nginx.
 * Called by: php import_certificates_worker.php
 * Writes progress to admin/exports/import_certificates.status (JSON)
 */

ini_set("memory_limit", "512M");
ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);
date_default_timezone_set("Asia/Kolkata");

$exportDir  = __DIR__ . '/exports';
if (!is_dir($exportDir)) {
    mkdir($exportDir, 0755, true);
}

$logFile    = $exportDir . '/import_certificates_error.log';
$statusFile = $exportDir . '/import_certificates.status';

function writeStatus($statusFile, $state, $message, $rows = 0, $total = 0) {
    $remaining = max(0, $total - $rows);
    $percent   = ($total > 0) ? round(($rows / $total) * 100, 1) : 0;
    file_put_contents($statusFile, json_encode([
        'state'     => $state,   // 'running', 'done', 'error'
        'message'   => $message,
        'rows'      => $rows,
        'total'     => $total,
        'remaining' => $remaining,
        'percent'   => $percent,
        'time'      => date('H:i:s'),
    ]));
}

writeStatus($statusFile, 'running', 'Starting import...', 0);

// ── DB connection ────────────────────────────────────────────
try {
    include_once(__DIR__ . '/include/classes/config.php');
    include_once(__DIR__ . '/include/classes/database_results.class.php');
} catch (Throwable $e) {
    $msg = "Failed to include config: " . $e->getMessage();
    file_put_contents($logFile, date('Y-m-d H:i:s') . " $msg\n", FILE_APPEND);
    writeStatus($statusFile, 'error', $msg);
    exit(1);
}

try {
    $db   = new database_results();
    $conn = $db->mysqli;
    if (!$conn || $conn->connect_error) {
        throw new Exception("DB connection failed: " . ($conn ? $conn->connect_error : 'null'));
    }
    $conn->set_charset("utf8mb4");
} catch (Throwable $e) {
    $msg = "DB connection error: " . $e->getMessage();
    file_put_contents($logFile, date('Y-m-d H:i:s') . " $msg\n", FILE_APPEND);
    writeStatus($statusFile, 'error', $msg);
    exit(1);
}

file_put_contents($logFile, date('Y-m-d H:i:s') . " START import\n", FILE_APPEND);

// ── Step 1: DROP & CREATE TABLE ──────────────────────────────
writeStatus($statusFile, 'running', 'Creating table...', 0);

$ddlStatements = [

"DROP TABLE IF EXISTS `laravel_certificates_export`",

"CREATE TABLE `laravel_certificates_export` (
  `id`                            int(10) UNSIGNED     NOT NULL AUTO_INCREMENT,
  `certificate_details_id`        int(11)              DEFAULT NULL,
  `certificate_request_id`        int(11)              DEFAULT NULL,
  `certificate_request_master_id` int(11)              DEFAULT NULL,
  `certificate_file`              varchar(255)         DEFAULT NULL,
  `certificate_serial_no`         varchar(100)         DEFAULT NULL,
  `certificate_prefix`            varchar(50)          DEFAULT NULL,
  `certificate_no`                varchar(100)         DEFAULT NULL,
  `issue_date`                    date                 DEFAULT NULL,
  `issue_date_format`             varchar(20)          DEFAULT NULL,
  `qr_file`                       varchar(255)         DEFAULT NULL,
  `student_id`                    int(11)              DEFAULT NULL,
  `student_code`                  varchar(100)         DEFAULT NULL,
  `student_name`                  varchar(255)         DEFAULT NULL,
  `student_fname`                 varchar(255)         DEFAULT NULL,
  `student_mname`                 varchar(255)         DEFAULT NULL,
  `student_lname`                 varchar(255)         DEFAULT NULL,
  `student_mother_name`           varchar(255)         DEFAULT NULL,
  `student_father_name`           varchar(255)         DEFAULT NULL,
  `son_of`                        varchar(255)         DEFAULT NULL,
  `student_photo`                 varchar(255)         DEFAULT NULL,
  `student_sign`                  varchar(255)         DEFAULT NULL,
  `student_dob`                   date                 DEFAULT NULL,
  `student_dob_format`            varchar(20)          DEFAULT NULL,
  `stud_id_proof_type`            varchar(100)         DEFAULT NULL,
  `stud_id_proof_number`          varchar(100)         DEFAULT NULL,
  `institute_id`                  int(11)              DEFAULT NULL,
  `institute_code`                varchar(100)         DEFAULT NULL,
  `institute_name`                varchar(255)         DEFAULT NULL,
  `owner_name`                    varchar(255)         DEFAULT NULL,
  `institute_city`                varchar(100)         DEFAULT NULL,
  `institute_state`               varchar(100)         DEFAULT NULL,
  `institute_address`             text                 DEFAULT NULL,
  `institute_email`               varchar(255)         DEFAULT NULL,
  `institute_mobile`              varchar(20)          DEFAULT NULL,
  `institute_sign`                varchar(255)         DEFAULT NULL,
  `course_id`                     int(11)              DEFAULT NULL,
  `multi_sub_course_id`           int(11)              DEFAULT NULL,
  `typing_course_id`              int(11)              DEFAULT NULL,
  `course_name`                   varchar(255)         DEFAULT NULL,
  `course_name_computed`          varchar(255)         DEFAULT NULL,
  `multi_sub_course_name`         varchar(255)         DEFAULT NULL,
  `course_duration`               varchar(100)         DEFAULT NULL,
  `multi_sub_course_duration`     varchar(100)         DEFAULT NULL,
  `typing_course_duration`        varchar(100)         DEFAULT NULL,
  `exam_title`                    varchar(255)         DEFAULT NULL,
  `exam_type`                     varchar(100)         DEFAULT NULL,
  `exam_result_id`                int(11)              DEFAULT NULL,
  `exam_result_final_id`          int(11)              DEFAULT NULL,
  `subject`                       longtext             DEFAULT NULL,
  `objective_marks`               varchar(50)          DEFAULT NULL,
  `practical_marks`               varchar(50)          DEFAULT NULL,
  `marks_per`                     varchar(50)          DEFAULT NULL,
  `grade`                         varchar(10)          DEFAULT NULL,
  `result_status`                 varchar(50)          DEFAULT NULL,
  `exam_fees`                     varchar(50)          DEFAULT NULL,
  `request_status`                varchar(50)          DEFAULT NULL,
  `marksheet_subjects_json`       longtext             DEFAULT NULL,
  `active`                        tinyint(4)           DEFAULT NULL,
  `delete_flag`                   tinyint(4)           DEFAULT NULL,
  `created_on`                    datetime             DEFAULT NULL,
  `created_by`                    varchar(255)         DEFAULT NULL,
  `request_created_on`            datetime             DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

];

foreach ($ddlStatements as $sql) {
    if ($conn->query($sql) === false) {
        $msg = "DDL error: " . $conn->error . " | SQL: " . substr($sql, 0, 80);
        file_put_contents($logFile, date('Y-m-d H:i:s') . " $msg\n", FILE_APPEND);
        writeStatus($statusFile, 'error', $msg);
        exit(1);
    }
}

file_put_contents($logFile, date('Y-m-d H:i:s') . " Table created\n", FILE_APPEND);

// ── Step 2: Count total records ──────────────────────────────
writeStatus($statusFile, 'running', 'Counting total records...', 0, 0);

$countRes = $conn->query("SELECT COUNT(*) AS total FROM certificates_details WHERE DELETE_FLAG = 0");
$totalCount = 0;
if ($countRes && $row = $countRes->fetch_assoc()) {
    $totalCount = (int)$row['total'];
    $countRes->free();
}
file_put_contents($logFile, date('Y-m-d H:i:s') . " Total records to import: $totalCount\n", FILE_APPEND);
writeStatus($statusFile, 'running', "Total records: $totalCount. Starting import...", 0, $totalCount);

// ── Step 3: Chunked INSERT ───────────────────────────────────
$chunkSize = 500;
$lastId    = 0;
$totalRows = 0;
$chunkNum  = 0;

while (true) {
    $chunkNum++;

    // SELECT one chunk from source tables
    $selectSql = "
        SELECT
            cd.CERTIFICATE_DETAILS_ID,
            cd.CERTIFICATE_REQUEST_ID,
            cr.CERTIFICATE_REQUEST_MASTER_ID,
            cd.CERTIFICATE_FILE,
            cd.CERTIFICATE_SERIAL_NO,
            cd.CERTIFICATE_PREFIX,
            cd.CERTIFICATE_NO,
            DATE_FORMAT(cd.ISSUE_DATE, '%Y-%m-%d')          AS ISSUE_DATE,
            DATE_FORMAT(cd.ISSUE_DATE, '%d-%m-%Y')          AS ISSUE_DATE_FORMAT,
            cd.QRFILE,
            cd.STUDENT_ID,
            sd.STUDENT_CODE,
            cd.STUDENT_NAME,
            cd.STUDENT_FNAME,
            cd.STUDENT_MNAME,
            cd.STUDENT_LNAME,
            cd.STUDENT_MOTHER_NAME,
            cd.STUDENT_FATHER_NAME,
            sd.SONOF,
            cd.STUDENT_PHOTO,
            cd.STUDENT_SIGN,
            DATE_FORMAT(cd.STUDENT_DOB, '%Y-%m-%d')         AS STUDENT_DOB,
            DATE_FORMAT(cd.STUDENT_DOB, '%d.%m.%Y')         AS STUDENT_DOB_FORMAT,
            cd.STUD_ID_PROOF_TYPE,
            cd.STUD_ID_PROOF_NUMBER,
            cd.INSTITUTE_ID,
            inst.INSTITUTE_CODE,
            cd.INSTITUTE_NAME,
            inst.INSTITUTE_OWNER_NAME,
            cm.CITY_NAME,
            sm.STATE_NAME,
            inst.ADDRESS_LINE1,
            inst.EMAIL,
            inst.MOBILE,
            (
                SELECT instf.FILE_NAME
                FROM   institute_files instf
                WHERE  instf.INSTITUTE_ID = cd.INSTITUTE_ID
                  AND  instf.FILE_LABEL   = 'sign'
                ORDER BY instf.FILE_ID ASC
                LIMIT 1
            )                                               AS INSTITUTE_SIGN,
            cd.COURSE_ID,
            cd.MULTI_SUB_COURSE_ID,
            cd.TYPING_COURSE_ID,
            cd.COURSE_NAME,
            co.COURSE_NAME                                  AS COURSE_NAME_COMPUTED,
            msc.MULTI_SUB_COURSE_NAME,
            UPPER(co.COURSE_DURATION)                       AS COURSE_DURATION,
            UPPER(msc.MULTI_SUB_COURSE_DURATION)            AS MULTI_SUB_COURSE_DURATION,
            UPPER(ct.TYPING_COURSE_DURATION)                AS TYPING_COURSE_DURATION,
            cr.EXAM_TITLE,
            cr.EXAM_TYPE,
            cr.EXAM_RESULT_ID,
            cr.EXAM_RESULT_FINAL_ID,
            cd.SUBJECT,
            cd.OBJECTIVE_MARKS,
            cd.PRACTICAL_MARKS,
            cd.MARKS_PER,
            cd.GRADE,
            cr.RESULT_STATUS,
            cr.EXAM_FEES,
            cr.REQUEST_STATUS,
            cd.ACTIVE,
            cd.DELETE_FLAG,
            DATE_FORMAT(cd.CREATED_ON, '%Y-%m-%d %H:%i:%s') AS CREATED_ON,
            cd.CREATED_BY,
            DATE_FORMAT(cr.CREATED_ON, '%Y-%m-%d %H:%i:%s') AS REQUEST_CREATED_ON
        FROM  certificates_details cd
        LEFT JOIN certificate_requests cr  ON cd.CERTIFICATE_REQUEST_ID  = cr.CERTIFICATE_REQUEST_ID
        LEFT JOIN student_details      sd  ON cd.STUDENT_ID              = sd.STUDENT_ID
        LEFT JOIN institute_details    inst ON cd.INSTITUTE_ID           = inst.INSTITUTE_ID
        LEFT JOIN city_master          cm  ON inst.CITY                  = cm.CITY_ID
        LEFT JOIN states_master        sm  ON inst.STATE                 = sm.STATE_ID
        LEFT JOIN courses              co  ON cd.COURSE_ID               = co.COURSE_ID
        LEFT JOIN multi_sub_courses    msc ON cd.MULTI_SUB_COURSE_ID     = msc.MULTI_SUB_COURSE_ID
        LEFT JOIN courses_typing       ct  ON cd.TYPING_COURSE_ID        = ct.TYPING_COURSE_ID
        WHERE cd.DELETE_FLAG = 0
          AND cd.CERTIFICATE_DETAILS_ID > $lastId
        ORDER BY cd.CERTIFICATE_DETAILS_ID ASC
        LIMIT $chunkSize
    ";

    $res = $conn->query($selectSql);
    if ($res === false) {
        $msg = "SELECT error (chunk $chunkNum, lastId=$lastId): " . $conn->error;
        file_put_contents($logFile, date('Y-m-d H:i:s') . " $msg\n", FILE_APPEND);
        writeStatus($statusFile, 'error', $msg);
        exit(1);
    }

    if ($res->num_rows == 0) {
        $res->free();
        break;
    }

    // Build multi-row INSERT VALUES
    $valueParts = [];
    while ($row = $res->fetch_assoc()) {
        $lastId = (int)$row['CERTIFICATE_DETAILS_ID'];

        $valueParts[] = sprintf(
            "(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
            $lastId,
            nullOrInt($row['CERTIFICATE_REQUEST_ID']),
            nullOrInt($row['CERTIFICATE_REQUEST_MASTER_ID']),
            nullOrStr($conn, $row['CERTIFICATE_FILE']),
            nullOrStr($conn, $row['CERTIFICATE_SERIAL_NO']),
            nullOrStr($conn, $row['CERTIFICATE_PREFIX']),
            nullOrStr($conn, $row['CERTIFICATE_NO']),
            nullOrStr($conn, $row['ISSUE_DATE']),
            nullOrStr($conn, $row['ISSUE_DATE_FORMAT']),
            nullOrStr($conn, $row['QRFILE']),
            nullOrInt($row['STUDENT_ID']),
            nullOrStr($conn, $row['STUDENT_CODE']),
            nullOrStr($conn, $row['STUDENT_NAME']),
            nullOrStr($conn, $row['STUDENT_FNAME']),
            nullOrStr($conn, $row['STUDENT_MNAME']),
            nullOrStr($conn, $row['STUDENT_LNAME']),
            nullOrStr($conn, $row['STUDENT_MOTHER_NAME']),
            nullOrStr($conn, $row['STUDENT_FATHER_NAME']),
            nullOrStr($conn, $row['SONOF']),
            nullOrStr($conn, $row['STUDENT_PHOTO']),
            nullOrStr($conn, $row['STUDENT_SIGN']),
            nullOrStr($conn, $row['STUDENT_DOB']),
            nullOrStr($conn, $row['STUDENT_DOB_FORMAT']),
            nullOrStr($conn, $row['STUD_ID_PROOF_TYPE']),
            nullOrStr($conn, $row['STUD_ID_PROOF_NUMBER']),
            nullOrInt($row['INSTITUTE_ID']),
            nullOrStr($conn, $row['INSTITUTE_CODE']),
            nullOrStr($conn, $row['INSTITUTE_NAME']),
            nullOrStr($conn, $row['INSTITUTE_OWNER_NAME']),
            nullOrStr($conn, $row['CITY_NAME']),
            nullOrStr($conn, $row['STATE_NAME']),
            nullOrStr($conn, $row['ADDRESS_LINE1']),
            nullOrStr($conn, $row['EMAIL']),
            nullOrStr($conn, $row['MOBILE']),
            nullOrStr($conn, $row['INSTITUTE_SIGN']),
            nullOrInt($row['COURSE_ID']),
            nullOrInt($row['MULTI_SUB_COURSE_ID']),
            nullOrInt($row['TYPING_COURSE_ID']),
            nullOrStr($conn, $row['COURSE_NAME']),
            nullOrStr($conn, $row['COURSE_NAME_COMPUTED']),
            nullOrStr($conn, $row['MULTI_SUB_COURSE_NAME']),
            nullOrStr($conn, $row['COURSE_DURATION']),
            nullOrStr($conn, $row['MULTI_SUB_COURSE_DURATION']),
            nullOrStr($conn, $row['TYPING_COURSE_DURATION']),
            nullOrStr($conn, $row['EXAM_TITLE']),
            nullOrStr($conn, $row['EXAM_TYPE']),
            nullOrInt($row['EXAM_RESULT_ID']),
            nullOrInt($row['EXAM_RESULT_FINAL_ID']),
            nullOrStr($conn, $row['SUBJECT']),
            nullOrStr($conn, $row['OBJECTIVE_MARKS']),
            nullOrStr($conn, $row['PRACTICAL_MARKS']),
            nullOrStr($conn, $row['MARKS_PER']),
            nullOrStr($conn, $row['GRADE']),
            nullOrStr($conn, $row['RESULT_STATUS']),
            nullOrStr($conn, $row['EXAM_FEES']),
            nullOrStr($conn, $row['REQUEST_STATUS']),
            'NULL',  // marksheet_subjects_json — to be filled later
            nullOrInt($row['ACTIVE']),
            nullOrInt($row['DELETE_FLAG']),
            nullOrStr($conn, $row['CREATED_ON']),
            nullOrStr($conn, $row['CREATED_BY']),
            nullOrStr($conn, $row['REQUEST_CREATED_ON'])
        );
    }
    $chunkCount = $res->num_rows;
    $res->free();

    // Execute batch INSERT
    $insertSql = "INSERT INTO `laravel_certificates_export`
        (certificate_details_id,certificate_request_id,certificate_request_master_id,
         certificate_file,certificate_serial_no,certificate_prefix,certificate_no,
         issue_date,issue_date_format,qr_file,
         student_id,student_code,student_name,student_fname,student_mname,student_lname,
         student_mother_name,student_father_name,son_of,student_photo,student_sign,
         student_dob,student_dob_format,stud_id_proof_type,stud_id_proof_number,
         institute_id,institute_code,institute_name,owner_name,
         institute_city,institute_state,institute_address,institute_email,institute_mobile,
         institute_sign,
         course_id,multi_sub_course_id,typing_course_id,
         course_name,course_name_computed,multi_sub_course_name,
         course_duration,multi_sub_course_duration,typing_course_duration,
         exam_title,exam_type,exam_result_id,exam_result_final_id,
         subject,objective_marks,practical_marks,marks_per,grade,
         result_status,exam_fees,request_status,
         marksheet_subjects_json,
         active,delete_flag,created_on,created_by,request_created_on)
    VALUES " . implode(",\n", $valueParts);

    if ($conn->query($insertSql) === false) {
        $msg = "INSERT error (chunk $chunkNum, lastId=$lastId): " . $conn->error;
        file_put_contents($logFile, date('Y-m-d H:i:s') . " $msg\n", FILE_APPEND);
        writeStatus($statusFile, 'error', $msg);
        exit(1);
    }

    $totalRows += $chunkCount;
    unset($valueParts);

    writeStatus($statusFile, 'running', "Importing... chunk $chunkNum", $totalRows, $totalCount);
    file_put_contents($logFile, date('Y-m-d H:i:s') . " chunk $chunkNum: $totalRows / $totalCount rows done\n", FILE_APPEND);

    if ($chunkCount < $chunkSize) {
        break;
    }
}

$conn->close();

writeStatus($statusFile, 'done', "Import complete: $totalRows rows inserted", $totalRows, $totalCount);
file_put_contents($logFile, date('Y-m-d H:i:s') . " DONE: $totalRows rows imported\n", FILE_APPEND);
exit(0);

// ── Helpers ──────────────────────────────────────────────────
function nullOrStr($conn, $val) {
    if ($val === null || $val === '') return 'NULL';
    return "'" . $conn->real_escape_string($val) . "'";
}

function nullOrInt($val) {
    if ($val === null || $val === '') return 'NULL';
    return (int)$val;
}
