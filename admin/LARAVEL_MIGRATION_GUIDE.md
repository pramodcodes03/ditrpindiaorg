# Laravel Migration Guide - Certificate System

## Excel Column Structure

### Single Table: `certificates`

| # | Column | Type | Source | Used By |
|---|--------|------|--------|---------|
| 1 | certificate_details_id | BIGINT PK | certificates_details.CERTIFICATE_DETAILS_ID | All views |
| 2 | certificate_request_id | INT | certificates_details.CERTIFICATE_REQUEST_ID | Internal |
| 3 | certificate_request_master_id | INT | certificate_requests.CERTIFICATE_REQUEST_MASTER_ID | List view |
| 4 | certificate_file | VARCHAR(255) | certificates_details.CERTIFICATE_FILE | Internal |
| 5 | certificate_serial_no | VARCHAR(50) | certificates_details.CERTIFICATE_SERIAL_NO | Internal |
| 6 | certificate_prefix | VARCHAR(50) | certificates_details.CERTIFICATE_PREFIX | Internal |
| 7 | **certificate_no** | VARCHAR(100) UNIQUE | certificates_details.CERTIFICATE_NO | **Verification code** |
| 8 | issue_date | DATE | certificates_details.ISSUE_DATE | Certificate, Marksheet, Verification |
| 9 | issue_date_format | VARCHAR(20) | Formatted dd-mm-yyyy | Certificate PDF |
| 10 | qr_file | VARCHAR(255) | certificates_details.QRFILE | Certificate PDF |
| 11 | student_id | INT | certificates_details.STUDENT_ID | All views |
| 12 | student_code | VARCHAR(50) | student_details.STUDENT_CODE | QR generation |
| 13 | student_name | VARCHAR(255) | certificates_details.STUDENT_NAME | All views |
| 14 | student_fname | VARCHAR(100) | certificates_details.STUDENT_FNAME | Marksheet, PDF filename |
| 15 | student_mname | VARCHAR(100) | certificates_details.STUDENT_MNAME (Father name) | Marksheet |
| 16 | student_lname | VARCHAR(100) | certificates_details.STUDENT_LNAME | Marksheet, PDF filename |
| 17 | student_mother_name | VARCHAR(100) | certificates_details.STUDENT_MOTHER_NAME | Marksheet |
| 18 | student_father_name | VARCHAR(100) | certificates_details.STUDENT_FATHER_NAME | Marksheet |
| 19 | son_of | VARCHAR(20) | certificates_details.SONOF | Certificate |
| 20 | student_photo | VARCHAR(255) | File path from get_stud_photo() | Certificate PDF, Verification |
| 21 | student_sign | VARCHAR(255) | File path from get_stud_sign() | Certificate PDF |
| 22 | student_dob | DATE | certificates_details.STUDENT_DOB | Marksheet |
| 23 | student_dob_format | VARCHAR(20) | Formatted dd.mm.yyyy | Marksheet PDF |
| 24 | stud_id_proof_type | VARCHAR(100) | certificates_details.STUD_ID_PROOF_TYPE | Certificate PDF |
| 25 | stud_id_proof_number | VARCHAR(100) | certificates_details.STUD_ID_PROOF_NUMBER | Certificate PDF |
| 26 | institute_id | INT | certificates_details.INSTITUTE_ID | All views |
| 27 | institute_code | VARCHAR(50) | From get_institute_code() | List view |
| 28 | institute_name | VARCHAR(255) | certificates_details.INSTITUTE_NAME | All views |
| 29 | owner_name | VARCHAR(255) | From get_institute_owner_name() | Certificate PDF |
| 30 | institute_city | VARCHAR(100) | From get_institute_city() | Certificate, Verification |
| 31 | institute_state | VARCHAR(100) | From get_institute_state() | Verification |
| 32 | institute_address | TEXT | From get_institute_address() | Verification |
| 33 | institute_email | VARCHAR(255) | From get_institute_email() | Verification |
| 34 | institute_mobile | VARCHAR(20) | From get_institute_mobile() | Verification |
| 35 | course_id | INT | certificates_details.COURSE_ID | All views |
| 36 | multi_sub_course_id | INT | certificates_details.MULTI_SUB_COURSE_ID | Marksheet |
| 37 | typing_course_id | INT | certificates_details.TYPING_COURSE_ID | Marksheet |
| 38 | course_name | VARCHAR(255) | certificates_details.COURSE_NAME | Certificate, Marksheet, Verification |
| 39 | course_name_computed | VARCHAR(255) | From get_course_title_modify() | Verification |
| 40 | multi_sub_course_name | VARCHAR(255) | From get_course_multi_sub_title_modify() | Verification |
| 41 | course_duration | VARCHAR(50) | From courses table | Marksheet, Verification |
| 42 | multi_sub_course_duration | VARCHAR(50) | From multi_sub_courses table | Marksheet, Verification |
| 43 | typing_course_duration | VARCHAR(50) | From courses_typing table | Marksheet, Verification |
| 44 | exam_title | VARCHAR(255) | certificate_requests.EXAM_TITLE | List view, SMS |
| 45 | exam_type | INT | certificate_requests.EXAM_TYPE | List view filter |
| 46 | exam_result_id | INT | certificate_requests.EXAM_RESULT_ID | Internal |
| 47 | exam_result_final_id | INT | certificate_requests.EXAM_RESULT_FINAL_ID | Marksheet lookup |
| 48 | subject | VARCHAR(255) | certificates_details.SUBJECT | Marksheet (single course) |
| 49 | objective_marks | DECIMAL(10,2) | certificates_details.OBJECTIVE_MARKS | Marksheet |
| 50 | practical_marks | DECIMAL(10,2) | certificates_details.PRACTICAL_MARKS | Marksheet |
| 51 | marks_per | DECIMAL(5,2) | certificates_details.MARKS_PER | All views |
| 52 | grade | VARCHAR(20) | certificates_details.GRADE | All views |
| 53 | result_status | INT | certificate_requests.RESULT_STATUS | List view |
| 54 | exam_fees | DECIMAL(10,2) | certificate_requests.EXAM_FEES | List view |
| 55 | request_status | INT | certificate_requests.REQUEST_STATUS | List view filter |
| 56 | **marksheet_subjects_json** | JSON/TEXT | Dynamic subject rows | **Marksheet PDF** |
| 57 | active | TINYINT | certificates_details.ACTIVE | Soft delete |
| 58 | delete_flag | TINYINT | certificates_details.DELETE_FLAG | Soft delete |
| 59 | created_on | DATETIME | certificates_details.CREATED_ON | Sorting |
| 60 | created_by | VARCHAR(100) | certificates_details.CREATED_BY | Audit |
| 61 | request_created_on | DATETIME | certificate_requests.CREATED_ON | List view |

