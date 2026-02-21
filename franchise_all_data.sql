-- ============================================================
-- Franchise All Data Table
-- Creates a consolidated table with all franchise/institute data
-- including login credentials, wallet balances, and approved status
-- (USER_ROLE = 8)
-- ============================================================

-- Step 1: Drop and recreate the table (clean slate)
DROP TABLE IF EXISTS `franchise_all_data`;

CREATE TABLE `franchise_all_data` (
    -- Institute Details columns
    `INSTITUTE_ID`           INT(11)        NOT NULL,
    `INSTITUTE_NAME`         VARCHAR(255)   DEFAULT NULL,
    `INSTITUTE_CODE`         VARCHAR(100)   DEFAULT NULL,
    `INSTITUTE_OWNER_NAME`         VARCHAR(100)   DEFAULT NULL,
    `AMC_CODE`               VARCHAR(100)   DEFAULT NULL,
    `EMAIL`                  VARCHAR(255)   DEFAULT NULL,
    `MOBILE`                 VARCHAR(20)    DEFAULT NULL,
    `CITY`                   VARCHAR(100)   DEFAULT NULL,
    `STATE`                  VARCHAR(100)   DEFAULT NULL,
    `STATE_NAME`             VARCHAR(150)   DEFAULT NULL,
    `POSTCODE`               VARCHAR(20)    DEFAULT NULL,
    `LOCATION`               TEXT           DEFAULT NULL,
    `address`               TEXT           DEFAULT NULL,
    `DOB`                    DATE           DEFAULT NULL,
    `ACTIVE`                 TINYINT(1)     DEFAULT NULL,
    `VERIFIED`               TINYINT(1)     DEFAULT NULL,
    `APPROVED`               VARCHAR(5)     DEFAULT NULL COMMENT 'YES or NO based on VERIFIED flag',
    `VERIFIED_ON`            DATETIME       DEFAULT NULL,
    `SHOW_ON_WEBSITE`        TINYINT(1)     DEFAULT NULL,
    `DELETE_FLAG`            TINYINT(1)     DEFAULT 0,
    `CREATED_ON`             DATETIME       DEFAULT NULL,
    `UPDATED_ON`             DATETIME       DEFAULT NULL,

    -- User Login columns (credentials)
    `USER_LOGIN_ID`          VARCHAR(255)   DEFAULT NULL,
    `USER_NAME`              VARCHAR(255)   DEFAULT NULL,
    `PASS_WORD`              VARCHAR(255)   DEFAULT NULL,
    `USER_ROLE`              INT(11)        DEFAULT NULL,
    `ACCOUNT_REGISTERED_ON`  DATE           DEFAULT NULL,
    `ACCOUNT_EXPIRED_ON`     DATE           DEFAULT NULL,

    -- Wallet balances
    `MAIN_WALLET_BALANCE`    DECIMAL(15,2)  DEFAULT 0.00 COMMENT 'From wallet table',
    `COURIER_WALLET_BALANCE` DECIMAL(15,2)  DEFAULT 0.00 COMMENT 'From courier_wallet table',

    -- Snapshot timestamp
    `SNAPSHOT_CREATED_AT`    DATETIME       DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (`INSTITUTE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Step 2: Insert all franchise data into the new table
-- ============================================================

INSERT INTO `franchise_all_data` (
    `INSTITUTE_ID`,
    `INSTITUTE_NAME`,
    `INSTITUTE_CODE`,
    `INSTITUTE_OWNER_NAME`,
    `AMC_CODE`,
    `EMAIL`,
    `MOBILE`,
    `CITY`,
    `STATE`,
    `STATE_NAME`,
    `POSTCODE`,
    `LOCATION`,
    `address`,
    `DOB`,
    `ACTIVE`,
    `VERIFIED`,
    `APPROVED`,
    `VERIFIED_ON`,
    `SHOW_ON_WEBSITE`,
    `DELETE_FLAG`,
    `CREATED_ON`,
    `UPDATED_ON`,
    `USER_LOGIN_ID`,
    `USER_NAME`,
    `PASS_WORD`,
    `USER_ROLE`,
    `ACCOUNT_REGISTERED_ON`,
    `ACCOUNT_EXPIRED_ON`,
    `MAIN_WALLET_BALANCE`,
    `COURIER_WALLET_BALANCE`
)
SELECT
    A.INSTITUTE_ID,
    A.INSTITUTE_NAME,
    A.INSTITUTE_CODE,
    A.INSTITUTE_OWNER_NAME,
    A.AMC_CODE,
    A.EMAIL,
    A.MOBILE,
    A.CITY,
    A.STATE,
    (SELECT SM.STATE_NAME FROM states_master SM WHERE SM.STATE_ID = A.STATE) AS STATE_NAME,
    A.POSTCODE,
    A.LOCATION,
    A.ADDRESS_LINE1,
    A.DOB,
    A.ACTIVE,
    A.VERIFIED,
    IF(A.VERIFIED = 1, 'YES', 'NO') AS APPROVED,
    A.VERIFIED_ON,
    A.SHOW_ON_WEBSITE,
    A.DELETE_FLAG,
    A.CREATED_ON,
    A.UPDATED_ON,
    B.USER_LOGIN_ID,
    B.USER_NAME,
    B.PASS_WORD,
    B.USER_ROLE,
    B.ACCOUNT_REGISTERED_ON,
    B.ACCOUNT_EXPIRED_ON,
    COALESCE(
        (SELECT TOTAL_BALANCE FROM wallet
         WHERE USER_ID = A.INSTITUTE_ID AND USER_ROLE = 8 AND DELETE_FLAG = 0
         ORDER BY WALLET_ID DESC LIMIT 1),
        0
    ) AS MAIN_WALLET_BALANCE,
    COALESCE(
        (SELECT TOTAL_BALANCE FROM courier_wallet
         WHERE USER_ID = A.INSTITUTE_ID AND USER_ROLE = 8 AND DELETE_FLAG = 0
         ORDER BY WALLET_ID ASC LIMIT 1),
        0
    ) AS COURIER_WALLET_BALANCE
FROM
    institute_details A
    LEFT JOIN user_login_master B ON A.INSTITUTE_ID = B.USER_ID
WHERE
    A.DELETE_FLAG = 0
    AND B.USER_ROLE = 8
ORDER BY
    A.CREATED_ON DESC;

-- ============================================================
-- Verification: Check record count and sample data after insert
-- ============================================================
SELECT COUNT(*) AS total_franchises_imported FROM franchise_all_data;

SELECT
    INSTITUTE_ID,
    INSTITUTE_NAME,
    INSTITUTE_CODE,
    USER_NAME,
    APPROVED,
    MAIN_WALLET_BALANCE,
    COURIER_WALLET_BALANCE
FROM franchise_all_data
ORDER BY CREATED_ON DESC
LIMIT 10;
