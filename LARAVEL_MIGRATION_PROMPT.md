# Laravel Certificate & Marksheet System - Implementation Guide

## Source Data

All certificate/marksheet data has been exported into a MySQL table called `laravel_certificates_export` in the existing database. Laravel should connect to the **same database** and read from this table for import.

### `laravel_certificates_export` Table Columns:

| Column | Type | Description |
|--------|------|-------------|
| id | INT (auto) | Auto-increment primary key |
| certificate_details_id | INT | Original certificate detail ID from core PHP system |
| certificate_request_id | INT | Certificate request ID |
| certificate_request_master_id | INT | Master request batch ID |
| certificate_file | VARCHAR | PDF filename of the certificate |
| certificate_serial_no | VARCHAR | Serial number |
| certificate_prefix | VARCHAR | Prefix (e.g., "DITRP") |
| certificate_no | VARCHAR | Certificate number |
| issue_date | DATE | Issue date (Y-m-d) |
| issue_date_format | VARCHAR | Issue date formatted (d-m-Y) |
| qr_file | VARCHAR | QR code image filename |
| student_id | INT | Student ID |
| student_code | VARCHAR | Student unique code |
| student_name | VARCHAR | Full name |
| student_fname | VARCHAR | First name |
| student_mname | VARCHAR | Middle name |
| student_lname | VARCHAR | Last name |
| student_mother_name | VARCHAR | Mother's name |
| student_father_name | VARCHAR | Father's name |
| son_of | VARCHAR | Son/Daughter of |
| student_photo | VARCHAR | Photo filename |
| student_sign | VARCHAR | Signature filename |
| student_dob | DATE | Date of birth |
| student_dob_format | VARCHAR | DOB formatted (d.m.Y) |
| stud_id_proof_type | VARCHAR | ID proof type (Aadhar, PAN, etc.) |
| stud_id_proof_number | VARCHAR | ID proof number |
| institute_id | INT | Institute ID |
| institute_code | VARCHAR | Institute unique code |
| institute_name | VARCHAR | Institute name |
| owner_name | VARCHAR | Institute owner name |
| institute_city | VARCHAR | City name |
| institute_state | VARCHAR | State name |
| institute_address | TEXT | Full address |
| institute_email | VARCHAR | Email |
| institute_mobile | VARCHAR | Mobile number |
| course_id | INT | Single-subject course ID (NULL if multi-sub or typing) |
| multi_sub_course_id | INT | Multi-subject course ID (NULL if single or typing) |
| typing_course_id | INT | Typing course ID (NULL if single or multi-sub) |
| course_name | VARCHAR | Course name from certificate |
| course_name_computed | VARCHAR | Course name from courses master table |
| multi_sub_course_name | VARCHAR | Multi-subject course name |
| course_duration | VARCHAR | Single course duration (e.g., "6 MONTHS", "1 YEAR") |
| multi_sub_course_duration | VARCHAR | Multi-sub course duration |
| typing_course_duration | VARCHAR | Typing course duration |
| exam_title | VARCHAR | Exam title |
| exam_type | VARCHAR | Exam type |
| exam_result_id | INT | Exam result ID |
| exam_result_final_id | INT | Final exam result ID |
| subject | VARCHAR | Subject name (for single-subject courses) |
| objective_marks | VARCHAR | Theory/objective marks |
| practical_marks | VARCHAR | Practical marks |
| marks_per | VARCHAR | Percentage |
| grade | VARCHAR | Grade (A, B, C, etc.) |
| result_status | VARCHAR | Result status (PASS/FAIL) |
| exam_fees | VARCHAR | Exam fees |
| request_status | VARCHAR | Request status |
| marksheet_subjects_json | LONGTEXT | JSON array of all subjects with marks (see below) |
| active | TINYINT | Active flag (1=active) |
| delete_flag | TINYINT | Soft delete (0=not deleted) |
| created_on | DATETIME | Record creation date |
| created_by | INT | Created by user ID |
| request_created_on | DATETIME | Certificate request creation date |

### `marksheet_subjects_json` Format:

This column contains a JSON array of subjects with marks. Three types exist:

**Single subject:**
```json
[{"type":"single","subject_name":"DCA","objective_marks":45,"practical_marks":40,"total_marks":85}]
```

**Multi-subject:**
```json
[
  {"type":"multi_sub","subject_name":"Computer Fundamentals","exam_title":"Theory","objective_marks":78,"practical_marks":65,"total_marks":143},
  {"type":"multi_sub","subject_name":"MS Office","exam_title":"Theory","objective_marks":82,"practical_marks":70,"total_marks":152}
]
```

