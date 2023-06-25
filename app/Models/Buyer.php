<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class Buyer extends Model
{
    use Searchable, HasFactory;
    protected $table = 'buyer';
    protected $primaryKey = 'buyer_id';

    public function searchableAs() {
        return 'buyer_index';
    }

    public function toSearchableArray()
    {
        return [
            'buyer_id' => $this->buyer_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'status' => $this->status
        ];
    }
}
