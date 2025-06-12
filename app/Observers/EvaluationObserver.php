<?php

namespace App\Observers;

use App\Models\Evaluation;
use App\Models\Activity;

class EvaluationObserver
{
    public function created(Evaluation $evaluation)
    {

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
        }
    }
}