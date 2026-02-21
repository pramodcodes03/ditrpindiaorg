-- ============================================================
-- laravel_certificates_export — full DDL + INSERT migration
-- Generated: 2026-02-21
-- Notes:
--   • institute_sign column added (filename from institute_files)
--   • marksheet_subjects_json is intentionally left NULL
--   • Only single-course (COURSE_ID != 0) rows are imported for
--     marksheet data; multi-sub and typing rows have NULL subject fields
--     in certificates_details anyway, so no extra filter is needed for them
-- ============================================================

-- ============================================================
-- 1. DROP & CREATE TABLE
-- ============================================================
DROP TABLE IF EXISTS `laravel_certificates_export`;

CREATE TABLE `laravel_certificates_export` (
  `id`                           int(10) UNSIGNED     NOT NULL AUTO_INCREMENT,
  `certificate_details_id`       int(11)              DEFAULT NULL,
  `certificate_request_id`       int(11)              DEFAULT NULL,
  `certificate_request_master_id` int(11)             DEFAULT NULL,
  `certificate_file`             varchar(255)         DEFAULT NULL,
  `certificate_serial_no`        varchar(100)         DEFAULT NULL,
  `certificate_prefix`           varchar(50)          DEFAULT NULL,
  `certificate_no`               varchar(100)         DEFAULT NULL,
  `issue_date`                   date                 DEFAULT NULL,
  `issue_date_format`            varchar(20)          DEFAULT NULL,
  `qr_file`                      varchar(255)         DEFAULT NULL,
  `student_id`                   int(11)              DEFAULT NULL,
  `student_code`                 varchar(100)         DEFAULT NULL,
  `student_name`                 varchar(255)         DEFAULT NULL,
  `student_fname`                varchar(255)         DEFAULT NULL,
  `student_mname`                varchar(255)         DEFAULT NULL,
  `student_lname`                varchar(255)         DEFAULT NULL,
  `student_mother_name`          varchar(255)         DEFAULT NULL,
  `student_father_name`          varchar(255)         DEFAULT NULL,
  `son_of`                       varchar(255)         DEFAULT NULL,
  `student_photo`                varchar(255)         DEFAULT NULL,
  `student_sign`                 varchar(255)         DEFAULT NULL,
  `student_dob`                  date                 DEFAULT NULL,
  `student_dob_format`           varchar(20)          DEFAULT NULL,
  `stud_id_proof_type`           varchar(100)         DEFAULT NULL,
  `stud_id_proof_number`         varchar(100)         DEFAULT NULL,
  `institute_id`                 int(11)              DEFAULT NULL,
  `institute_code`               varchar(100)         DEFAULT NULL,
  `institute_name`               varchar(255)         DEFAULT NULL,
  `owner_name`                   varchar(255)         DEFAULT NULL,
  `institute_city`               varchar(100)         DEFAULT NULL,
  `institute_state`              varchar(100)         DEFAULT NULL,
  `institute_address`            text                 DEFAULT NULL,
  `institute_email`              varchar(255)         DEFAULT NULL,
  `institute_mobile`             varchar(20)          DEFAULT NULL,
  `institute_sign`               varchar(255)         DEFAULT NULL,
  `course_id`                    int(11)              DEFAULT NULL,
  `multi_sub_course_id`          int(11)              DEFAULT NULL,
  `typing_course_id`             int(11)              DEFAULT NULL,
  `course_name`                  varchar(255)         DEFAULT NULL,
  `course_name_computed`         varchar(255)         DEFAULT NULL,
  `multi_sub_course_name`        varchar(255)         DEFAULT NULL,
  `course_duration`              varchar(100)         DEFAULT NULL,
  `multi_sub_course_duration`    varchar(100)         DEFAULT NULL,
  `typing_course_duration`       varchar(100)         DEFAULT NULL,
  `exam_title`                   varchar(255)         DEFAULT NULL,
  `exam_type`                    varchar(100)         DEFAULT NULL,
  `exam_result_id`               int(11)              DEFAULT NULL,
  `exam_result_final_id`         int(11)              DEFAULT NULL,
  `subject`                      longtext             DEFAULT NULL,
  `objective_marks`              varchar(50)          DEFAULT NULL,
  `practical_marks`              varchar(50)          DEFAULT NULL,
  `marks_per`                    varchar(50)          DEFAULT NULL,
  `grade`                        varchar(10)          DEFAULT NULL,
  `result_status`                varchar(50)          DEFAULT NULL,
  `exam_fees`                    varchar(50)          DEFAULT NULL,
  `request_status`               varchar(50)          DEFAULT NULL,
  `marksheet_subjects_json`      longtext             DEFAULT NULL,
  `active`                       tinyint(4)           DEFAULT NULL,
  `delete_flag`                  tinyint(4)           DEFAULT NULL,
  `created_on`                   datetime             DEFAULT NULL,
  `created_by`                   varchar(255)         DEFAULT NULL,
  `request_created_on`           datetime             DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- 2. INSERT all certificate records
--
-- Source tables (mirrors export_certificates_worker.php query):
--   certificates_details    cd  — main record
--   certificate_requests    cr  — exam/request meta
--   student_details         sd  — student_code, son_of
--   institute_details       inst — institute info
--   city_master             cm  — institute city name
--   states_master           sm  — institute state name
--   courses                 co  — single-course computed name/duration
--   multi_sub_courses       msc — multi-sub course name/duration
--   courses_typing          ct  — typing course duration
--   institute_files         instf — institute sign filename
--
-- Rules applied:
--   • WHERE cd.DELETE_FLAG = 0  (exclude soft-deleted records)
--   • For marksheet, only single-course rows carry subject/marks
--     columns; multi_sub and typing rows naturally have NULL in
--     cd.SUBJECT / cd.OBJECTIVE_MARKS / cd.PRACTICAL_MARKS
--   • marksheet_subjects_json  → NULL  (not required)
--   • institute_sign → filename only (e.g. "sign_abc.jpg"),
--     full path: uploads/institute/docs/{institute_id}/{institute_sign}
-- ============================================================
INSERT INTO `laravel_certificates_export` (
    `certificate_details_id`,
    `certificate_request_id`,
    `certificate_request_master_id`,
    `certificate_file`,
    `certificate_serial_no`,
    `certificate_prefix`,
    `certificate_no`,
    `issue_date`,
    `issue_date_format`,
    `qr_file`,
    `student_id`,
    `student_code`,
    `student_name`,
    `student_fname`,
    `student_mname`,
    `student_lname`,
    `student_mother_name`,
    `student_father_name`,
    `son_of`,
    `student_photo`,
    `student_sign`,
    `student_dob`,
    `student_dob_format`,
    `stud_id_proof_type`,
    `stud_id_proof_number`,
    `institute_id`,
    `institute_code`,
    `institute_name`,
    `owner_name`,
    `institute_city`,
    `institute_state`,
    `institute_address`,
    `institute_email`,
    `institute_mobile`,
    `institute_sign`,
    `course_id`,
    `multi_sub_course_id`,
    `typing_course_id`,
    `course_name`,
    `course_name_computed`,
    `multi_sub_course_name`,
    `course_duration`,
    `multi_sub_course_duration`,
    `typing_course_duration`,
    `exam_title`,
    `exam_type`,
    `exam_result_id`,
    `exam_result_final_id`,
    `subject`,
    `objective_marks`,
    `practical_marks`,
    `marks_per`,
    `grade`,
    `result_status`,
    `exam_fees`,
    `request_status`,
    `marksheet_subjects_json`,
    `active`,
    `delete_flag`,
    `created_on`,
    `created_by`,
    `request_created_on`
)
SELECT
    -- Certificate identity
    cd.CERTIFICATE_DETAILS_ID,
    cd.CERTIFICATE_REQUEST_ID,
    cr.CERTIFICATE_REQUEST_MASTER_ID,

    -- Certificate document fields
    cd.CERTIFICATE_FILE,
    cd.CERTIFICATE_SERIAL_NO,
    cd.CERTIFICATE_PREFIX,
    cd.CERTIFICATE_NO,
    DATE_FORMAT(cd.ISSUE_DATE, '%Y-%m-%d')       AS issue_date,
    DATE_FORMAT(cd.ISSUE_DATE, '%d-%m-%Y')       AS issue_date_format,
    cd.QRFILE                                    AS qr_file,

    -- Student fields
    cd.STUDENT_ID,
    sd.STUDENT_CODE,
    cd.STUDENT_NAME,
    cd.STUDENT_FNAME,
    cd.STUDENT_MNAME,
    cd.STUDENT_LNAME,
    cd.STUDENT_MOTHER_NAME,
    cd.STUDENT_FATHER_NAME,
    sd.SONOF                                     AS son_of,
    cd.STUDENT_PHOTO,
    cd.STUDENT_SIGN,
    DATE_FORMAT(cd.STUDENT_DOB, '%Y-%m-%d')      AS student_dob,
    DATE_FORMAT(cd.STUDENT_DOB, '%d.%m.%Y')      AS student_dob_format,
    cd.STUD_ID_PROOF_TYPE,
    cd.STUD_ID_PROOF_NUMBER,

    -- Institute fields
    cd.INSTITUTE_ID,
    inst.INSTITUTE_CODE,
    cd.INSTITUTE_NAME,
    inst.INSTITUTE_OWNER_NAME                    AS owner_name,
    cm.CITY_NAME                                 AS institute_city,
    sm.STATE_NAME                                AS institute_state,
    inst.ADDRESS_LINE1                           AS institute_address,
    inst.EMAIL                                   AS institute_email,
    inst.MOBILE                                  AS institute_mobile,

    -- Institute sign: filename from institute_files (FILE_LABEL = 'sign')
    (
        SELECT instf.FILE_NAME
        FROM   institute_files instf
        WHERE  instf.INSTITUTE_ID = cd.INSTITUTE_ID
          AND  instf.FILE_LABEL   = 'sign'
        ORDER BY instf.FILE_ID ASC
        LIMIT 1
    )                                            AS institute_sign,

    -- Course identity
    cd.COURSE_ID,
    cd.MULTI_SUB_COURSE_ID,
    cd.TYPING_COURSE_ID,

    -- Course names / durations
    cd.COURSE_NAME,
    co.COURSE_NAME                               AS course_name_computed,
    msc.MULTI_SUB_COURSE_NAME,
    UPPER(co.COURSE_DURATION)                    AS course_duration,
    UPPER(msc.MULTI_SUB_COURSE_DURATION)         AS multi_sub_course_duration,
    UPPER(ct.TYPING_COURSE_DURATION)             AS typing_course_duration,

    -- Exam / request meta
    cr.EXAM_TITLE,
    cr.EXAM_TYPE,
    cr.EXAM_RESULT_ID,
    cr.EXAM_RESULT_FINAL_ID,

    -- Marksheet columns — single-course type only
    -- (multi-sub and typing rows have NULL here in certificates_details naturally)
    CASE
        WHEN cd.COURSE_ID IS NOT NULL AND cd.COURSE_ID != 0
        THEN cd.SUBJECT
        ELSE NULL
    END                                          AS subject,
    CASE
        WHEN cd.COURSE_ID IS NOT NULL AND cd.COURSE_ID != 0
        THEN cd.OBJECTIVE_MARKS
        ELSE NULL
    END                                          AS objective_marks,
    CASE
        WHEN cd.COURSE_ID IS NOT NULL AND cd.COURSE_ID != 0
        THEN cd.PRACTICAL_MARKS
        ELSE NULL
    END                                          AS practical_marks,
    CASE
        WHEN cd.COURSE_ID IS NOT NULL AND cd.COURSE_ID != 0
        THEN cd.MARKS_PER
        ELSE NULL
    END                                          AS marks_per,
    CASE
        WHEN cd.COURSE_ID IS NOT NULL AND cd.COURSE_ID != 0
        THEN cd.GRADE
        ELSE NULL
    END                                          AS grade,

    -- Result / status fields
    cr.RESULT_STATUS,
    cr.EXAM_FEES,
    cr.REQUEST_STATUS,

    -- marksheet_subjects_json intentionally NULL (not required)
    NULL                                         AS marksheet_subjects_json,

    -- Audit fields
    cd.ACTIVE,
    cd.DELETE_FLAG,
    DATE_FORMAT(cd.CREATED_ON,    '%Y-%m-%d %H:%i:%s') AS created_on,
    cd.CREATED_BY,
    DATE_FORMAT(cr.CREATED_ON,    '%Y-%m-%d %H:%i:%s') AS request_created_on

FROM certificates_details cd

-- Request meta
LEFT JOIN certificate_requests    cr   ON cd.CERTIFICATE_REQUEST_ID    = cr.CERTIFICATE_REQUEST_ID

-- Student info
LEFT JOIN student_details         sd   ON cd.STUDENT_ID                = sd.STUDENT_ID

-- Institute info
LEFT JOIN institute_details       inst ON cd.INSTITUTE_ID              = inst.INSTITUTE_ID
LEFT JOIN city_master             cm   ON inst.CITY                    = cm.CITY_ID
LEFT JOIN states_master           sm   ON inst.STATE                   = sm.STATE_ID

-- Course lookups
LEFT JOIN courses                 co   ON cd.COURSE_ID                 = co.COURSE_ID
LEFT JOIN multi_sub_courses       msc  ON cd.MULTI_SUB_COURSE_ID       = msc.MULTI_SUB_COURSE_ID
LEFT JOIN courses_typing          ct   ON cd.TYPING_COURSE_ID          = ct.TYPING_COURSE_ID

-- Only active (non-deleted) certificate records
WHERE cd.DELETE_FLAG = 0

ORDER BY cd.CERTIFICATE_DETAILS_ID ASC;
