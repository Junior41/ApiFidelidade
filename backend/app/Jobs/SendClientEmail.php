<?php

namespace App\Jobs;

use App\Mail\ClientEmail;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendClientEmail implements ShouldQueue
{
    use Queueable;

    private $client, $view, $subject, $data;

    public function __construct($client, $view, $subject, $data)
    {
        $this->client = $client;
        $this->view = $view;
        $this->subject = $subject;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->client->email)->send(new ClientEmail($this->client, $this->view, $this->subject, $this->data)); 
    }
}
