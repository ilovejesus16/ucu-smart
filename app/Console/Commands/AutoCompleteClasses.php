<?php

namespace App\Console\Commands;

use App\Models\ClassOccurrence;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoCompleteClasses extends Command
{
    protected $signature = 'classes:auto-complete';

    protected $description = 'Automatically complete classes five minutes after their scheduled end time';

    public function handle(): int
    {
        $now = Carbon::now();

        $occurrences = ClassOccurrence::with('schedule')
            ->where('status', 'in_progress')
            ->get();

        $completed = 0;

        foreach ($occurrences as $occurrence) {

            $schedule = $occurrence->schedule;

            if (!$schedule) {
                continue;
            }

            $endTime = Carbon::parse(
                $occurrence->class_date->toDateString() . ' ' . $schedule->end_time
            )->addMinutes(5);

            if ($now->greaterThanOrEqualTo($endTime)) {

                $occurrence->update([
                    'status' => 'completed',
                    'ended_at' => $now,
                ]);

                $completed++;
            }
        }

        $this->info("Automatically completed classes: {$completed}");

        return self::SUCCESS;
    }
}