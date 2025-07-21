<?php

namespace App\Jobs;

use App\Mail\ContactActionNotification;
use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendContactNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $contact;
    public string $action;
    public string $email;

    /**
     * Create a new job instance.
     */
    public function __construct(array $contact, string $action, string $email)
    {
        $this->contact = $contact;
        $this->action = $action;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)->send(new ContactActionNotification($this->contact, $this->action));
    }
}
