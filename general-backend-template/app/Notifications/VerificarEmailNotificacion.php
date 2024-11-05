<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerificarEmailNotificacion extends Notification
{
  use Queueable;

  protected $token;
  protected $password;

  public function __construct($token, $password)
  {
    $this->token = $token;
    $this->password = $password;
  }

  public function via($notifiable)
  {
    return ['mail'];
  }

  public function toMail($notifiable)
  {
    $verificationUrl = url('/public/auth/verificar?token=' . $this->token);

    return (new MailMessage)
      ->subject('Verificación de correo electrónico')
      ->greeting('Hola, ' . $notifiable->primer_nombre)
      ->line('Gracias por registrarte. Por favor, verifica tu correo electrónico haciendo clic en el botón a continuación.')
      ->action('Verificar correo', $verificationUrl)
      ->line('Tu contraseña es: ' . $this->password)
      ->line('Si no creaste esta cuenta, no se requiere ninguna acción.');
  }
}