**Typing course:**
```json
[
  {"type":"typing","subject_name":"Hindi Typing","exam_title":"Typing Test","speed_wpm":30,"minimum_marks":25,"exam_total_marks":100,"marks_obtained":78,"total_marks":78},
  {"type":"typing","subject_name":"English Typing","exam_title":"Typing Test","speed_wpm":35,"minimum_marks":25,"exam_total_marks":100,"marks_obtained":85,"total_marks":85}
]
```

### Course Type Logic:
- If `course_id` is NOT NULL and > 0 → **Single-subject course**
- If `multi_sub_course_id` is NOT NULL and > 0 → **Multi-subject course**
- If `typing_course_id` is NOT NULL and > 0 → **Typing course**
- A certificate can belong to one or more course types

---

## STEP 1: Database Schema & Models

### Create these Laravel migrations and models:

#### 1.1 Students Table
```
php artisan make:model Student -m
```

**Migration columns:**
- `id` - bigIncrements
- `legacy_student_id` - integer, nullable (maps to `student_id` from export)
- `student_code` - string, unique
- `first_name` - string
- `middle_name` - string, nullable
- `last_name` - string, nullable
- `full_name` - string
- `mother_name` - string, nullable
- `father_name` - string, nullable
- `son_daughter_of` - string, nullable
- `photo` - string, nullable (filename/path)
- `signature` - string, nullable (filename/path)
- `date_of_birth` - date, nullable
- `id_proof_type` - string, nullable
- `id_proof_number` - string, nullable
- `timestamps`
- `softDeletes`

**Indexes:** `legacy_student_id`, `student_code`

#### 1.2 Institutes Table
```
php artisan make:model Institute -m
```

**Migration columns:**
- `id` - bigIncrements
- `legacy_institute_id` - integer, nullable
- `institute_code` - string, unique
- `name` - string
- `owner_name` - string, nullable
- `city` - string, nullable
- `state` - string, nullable
- `address` - text, nullable
- `email` - string, nullable
- `mobile` - string, nullable
- `timestamps`
- `softDeletes`

**Indexes:** `legacy_institute_id`, `institute_code`

#### 1.3 Courses Table
```
php artisan make:model Course -m
```

**Migration columns:**
- `id` - bigIncrements
- `legacy_course_id` - integer, nullable (maps to course_id / multi_sub_course_id / typing_course_id)
- `course_type` - enum: ['single', 'multi_sub', 'typing']
- `name` - string
- `duration` - string, nullable (e.g., "6 MONTHS", "1 YEAR")
- `timestamps`
- `softDeletes`

**Indexes:** `legacy_course_id`, `course_type`

#### 1.4 Certificates Table
```
php artisan make:model Certificate -m
```

**Migration columns:**
- `id` - bigIncrements
- `legacy_certificate_details_id` - integer, nullable
- `legacy_certificate_request_id` - integer, nullable
- `student_id` - foreignId → students table
- `institute_id` - foreignId → institutes table
- `course_id` - foreignId → courses table
- `certificate_file` - string, nullable (PDF filename)
- `serial_no` - string, nullable
- `prefix` - string, nullable
- `certificate_no` - string, nullable, unique
- `qr_code_file` - string, nullable
- `issue_date` - date, nullable
- `exam_title` - string, nullable
- `exam_type` - string, nullable
- `result_status` - string, nullable (PASS/FAIL)
- `grade` - string, nullable
- `marks_percentage` - string, nullable
- `total_objective_marks` - decimal(8,2), nullable
- `total_practical_marks` - decimal(8,2), nullable
- `exam_fees` - decimal(8,2), nullable
- `request_status` - string, nullable
- `verification_token` - string, unique, nullable (for public verification URL)
- `is_active` - boolean, default true
- `issued_at` - datetime, nullable
- `timestamps`
- `softDeletes`

**Indexes:** `legacy_certificate_details_id`, `certificate_no`, `serial_no`, `verification_token`

#### 1.5 Certificate Subjects (Marksheet) Table
```
php artisan make:model CertificateSubject -m
```

