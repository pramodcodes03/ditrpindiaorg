-- =============================================================================
-- UPDATE marksheet_subjects_json for TYPING courses
-- Table: laravel_certificates_export
-- =============================================================================
--
-- PURPOSE:
--   Populate the marksheet_subjects_json column for rows that belong to
--   typing courses (typing_course_id IS NOT NULL AND != 0).
--
-- SOURCE TABLES:
--   certificate_requests              -> links to result via EXAM_RESULT_TYPING_ID
--   course_typing_exam_result_final   -> final result header (ert)
--   course_typing_exam_result         -> per-subject result rows (er)
--   courses_typing_subjects           -> subject name + speed lookup (cts)
--   course_typing_exam_structure      -> holds TOTAL_MARKS (NOT used here; see note below)
--
-- NOTE on TOTAL_MARKS:
--   course_typing_exam_result does NOT have a TOTAL_MARKS column.
--   Columns confirmed from INSERT statement in coursetypingexam.class.php:
--     EXAM_RESULT_ID, STUD_COURSE_ID, STUDENT_ID, STUDENT_SUBJECT_ID, INSTITUTE_ID,
--     EXAM_ID, INSTITUTE_COURSE_ID, EXAM_TITLE, EXAM_TOTAL_MARKS, MARKS_OBTAINED,
--     EXAM_TYPE, CREATED_BY, CREATED_ON, CREATED_ON_IP, MINIMUM_MARKS
--   TOTAL_MARKS is a column of course_typing_exam_structure, not course_typing_exam_result.
--   For typing courses there is no practical component, so total_marks = marks_obtained.
--   This matches the JSON example in LARAVEL_MIGRATION_PROMPT.md where both values
--   are equal (e.g. marks_obtained=78, total_marks=78).
--
-- KEY DIFFERENCES vs. the multi_sub equivalent:
--   1. JOIN key on result_final : creq.EXAM_RESULT_TYPING_ID  (not EXAM_RESULT_FINAL_ID)
--   2. Result table              : course_typing_exam_result   (not multi_sub_exam_result)
--   3. Subject lookup table      : courses_typing_subjects     (not multi_sub_courses_subjects)
--   4. Subject PK/FK             : TYPING_COURSE_SUBJECT_ID   (not COURSE_SUBJECT_ID)
--   5. Subject name column       : TYPING_COURSE_SUBJECT_NAME (not COURSE_SUBJECT_NAME)
--   6. No ACTIVE = 1 filter      : typing results only require DELETE_FLAG = 0
--   7. Filter column on lce      : typing_course_id           (not multi_sub_course_id)
--   8. JSON 'type' value         : 'typing'                   (not 'multi_sub')
--   9. Marks fields              : marks_obtained, exam_total_marks, total_marks,
--                                  minimum_marks, speed_wpm  (no practical_marks)
--
-- JSON OBJECT STRUCTURE (matches export_certificates_worker.php):
--   {
--     "type"            : "typing",
--     "subject_name"    : "Hindi Typing",
--     "exam_title"      : "Typing Test",
--     "speed_wpm"       : 30,
--     "minimum_marks"   : 25.00,
--     "exam_total_marks": 100.00,
--     "marks_obtained"  : 78.00,
--     "total_marks"     : 78.00
--   }
--
-- ADJUST the id BETWEEN range before running.
-- =============================================================================

UPDATE laravel_certificates_export lce
SET marksheet_subjects_json = (
  SELECT CONCAT('[', GROUP_CONCAT(
    JSON_OBJECT(
      'type',             'typing',
      'subject_name',     cts.TYPING_COURSE_SUBJECT_NAME,
      'exam_title',       er.EXAM_TITLE,
      'speed_wpm',        CAST(cts.TYPING_COURSE_SPEED AS UNSIGNED),
      'minimum_marks',    CAST(er.MINIMUM_MARKS    AS DECIMAL(10,2)),
      'exam_total_marks', CAST(er.EXAM_TOTAL_MARKS AS DECIMAL(10,2)),
      'marks_obtained',   CAST(er.MARKS_OBTAINED   AS DECIMAL(10,2)),
      'total_marks',      CAST(er.MARKS_OBTAINED   AS DECIMAL(10,2))  -- no TOTAL_MARKS column; total = obtained for typing
    )
    ORDER BY er.EXAM_RESULT_ID ASC
  ), ']')
  FROM certificate_requests creq
  INNER JOIN course_typing_exam_result_final ert
         ON creq.EXAM_RESULT_TYPING_ID = ert.EXAM_RESULT_FINAL_ID
  INNER JOIN course_typing_exam_result er
         ON er.STUDENT_ID    = ert.STUDENT_ID
        AND er.INSTITUTE_ID  = ert.INSTITUTE_ID
        AND er.STUD_COURSE_ID = ert.STUD_COURSE_ID
  LEFT JOIN courses_typing_subjects cts
         ON er.STUDENT_SUBJECT_ID = cts.TYPING_COURSE_SUBJECT_ID
  WHERE creq.CERTIFICATE_REQUEST_ID = lce.certificate_request_id
    AND er.DELETE_FLAG = 0
)
WHERE lce.typing_course_id IS NOT NULL
  AND lce.typing_course_id != 0
  AND lce.id BETWEEN 1100 AND 1500;
