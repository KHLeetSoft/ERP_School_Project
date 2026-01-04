<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExamSms;
use App\Jobs\SendExamSmsJob;

class DispatchScheduledExamSms extends Command
{
    protected $signature = 'exams:sms-dispatch-scheduled';

    protected $description = 'Dispatch SendExamSmsJob for scheduled exam SMS campaigns whose time has arrived';

    public function handle(): int
    {
        $due = ExamSms::where('status', 'scheduled')
            ->whereNotNull('schedule_at')
            ->where('schedule_at', '<=', now())
            ->limit(100)
            ->get(['id']);

        foreach ($due as $row) {
            SendExamSmsJob::dispatch($row->id);
            $this->info("Dispatched job for ExamSms #{$row->id}");
        }

        if ($due->isEmpty()) {
            $this->info('No due scheduled exam SMS found.');
        }

        return self::SUCCESS;
    }
}







