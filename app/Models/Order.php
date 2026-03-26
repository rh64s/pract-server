<?php

namespace Models;

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

        $division_product = ProductInDivision::where('division_id', $this->division)->where('product_id', $this->product_id)->first();
        $division_product->count += $this->count;
        $division_product->save();
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