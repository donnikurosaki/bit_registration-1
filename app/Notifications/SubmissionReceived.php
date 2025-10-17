<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissionReceived extends Notification
{
    use Queueable;

    protected $submission;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Confirmation de réception de votre dossier')
            ->greeting('Bonjour ' . $this->submission->first_name . ' ' . $this->submission->last_name . ',')
            ->line('Nous avons bien reçu votre dossier de candidature.')
            ->line('Numéro de référence : ' . $this->submission->submission_id)
            ->line('Votre dossier est en cours d\'examen. Vous pouvez suivre son statut à tout moment.')
            ->action('Suivre mon dossier', url('/filetracking/' . $this->submission->submission_id))
            ->line('Merci de votre intérêt pour notre établissement !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'submission_id' => $this->submission->submission_id,
            'first_name' => $this->submission->first_name,
            'last_name' => $this->submission->last_name
        ];
    }
} 