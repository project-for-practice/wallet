<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    protected $table = 'transactions';


    protected $fillable = [
        'user_id',
        'amount',
        'transaction_type',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'Pending';
            case self::STATUS_COMPLETED:
                return 'Completed';
            case self::STATUS_FAILED:
                return 'Failed';
            default:
                return 'Unknown';
        }
    }

    public function setStatusAttribute($value)
    {
        if (!in_array($value, [self::STATUS_PENDING, self::STATUS_COMPLETED, self::STATUS_FAILED])) {
            throw new \InvalidArgumentException("Invalid status value.");
        }
        $this->attributes['status'] = $value;
    }


}

// $transaction = Transaction::create([
//     'user_id' => 1,
//     'amount' => 100.50,
//     'transaction_type' => 'deposit',
//     // The status will default to 'pending' if not provided
// ]);

// // Create a new transaction with a specific status
// $completedTransaction = Transaction::create([
//     'user_id' => 1,
//     'amount' => 50.00,
//     'transaction_type' => 'withdrawal',
//     'status' => Transaction::STATUS_COMPLETED, // Use the constant for status
// ]);
