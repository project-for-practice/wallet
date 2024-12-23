<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id', // user must exist in the users table
            'amount' => 'required|numeric|min:0', // amount must be a number and greater than 0
            'transaction_type' => 'required|in:deposit,withdrawal', // must be 'deposit' or 'withdrawal'
            'status' => 'nullable|in:pending,completed,failed', // optional but should be a valid status if provided
        ];
    }
}
