<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    public $timestamps = false; 
    

    public $fillable = [
        'user_id', 
        'month', 
        'total_income', 
        'total_expense'
    ];

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public static function get_report_by_date($year, $month, $user_id, $include_transactions = false){
        $report =  static::whereYear('month', $year)->whereMonth('month', $month)->where('user_id', $user_id);


        if($include_transactions){
            $report->with('transactions');
        }

        return $report->first();
    }


    public static function get_last_report($user_id, $include_transactions = false, $exclude_first){
        $report = static::where('user_id', $user_id)->orderBy('month', 'desc');
        
        if($exclude_first){
            $report = $report->skip(1);
        }

        $report = $report->first();
        return $report;
    }

    public function refresh_values(){
        $new_total_expense = $this->transactions()->where('type', 'E')->sum('value');
        $new_total_income = $this->transactions()->where('type','I')->sum('value');

        $this->total_income = $new_total_income;
        $this->total_expense = $new_total_expense;

        $this->save();

    }

    

     
}
