<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FreelancerCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('freelancer')->check()) {
            $freelancer = authFreelancer();
            if ($freelancer->status  && $freelancer->ev  && $freelancer->sv  && $freelancer->tv) {
                return $next($request);
            } else {
                if ($request->is('api/*')) {
                    $notify[] = 'You need to verify your account first.';
                    return response()->json([
                        'remark' => 'unverified',
                        'status' => 'error',
                        'message' => ['error' => $notify],
                        'data' => [
                            'is_ban' => $freelancer->status,
                            'email_verified' => $freelancer->ev,
                            'mobile_verified' => $freelancer->sv,
                            'twofa_verified' => $freelancer->tv,
                        ],
                    ]);
                } else {
                    return to_route('freelancer.authorization');
                }
            }
        }
        abort(403);
    }
}
