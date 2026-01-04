<?php

namespace App\Jobs;

use App\Models\ExamSms;
use App\Models\StudentDetail;
use App\Models\ParentDetail;
use App\Models\ExamSmsRecipient;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendExamSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $smsId;

    public function __construct(int $smsId)
    {
        $this->smsId = $smsId;
    }

    public function handle(SmsService $smsService): void
    {
        $sms = ExamSms::find($this->smsId);
        if (!$sms) {
            return;
        }

        $schoolId = $sms->school_id;
        $message = $sms->message_template;

        $sent = 0;
        $failed = 0;

        if ($sms->audience_type === 'students') {
            $query = StudentDetail::query()->where('school_id', $schoolId);
            if ($sms->class_name) {
                $query->whereHas('class', function ($q) use ($sms) {
                    $q->where('name', $sms->class_name);
                });
            }
            if ($sms->section_name) {
                $query->whereHas('section', function ($q) use ($sms) {
                    $q->where('name', $sms->section_name);
                });
            }
            $query->with('user:id,phone');
            foreach ($query->cursor() as $student) {
                $phone = optional($student->user)->phone ?? $student->guardian_contact;
                $log = ExamSmsRecipient::create([
                    'exam_sms_id' => $sms->id,
                    'recipient_type' => 'student',
                    'recipient_id' => $student->id,
                    'phone' => (string)$phone,
                    'status' => 'pending',
                ]);
                $ok = $smsService->send((string)$phone, $message);
                if ($ok) {
                    $log->update(['status' => 'sent', 'sent_at' => now()]);
                    $sent++;
                } else {
                    $log->update(['status' => 'failed', 'error' => 'invalid phone']);
                    $failed++;
                }
            }
        } elseif ($sms->audience_type === 'parents') {
            $parents = ParentDetail::query()->where('school_id', $schoolId)->cursor();
            foreach ($parents as $parent) {
                $phone = $parent->phone_primary ?: $parent->phone_secondary ?: $parent->emergency_contact_phone;
                $log = ExamSmsRecipient::create([
                    'exam_sms_id' => $sms->id,
                    'recipient_type' => 'parent',
                    'recipient_id' => $parent->id,
                    'phone' => (string)$phone,
                    'status' => 'pending',
                ]);
                $ok = $smsService->send((string)$phone, $message);
                if ($ok) {
                    $log->update(['status' => 'sent', 'sent_at' => now()]);
                    $sent++;
                } else {
                    $log->update(['status' => 'failed', 'error' => 'invalid phone']);
                    $failed++;
                }
            }
        } else {
            // extend for staff/custom as needed
        }

        $sms->update([
            'status' => 'sent',
            'sent_count' => $sms->sent_count + $sent,
            'failed_count' => $sms->failed_count + $failed,
        ]);
    }
}


