<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = ['name', 'display_name'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Konstanta untuk kemudahan
    const OWNER      = 'owner';
    const MANAGER    = 'manager';
    const SUPERVISOR = 'supervisor';
    const CASHIER    = 'cashier';
    const WAREHOUSE  = 'warehouse';
}
