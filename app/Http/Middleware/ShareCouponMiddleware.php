<?php

namespace App\Http\Middleware;

use App\Models\Coupon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareCouponMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $coupon = Coupon::where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->first();

        View::share('globalCoupon', $coupon);
        return $next($request);
    }
}
