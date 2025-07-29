<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public $timestamps = ["created_at"]; 
    const UPDATED_AT = null;

    protected $fillable = [
        'monthly_report_id',
        'value',
        'name',
        'description',
        'type',
        'category',
        'recurrent'
    ];


    protected $casts = [
        'value' => 'decimal:2'
    ];

    public function report(){
        return $this->belongsTo(MonthlyReport::class, 'monthly_report_id');
    }

    public static function get_all_by_category($category){
        return static::where('category', $category)->get();
    }


    public static function report_where($condition){
        $result = static::whereHas('report', function($report) use ($condition){
            $report->where($condition);
        });

        return $result;
    }
}
