<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\SchedulesImport;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleImportController extends Controller
{
    /**
     * Import schedule file and prepare preview.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Read Excel File
        |--------------------------------------------------------------------------
        */

        $import = new SchedulesImport();

        Excel::import(
            $import,
            $request->file('file')
        );

        /*
        |--------------------------------------------------------------------------
        | Get Prepared Schedule Rows
        |--------------------------------------------------------------------------
        */

        $schedules = $import->schedules;

        /*
        |--------------------------------------------------------------------------
        | Calculate Statistics
        |--------------------------------------------------------------------------
        */

        $total = count($schedules);

        $ready = collect($schedules)
            ->where('status', 'new')
            ->count();

        $duplicates = collect($schedules)
            ->where('status', 'duplicate')
            ->count();

        $invalid = collect($schedules)
            ->where('status', 'invalid')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Store Preview in Session
        |--------------------------------------------------------------------------
        */

        session([
            'schedule_import_preview' => $schedules,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect to Preview
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.schedules.preview');
    }


    /**
     * Display schedule import preview.
     */
    public function preview()
    {
        $schedules = session(
            'schedule_import_preview',
            []
        );

        /*
        |--------------------------------------------------------------------------
        | If There Is No Preview
        |--------------------------------------------------------------------------
        */

        if (empty($schedules)) {

            return redirect()
                ->route('admin.schedules')
                ->with(
                    'error',
                    'There is no schedule import to preview.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $total = count($schedules);

        $ready = collect($schedules)
            ->where('status', 'new')
            ->count();

        $duplicates = collect($schedules)
            ->where('status', 'duplicate')
            ->count();

        $invalid = collect($schedules)
            ->where('status', 'invalid')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Preview Page
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.schedules.import-preview',
            compact(
                'schedules',
                'total',
                'ready',
                'duplicates',
                'invalid'
            )
        );
    }


    /**
     * Confirm and save imported schedules.
     */
    public function storeImportedSchedules()
    {
        $schedules = session(
            'schedule_import_preview',
            []
        );

        /*
        |--------------------------------------------------------------------------
        | Nothing To Import
        |--------------------------------------------------------------------------
        */

        if (empty($schedules)) {

            return redirect()
                ->route('admin.schedules')
                ->with(
                    'error',
                    'There are no schedules ready to import.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Insert Ready Rows
        |--------------------------------------------------------------------------
        */

        $imported = 0;

        foreach ($schedules as $schedule) {

            /*
            |--------------------------------------------------------------------------
            | Only Import New Rows
            |--------------------------------------------------------------------------
            */

            if (
                !isset($schedule['status']) ||
                $schedule['status'] !== 'new'
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Create Schedule
            |--------------------------------------------------------------------------
            */

            Schedule::create([

                'room_id' =>
                    $schedule['room_id'],

                'instructor_id' =>
                    $schedule['instructor_id'],

                'subject_code' =>
                    $schedule['subject_code'],

                'subject_name' =>
                    $schedule['subject_name'],

                'day' =>
                    $schedule['day'],

                'start_time' =>
                    $schedule['start_time'],

                'end_time' =>
                    $schedule['end_time'],

                'semester' =>
                    $schedule['semester'],

                'school_year' =>
                    $schedule['school_year'],

                'status' =>
                    'scheduled',

            ]);

            $imported++;
        }

        /*
        |--------------------------------------------------------------------------
        | Clear Preview
        |--------------------------------------------------------------------------
        */

        session()->forget(
            'schedule_import_preview'
        );

        /*
        |--------------------------------------------------------------------------
        | Return To Schedule Management
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.schedules')
            ->with(
                'success',
                "{$imported} schedule(s) imported successfully."
            );
    }


    /**
     * Cancel schedule import.
     */
    public function cancel()
    {
        session()->forget(
            'schedule_import_preview'
        );

        return redirect()
            ->route('admin.schedules');
    }
}