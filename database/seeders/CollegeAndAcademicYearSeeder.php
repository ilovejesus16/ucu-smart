<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\AcademicYear;
use Illuminate\Database\Seeder;

class CollegeAndAcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        
        $colleges = [
            ['CAS', 'College of Arts & Sciences', '#2563EB'],
            ['CBMA', 'College of Business & Management Accounting', '#16A34A'],
            ['CCJE', 'College of Criminal Justice Education', '#9333EA'],
            ['CEA', 'College of Engineering & Architecture', '#EAB308'],
            ['CHS', 'College of Health Sciences', '#EC4899'],
            ['CHTM', 'College of Hospitality & Tourism Management', '#F97316'],
            ['CHUMS', 'College of Human Sciences', '#06B6D4'],
            ['CITE', 'College of Information & Technology Education', '#DC2626'],
            ['COP', 'College of Pharmacy', '#14B8A8'],
            ['CTE', 'College of Teacher Education', '#A855F7'],
            ['IGAS', 'Institute of Graduate & Advance Studies', '#64748B'],
            ['COL', 'College of Law', '#854D0E'],
        ];

        foreach ($colleges as [$code, $name, $color]) {
            College::updateOrCreate(
                ['college_code' => $code],
                [
                    'college_name' => $name,
                    'color' => $color,
                ]
            );
        }

        // Set the 2026–2027 academic year as active.
        AcademicYear::updateOrCreate(
            ['year_label' => '2026-2027'],
            [
                'is_active' => true,
            ]
        );
    }
}
