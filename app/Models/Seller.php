<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class Seller extends Model implements Authenticatable
{
    use HasApiTokens, Searchable;
    protected $table = 'seller';
    protected $primaryKey = 'seller_id';

    function getAuthIdentifierName() {
        return 'seller_id';
    }
    public function getAuthIdentifier() {
        return $this->seller_id;
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

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
    use HasFactory;
}