**Migration columns:**
- `id` - bigIncrements
- `certificate_id` - foreignId → certificates table
- `subject_type` - enum: ['single', 'multi_sub', 'typing']
- `subject_name` - string
- `exam_title` - string, nullable
- `objective_marks` - decimal(8,2), nullable
- `practical_marks` - decimal(8,2), nullable
- `total_marks` - decimal(8,2), nullable
- `speed_wpm` - integer, nullable (for typing courses only)
- `minimum_marks` - decimal(8,2), nullable (for typing courses only)
- `exam_total_marks` - decimal(8,2), nullable (for typing courses only)
- `marks_obtained` - decimal(8,2), nullable (for typing courses only)
- `sort_order` - integer, default 0
- `timestamps`

**Indexes:** `certificate_id`, `subject_type`

---

## STEP 2: Import Data from `laravel_certificates_export`

### Create an Artisan command:
```
php artisan make:command ImportCertificates
```

**Command name:** `import:certificates`

### Import Logic:

The command should read from `laravel_certificates_export` table (same database) and populate the normalized Laravel tables.

```php
// Pseudocode for the import command:

// 1. Read all rows from laravel_certificates_export
// 2. For each row:
//    a. Find or create Student (by legacy_student_id)
//    b. Find or create Institute (by legacy_institute_id)
//    c. Determine course type and find or create Course
//    d. Create Certificate record
//    e. Parse marksheet_subjects_json and create CertificateSubject records
//    f. Generate verification_token (use Str::uuid() or hash of certificate_no)

// Process in chunks of 200 for memory efficiency:
DB::table('laravel_certificates_export')
    ->where('delete_flag', 0)
    ->orderBy('id')
    ->chunk(200, function ($rows) {
        foreach ($rows as $row) {
            // ... import logic
        }
    });
```

**Important rules for import:**
- Use `firstOrCreate()` for students, institutes, courses to avoid duplicates
- Match students by `legacy_student_id` (the `student_id` from export)
- Match institutes by `legacy_institute_id`
- For courses: determine type from which ID is non-null:
  - `course_id > 0` → type = 'single', `legacy_course_id = course_id`, name = `course_name_computed` or `course_name`
  - `multi_sub_course_id > 0` → type = 'multi_sub', `legacy_course_id = multi_sub_course_id`, name = `multi_sub_course_name`
  - `typing_course_id > 0` → type = 'typing', `legacy_course_id = typing_course_id`, name = `course_name`
- Parse `marksheet_subjects_json` with `json_decode()` and create `CertificateSubject` rows
- Generate `verification_token` = `Str::uuid()` for each certificate
- Wrap each chunk in a DB transaction
- Log progress: "Imported 200/15000 certificates..."
- Handle NULL/empty values gracefully

---

## STEP 3: Display Certificate & Marksheet Data

### 3.1 List All Certificates Page

**Route:** `GET /admin/certificates`
**Controller:** `CertificateController@index`

**Features:**
- Paginated table (15-25 per page) showing: Serial No, Student Name, Student Code, Institute, Course, Issue Date, Status, Actions
- Search by: student name, student code, certificate number, institute name
- Filter by: course type (single/multi-sub/typing), result status (PASS/FAIL), date range
- Sort by: issue date, student name, certificate number
- Export to Excel/PDF option

### 3.2 View Certificate Page

**Route:** `GET /admin/certificates/{id}`
**Controller:** `CertificateController@show`

**Display:**
- Certificate details: number, serial no, prefix, issue date
- Student info: photo, name, father name, DOB, ID proof
- Institute info: name, code, city, state, owner
- Course info: name, type, duration
- Result: status, grade, percentage, marks
- QR code image
- Link to view marksheet
- Link to download original PDF (if certificate_file exists)
- Button: "Print Certificate"

### 3.3 View Marksheet Page

**Route:** `GET /admin/certificates/{id}/marksheet`
**Controller:** `CertificateController@marksheet`

**Display:**
- Student details header (name, code, photo, father name, DOB)
- Institute details (name, code, address)
- Course details (name, duration, type)
- **Subjects table:**
  - For single: Subject | Theory Marks | Practical Marks | Total
  - For multi_sub: Subject | Exam Title | Theory Marks | Practical Marks | Total
  - For typing: Subject | Speed (WPM) | Min Marks | Total Marks | Marks Obtained | Total
- Grand total row
- Result: PASS/FAIL, Grade, Percentage
- Certificate number and issue date at bottom
- Button: "Print Marksheet"

### 3.4 Print-Friendly Views

Create separate Blade views for printing:
- `certificates.print-certificate` - Clean certificate layout for printing
- `certificates.print-marksheet` - Clean marksheet layout for printing

Use `@media print` CSS to hide navigation, buttons, etc.

---

## STEP 4: Public Verification System

### 4.1 Certificate Verification Page

