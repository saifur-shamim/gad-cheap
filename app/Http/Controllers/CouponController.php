<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    // Show available coupons
    public function index()
    {
        $coupons = Coupon::where('expires_at', '>', now())
            ->where('is_used', false)
            ->get();

        return response()->json($coupons);
    }

    // Redeem coupon
    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:coupons,code',
        ]);

        $user = Auth::user();
        $coupon = Coupon::where('code', $request->code)->first();

        if ($coupon->is_used) {
            return response()->json(['message' => 'Coupon already used!'], 400);
        }

        if ($coupon->expires_at && $coupon->expires_at < now()) {
            return response()->json(['message' => 'Coupon expired!'], 400);
        }

        if ($coupon->min_points && $user->points < $coupon->min_points) {
            return response()->json(['message' => 'Not enough points!'], 400);
        }

        if ($coupon->min_purchase && $request->amount < $coupon->min_purchase) {
            return response()->json(['message' => 'Minimum purchase not met!'], 400);
        }

        // Mark coupon as used
        $coupon->update([
            'is_used' => true,
            'user_id' => $user->id,
        ]);

        // Optionally deduct points
        if ($coupon->min_points) {
            $user->points -= $coupon->min_points;
            $user->save();
        }

        return response()->json([
            'message' => 'Coupon redeemed successfully!',
            'discount' => $coupon->discount,
        ]);
    }
}

