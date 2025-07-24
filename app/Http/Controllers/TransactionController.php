<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionCollection;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;

class TransactionController extends Controller
{
    public function index(Request $request){
        $transactions = Transaction::report_where(['user_id' => $request->user()->id]);

        $total_expense = (clone $transactions)->where('type', 'E')->sum('value') ;
        $total_income = (clone $transactions)->where('type', 'I')->sum('value') ;

        $data = [
            'transactions' => new TransactionCollection($transactions->get()),
            'totalExpense' => $total_expense,
            'totalIncome' => $total_income,
        ];

        return  $data;
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

    public function delete(Transaction $transaction, TransactionService $transactionService){
        $transactionService->delete($transaction);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction, TransactionService $transactionService){
        $data = $request->validated();
        $transactionService->update($transaction, $data);
        
    }
}
