<?php

namespace App\Http\Middleware;

use Closure;

class Checksession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {
    //     $uriSegments = request()->segments();
    //     $systemRolesArray = $request->session()->get('system_roles');
    //     $message = 'You do not have permission to access this page. Kindly contact administrator.';
    //     if ($request->session()->get('id') == "") {
    //         return redirect('/admin');
    //     }
    //     if (isset($systemRolesArray[$uriSegments[1]])) {
    //         $permission = $systemRolesArray[$uriSegments[1]];
    //         if ($permission == 5) {
    //             return $next($request);
    //         } elseif ($permission == 4) {
    //             if (isset($uriSegments[2])) {
    //                 if ($uriSegments[2] == 'list' || $uriSegments[2] == 'add' || $uriSegments[2] == 'edit' || $uriSegments[1] == 'dashboard') {
    //                     return $next($request);
    //                 } else {
    //                     return redirect()->back()->withErrors([$message]);
    //                 }
    //             } else {
    //                 return $next($request);
    //             }
    //         } elseif ($permission == 3) {
    //             if (isset($uriSegments[2])) {
    //                 if ($uriSegments[2] == 'list' || $uriSegments[2] == 'add' || $uriSegments[1] == 'dashboard') {
    //                     return $next($request);
    //                 } else {
    //                     return redirect()->back()->withErrors([$message]);
    //                 }
    //             } else {
    //                 return $next($request);
    //             }
    //         } elseif ($permission == 2) {
    //             if (isset($uriSegments[2])) {
    //                 if ($uriSegments[2] == 'list' || $uriSegments[2] == 'edit' || $uriSegments[1] == 'dashboard') {
    //                     return $next($request);
    //                 } else {
    //                     return redirect()->back()->withErrors([$message]);
    //                 }
    //             } else {
    //                 return $next($request);
    //             }
    //         } elseif ($permission == 1) {
    //             if (isset($uriSegments[2])) {
    //                 if ($uriSegments[2] == 'list' || $uriSegments[1] == 'dashboard') {
    //                     return $next($request);
    //                 } else {
    //                     return redirect()->back()->withErrors([$message]);
    //                 }
    //             } else {
    //                 return $next($request);
    //             }
    //         } else {
    //             // dd($uriSegments);
    //             if ($uriSegments[2] == 'dashboard') {
    //                 return $next($request);
    //             } else {
    //                 return redirect()->back()->withErrors([$message]);
    //             }
    //         }
    //     } else {
    //         if ($uriSegments[1] == 'dashboard') {
    //             return $next($request);
    //         } else {
    //             // Auth::logout();
    //             // Session::flush();
    //             return redirect()->back()->withErrors([$message]);
    //         }
    //     }
    //     // return $next($request);
    // }

    public function handle($request, Closure $next)
{
    $uriSegments = request()->segments();
    $segment1 = $uriSegments[1] ?? null;
    $segment2 = $uriSegments[2] ?? null;

    $systemRolesArray = $request->session()->get('system_roles');
    $message = 'You do not have permission to access this page. Kindly contact administrator.';

    // If session 'id' is missing, redirect to login
    if ($request->session()->get('id') == "") {
        return redirect('/admin');
    }

    // ✅ Always allow access to dashboard
    if ($segment1 === 'dashboard') {
        return $next($request);
    }

    if (isset($systemRolesArray[$segment1])) {
        $permission = $systemRolesArray[$segment1];

        switch ($permission) {
            case 5:
                return $next($request);
            case 4:
                if (in_array($segment2, ['list', 'add', 'edit']) || $segment1 === 'dashboard') {
                    return $next($request);
                }
                break;
            case 3:
                if (in_array($segment2, ['list', 'add']) || $segment1 === 'dashboard') {
                    return $next($request);
                }
                break;
            case 2:
                if (in_array($segment2, ['list', 'edit']) || $segment1 === 'dashboard') {
                    return $next($request);
                }
                break;
            case 1:
                if ($segment2 === 'list' || $segment1 === 'dashboard') {
                    return $next($request);
                }
                break;
            default:
                break;
        }

        return redirect()->back()->withErrors([$message]);
    }

    // ✅ If no permission set for this module but it's dashboard, already allowed above
    // If not dashboard and no permission:
    return redirect()->back()->withErrors([$message]);
}

}
