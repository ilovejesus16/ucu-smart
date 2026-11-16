<?php

namespace App\Console\Commands;

use App\Models\Schedule as ClassSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateClassOccurrences extends Command
{
    protected $signature = 'classes:generate-occurrences';

    protected $description = 'Generate individual class occurrences for scheduled dates';

    public function handle(): int
    {
        $schedules = ClassSchedule::all();

        $created = 0;

        for ($i = 0; $i <= 30; $i++) {
            $date = Carbon::today()->addDays($i);

            foreach ($schedules as $schedule) {
                if (!$this->scheduleMatchesDate($schedule->day, $date)) {
                    continue;
                }

                $occurrence = $schedule->occurrences()->firstOrCreate(
                    [
                        'class_date' => $date->toDateString(),
                    ],
                    [
                        'status' => 'scheduled',
                    ]
                );

                if ($occurrence->wasRecentlyCreated) {
                    $created++;
                }
            }
        }

        $this->info("Class occurrence generation completed. Created: {$created}");

        return self::SUCCESS;
    }

    private function scheduleMatchesDate(?string $scheduleDays, Carbon $date): bool
    {
        if (!$scheduleDays) {
            return false;
        }

        $fullDays = [
            'monday' => 'M',
            'tuesday' => 'T',
            'wednesday' => 'W',
            'thursday' => 'TH',
            'friday' => 'F',
            'saturday' => 'S',
            'sunday' => 'SU',
        ];

        $selectedDay = $fullDays[strtolower($date->format('l'))];

        $storedDays = strtoupper(
            preg_replace('/[\s,\/-]+/', '', $scheduleDays)
        );

        preg_match_all('/TH|SU|[MTWFS]/', $storedDays, $matches);

        return in_array($selectedDay, $matches[0] ?? [], true);
    }
}
