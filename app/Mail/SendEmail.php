<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $nome;
    public $email;
    public $telefone;
    public $mensagem;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nome, $email, $telefone, $mensagem)
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->mensagem = $mensagem;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contato');
    }
}
