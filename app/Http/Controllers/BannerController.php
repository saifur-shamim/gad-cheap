<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\BannerService;

class BannerController extends Controller
{
    protected $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function index()
    {
        $banners = $this->bannerService->getBanners();

        return response()->json([
            'status'   => 200,
            'success'  => true,
            'message'  => 'Banners retrieved successfully',
            'banners'  => $banners
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'nullable|string|max:255',
            'link'   => 'nullable|url|max:2048',
            'status' => 'nullable|boolean',
            'image'  => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'success' => false,
                'errors'  => $validator->errors(),
            ], 400);
        }

        
        $path = $request->file('image')->store('banners', 'public');

        $banner = Banner::create([
            'title'  => $request->input('title'),
            'link'   => $request->input('link'),
            'status' => $request->boolean('status', true),
            'image'  => $path, 
        ]);

        return response()->json([
            'status'  => 201,
            'success' => true,
            'message' => 'Banner created successfully',
            'banner'  => $banner,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json([
                'status'  => 404,
                'success' => false,
                'message' => 'Banner not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'  => 'nullable|string|max:255',
            'link'   => 'nullable|url|max:2048',
            'status' => 'nullable|boolean',
            'image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'success' => false,
                'errors'  => $validator->errors(),
            ], 400);
        }

        
        if ($request->hasFile('image')) {
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }

            $path = $request->file('image')->store('banners', 'public');
            $banner->image = $path;
        }

        $banner->title  = $request->input('title', $banner->title);
        $banner->link   = $request->input('link', $banner->link);
        $banner->status = $request->has('status') ? $request->boolean('status') : $banner->status;
        $banner->save();

        return response()->json([
            'status'  => 200,
            'success' => true,
            'message' => 'Banner updated successfully',
            'banner'  => $banner,
        ], 200);
    }
}
