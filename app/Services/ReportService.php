<?php

namespace App\Services;

use Request;
use Carbon\Carbon;
use App\Models\MonthlyReport;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ReportService{

    public function create_report(){
        
        
        if($this->get_current_report()){
            abort(409, 'Already have a report this month');
        }
        
        
        $transactionService = new TransactionService();
        
        $user_id = Auth::user()->id;
        
        $report = MonthlyReport::create(['user_id' => $user_id]);
        $report->refresh();
        
        $last_report = $this->get_last_report(exclude_first: true);
        if($last_report){
            $transactionService->recreate_recurrent($report,  $last_report);
        }
        
        return $report;
    }

    public function get_report($year, $month, $include_transactions = false){
        
        if (!$year || !$month) {
            abort(422, 'Please insert a year and a month');
        }

        $user_id = Auth::user()->id;
        $report = MonthlyReport::get_report_by_date($year, $month, $user_id,  $include_transactions);

        if(!$report){
            abort(404, "Couldn't find any report with given date");
        }

        return $report;
    }


    public function get_current_report($include_transactions = false){
        $date = Carbon::now();
        $year =  $date->format('Y');
        $month =  $date->format('m');

        $user_id = Auth::user()->id;
        $report = MonthlyReport::get_report_by_date($year, $month,$user_id,  $include_transactions);


        return $report;
    }

    public function get_last_report( $exclude_first = false, $include_transactions = false){
        $user_id = Auth::user()->id;

        $last_report = MonthlyReport::get_last_report($user_id, $include_transactions, $exclude_first);
        return $last_report;
    }


    public function update_report($type, $value, MonthlyReport  $report){

        $field = $type === "I" ? 'total_income' : 'total_expense';

        if($value >= 0){
            $report->increment($field, $value);
        }else{
            $report->decrement($field, abs($value));
        }
    }

    public function refresh_report(MonthlyReport $report){
        
    }


    public function categories_info_in_report($report_id){
    
        $user_id = Auth::user()->id;

        $data =Transaction::selectRaw('category, 
            SUM(CASE WHEN type = "I" THEN value ELSE 0 END) as totalIncome,
            SUM(CASE WHEN type = "E" THEN value ELSE 0 END) as totalExpense')
        ->where('monthly_report_id', $report_id)
        ->groupBy('category') //Join all the table values that is on the same category
        ->whereHas('report', function($report) use ($user_id){
            $report->where('user_id', $user_id);
        })
        ->get()
        
        ->map(function (Transaction $item){ 
            return [
                'name' => $item->category,
                'totalIncome' => $item->totalIncome,
                'totalExpense'=> $item->totalExpense,
                'profit' => (string)($item->totalIncome - $item->totalExpense)
            ];
        })
        ;
        
        

       
        return $data;
    }


}