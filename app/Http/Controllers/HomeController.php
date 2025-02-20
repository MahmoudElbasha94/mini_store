<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $coupon = Coupon::where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->first();
        return view('user.home', compact('coupon'));
    }
}