**Route:** `GET /verify` (public, no auth required)
**Route:** `GET /verify/{token}` (direct verification link)
**Controller:** `VerificationController@index` / `VerificationController@verify`

**Verification Page (`/verify`):**
- Clean public-facing page (not admin layout)
- Organization logo and name at top
- Search form with fields:
  - Certificate Number (text input)
  - OR Student Code (text input)
  - OR Scan QR Code (camera-based QR scanner using JavaScript)
- Submit button: "Verify Certificate"

**Verification Result (`/verify/{token}` or POST `/verify`):**

**If found (valid certificate):**
- Green success banner: "Certificate is Valid and Verified"
- Show: Certificate No, Student Name, Student Photo, Course Name, Institute Name, Issue Date, Grade, Result Status
- Show marksheet subjects table (read-only)
- Print button

**If not found:**
- Red error banner: "Certificate Not Found"
- Message: "The certificate number or code you entered could not be verified. Please check and try again."

### 4.2 QR Code Verification

Each certificate has a QR code. The QR code should encode the verification URL:
```
https://yourdomain.com/verify/{verification_token}
```

When someone scans the QR code, it opens the verification page directly showing the certificate details.

**Update existing QR codes:** Create an artisan command to regenerate QR codes with the new verification URL:
```
php artisan certificates:generate-qr
```

Use the `simplesoftwareio/simple-qrcode` package or `chillerlan/php-qrcode`.

### 4.3 Marksheet Verification

**Route:** `GET /verify/{token}/marksheet`

Same as certificate verification but shows the full marksheet with all subjects and marks.

---

## STEP 5: Model Relationships

### Student Model
```php
class Student extends Model {
    use SoftDeletes;

    public function certificates() {
        return $this->hasMany(Certificate::class);
    }
}
```

### Institute Model
```php
class Institute extends Model {
    use SoftDeletes;

    public function certificates() {
        return $this->hasMany(Certificate::class);
    }
}
```

### Course Model
```php
class Course extends Model {
    use SoftDeletes;

    public function certificates() {
        return $this->hasMany(Certificate::class);
    }

    public function isSingle() { return $this->course_type === 'single'; }
    public function isMultiSub() { return $this->course_type === 'multi_sub'; }
    public function isTyping() { return $this->course_type === 'typing'; }
}
```

### Certificate Model
```php
class Certificate extends Model {
    use SoftDeletes;

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function institute() {
        return $this->belongsTo(Institute::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function subjects() {
        return $this->hasMany(CertificateSubject::class)->orderBy('sort_order');
    }

    public function getVerificationUrlAttribute() {
        return url("/verify/{$this->verification_token}");
    }
}
```

### CertificateSubject Model
```php
class CertificateSubject extends Model {

    public function certificate() {
        return $this->belongsTo(Certificate::class);
    }
}
```

---

## STEP 6: Required Laravel Packages

```bash
composer require maatwebsite/excel          # For Excel export
composer require simplesoftwareio/simple-qrcode  # For QR code generation
composer require barryvdh/laravel-dompdf     # For PDF generation (optional)
```

---

## File/Image Paths

Student photos and signatures from the core PHP system are stored at:
- Photos: `uploads/student_photos/{filename}`
- Signatures: `uploads/student_signs/{filename}`
- QR codes: `uploads/qr_codes/{filename}` or `certificates/qr/{filename}`
- Certificate PDFs: `uploads/certificates/{filename}`

You may need to:
1. Copy these files to Laravel's `storage/app/public/` directory
2. Or create symbolic links to the old upload directory
3. Or update the paths in the database after import

---

## Summary of Artisan Commands to Create

| Command | Purpose |
|---------|---------|
| `php artisan make:model Student -m` | Student model + migration |
| `php artisan make:model Institute -m` | Institute model + migration |
| `php artisan make:model Course -m` | Course model + migration |
| `php artisan make:model Certificate -m` | Certificate model + migration |
| `php artisan make:model CertificateSubject -m` | Subject/marks model + migration |
| `php artisan make:command ImportCertificates` | Import from laravel_certificates_export |
| `php artisan make:controller CertificateController --resource` | CRUD controller |
| `php artisan make:controller VerificationController` | Public verification |
| `php artisan make:command GenerateQrCodes` | Regenerate QR codes with verification URLs |

---

## Execution Order

1. Run migrations: `php artisan migrate`
2. Import data: `php artisan import:certificates`
3. Generate QR codes: `php artisan certificates:generate-qr`
4. Build views and controllers
5. Test verification system
