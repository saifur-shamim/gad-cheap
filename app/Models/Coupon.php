<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
   protected $guarded = BaseModel::COMMON_GUARDED_FIELDS_SIMPLE;

   // 🔗 Coupon belongs to a user
   public function user()
   {
      return $this->belongsTo(User::class);
   }
}
