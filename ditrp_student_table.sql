-- ============================================================
-- Create and populate the ditrp_student table
-- Source tables: student_details, user_login_master, student_files
-- ============================================================

-- Step 1: Create the table
CREATE TABLE IF NOT EXISTS `ditrp_student` (
    `id`              INT(11)       NOT NULL COMMENT 'Student ID (from student_details.STUDENT_ID)',
    `franchise_id`    INT(11)       DEFAULT NULL COMMENT 'Institute / Franchise ID (from student_details.INSTITUTE_ID)',
    `first_name`      VARCHAR(100)  DEFAULT NULL COMMENT 'First name (STUDENT_FNAME)',
    `last_name`       VARCHAR(100)  DEFAULT NULL COMMENT 'Last name (STUDENT_LNAME)',
    `middle_name`     VARCHAR(100)  DEFAULT NULL COMMENT 'Middle name (STUDENT_MNAME)',
    `mother_name`     VARCHAR(100)  DEFAULT NULL COMMENT 'Mother name (STUDENT_MOTHERNAME)',
    `abbreviation`    VARCHAR(50)   DEFAULT NULL COMMENT 'Abbreviation / Title (ABBREVIATION)',
    `dob`             DATE          DEFAULT NULL COMMENT 'Date of birth (STUDENT_DOB)',
    `gender`          VARCHAR(10)   DEFAULT NULL COMMENT 'Gender (STUDENT_GENDER)',
    `student_mobile`  VARCHAR(15)   DEFAULT NULL COMMENT 'Student mobile (STUDENT_MOBILE)',
    `email`           VARCHAR(150)  DEFAULT NULL COMMENT 'Email = username + @gmail.com (USER_NAME from user_login_master)',
    `profile_image`   VARCHAR(255)  DEFAULT NULL COMMENT 'Profile photo filename (student_files where FILE_LABEL = STUD_PHOTO)',
    `signature`       VARCHAR(255)  DEFAULT NULL COMMENT 'Signature filename (student_files where FILE_LABEL = STUD_PHOTO_SIGN)',
    `state_id`        VARCHAR(100)  DEFAULT NULL COMMENT 'State (STUDENT_STATE)',
    `city`            VARCHAR(100)  DEFAULT NULL COMMENT 'City (STUDENT_CITY)',
    `pincode`         VARCHAR(10)   DEFAULT NULL COMMENT 'Pincode (STUDENT_PINCODE)',
    `qualification`   VARCHAR(150)  DEFAULT NULL COMMENT 'Educational qualification (EDUCATIONAL_QUALIFICATION)',
    `cast`            VARCHAR(100)  DEFAULT NULL COMMENT 'Caste (CASTE)',
    `marital_status`  VARCHAR(20)   DEFAULT NULL COMMENT 'Marital status (not in source schema – stored manually)',
    `address`         TEXT          DEFAULT NULL COMMENT 'Permanent address (STUDENT_PER_ADD)',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- Step 2: Populate ditrp_student from existing tables
-- ============================================================
INSERT INTO `ditrp_student`
    (
        `id`,
        `franchise_id`,
        `first_name`,
        `last_name`,
        `middle_name`,
        `mother_name`,
        `abbreviation`,
        `dob`,
        `gender`,
        `student_mobile`,
        `email`,
        `profile_image`,
        `signature`,
        `state_id`,
        `city`,
        `pincode`,
        `qualification`,
        `cast`,
        `marital_status`,
        `address`
    )
SELECT
    sd.STUDENT_ID                                           AS id,
    sd.INSTITUTE_ID                                         AS franchise_id,
    sd.STUDENT_FNAME                                        AS first_name,
    sd.STUDENT_LNAME                                        AS last_name,
    sd.STUDENT_MNAME                                        AS middle_name,
    sd.STUDENT_MOTHERNAME                                   AS mother_name,
    sd.ABBREVIATION                                         AS abbreviation,
    sd.STUDENT_DOB                                          AS dob,
    sd.STUDENT_GENDER                                       AS gender,
    sd.STUDENT_MOBILE                                       AS student_mobile,
    CONCAT(ulm.USER_NAME, '@gmail.com')                     AS email,
    -- profile image: latest active photo from student_files
    (
        SELECT sf1.FILE_NAME
        FROM   student_files sf1
        WHERE  sf1.STUDENT_ID  = sd.STUDENT_ID
          AND  sf1.FILE_LABEL  = 'STUD_PHOTO'
          AND  sf1.DELETE_FLAG = 0
          AND  sf1.ACTIVE      = 1
        ORDER  BY sf1.FILE_ID DESC
        LIMIT  1
    )                                                       AS profile_image,
    -- signature: latest active signature from student_files
    (
        SELECT sf2.FILE_NAME
        FROM   student_files sf2
        WHERE  sf2.STUDENT_ID  = sd.STUDENT_ID
          AND  sf2.FILE_LABEL  = 'STUD_PHOTO_SIGN'
          AND  sf2.DELETE_FLAG = 0
          AND  sf2.ACTIVE      = 1
        ORDER  BY sf2.FILE_ID DESC
        LIMIT  1
    )                                                       AS signature,
    sd.STUDENT_STATE                                        AS state_id,
    sd.STUDENT_CITY                                         AS city,
    sd.STUDENT_PINCODE                                      AS pincode,
    sd.EDUCATIONAL_QUALIFICATION                            AS qualification,
    sd.CASTE                                                AS cast,
    NULL                                                    AS marital_status,  -- no source column exists yet
    sd.STUDENT_PER_ADD                                      AS address

FROM student_details sd
LEFT JOIN user_login_master ulm
       ON ulm.USER_ID   = sd.STUDENT_ID
      AND ulm.USER_ROLE = 4          -- role 4 = student

WHERE sd.DELETE_FLAG = 0

-- avoid duplicate inserts on re-run
ON DUPLICATE KEY UPDATE
    franchise_id    = VALUES(franchise_id),
    first_name      = VALUES(first_name),
    last_name       = VALUES(last_name),
    middle_name     = VALUES(middle_name),
    mother_name     = VALUES(mother_name),
    abbreviation    = VALUES(abbreviation),
    dob             = VALUES(dob),
    gender          = VALUES(gender),
    student_mobile  = VALUES(student_mobile),
    email           = VALUES(email),
    profile_image   = VALUES(profile_image),
    signature       = VALUES(signature),
    state_id        = VALUES(state_id),
    city            = VALUES(city),
    pincode         = VALUES(pincode),
    qualification   = VALUES(qualification),
    cast            = VALUES(cast),
    address         = VALUES(address);
