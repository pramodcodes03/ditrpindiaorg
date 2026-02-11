# Laravel Certificate & Marksheet System - Implementation Guide

## Overview

Single table approach: **`ditrp_gold_certificates`** — one Laravel table with the same structure as the source data. No normalization into multiple tables. The `marksheet_subjects_json` column holds all subject/marks data as JSON.

---

## Source Data

The data already exists in `laravel_certificates_export` table in the **core PHP project database**. Connect Laravel to the same MySQL database (or a second connection) and import it into `ditrp_gold_certificates`.

### `marksheet_subjects_json` Column Format:

This column contains a JSON array of subjects with marks. Three types:

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
- `course_id > 0` → **Single-subject course**
- `multi_sub_course_id > 0` → **Multi-subject course**
- `typing_course_id > 0` → **Typing course**

---

## STEP 1: Model with Migration

### Create model + migration:
```bash
php artisan make:model DitrpGoldCertificate -m
```

### Migration: `create_ditrp_gold_certificates_table`

```php
Schema::create('ditrp_gold_certificates', function (Blueprint $table) {
    $table->id();
    $table->integer('certificate_details_id')->nullable()->index();
    $table->integer('certificate_request_id')->nullable()->index();
    $table->integer('certificate_request_master_id')->nullable();
    $table->string('certificate_file', 255)->nullable();
    $table->string('certificate_serial_no', 100)->nullable();
    $table->string('certificate_prefix', 50)->nullable();
    $table->string('certificate_no', 100)->nullable()->index();
    $table->date('issue_date')->nullable();
    $table->string('issue_date_format', 20)->nullable();
    $table->string('qr_file', 255)->nullable();

    // Student
    $table->integer('student_id')->nullable()->index();
    $table->string('student_code', 100)->nullable()->index();
    $table->string('student_name', 255)->nullable();
    $table->string('student_fname', 255)->nullable();
    $table->string('student_mname', 255)->nullable();
    $table->string('student_lname', 255)->nullable();
    $table->string('student_mother_name', 255)->nullable();
    $table->string('student_father_name', 255)->nullable();
    $table->string('son_of', 255)->nullable();
    $table->string('student_photo', 255)->nullable();
    $table->string('student_sign', 255)->nullable();
    $table->date('student_dob')->nullable();
    $table->string('student_dob_format', 20)->nullable();
    $table->string('stud_id_proof_type', 100)->nullable();
    $table->string('stud_id_proof_number', 100)->nullable();

    // Institute
    $table->integer('institute_id')->nullable()->index();
    $table->string('institute_code', 100)->nullable();
    $table->string('institute_name', 255)->nullable();
    $table->string('owner_name', 255)->nullable();
    $table->string('institute_city', 100)->nullable();
    $table->string('institute_state', 100)->nullable();
    $table->text('institute_address')->nullable();
    $table->string('institute_email', 255)->nullable();
    $table->string('institute_mobile', 20)->nullable();

    // Course
    $table->integer('course_id')->nullable();
    $table->integer('multi_sub_course_id')->nullable();
    $table->integer('typing_course_id')->nullable();
    $table->string('course_name', 255)->nullable();
    $table->string('course_name_computed', 255)->nullable();
    $table->string('multi_sub_course_name', 255)->nullable();
    $table->string('course_duration', 100)->nullable();
    $table->string('multi_sub_course_duration', 100)->nullable();
    $table->string('typing_course_duration', 100)->nullable();

    // Exam & Results
    $table->string('exam_title', 255)->nullable();
    $table->string('exam_type', 100)->nullable();
    $table->integer('exam_result_id')->nullable();
    $table->integer('exam_result_final_id')->nullable();
    $table->string('subject', 255)->nullable();
    $table->string('objective_marks', 50)->nullable();
    $table->string('practical_marks', 50)->nullable();
    $table->string('marks_per', 50)->nullable();
    $table->string('grade', 10)->nullable();
    $table->string('result_status', 50)->nullable();
    $table->string('exam_fees', 50)->nullable();
    $table->string('request_status', 50)->nullable();

    // Marksheet JSON (all subjects with marks)
    $table->longText('marksheet_subjects_json')->nullable();

    // Verification
    $table->string('verification_token', 64)->nullable()->unique();

    // Flags
    $table->tinyInteger('active')->default(1);
    $table->tinyInteger('delete_flag')->default(0);
    $table->dateTime('created_on')->nullable();
    $table->integer('created_by')->nullable();
    $table->dateTime('request_created_on')->nullable();

    $table->timestamps();
});
```

