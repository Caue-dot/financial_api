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

    public function refresh_values(){
        $new_total_expense = $this->transactions()->where('type', 'E')->sum('value');
        $new_total_income = $this->transactions()->where('type','I')->sum('value');

        $this->total_income = $new_total_income;
        $this->total_expense = $new_total_expense;

        $this->save();

    }

    

     
}
