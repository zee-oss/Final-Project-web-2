<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = ['code', 'name', 'address', 'city', 'phone', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function users(): HasMany       { return $this->hasMany(User::class); }
    public function transactions(): HasMany { return $this->hasMany(Transaction::class); }
    public function stocks(): HasMany      { return $this->hasMany(Stock::class); }
}
