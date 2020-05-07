<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePassword;
use App\Domain\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['verify', 'password']);
        $this->middleware('signed')->only(['verify', 'password']);
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the select password interface.
     *
     * @param int $id
     * @param Request $request
     * @return Factory|RedirectResponse|View
     */
    public function verify(int $id, Request $request)
    {
        if (!is_null(User::findOrFail($id)->email_verified_at)) {
            return redirect()->route('home');
        }
        $link = URL::temporarySignedRoute('verification.activate', Carbon::now()->addMinutes(60), compact('id'));
        return view('auth.activate', compact('link'));
    }

    /**
     * Mark the authenticated user's email address as verified and
     * set a password.
     *
     * @param int $id
     * @param ChangePassword $request
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function password(int $id, ChangePassword $request)
    {
        $user = User::findOrFail($id);

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $user->password = Hash::make($request->validated()['password']);
        $user->save();

        Auth::guard()->login($user);

        return redirect($this->redirectPath())->with('alert', trans('auth.flash.account_activated'));
    }
}
