<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionCollection;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;

class TransactionController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request){
        $transactions = Transaction::report_where(['user_id' => $request->user()->id]);

        $total_expense = (clone $transactions)->where('type', 'E')->sum('value') ;
        $total_income = (clone $transactions)->where('type', 'I')->sum('value') ;

        $data = [
            'transactions' => new TransactionCollection($transactions->orderBy('created_at', 'desc')->get()),
            'totalExpense' => $total_expense,
            'totalIncome' => $total_income,
        ];

        return  $data;
    }

    public function get_transaction(Transaction $transaction){
        $this->authorize('view', $transaction);
        return new TransactionResource($transaction);
    }

    public function store(StoreTransactionRequest $request, TransactionService $transactionService){
        $data = $request->validated();
        $transaction = $transactionService->store($data);
        return new TransactionResource($transaction);
    }

    public function list_by_category($category, TransactionService $transactionService){
        $transactions = $transactionService->list_by_category($category);
        return new TransactionCollection($transactions);
    }

    public function list_all_categories(TransactionService $transactionService){
        $categories = $transactionService->categories_info_in_transaction();

        return $categories;
    }

    public function delete(Transaction $transaction, TransactionService $transactionService){
        $transactionService->delete($transaction);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction, TransactionService $transactionService){
        $data = $request->validated();
        $transactionService->update($transaction, $data);
        
    }
}
