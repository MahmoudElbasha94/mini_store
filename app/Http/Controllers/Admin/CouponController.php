<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{

    public function index() {
        $coupons = Coupon::paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create() {
        return view('admin.coupons.create');
    }

    public function store(Request $request) {

        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'discount' => 'required|numeric|min:1|max:100',
            'valid_from' => 'required|date|after_or_equal:' . Carbon::now('Africa/Cairo')->toDateTimeString(),
            'valid_until' => 'required|date|after_or_equal:valid_from'
        ]);


        Coupon::create([
            'code' => $request->code,
            'discount' => $request->discount,
            'valid_from' => $request->valid_from ? Carbon::parse($request->valid_from, 'Africa/Cairo')->timezone('UTC') : null,
            'valid_until' => $request->valid_until ? Carbon::parse($request->valid_until, 'Africa/Cairo')->timezone('UTC') : null,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully!');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }
}
