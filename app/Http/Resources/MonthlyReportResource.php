<?php

namespace App\Http\Resources;

use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $include_categories  = (bool) $request->query('includeCategories');
        $reportService = new ReportService;
        return [
            'id' => $this->id,
            'userId'=> $this->user_id,
            //Carbon:parse because for some reason the create method its returning a string on month timestamp
            'month' => Carbon::parse($this->month)->format('Y-m'), 
            'totalIncome' => $this->total_income,
            'totalExpense' => $this->total_expense,
            'transactions' => TransactionResource::collection($this->whenLoaded('transactions')),
            'categories' => $this->when($include_categories, $reportService->categories_info_in_report($this->id)),
        ];
    }
}
