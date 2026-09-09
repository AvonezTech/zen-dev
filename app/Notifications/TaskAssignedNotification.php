<?php

namespace App\Notifications;

use App\Enums\Task\TaskableType;
use App\Enums\Task\TaskPriority;
use App\Filament\Pages\MyTasks;
use App\Filament\Resources\PersonalBoards\PersonalBoardResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\PersonalBoard;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $projectName = $this->task->taskable?->name ?? 'General Board';
        $projectType = $this->task->taskable_type instanceof TaskableType
            ? $this->task->taskable_type->getLabel()
            : 'Project';

        $priority = $this->task->priority instanceof TaskPriority
            ? $this->task->priority->getLabel()
            : ($this->task->priority ? ucfirst((string) $this->task->priority) : 'Normal');

        $dueDate = $this->task->due_date 
            ? $this->task->due_date->format('M d, Y') 
            : 'No due date';

        $mail = (new MailMessage)
            ->subject("Task Assigned: {$this->task->title} ({$projectName})")
            ->greeting('Hello ' . ($notifiable->name ?? 'there') . ',')
            ->line("You have been assigned to: **{$this->task->title}**")
            ->line("**{$projectType}:** {$projectName}")
            ->line("**Priority:** {$priority}")
            ->line("**Due Date:** {$dueDate}");

        if (! empty($this->task->description)) {
            $mail->line('---')
                 ->line('**Description:**')
                 ->line(strip_tags($this->task->description));
        }

        return $mail
            ->action('Open Task in Kanban', $this->getTaskUrl())
            ->line('Please review the task details and update your progress.');
    }

    public function getTaskUrl(): string
    {
        try {
            $taskableType = $this->task->taskable_type instanceof TaskableType
                ? $this->task->taskable_type->value
                : (string) $this->task->taskable_type;

            if ($taskableType === Project::class && $this->task->taskable_id) {
                return ProjectResource::getUrl('tasks', ['record' => $this->task->taskable_id]);
            }

            if ($taskableType === PersonalBoard::class && $this->task->taskable_id) {
                return PersonalBoardResource::getUrl('tasks', ['record' => $this->task->taskable_id]);
            }

            return MyTasks::getUrl();
        } catch (\Throwable) {
            return url('/project-management/my-tasks');
        }
    }
}