<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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

    /**
     * POST /api/banners
     * Body: multipart/form-data (title, link?, status?, image)
     */
    public function store(Request $request)
    {
        // 1) Validate input
        $validator = Validator::make($request->all(), [
            'title'  => 'nullable|string|max:255',
            'link'   => 'nullable|url|max:2048',
            'status' => 'nullable|boolean',
            'image'  => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:3072', // 3MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'success' => false,
                'errors'  => $validator->errors(),
            ], 400);
        }

        // 2) Handle file upload to public/uploads/banners
        $path = null;
        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $filename  = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/banners'), $filename);
            $path = 'uploads/banners/' . $filename; // store relative path in DB
        }

        // 3) Create banner
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
        // 1) Find banner
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json([
                'status'  => 404,
                'success' => false,
                'message' => 'Banner not found',
            ], 404);
        }

        // 2) Validate input
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

        // 3) Handle file upload (if new image uploaded)
        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($banner->image && file_exists(public_path($banner->image))) {
                unlink(public_path($banner->image));
            }

            $file     = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/banners'), $filename);
            $banner->image = 'uploads/banners/' . $filename;
        }

        // 4) Update fields
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