---

## JSON Structure for `marksheet_subjects_json`

### Single Course (course_id > 0)
```json
[
  {
    "type": "single",
    "subject_name": "Computer Application",
    "objective_marks": 45,
    "practical_marks": 40,
    "total_marks": 85
  }
]
```

### Multi-Subject Course (multi_sub_course_id > 0)
```json
[
  {
    "type": "multi_sub",
    "subject_name": "MS Word",
    "exam_title": "Final Exam",
    "objective_marks": 42,
    "practical_marks": 38,
    "total_marks": 80
  },
  {
    "type": "multi_sub",
    "subject_name": "MS Excel",
    "exam_title": "Final Exam",
    "objective_marks": 45,
    "practical_marks": 40,
    "total_marks": 85
  }
]
```

### Typing Course (typing_course_id > 0)
```json
[
  {
    "type": "typing",
    "subject_name": "Hindi Typing",
    "exam_title": "Typing Test",
    "speed_wpm": 35,
    "minimum_marks": 25,
    "exam_total_marks": 100,
    "marks_obtained": 78,
    "total_marks": 78
  }
]
```

---

## Laravel Migration

```php
Schema::create('certificates', function (Blueprint $table) {
    $table->id('certificate_details_id');
    $table->unsignedInteger('certificate_request_id')->nullable();
    $table->unsignedInteger('certificate_request_master_id')->nullable();
    $table->string('certificate_file', 255)->nullable();
    $table->string('certificate_serial_no', 50)->nullable();
    $table->string('certificate_prefix', 50)->nullable();
    $table->string('certificate_no', 100)->unique();
    $table->date('issue_date')->nullable();
    $table->string('issue_date_format', 20)->nullable();
    $table->string('qr_file', 255)->nullable();

    $table->unsignedInteger('student_id');
    $table->string('student_code', 50)->nullable();
    $table->string('student_name', 255);
    $table->string('student_fname', 100)->nullable();
    $table->string('student_mname', 100)->nullable();
    $table->string('student_lname', 100)->nullable();
    $table->string('student_mother_name', 100)->nullable();
    $table->string('student_father_name', 100)->nullable();
    $table->string('son_of', 20)->nullable();
    $table->string('student_photo', 255)->nullable();
    $table->string('student_sign', 255)->nullable();
    $table->date('student_dob')->nullable();
    $table->string('student_dob_format', 20)->nullable();
    $table->string('stud_id_proof_type', 100)->nullable();
    $table->string('stud_id_proof_number', 100)->nullable();

    $table->unsignedInteger('institute_id');
    $table->string('institute_code', 50)->nullable();
    $table->string('institute_name', 255)->nullable();
    $table->string('owner_name', 255)->nullable();
    $table->string('institute_city', 100)->nullable();
    $table->string('institute_state', 100)->nullable();
    $table->text('institute_address')->nullable();
    $table->string('institute_email', 255)->nullable();
    $table->string('institute_mobile', 20)->nullable();

    $table->unsignedInteger('course_id')->default(0);
    $table->unsignedInteger('multi_sub_course_id')->default(0);
    $table->unsignedInteger('typing_course_id')->default(0);
    $table->string('course_name', 255)->nullable();
    $table->string('course_name_computed', 255)->nullable();
    $table->string('multi_sub_course_name', 255)->nullable();
    $table->string('course_duration', 50)->nullable();
    $table->string('multi_sub_course_duration', 50)->nullable();
    $table->string('typing_course_duration', 50)->nullable();

    $table->string('exam_title', 255)->nullable();
    $table->unsignedInteger('exam_type')->nullable();
    $table->unsignedInteger('exam_result_id')->nullable();
    $table->unsignedInteger('exam_result_final_id')->nullable();
    $table->string('subject', 255)->nullable();
    $table->decimal('objective_marks', 10, 2)->default(0);
    $table->decimal('practical_marks', 10, 2)->default(0);
    $table->decimal('marks_per', 5, 2)->default(0);
    $table->string('grade', 20)->nullable();
    $table->unsignedTinyInteger('result_status')->nullable();
    $table->decimal('exam_fees', 10, 2)->default(0);
    $table->unsignedTinyInteger('request_status')->nullable();

    $table->json('marksheet_subjects_json')->nullable();

    $table->boolean('active')->default(1);
    $table->boolean('delete_flag')->default(0);
    $table->string('created_by', 100)->nullable();
    $table->timestamps();
    $table->dateTime('request_created_on')->nullable();

    $table->index('student_id');
    $table->index('institute_id');
    $table->index('course_id');
    $table->index('certificate_no');
    $table->index('request_status');
});
```

