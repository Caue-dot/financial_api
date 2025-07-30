<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\MonthlyReport;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MonthlyReportResource;
use App\Http\Resources\MonthlyReportCollection;

class ReportController extends Controller
{
    use AuthorizesRequests;
    public function index(){

        $user_id = Auth::user()->id;
        $reports = MonthlyReport::where('user_id', $user_id)->orderBy('month', 'desc');

        return new MonthlyReportCollection($reports->get());
    }

    public function get_report_by_id($reportId){

        $report = MonthlyReport::with('transactions')->findOrFail($reportId);
        
        $this->authorize('view', $report);

        return new MonthlyReportResource($report);
    }

    public function get_report(Request $request, ReportService $reportService){

        $year = $request->query('year');
        $month  = $request->query('month');
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
