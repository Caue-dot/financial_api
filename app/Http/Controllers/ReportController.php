<?php

namespace App\Http\Controllers;

use App\Http\Resources\MonthlyReportResource;
use Illuminate\Http\Request;
use App\Services\ReportService;

class ReportController extends Controller
{

   

    public function get_report(Request $request, ReportService $reportService){

        $year = $request->query('year');
        $month  = $request->query('month');
        $user_id = $request->user()->id;
        $includeTransactions = $request->query('includeTransactions');

        $report = $reportService->get_report($year, $month, $includeTransactions);
        
        return new MonthlyReportResource($report);
    }


    public function get_current_report(Request $request, ReportService $reportService){
        $includeTransactions = $request->query('includeTransactions');
        $report = $reportService->get_current_report($includeTransactions);

        return new MonthlyReportResource($report);
    }

}
