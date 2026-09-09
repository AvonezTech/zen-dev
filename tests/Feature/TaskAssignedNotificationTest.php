<?php

use App\Enums\Project\ProjectPriority as ProjectPriorityEnum;
use App\Enums\Project\ProjectStatus as ProjectStatusEnum;
use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStatus;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->userA = User::create([
        'name' => 'User A',
        'email' => 'user_a@example.com',
        'password' => bcrypt('password'),
        'mobile_number' => '1111111111',
        'github_username' => 'usera',
    ]);

    $this->userB = User::create([
        'name' => 'User B',
        'email' => 'user_b@example.com',
        'password' => bcrypt('password'),
        'mobile_number' => '2222222222',
        'github_username' => 'userb',
    ]);

    $this->client = Client::create([
        'name' => 'Test Client',
        'contact_name' => 'John Doe',
        'contact_email' => 'john@example.com',
        'contact_number' => '1234567890',
    ]);

    $this->project = Project::create([
        'client_id' => $this->client->id,
        'name' => 'Test Project',
        'status' => ProjectStatusEnum::ACTIVE,
        'priority' => ProjectPriorityEnum::HIGH,
        'start_date' => now(),
        'end_date' => now()->addMonth(),
        'budget' => 10000,
    ]);
});

test('1. create task with user A assigned sends notification to user A', function () {
    Notification::fake();

    $task = Task::create([
        'title' => 'Design Architecture',
        'taskable_id' => $this->project->id,
        'taskable_type' => Project::class,
        'assigned_to_id' => $this->userA->id,
        'status' => TaskStatus::TODO,
        'priority' => TaskPriority::HIGH,
        'estimated_days' => 3,
        'description' => 'Detailed description',
    ]);

    Notification::assertSentTo(
        $this->userA,
        TaskAssignedNotification::class,
        function (TaskAssignedNotification $notification) use ($task) {
            return $notification->task->id === $task->id;
        }
    );

    Notification::assertNotSentTo($this->userB, TaskAssignedNotification::class);
});

test('2. create task without assignee does not send notification', function () {
    Notification::fake();

    $task = new Task([
        'title' => 'Unassigned Task',
        'taskable_id' => $this->project->id,
        'taskable_type' => Project::class,
        'assigned_to_id' => null,
        'status' => TaskStatus::TODO,
        'priority' => TaskPriority::LOW,
        'estimated_days' => 1,
        'description' => 'No assignee',
    ]);

    // Dispatch the eloquent created event for a task instance with null assigned_to_id
    Task::getEventDispatcher()->dispatch('eloquent.created: ' . Task::class, $task);

    Notification::assertNothingSent();
});

test('3. reassigning task from user A to user B notifies user B and not user A', function () {
    $task = Task::create([
        'title' => 'Implement Feature',
        'taskable_id' => $this->project->id,
        'taskable_type' => Project::class,
        'assigned_to_id' => $this->userA->id,
        'status' => TaskStatus::TODO,
        'priority' => TaskPriority::MEDIUM,
        'estimated_days' => 2,
        'description' => 'Feature work',
    ]);

    // Pre-load the relationship to verify cached relation handling
    $task->load('assignedTo');
    expect($task->assignedTo->id)->toBe($this->userA->id);

    Notification::fake();

    $task->assigned_to_id = $this->userB->id;
    $task->save();

    Notification::assertSentTo(
        $this->userB,
        TaskAssignedNotification::class,
        function (TaskAssignedNotification $notification) use ($task) {
            return $notification->task->id === $task->id;
        }
    );

    Notification::assertNotSentTo($this->userA, TaskAssignedNotification::class);
});

test('4. updating task title or priority without changing assignee does not send notification', function () {
    $task = Task::create([
        'title' => 'Initial Title',
        'taskable_id' => $this->project->id,
        'taskable_type' => Project::class,
        'assigned_to_id' => $this->userA->id,
        'status' => TaskStatus::TODO,
        'priority' => TaskPriority::LOW,
        'estimated_days' => 2,
        'description' => 'Some description',
    ]);

    Notification::fake();

    $task->title = 'Updated Title';
    $task->priority = TaskPriority::HIGH;
    $task->save();

    Notification::assertNothingSent();
});

