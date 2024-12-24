<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|string',  // Add validation for status
        ]);

        $query = Transaction::query();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($query) use ($search) {
                $query->where('transaction_id', 'like', "%$search%")
                    ->orWhere('amount', 'like', "%$search%")
                    ->orWhere('created_at', 'like', "%$search%");
            });
        }

        if ($request->has('transaction_type') && !empty($request->transaction_type)) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        if ($request->has('status') && $request->status !== '') {
            $status = $request->status;
            $query->where('status', $status);
        }
        $transactions = $query->with('user')->get();

        return Inertia::render('Transaction', [
            'transaction' => $transactions,
        ]);
    }

    public function createTransaction()
    {
        try {
            $transaction = Transaction::create([
                'user_id' => 1,
                'amount' => 150.75,
                'transaction_type' => 'deposit',
                'transaction_id' => 'TX12345',
                'status' => Transaction::STATUS_PENDING,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction created successfully.',
                'transaction_id' => 'TX1234534545',
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
        // $completedTransactions = Transaction::where('status', Transaction::STATUS_COMPLETED)->get();
        $completedTransactions = Transaction::with('user')->get();
        // dd($completedTransactions);
        return Inertia::render('Transaction', [
            'transaction' => $completedTransactions
        ]);
    }
    public function search(Request $request)
    {

        $request->validate([
            'search' => 'nullable|string|max:255'
        ]);

        $query = Transaction::query();

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($query) use ($search) {
                $query->where('transaction_id', 'like', "%$search%")
                    ->orWhere('amount', 'like', "%$search%")
                    ->orWhere('created_at', 'like', "%$search%");
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $transactions = $query->get();

        return Inertia::render('Transaction', [
            'transaction' => $transactions,
        ]);
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled', // The possible status values
        ]);

        $transaction = Transaction::findOrFail($id);

        $transaction->status = $request->status;
        $transaction->save();
        return response()->json([
            'message' => 'Transaction status updated successfully.',
            'transaction' => $transaction,
        ]);
    }
}
