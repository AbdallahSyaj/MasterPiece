<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Form;

class MessageReply extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $replyText;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Form $message, $replyText)
    {
        $this->message = $message;
        $this->replyText = $replyText;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reply to your message')
                    ->view('emails.reply')
                    ->with([
                        'name' => $this->message->name,
                        'originalMessage' => $this->message->message,
                        'replyText' => $this->replyText,
                    ]);
    }
}