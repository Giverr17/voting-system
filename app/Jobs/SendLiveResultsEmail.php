<?php

namespace App\Jobs;

use App\Mail\LiveResults;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendLiveResultsEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

    public $user;
    public function __construct(User $user)
    {
       $this->user=$user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new LiveResults($this->user));
    }
}
