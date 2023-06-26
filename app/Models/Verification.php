<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Verification extends Model
{
    use Searchable, HasFactory;
    protected $table = 'verification';
    protected $primaryKey = 'ver_id';

    public function searchableAs() {
        return 'verification_index';
    }

    public function toSearchableArray()
    {
        return [
            'seller_id' => $this->seller_id,
            'ver_id' => $this->ver_id,
            'store_name' => $this->store_name,
            'verified_by' => $this->verified_by,
            'verified_at' => $this->verified_at,
            'created_at' => $this->created_at,
        ];
    }
}
