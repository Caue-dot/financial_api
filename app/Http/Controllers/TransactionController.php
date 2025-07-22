<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransactionCollection;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;

class TransactionController extends Controller
{
    public function index(){
        $transactions = Transaction::all();

        return new TransactionCollection($transactions);
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
