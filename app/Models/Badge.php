<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $guarded = BaseModel::COMMON_GUARDED_FIELDS_SIMPLE;

    //  Badge has many users
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
