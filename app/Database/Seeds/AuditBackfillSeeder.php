<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AuditBackfillSeeder extends Seeder
{
    /**
     * Tables that require audit backfill.
     *
     * @var list<string>
     */
    protected array $tables = [
        'students',
        'classes',
        'sections',
        'class_sections',
        'sessions',
        'tz_web_setting',
        'tz_exams',
        'tz_exam_classes',
        'tz_exam_results',
        'tz_exam_subjects',
        'tz_exam_subject_marks',
        'tz_alevel_combinations',
        'tz_alevel_combination_subjects',
        'tz_alevel_exam_results',
        'tz_alevel_subject_marks',
        'tz_alevel_exam_combinations',
        'tz_student_alevel_combinations',
        'student_session',
    ];

    public function run(): void
    {
        $db = \Config\Database::connect();
        $systemUser = $this->deterministicUuid('system-user');
        $defaultSchool = $this->deterministicUuid('default-school');
        $now = date('Y-m-d H:i:s');

        foreach ($this->tables as $table) {
            $builder = $db->table($table);

            $db->transStart();

            $builder
                ->set('created_by', $systemUser)
                ->where('created_by', null)
                ->update();

            $builder
                ->set('updated_by', $systemUser)
                ->where('updated_by', null)
                ->update();

            $builder
                ->set('school_id', $defaultSchool)
                ->where('school_id', null)
                ->update();

            $builder
                ->set('created_at', $now)
                ->where('created_at', null)
                ->update();

            $builder
                ->set('updated_at', $now)
                ->where('updated_at', null)
                ->update();

            $db->transComplete();
        }
    }

    private function deterministicUuid(string $seed): string
    {
        $hash = md5('audit-seed:' . $seed);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split($hash, 4));
    }
}
