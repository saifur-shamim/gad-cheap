<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    /**
     * GET /api/sliders  (optional)
     */
    public function index()
    {
        $sliders = Slider::where('status', true)
            ->get(['id', 'title', 'caption', 'image', 'link', 'status']);

        // Optionally add full URL
        $sliders->transform(function ($slider) {
            $slider->image_url = $slider->image ? url($slider->image) : null;
            return $slider;
        });

        return response()->json([
            'status'  => 200,
            'success' => true,
            'message' => 'Sliders retrieved successfully',
            'sliders' => $sliders
        ], 200);
    }

    /**
     * POST /api/sliders
     * Body: multipart/form-data (title?, caption?, link?, status?, image)
     */
    public function store(Request $request)
    {
        // 1) Validate input
        $validator = Validator::make($request->all(), [
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
            'link'    => 'nullable|url|max:2048',
            'status'  => 'nullable|boolean',
            'image'   => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'success' => false,
                'errors'  => $validator->errors(),
            ], 400);
        }

        // 2) Handle file upload to public/uploads/sliders
        $path = null;
        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $filename  = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/sliders'), $filename);
            $path = 'uploads/sliders/' . $filename;
        }

        // 3) Create slider
        $slider = Slider::create([
            'title'   => $request->input('title'),
            'caption' => $request->input('caption'),
            'link'    => $request->input('link'),
            'status'  => $request->boolean('status', true),
            'image'   => $path,
        ]);

        return response()->json([
            'status'  => 201,
            'success' => true,
            'message' => 'Slider created successfully',
            'slider'  => $slider,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        // 1) Find slider
        $slider = Slider::find($id);

        if (!$slider) {
            return response()->json([
                'status'  => 404,
                'success' => false,
                'message' => 'Slider not found',
            ], 404);
        }

        // 2) Validate input
        $validator = Validator::make($request->all(), [
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
            'link'    => 'nullable|url|max:2048',
            'status'  => 'nullable|boolean',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 400,
                'success' => false,
                'errors'  => $validator->errors(),
            ], 400);
        }

        // 3) Handle file upload if new image provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($slider->image && file_exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }

            $file      = $request->file('image');
            $filename  = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/sliders'), $filename);
            $slider->image = 'uploads/sliders/' . $filename;
        }

        // 4) Update fields
        $slider->title   = $request->input('title', $slider->title);
        $slider->caption = $request->input('caption', $slider->caption);
        $slider->link    = $request->input('link', $slider->link);
        $slider->status  = $request->has('status') ? $request->boolean('status') : $slider->status;

        $slider->save();

        return response()->json([
            'status'  => 200,
            'success' => true,
            'message' => 'Slider updated successfully',
            'slider'  => $slider,
        ], 200);
    }
}
