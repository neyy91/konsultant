<?php 

namespace App\Libs;

use Mail;
use Auth;
use Illuminate\Support\Facades\Password;

use App\Models\EmailChange;

trait UserEmailChange {

    protected $defaultUserEmailChangeViews = ['email.user.email_change.html', 'email.user.email_change.text'];

    protected function requestChangeUserEmail($email)
    {
        $token = Password::getRepository()->createNewToken();
        $user = Auth::user();
        $emailChange = EmailChange::where('user_id', $user->id)->first();
        if ($emailChange) {
            $emailChange->delete();
        }
        $emailChange = EmailChange::create(['email' => $email, 'token' => $token, 'user' => $user]);
        $this->sendEmailChangeLink([
            'token' => $token,
            'email' => $email,
            'url' => route('user.edit.email_change', ['token' => $token]),
            'user' => $user,
        ]);
    }

    protected function sendEmailChangeLink($data)
    {
        $user = Auth::user();
        Mail::send($this->getUserEmailChangeViews(), $data, function ($mail) use ($user)
        {
            $mail->from(config('mail.from.address'), config('mail.from.name'));
            $mail->to($user->email);
        });
    }

    protected function changeUserEmailByToken($token)
    {
        $user = Auth::user();
        $emailChange = EmailChange::where(['token' => $token, 'user_id' => $user->id])->first();
        if ($emailChange) {
            $user->fill(['email' => $emailChange->email])->save();
            $emailChange->delete();
            return true;
        }
        return false;
    }

    protected function getUserEmailChangeViews()
    {
        return property_exists($this, 'userEmailChangeViews') ? $this->userEmailChangeViews : $this->defaultUserEmailChangeViews;
    }
}