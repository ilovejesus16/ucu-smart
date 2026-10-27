<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Imports\InstructorsImport;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Record an activity performed by the authenticated user.
     */
    private function logActivity(string $action, string $module, string $description): void
    {
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user
                ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
                : 'Unknown User',
            'action' => $action,
            'module' => $module,
            'description' => $description,
        ]);
    }

    /**
     * Display User Management.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('employee_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Course Filter
        if ($request->filled('course')) {
            $query->where('course', $request->course);
        }

        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'studentCount' => User::where('role', 'student')->count(),
            'instructorCount' => User::where('role', 'instructor')->count(),
            'adminCount' => User::where('role', 'admin')->count(),
            'pendingCount' => User::where('status', 'pending')->count(),
            'courses' => User::whereNotNull('course')
                ->where('course', '!=', '')
                ->distinct()
                ->orderBy('course')
                ->pluck('course'),
        ]);
    }

    /**
     * Approve User.
     */
    public function approve(User $user)
    {
        $user->update([
            'status' => 'active',
        ]);

        $this->logActivity(
            'Approved',
            'Users',
            "Approved user: {$user->first_name} {$user->last_name} (ID: {$user->id})."
        );

        return back()->with(
            'success',
            "{$user->first_name} {$user->last_name} has been approved."
        );
    }

    /**
     * Reject User.
     */
    public function reject(User $user)
    {
        $user->update([
            'status' => 'rejected',
        ]);

        $this->logActivity(
            'Rejected',
            'Users',
            "Rejected user: {$user->first_name} {$user->last_name} (ID: {$user->id})."
        );

        return back()->with(
            'success',
            "{$user->first_name} {$user->last_name} has been rejected."
        );
    }

    /**
     * Delete User.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself.
        if (auth()->id() == $user->id) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        // Save the details before deleting the user.
        $name = trim($user->first_name . ' ' . $user->last_name);
        $deletedUserId = $user->id;

        $this->logActivity(
            'Deleted',
            'Users',
            "Deleted user: {$name} (ID: {$deletedUserId})."
        );

        $user->delete();

        return back()->with(
            'success',
            "{$name} has been deleted successfully."
        );
    }

    /**
     * Delete All Users.
     *
     * Deletes all users except the currently logged-in administrator.
     */
    public function deleteAll()
    {
        $currentUserId = auth()->id();

        $usersToDelete = User::where('id', '!=', $currentUserId)->get();

        $deletedCount = $usersToDelete->count();

        if ($deletedCount > 0) {
            $deletedNames = $usersToDelete
                ->map(function ($user) {
                    return trim($user->first_name . ' ' . $user->last_name)
                        . " (ID: {$user->id})";
                })
                ->implode(', ');

            $this->logActivity(
                'Bulk Deleted',
                'Users',
                "Deleted {$deletedCount} user(s): {$deletedNames}."
            );

            User::where('id', '!=', $currentUserId)->delete();
        }

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                "{$deletedCount} user(s) have been deleted successfully."
            );
    }

    /**
     * Student Import Page.
     */
    public function studentImportForm()
    {
        return view('admin.users.import-students');
    }

    /**
     * Instructor Import Page.
     */
    public function instructorImportForm()
    {
        return view('admin.users.import-instructors');
    }

    /**
     * Import Students.
     */
    public function importStudents(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $import = new StudentsImport();

        Excel::import($import, $request->file('file'));

        session([
            'student_import_preview' => $import->students,
        ]);

        return redirect()->route('admin.users.student.preview');
    }

    /**
     * Display Student Import Preview.
     */
    public function studentPreview()
    {
        $students = session('student_import_preview', []);

        $total = count($students);
        $ready = collect($students)->where('status', 'new')->count();
        $duplicates = collect($students)->where('status', 'duplicate')->count();
        $invalid = collect($students)->where('status', 'invalid')->count();

        return view('admin.users.import.student-preview', compact(
            'students',
            'total',
            'ready',
            'duplicates',
            'invalid'
        ));
    }

    /**
     * Import Instructors.
     */
    public function importInstructors(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $import = new InstructorsImport();

        Excel::import($import, $request->file('file'));

        session([
            'instructor_import_preview' => $import->instructors,
        ]);

        return redirect()->route('admin.users.instructor.preview');
    }

    /**
     * Display Instructor Import Preview.
     */
    public function instructorPreview()
    {
        $instructors = session('instructor_import_preview', []);

        $total = count($instructors);
        $ready = collect($instructors)->where('status', 'new')->count();
        $duplicates = collect($instructors)->where('status', 'duplicate')->count();
        $invalid = collect($instructors)->where('status', 'invalid')->count();

        return view('admin.users.import.instructor-preview', compact(
            'instructors',
            'total',
            'ready',
            'duplicates',
            'invalid'
        ));
    }

    /**
     * Save Imported Instructors.
     */
    public function storeImportedInstructors()
    {
        $instructors = session('instructor_import_preview', []);
        $importedCount = 0;

        foreach ($instructors as $instructor) {
            if ($instructor['status'] !== 'new') {
                continue;
            }

            User::create([
                'employee_id' => $instructor['employee_id'],
                'username' => $instructor['employee_id'],
                'first_name' => $instructor['first_name'],
                'last_name' => $instructor['last_name'],
                'department' => $instructor['department'],
                'email' => $instructor['email'],
                'role' => 'instructor',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make($instructor['employee_id']),
            ]);

            $importedCount++;
        }

        if ($importedCount > 0) {
            $this->logActivity(
                'Imported',
                'Users',
                "Imported {$importedCount} instructor account(s)."
            );
        }

        session()->forget('instructor_import_preview');

        return redirect()
            ->route('admin.users')
            ->with('success', 'Instructors imported successfully.');
    }

    /**
     * Export Users.
     */
    public function export()
    {
        return Excel::download(
            new UsersExport,
            'users.xlsx'
        );
    }

    /**
     * Download Student CSV Template.
     */
    public function studentTemplate()
    {
        $headers = [
            'Student ID',
            'First Name',
            'Last Name',
            'Course',
            'Email',
        ];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $headers);

            fclose($file);
        };

        return Response::stream(
            $callback,
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=student_template.csv',
            ]
        );
    }

    /**
     * Download Instructor CSV Template.
     */
    public function instructorTemplate()
    {
        $headers = [
            'Employee ID',
            'First Name',
            'Last Name',
            'Department',
            'Email',
        ];

        $callback = function () use ($headers) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $headers);

            fclose($file);
        };

        return Response::stream(
            $callback,
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=instructor_template.csv',
            ]
        );
    }

    /**
     * Save Imported Students.
     */
    public function storeImportedStudents()
    {
        $students = session('student_import_preview', []);
        $importedCount = 0;

        foreach ($students as $student) {
            if ($student['status'] !== 'new') {
                continue;
            }

            User::create([
                'student_id' => $student['student_id'],
                'username' => $student['student_id'],
                'first_name' => $student['first_name'],
                'last_name' => $student['last_name'],
                'course' => $student['course'],
                'email' => $student['email'],
                'role' => 'student',
                'status' => 'active',
                'email_verified_at' => now(),
                'password' => Hash::make($student['student_id']),
            ]);

            $importedCount++;
        }

        if ($importedCount > 0) {
            $this->logActivity(
                'Imported',
                'Users',
                "Imported {$importedCount} student account(s)."
            );
        }

        session()->forget('student_import_preview');

        return redirect()
            ->route('admin.users')
            ->with('success', 'Students imported successfully.');
    }

    /**
     * Show User.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Edit User.
     */
    public function edit(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update User.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'status' => 'required|in:active,pending,rejected',
            'department' => 'nullable|string|max:255',
        ]);

        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->status = $validated['status'];

        // Store in the correct field depending on the user's role.
        if ($user->role === 'student') {
            $user->course = $validated['department'];
        } else {
            $user->department = $validated['department'];
        }

        $user->save();

        $this->logActivity(
            'Updated',
            'Users',
            "Updated user: {$user->first_name} {$user->last_name} (ID: {$user->id})."
        );

        return redirect()
            ->route('admin.users')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Create User.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:student,instructor,admin',
            'status' => 'required|in:active,pending,rejected',

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'student_id' => 'nullable|unique:users,student_id',
            'employee_id' => 'nullable|unique:users,employee_id',

            'username' => 'required_if:role,admin|nullable|unique:users,username',

            'course' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',

            'password' => 'required|min:8',
        ]);

        // Determine username.
        if ($request->role === 'student') {
            $username = $request->student_id;
        } elseif ($request->role === 'instructor') {
            $username = $request->employee_id;
        } else {
            $username = $request->username;
        }

        $user = User::create([
            'role' => $request->role,
            'status' => $request->status,

            'username' => $username,

            'student_id' => $request->student_id,
            'employee_id' => $request->employee_id,

            'first_name' => $request->first_name,
            'last_name' => $request->last_name,

            'course' => $request->course,
            'department' => $request->department,

            'email' => $request->email,
            'email_verified_at' => now(),

            'password' => Hash::make($request->password),
        ]);

        $this->logActivity(
            'Created',
            'Users',
            "Created {$user->role} account: {$user->first_name} {$user->last_name} (ID: {$user->id})."
        );

        return redirect()
            ->route('admin.users')
            ->with('success', 'User added successfully.');
    }
}