test('5. reassigning to a missing or null user does not notify and throws no exception', function () {
    $task = Task::create([
        'title' => 'Edge Case Task',
        'taskable_id' => $this->project->id,
        'taskable_type' => Project::class,
        'assigned_to_id' => $this->userA->id,
        'status' => TaskStatus::TODO,
        'priority' => TaskPriority::LOW,
        'estimated_days' => 1,
        'description' => 'Edge case',
    ]);

    Notification::fake();

    // Case 1: missing non-existent user ID via actual model save
    $task->assigned_to_id = 999999;
    $task->save();

    Notification::assertNothingSent();

    // Case 2: null user ID via updated event dispatch
    $task->assigned_to_id = null;
    Task::getEventDispatcher()->dispatch('eloquent.updated: ' . Task::class, $task);

    Notification::assertNothingSent();
});

test('6. existing completed_at behavior still sets timestamp when completed', function () {
    $task = Task::create([
        'title' => 'Completion Test',
        'taskable_id' => $this->project->id,
        'taskable_type' => Project::class,
        'assigned_to_id' => $this->userA->id,
        'status' => TaskStatus::IN_PROGRESS,
        'priority' => TaskPriority::MEDIUM,
        'estimated_days' => 2,
        'description' => 'Check completed_at',
    ]);

    expect($task->completed_at)->toBeNull();

    $task->status = TaskStatus::COMPLETED;
    $task->save();

    expect($task->completed_at)->not->toBeNull();
});

test('7. notification implements ShouldQueue and builds valid MailMessage', function () {
    $task = Task::create([
        'title' => 'Build Report Page',
        'taskable_id' => $this->project->id,
        'taskable_type' => Project::class,
        'assigned_to_id' => $this->userA->id,
        'status' => TaskStatus::TODO,
        'priority' => TaskPriority::HIGH,
        'estimated_days' => 2,
        'description' => 'Create reports',
    ]);

    $notification = new TaskAssignedNotification($task);

    expect($notification)->toBeInstanceOf(ShouldQueue::class);
    expect($notification->via($this->userA))->toBe(['mail']);

    $mail = $notification->toMail($this->userA);

    expect($mail->subject)->toBe('Task Assigned: Build Report Page');
    expect($mail->greeting)->toBe('Hello User A,');
    expect(implode(' ', $mail->introLines))->toContain('Build Report Page');
    expect(implode(' ', $mail->introLines))->toContain('High');
    expect($mail->actionText)->toBe('View Task');
    expect($mail->actionUrl)->toContain('/project-management/projects/' . $this->project->id . '/tasks');
});

test('8. notification generates valid PersonalBoard task URL when task belongs to PersonalBoard', function () {
    $board = \App\Models\PersonalBoard::create([
        'name' => 'My Tasks Board',
        'user_id' => $this->userA->id,
    ]);

    $task = Task::create([
        'title' => 'Personal Board Task',
        'taskable_id' => $board->id,
        'taskable_type' => \App\Models\PersonalBoard::class,
        'assigned_to_id' => $this->userA->id,
        'status' => TaskStatus::TODO,
        'priority' => TaskPriority::LOW,
        'estimated_days' => 1,
        'description' => 'Personal task',
    ]);

    $notification = new TaskAssignedNotification($task);
    $mail = $notification->toMail($this->userA);

    expect($mail->actionUrl)->toContain('/project-management/personal-boards/' . $board->id . '/tasks');
});

test('9. notification falls back to MyTasks URL when taskable is not set', function () {
    $task = new Task([
        'title' => 'Standalone Task',
        'assigned_to_id' => $this->userA->id,
        'status' => TaskStatus::TODO,
        'priority' => TaskPriority::LOW,
    ]);

    $notification = new TaskAssignedNotification($task);
    $mail = $notification->toMail($this->userA);

    expect($mail->actionUrl)->toContain('/project-management/my-tasks');
});

