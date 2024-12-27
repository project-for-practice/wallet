<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'cancelled';
    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'amount',
        'transaction_type',
        'transaction_id',
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
                return 'Cancelled';
            default:
                return 'Unknown';
        }
    }
    public static function calculateAmountForUser($userId)
    {
        $totalAmount = self::where('user_id', $userId)
            ->get()
            ->reduce(function ($carry, $transaction) {
                return $transaction->transaction_type == 'deposit'
                    ? $carry + $transaction->amount
                    : $carry - $transaction->amount;
            }, 0);

        return $totalAmount;
    }

    public function setStatusAttribute($value)
    {
        if (!in_array($value, [self::STATUS_PENDING, self::STATUS_COMPLETED, self::STATUS_FAILED])) {
            throw new \InvalidArgumentException("Invalid status value.");
        }
        $this->attributes['status'] = $value;
    }
}

