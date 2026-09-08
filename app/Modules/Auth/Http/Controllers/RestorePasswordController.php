<?php

namespace App\Modules\Auth\Http\Controllers;

use Captcha;
use Contentify\Models\User;
use FrontController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Mail;
use Redirect;
use Request;
use Reminder;
use Sentinel;
use Validator;

class RestorePasswordController extends FrontController
{
    public function getIndex()
    {
        $this->pageView('auth::restore_password');
    }

    public function postIndex()
    {
        if (! Captcha::check(Request::get('captcha'))) {
            return Redirect::to('auth/restore')->withErrors(['message' => trans('app.captcha_invalid')]);
        }
        $email = Request::input('email');
        $validator = Validator::make(['email' => $email], ['email' => 'required|string|email|max:254']);
        if ($validator->fails()) {
            return Redirect::to('auth/restore')->withErrors($validator);
        }
        $key = 'password-reset:'.hash('sha256', Request::ip());
        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429);
        }
        RateLimiter::hit($key, 3600);
        $user = User::where('email', $email)->first();
        if ($user) {
            $code = DB::transaction(function () use ($user) {
                User::whereKey($user->id)->lockForUpdate()->firstOrFail();
                DB::table('reminders')->where('user_id', $user->id)->delete();
                $reminder = Reminder::create($user);
                $code = $reminder->code;
                // Only the emailed link carries the secret; the DB stores its digest.
                $reminder->code = hash('sha256', $code);
                $reminder->save();
                return $code;
            });
            Mail::send('auth::emails.restore_password', compact('user', 'code'), function ($message) use ($user) {
                $message->to($user->email, $user->username)->subject(trans('auth::password_reset'));
            });
        }
        // Same visible response for known and unknown addresses.
        $this->alertSuccess(trans('auth::reset_requested'));
    }

    public function getNew(string $email, string $code)
    {
        $user = User::where('email', $email)->first();
        if (! $user || ! Reminder::exists($user, hash('sha256', $code))) {
            abort(400, trans('auth::code_invalid'));
        }
        $this->pageView('auth::new_password', compact('email', 'code'));
    }

    public function postNew(string $email, string $code)
    {
        $validator = Validator::make(Request::only('password', 'password_confirmation'), [
            'password' => ['required', 'string', 'min:12', 'max:72', 'confirmed',
                function ($attribute, $value, $fail) {
                    // Bcrypt's limit is bytes, not Unicode characters.
                    if (is_string($value) && strlen($value) > 72) {
                        $fail(trans('auth::reset_password_bytes'));
                    }
                }],
        ]);
        if ($validator->fails()) {
            // Never flash password fields to the session.
            return Redirect::to(Request::url())->withErrors($validator);
        }
        $completed = DB::transaction(function () use ($email, $code) {
            // Serializes simultaneous completions and replacement-token requests.
            $user = User::where('email', $email)->lockForUpdate()->first();
            if (! $user || ! Reminder::complete($user, hash('sha256', $code), Request::input('password'))) {
                return false;
            }
            DB::table('reminders')->where('user_id', $user->id)->delete();
            Sentinel::logout($user, true);
            return true;
        });
        if (! $completed) {
            abort(400, trans('auth::code_invalid'));
        }
        Request::session()->invalidate();
        Request::session()->regenerateToken();
        $this->alertSuccess(trans('auth::reset_completed'));
    }
}