---

## How Each View Maps to This Table

### 1. List View (admin)
```php
Certificate::where('delete_flag', 0)
    ->where('institute_id', $instituteId)
    ->orderBy('certificate_details_id', 'desc')
    ->paginate(20);
```
Uses: certificate_no, student_name, student_photo, course_name, marks_per, grade, result_status, request_status, issue_date, exam_title, institute_name

### 2. Certificate PDF (mPDF)
Uses: student_name, student_photo, student_sign, certificate_no, issue_date_format, course_name, marks_per, grade, institute_name, institute_city, owner_name, stud_id_proof_type, stud_id_proof_number, qr_file, course_duration/multi_sub_course_duration/typing_course_duration

### 3. Marksheet PDF (mPDF)
Uses: student_fname, student_mname (father), student_lname, student_mother_name, student_dob_format, institute_name, course_name, certificate_no, course_duration, **marksheet_subjects_json** (decode and loop), marks_per, grade

### 4. Verification Page
```php
Certificate::where('certificate_no', $code)
    ->where('delete_flag', 0)
    ->first();
```
Uses: certificate_no, student_name, student_photo, course_name, course_name_computed, multi_sub_course_name, marks_per, grade, issue_date, course_duration, institute_name, institute_email, institute_mobile, institute_address, institute_city, institute_state
