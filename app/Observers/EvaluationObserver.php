<?php

namespace App\Observers;

use App\Models\Evaluation;
use App\Models\Activity;

class EvaluationObserver
{
    public function created(Evaluation $evaluation)
    {
        Activity::create([
            'description' => 'Penilaian baru dicipta untuk ' . $evaluation->pyd->name,
            'changes' => null,
            'subject_type' => Evaluation::class,
            'subject_id' => $evaluation->id,
            'causer_type' => User::class,
            'causer_id' => auth()->id(),
        ]);
    }

    public function updated(Evaluation $evaluation)
    {
        if ($evaluation->isDirty('status')) {
            $statusMap = [
                'draf_pyd' => 'Draf PYD',
                'draf_ppp' => 'Draf PPP',
                'draf_ppk' => 'Draf PPK',
                'selesai' => 'Selesai',
                'reopened' => 'Dibuka Semula',
            ];
            
            Activity::create([
                'description' => 'Status penilaian diubah ke ' . ($statusMap[$evaluation->status] ?? $evaluation->status),
                'changes' => null,
                'subject_type' => Evaluation::class,
                'subject_id' => $evaluation->id,
                'causer_type' => User::class,
                'causer_id' => auth()->id(),
            ]);
        }
    }
}