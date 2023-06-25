<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class Seller extends Model
{
    use Searchable, HasFactory;
    protected $table = 'seller';
    protected $primaryKey = 'seller_id';


    public function searchableAs() {
        return 'seller_index';
    }

    public function toSearchableArray()
    {
        return [
            'seller_id' => $this->seller_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'status' => $this->status
        ];
    }
}
