<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request, $id, $hash)
    {
        // Check that the verification URL is signed and valid
        if (! URL::hasValidSignature($request)) {
            abort(403, 'This verification link is invalid or has expired.');
        }

        // Find the account
        $user = User::findOrFail($id);

        // Make sure the hash belongs to this user's email
        if (! hash_equals(
            sha1($user->getEmailForVerification()),
            $hash
        )) {
            abort(403, 'This verification link is invalid.');
        }

        // If already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()
                ->route('login')
                ->with(
                    'success',
                    'Your email is already verified. You can now log in.'
                );
        }

        // Actually verify the email
        $user->markEmailAsVerified();

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Your email has been verified successfully. You can now log in.'
            );
    }
}