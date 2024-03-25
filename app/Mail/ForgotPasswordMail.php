<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
  use Queueable, SerializesModels;

  public User $user;

  /**
   * Create a new message instance.
   */
  public function __construct(User $user)
  {
    // Set the user.
    $this->user = $user;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(
      subject: 'Forgot Password in ecommerce.com',
    );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    $user = $this->user;
    
    return new Content(
      view: 'mail.forgot_password',
      with: compact('user'),
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
