<?php

namespace Models;

use Debug\DebugTools;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = [
        'division_id',
        'product_id',
        'count',
        'is_completed'
    ];

    public function markAsCompleted(): void
    {
        $this->is_completed = true;
        $this->save();
    }
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}