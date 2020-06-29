<?php

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;

class VerifyEmail extends VerifyEmailBase
{
    // private $id;
    // private $expires;
    // private $signature;
    private $verificationEmail;

    public function __construct()
    {
        // $this->id = $emailData['id'];
        // $this->expires = $emailData['expires'];
        // $this->signature = $emailData['signature'];
        // $this->content = $emailData['content'];
    }

    public function setContent($verificationEmail)
    {
        $this->verificationEmail = $verificationEmail;
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);
        // $url = url('/api/email/verify/' . $this->id . '?expires=' . $this->expires . '&signature=' . $this->signature);

        $mailMessage = new MailMessage;
        $mailMessage->level($this->verificationEmail['level']);

        foreach ($this->verificationEmail['content'] as $item) {
            switch ($item['type']) {
                case "action":
                    $mailMessage->action($item['text'], url($verificationUrl));
                    break;
                case "subject":
                    $mailMessage->subject($item['text']);
                    break;
                case "line":
                    $mailMessage->line($item['text']);
                    break;
                case "greeting":
                    $mailMessage->greeting($item['text']);
                    break;
                case "salutation":
                    $mailMessage->salutation($item['text']);
                    break;
            }
        }

        return $mailMessage;
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        /* return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $notifiable->getKey()]
        ); // this will basically mimic the email endpoint with get request */

        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
