<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissionStatusChanged extends Notification
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
        $mailMessage = new MailMessage;
        
        $status = $this->submission->status;
        $mailMessage->subject('Mise à jour du statut de votre dossier d\'admission');
        $mailMessage->greeting('Bonjour ' . $this->submission->first_name . ' ' . $this->submission->last_name . ',');
        
        if ($status === 'approved') {
            $mailMessage->line('Nous avons le plaisir de vous informer que votre dossier d\'admission a été approuvé.');
            $mailMessage->line('Félicitations ! Vous serez contacté prochainement pour les prochaines étapes.');
        } elseif ($status === 'rejected') {
            $mailMessage->line('Nous sommes au regret de vous informer que votre dossier d\'admission n\'a pas été retenu.');
            $mailMessage->line('Nous vous remercions pour l\'intérêt que vous avez porté à notre établissement.');
        } else {
            $mailMessage->line('Le statut de votre dossier d\'admission a été mis à jour.');
            $mailMessage->line('Votre dossier est actuellement : ' . $this->getStatusInFrench($status));
        }
        
        if ($this->submission->admin_notes) {
            $mailMessage->line('Notes : ' . $this->submission->admin_notes);
        }
        
        $mailMessage->action('Voir les détails', url('/filetracking/' . $this->submission->submission_id));
        $mailMessage->line('Merci de votre intérêt pour notre établissement !');
        
        return $mailMessage;
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
            'status' => $this->submission->status,
            'admin_notes' => $this->submission->admin_notes
        ];
    }
    
    /**
     * Get status in French
     *
     * @param string $status
     * @return string
     */
    private function getStatusInFrench($status)
    {
        switch ($status) {
            case 'pending':
                return 'En attente';
            case 'approved':
                return 'Approuvé';
            case 'rejected':
                return 'Rejeté';
            case 'under_review':
                return 'En cours d\'examen';
            default:
                return $status;
        }
    }
} 