<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   protected $guarded = BaseModel::COMMON_GUARDED_FIELDS_SIMPLE;

   // Product belongs to a brand
   public function brand()
   {
      return $this->belongsTo(Brand::class);
   }

   // Product belongs to a category
   public function category()
   {
      return $this->belongsTo(Category::class);
   }

   // Product can belong to many orders
   public function orders()
   {
      return $this->belongsToMany(Order::class)->withPivot('quantity', 'price');
   }

   // Product can belong to many events
   public function events()
   {
      return $this->belongsToMany(Event::class);
   }

   
}
