<?php

namespace App\Enums;

enum BrandTypes: string
{
    case ALL = 'all';
    case PRODUCTS ='products'; 
    case BRANDS = 'brands'; 
    case CATEGORIES = 'categories';
}