### Model: `DitrpGoldCertificate`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DitrpGoldCertificate extends Model
{
    protected $table = 'ditrp_gold_certificates';

    protected $guarded = ['id'];

    protected $casts = [
        'issue_date' => 'date',
        'student_dob' => 'date',
        'created_on' => 'datetime',
        'request_created_on' => 'datetime',
        'marksheet_subjects_json' => 'array',
    ];

    /**
     * Get the course type for this certificate.
     */
    public function getCourseTypeAttribute(): string
    {
        if ($this->multi_sub_course_id && $this->multi_sub_course_id > 0) return 'multi_sub';
        if ($this->typing_course_id && $this->typing_course_id > 0) return 'typing';
        return 'single';
    }

    /**
     * Get the effective course name.
     */
    public function getEffectiveCourseNameAttribute(): string
    {
        if ($this->course_type === 'multi_sub') return $this->multi_sub_course_name ?? $this->course_name ?? '';
        return $this->course_name_computed ?? $this->course_name ?? '';
    }

    /**
     * Get the effective course duration.
     */
    public function getEffectiveCourseDurationAttribute(): string
    {
        if ($this->course_type === 'multi_sub') return $this->multi_sub_course_duration ?? '';
        if ($this->course_type === 'typing') return $this->typing_course_duration ?? '';
        return $this->course_duration ?? '';
    }

    /**
     * Get parsed marksheet subjects.
     */
    public function getMarksheetSubjectsAttribute(): array
    {
        return $this->marksheet_subjects_json ?? [];
    }

    /**
     * Get the verification URL.
     */
    public function getVerificationUrlAttribute(): string
    {
        return url("/verify/{$this->verification_token}");
    }

    /**
     * Scope for active, non-deleted records.
     */
    public function scopeValid($query)
    {
        return $query->where('active', 1)->where('delete_flag', 0);
    }

    /**
     * Scope for searching.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('student_name', 'LIKE', "%{$term}%")
              ->orWhere('student_code', 'LIKE', "%{$term}%")
              ->orWhere('certificate_no', 'LIKE', "%{$term}%")
              ->orWhere('institute_name', 'LIKE', "%{$term}%")
              ->orWhere('certificate_serial_no', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Boot: auto-generate verification token.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->verification_token)) {
                $model->verification_token = Str::uuid()->toString();
            }
        });
    }
}
```

---

## STEP 2: Import Data from `laravel_certificates_export`

### Create artisan command:
```bash
php artisan make:command ImportCertificates
```

### Command: `app/Console/Commands/ImportCertificates.php`

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportCertificates extends Command
{
    protected $signature = 'import:certificates {--fresh : Truncate table before import}';
    protected $description = 'Import certificates from laravel_certificates_export into ditrp_gold_certificates';

    public function handle()
    {
        if ($this->option('fresh')) {
            DB::table('ditrp_gold_certificates')->truncate();
            $this->info('Table truncated.');
        }

        $total = DB::table('laravel_certificates_export')->count();
        $this->info("Total records to import: {$total}");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $imported = 0;

        DB::table('laravel_certificates_export')
            ->orderBy('id')
            ->chunk(500, function ($rows) use (&$imported, $bar) {
                $insertData = [];
                foreach ($rows as $row) {
                    $insertData[] = [
                        'certificate_details_id' => $row->certificate_details_id,
                        'certificate_request_id' => $row->certificate_request_id,
                        'certificate_request_master_id' => $row->certificate_request_master_id,
                        'certificate_file' => $row->certificate_file,
                        'certificate_serial_no' => $row->certificate_serial_no,
                        'certificate_prefix' => $row->certificate_prefix,
                        'certificate_no' => $row->certificate_no,
                        'issue_date' => $row->issue_date,
                        'issue_date_format' => $row->issue_date_format,
                        'qr_file' => $row->qr_file,
                        'student_id' => $row->student_id,
                        'student_code' => $row->student_code,
                        'student_name' => $row->student_name,
                        'student_fname' => $row->student_fname,
                        'student_mname' => $row->student_mname,
                        'student_lname' => $row->student_lname,
                        'student_mother_name' => $row->student_mother_name,
                        'student_father_name' => $row->student_father_name,
                        'son_of' => $row->son_of,
                        'student_photo' => $row->student_photo,
                        'student_sign' => $row->student_sign,
                        'student_dob' => $row->student_dob,
                        'student_dob_format' => $row->student_dob_format,
                        'stud_id_proof_type' => $row->stud_id_proof_type,
                        'stud_id_proof_number' => $row->stud_id_proof_number,
                        'institute_id' => $row->institute_id,
                        'institute_code' => $row->institute_code,
                        'institute_name' => $row->institute_name,
                        'owner_name' => $row->owner_name,
                        'institute_city' => $row->institute_city,
                        'institute_state' => $row->institute_state,
                        'institute_address' => $row->institute_address,
                        'institute_email' => $row->institute_email,
                        'institute_mobile' => $row->institute_mobile,
                        'course_id' => $row->course_id,
                        'multi_sub_course_id' => $row->multi_sub_course_id,
                        'typing_course_id' => $row->typing_course_id,
                        'course_name' => $row->course_name,
                        'course_name_computed' => $row->course_name_computed,
                        'multi_sub_course_name' => $row->multi_sub_course_name,
                        'course_duration' => $row->course_duration,
                        'multi_sub_course_duration' => $row->multi_sub_course_duration,
                        'typing_course_duration' => $row->typing_course_duration,
                        'exam_title' => $row->exam_title,
                        'exam_type' => $row->exam_type,
                        'exam_result_id' => $row->exam_result_id,
                        'exam_result_final_id' => $row->exam_result_final_id,
                        'subject' => $row->subject,
                        'objective_marks' => $row->objective_marks,
                        'practical_marks' => $row->practical_marks,
                        'marks_per' => $row->marks_per,
                        'grade' => $row->grade,
                        'result_status' => $row->result_status,
                        'exam_fees' => $row->exam_fees,
                        'request_status' => $row->request_status,
                        'marksheet_subjects_json' => $row->marksheet_subjects_json,
                        'verification_token' => Str::uuid()->toString(),
                        'active' => $row->active,
                        'delete_flag' => $row->delete_flag,
                        'created_on' => $row->created_on,
                        'created_by' => $row->created_by,
                        'request_created_on' => $row->request_created_on,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('ditrp_gold_certificates')->insert($insertData);
                $imported += count($insertData);
                $bar->advance(count($insertData));
            });

        $bar->finish();
        $this->newLine();
        $this->info("Import complete! {$imported} records imported.");
    }
}
```

### Run import:
```bash
php artisan import:certificates --fresh
```

---

## STEP 3: Controllers & Routes

### Routes: `routes/web.php`

```php
// Admin routes (auth required)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{id}', [CertificateController::class, 'show'])->name('certificates.show');
    Route::get('/certificates/{id}/marksheet', [CertificateController::class, 'marksheet'])->name('certificates.marksheet');
});

// Public verification (no auth)
Route::get('/verify', [VerificationController::class, 'index'])->name('verify.index');
Route::post('/verify', [VerificationController::class, 'search'])->name('verify.search');
Route::get('/verify/{token}', [VerificationController::class, 'verify'])->name('verify.show');
Route::get('/verify/{token}/marksheet', [VerificationController::class, 'marksheet'])->name('verify.marksheet');
```

### CertificateController

```bash
php artisan make:controller CertificateController
```

```php
<?php

namespace App\Http\Controllers;

use App\Models\DitrpGoldCertificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    // List all certificates with search, filter, pagination
    public function index(Request $request)
    {
        $query = DitrpGoldCertificate::valid();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('course_type')) {
            $type = $request->course_type;
            if ($type === 'single') {
                $query->where('course_id', '>', 0)
                      ->where(function($q) {
                          $q->whereNull('multi_sub_course_id')->orWhere('multi_sub_course_id', 0);
                      })
                      ->where(function($q) {
                          $q->whereNull('typing_course_id')->orWhere('typing_course_id', 0);
                      });
            } elseif ($type === 'multi_sub') {
                $query->where('multi_sub_course_id', '>', 0);
            } elseif ($type === 'typing') {
                $query->where('typing_course_id', '>', 0);
            }
        }

        if ($request->filled('result_status')) {
            $query->where('result_status', $request->result_status);
        }

        $certificates = $query->orderByDesc('issue_date')->paginate(20);

        return view('certificates.index', compact('certificates'));
    }

    // View single certificate
    public function show($id)
    {
        $certificate = DitrpGoldCertificate::findOrFail($id);
        return view('certificates.show', compact('certificate'));
    }

    // View marksheet for a certificate
    public function marksheet($id)
    {
        $certificate = DitrpGoldCertificate::findOrFail($id);
        $subjects = $certificate->marksheet_subjects;
        return view('certificates.marksheet', compact('certificate', 'subjects'));
    }
}
```

### VerificationController

```bash
php artisan make:controller VerificationController
```

```php
<?php

namespace App\Http\Controllers;

use App\Models\DitrpGoldCertificate;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    // Show verification search page
    public function index()
    {
        return view('verification.index');
    }

    // Search by certificate number or student code
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $term = $request->query_input;

        $certificate = DitrpGoldCertificate::valid()
            ->where(function ($q) use ($term) {
                $q->where('certificate_no', $term)
                  ->orWhere('student_code', $term)
                  ->orWhere('certificate_serial_no', $term);
            })
            ->first();

        if (!$certificate) {
            return back()->with('error', 'Certificate not found. Please check the number and try again.');
        }

        return redirect()->route('verify.show', $certificate->verification_token);
    }

    // Show verified certificate (via token from QR code or search)
    public function verify($token)
    {
        $certificate = DitrpGoldCertificate::where('verification_token', $token)
            ->valid()
            ->first();

        if (!$certificate) {
            return view('verification.not_found');
        }

        return view('verification.verified', compact('certificate'));
    }

    // Show marksheet via verification token
    public function marksheet($token)
    {
        $certificate = DitrpGoldCertificate::where('verification_token', $token)
            ->valid()
            ->first();

        if (!$certificate) {
            return view('verification.not_found');
        }

        $subjects = $certificate->marksheet_subjects;
        return view('verification.marksheet', compact('certificate', 'subjects'));
    }
}
```

---

## STEP 4: Blade Views

### 4.1 `resources/views/certificates/index.blade.php` — List Certificates

- Paginated table: Certificate No, Student Name, Student Code, Course, Institute, Issue Date, Result, Actions
- Search bar (student name, code, certificate number)
- Filter dropdowns: Course Type (Single/Multi-Sub/Typing), Result (PASS/FAIL)
- Action buttons: View Certificate, View Marksheet

### 4.2 `resources/views/certificates/show.blade.php` — View Certificate

- Student photo and signature images
- Student info: name, father name, DOB, ID proof
- Institute info: name, code, city, state, address
- Course: `$certificate->effective_course_name`, `$certificate->effective_course_duration`
- Certificate: number, serial no, prefix, issue date, QR code image
- Result: status, grade, percentage
- Buttons: View Marksheet, Print, Back to List

### 4.3 `resources/views/certificates/marksheet.blade.php` — View Marksheet

- Student + Institute + Course header
- Subjects table built from `$subjects` array (parsed from `marksheet_subjects_json`):

```blade
@if(!empty($subjects))
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Subject</th>
            <th>Theory/Objective Marks</th>
            <th>Practical Marks</th>
            <th>Total Marks</th>
            @if($certificate->course_type === 'typing')
                <th>Speed (WPM)</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($subjects as $i => $sub)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $sub['subject_name'] ?? '' }}</td>
            @if(($sub['type'] ?? '') === 'typing')
                <td>{{ $sub['marks_obtained'] ?? '-' }}</td>
                <td>-</td>
                <td>{{ $sub['total_marks'] ?? '-' }}</td>
                <td>{{ $sub['speed_wpm'] ?? '-' }} WPM</td>
            @else
                <td>{{ $sub['objective_marks'] ?? '-' }}</td>
                <td>{{ $sub['practical_marks'] ?? '-' }}</td>
                <td>{{ $sub['total_marks'] ?? '-' }}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
@endif
```

- Grand total, result, grade, percentage at bottom
- Print button

### 4.4 `resources/views/verification/index.blade.php` — Public Verification Page

- Clean public layout (no admin sidebar)
- Organization logo + name
- Search form:
  - Input: "Enter Certificate Number or Student Code"
  - Submit: "Verify Certificate"
- Error flash message if not found

### 4.5 `resources/views/verification/verified.blade.php` — Verification Result

- Green banner: "Certificate is Valid and Verified ✓"
- Certificate details: number, student name, photo, course, institute, issue date, grade, result
- Marksheet subjects table (read-only)
- Print button
- "Verify Another" link

### 4.6 `resources/views/verification/not_found.blade.php` — Not Found

- Red banner: "Certificate Not Found ✗"
- Message: "The certificate number or code could not be verified."
- "Try Again" button

### 4.7 `resources/views/verification/marksheet.blade.php` — Public Marksheet View

- Same as certificates/marksheet but with public layout (no admin nav)
- Read-only, print-friendly

---

## STEP 5: QR Code Verification URL

Each certificate has a `verification_token`. The QR code should link to:
```
https://yourdomain.com/verify/{verification_token}
```

To generate QR codes for all certificates:

```bash
composer require simplesoftwareio/simple-qrcode
php artisan make:command GenerateQrCodes
```

```php
// In the command:
DitrpGoldCertificate::valid()->chunk(200, function ($certs) {
    foreach ($certs as $cert) {
        $url = route('verify.show', $cert->verification_token);
        $qrPath = storage_path("app/public/qrcodes/{$cert->certificate_no}.png");
        QrCode::format('png')->size(300)->generate($url, $qrPath);
        $cert->update(['qr_file' => "qrcodes/{$cert->certificate_no}.png"]);
    }
});
```

---

## STEP 6: Required Packages

```bash
composer require simplesoftwareio/simple-qrcode   # QR code generation
composer require barryvdh/laravel-dompdf           # PDF generation (optional)
```

---

## Execution Order

1. `php artisan make:model DitrpGoldCertificate -m` → edit migration with schema above
2. `php artisan migrate`
3. `php artisan make:command ImportCertificates` → add import logic above
4. `php artisan import:certificates --fresh` → import data from `laravel_certificates_export`
5. Create controllers: `CertificateController`, `VerificationController`
6. Add routes to `routes/web.php`
7. Create Blade views for list, show, marksheet, verification
8. `php artisan make:command GenerateQrCodes` → generate QR codes
9. Test verification at `/verify`
