<?php

namespace App\Services;

use App\Models\Banner;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class BannerService
{
    public function getBanners()
    {
        $bannerBuilder = Banner::select([
            'id',
            'title',
            'image',
            'link',
            'status'
        ]);

        $bannerBuilder->where('status', true);

        $banners = $bannerBuilder->get();

        // Add image_url
        $banners->transform(fn($banner) => tap(
            $banner,
            fn($b) => $b->image_url = $b->image ? url($b->image) : null
        ));

        return $banners;
    }

}
