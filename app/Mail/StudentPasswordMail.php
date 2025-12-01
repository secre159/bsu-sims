<?php

namespace App\Mail;

use App\Models\StudentUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentUser;
    public $password;
    public $studentName;

    /**
     * Create a new message instance.
     */
    public function __construct(StudentUser $studentUser, string $password, string $studentName)
    {
        $this->studentUser = $studentUser;
        $this->password = $password;
        $this->studentName = $studentName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your SIMS Login Credentials - ' . $this->studentUser->student_id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.student-password',
            with: [
                'studentName' => $this->studentName,
                'studentId' => $this->studentUser->student_id,
                'email' => $this->studentUser->email,
                'password' => $this->password,
                'loginUrl' => route('student.login'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
