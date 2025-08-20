<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
   protected $guarded = BaseModel::COMMON_GUARDED_FIELDS_SIMPLE;

   public function user()
   {
      return $this->belongsTo(User::class);
   }

   public function order()
   {
      return $this->belongsTo(Order::class);
   }
}
