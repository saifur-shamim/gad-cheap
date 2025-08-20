<?php 

namespace App\Services;

use App\Models\Event;
use App\Models\Product;

class EventService
{
    public function addProductsToEvent(Event $event, array $productIds)
    {
        foreach($productIds as $id){
            $product = Product::find($id);
            if($product && $product->events()->count() === 0){
                $event->products()->attach($id);
            }
        }
    }
}
