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
            'product_name' => $this->product_name,
            'category_id' => $this->category_id,
            'is_approved' => $this->is_approved,
            'created_at' => $this->created_at,
            // havent run command to import this index again yet
        ];
    }
}
