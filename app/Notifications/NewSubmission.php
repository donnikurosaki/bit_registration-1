<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubmission extends Notification
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
        $program = $this->getProgramName($this->submission->program);
        
        return (new MailMessage)
            ->subject('Nouvelle candidature reçue')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle candidature a été soumise.')
            ->line('Identifiant: ' . $this->submission->submission_id)
            ->line('Nom: ' . $this->submission->first_name . ' ' . $this->submission->last_name)
            ->line('Email: ' . $this->submission->email)
            ->line('Programme: ' . $program)
            ->action('Voir la candidature', url('/admin/submissions/' . $this->submission->id))
            ->line('Merci d\'utiliser notre plateforme !');
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
            'last_name' => $this->submission->last_name,
            'program' => $this->submission->program,
        ];
    }
    
    /**
     * Get program name
     *
     * @param string $programKey
     * @return string
     */
    private function getProgramName($programKey)
    {
        $programs = [
            'informatique' => 'Licence Informatique et Entrepreunariat',
            'mecanique_agriculture' => 'Licence Mécanique Option Agriculture',
            'mecanique_mine' => 'Licence Mécanique Option Mine',
            'energie' => 'Licence Énergie Renouvelable',
            'master' => 'Master Informatique Option Intelligence Artificielle',
        ];
        
        return $programs[$programKey] ?? $programKey;
    }
} 