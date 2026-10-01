<?php

namespace App\Console\Commands;

use App\Jobs\SendTaskDeadlineReminderJob;
use App\Models\TechnicalTask;
use Illuminate\Console\Command;

class CheckTaskDeadlinesCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:check-deadlines';

    /**
     * The console command description.
     */
    protected $description = 'Check technical task deadlines, mark overdue tasks, and queue 24-hour reminders';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // 1. Mark overdue tasks
        $overdueCount = TechnicalTask::where('deadline', '<', now())
            ->whereNotIn('status', [
                TechnicalTask::STATUS_SUBMITTED,
                TechnicalTask::STATUS_REVIEWED,
                TechnicalTask::STATUS_OVERDUE,
            ])
            ->update([
                'status' => TechnicalTask::STATUS_OVERDUE,
            ]);

        $this->info("Marked {$overdueCount} technical tasks as Overdue.");

        // 2. Queue 24h reminders for tasks due within the next 24 hours
        $tasksForReminder = TechnicalTask::whereBetween('deadline', [now(), now()->addHours(24)])
            ->whereNull('reminder_sent_at')
            ->whereIn('status', [
                TechnicalTask::STATUS_PENDING,
                TechnicalTask::STATUS_IN_PROGRESS,
            ])
            ->get();

        foreach ($tasksForReminder as $task) {
            SendTaskDeadlineReminderJob::dispatch($task);
        }

        $this->info("Queued {$tasksForReminder->count()} 24h deadline reminder jobs.");

        return Command::SUCCESS;
    }
}
