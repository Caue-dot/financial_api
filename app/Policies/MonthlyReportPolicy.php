<?php

namespace App\Policies;

use App\Models\MonthlyReport;
use App\Models\User;


class MonthlyReportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    public function view(User $user, MonthlyReport $report){
        return $user->id == $report->user_id;
    }
}
