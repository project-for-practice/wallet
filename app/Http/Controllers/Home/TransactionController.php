<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function createTransaction()
    {
        try {
            $transaction = Transaction::create([
                'user_id' => 1,
                'amount' => 150.75,
                'transaction_type' => 'deposit',
                'status' => Transaction::STATUS_PENDING,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully.',
                'transaction' => $transaction,
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction: ' . $e->getMessage(),
            ], 400);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function createTransactions(StoreTransactionRequest $request)
    {
        $transaction = Transaction::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Transaction created successfully.',
            'transaction' => $transaction,
        ]);
    }
    public function getTrasaction()
    {
        $completedTransactions = Transaction::where('status', Transaction::STATUS_COMPLETED)->get();
        // dd($completedTransactions);
        return Inertia::render('Transaction', [
            'transaction' => $completedTransactions
        ]);
    }
}
