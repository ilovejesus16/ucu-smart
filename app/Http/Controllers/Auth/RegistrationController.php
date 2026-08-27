<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function student()
    {
        return view('auth.register-student');
    }

    public function instructor()
    {
        return view('auth.register-instructor');
    }

    public function storeStudent(Request $request)
    {
      $validated = $request->validate([
    'student_id' => [
    'required',
    'digits:8',
    'regex:/^[0-9]+$/',
    'unique:users,student_id',
],

    'first_name' => [
        'required',
        'string',
        'max:255',
    ],

    'last_name' => [
        'required',
        'string',
        'max:255',
    ],

    'course' => [
        'required',
        'string',
        'max:255',
    ],

    'email' => [
        'required',
        'email',
        'unique:users,email',
    ],

    'password' => [
        'required',
        'confirmed',
        Rules\Password::defaults(),
    ],

], [
    'student_id.required' => 'Student ID is required.',
    'student_id.numeric' => 'Student ID must contain numbers only.',
    'student_id.digits' => 'Student ID must be exactly 8 digits.',
    'student_id.unique' => 'This Student ID is already registered.',
]);

        $user = User::create([
            'role' => 'student',
            'status' => 'pending',

            'username' => $validated['student_id'],

            'student_id' => $validated['student_id'],
            'employee_id' => null,

            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],

          
            'course' => $validated['course'],
            'department' => null,

            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->sendEmailVerificationNotification();

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please check your email to verify your account. After verifying your email, wait for the Registrar to approve your account before you can log in.');
    }

  public function storeInstructor(Request $request)
{
    $validated = $request->validate([
        'employee_id' => ['required', 'unique:users,employee_id'],
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'department' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
        'role' => 'instructor',
        'status' => 'pending',

        'username' => $validated['employee_id'],

        'student_id' => null,
        'employee_id' => $validated['employee_id'],

        'first_name' => $validated['first_name'],
        'last_name' => $validated['last_name'],

        
        'course' => null,
        'department' => $validated['department'],

        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    $user->sendEmailVerificationNotification();

    return redirect()->route('login')
        ->with('success', 'Registration successful! Please check your email to verify your account. After verifying your email, wait for the Registrar to approve your account before you can log in.');
}
}