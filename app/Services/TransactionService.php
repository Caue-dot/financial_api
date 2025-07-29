<?php

namespace App\Services;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;


class TransactionService{



    public function store($data){
      
        $reportService = new ReportService();

        $current_report = $reportService->get_current_report();

        if(!$current_report){
            $current_report = $reportService->create_report();
        }

        $reportService->update_report($data['type'], $data['value'], $current_report);

        $data['monthly_report_id'] = $current_report->id;
        

        $transaction = Transaction::create($data);
        return $transaction;
    }

    public function delete(Transaction $transaction){

        $report = $transaction->report()->first();

        $reportService = new ReportService();
        $reportService->update_report($transaction->type, -$transaction->value, $report);

        $transaction->delete();

    }

    public function update(Transaction $transaction, $data){
        $transaction->update($data);
        $transaction->report->refresh_values();
    }
    public function list_by_category($category){
        return Transaction::get_all_by_category($category);
    }


    public function categories_info_in_transaction(){
        $user_id = Auth::user()->id;

        $data =Transaction::selectRaw('category, 
            SUM(CASE WHEN type = "I" THEN value ELSE 0 END) as totalIncome,
            SUM(CASE WHEN type = "E" THEN value ELSE 0 END) as totalExpense')

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
                'profit' => (string) number_format($item->totalIncome - $item->totalExpense, 2)
            ];
        });

        return $data;
    }



    public function recreate_recurrent($report, $last_report) {
        $reportService = new ReportService();

        $recurrent_transactions = $last_report->transactions()->where('recurrent', true)->get();
        foreach($recurrent_transactions as $transaction){

            $duped_trasaction = $transaction->replicate();
            $duped_trasaction->monthly_report_id = $report->id;
            $duped_trasaction->created_at = now();
            $duped_trasaction->save();

            $reportService->update_report($duped_trasaction->type, $duped_trasaction->value, $report);
        }
    }

}