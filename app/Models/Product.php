<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable, HasFactory;
    protected $table = 'product';
    protected $primaryKey = 'product_id';

    public function searchableAs() {
        return 'product_index';
    }

    public function toSearchableArray()
    {
        return [
            'product_id' => $this->product_id,
            'seller_id' => $this->seller_id,
            'product_name' => $this->product_name,
            'is_approved' => $this->is_approved,
            'banned_by' => $this->banned_by,
            'category_id' => $this->category_id,
        ];

    }
}
