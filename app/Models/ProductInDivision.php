<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Models\Division;
use Models\Product;

class ProductInDivision extends Model
{
    public $timestamps = false;
    protected $table = 'divisions_products';

    protected $fillable = ['product_id', 'division_id', 'count', 'min_value'];

    public function division(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function addToCount(int $count)
    {
        $this->count += $count;
    }
}