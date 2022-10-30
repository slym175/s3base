<?php

namespace S3base\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;

class DebugController extends Controller
{
    public function optimizeClear(Request $request)
    {
        try {
            Artisan::call('optimize:clear');
            return redirect()->back()->with('_success_msg', trans('s3base/core::core.optimize-clear'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('_error_msg', $ex->getMessage());
        }
    }

    public function cacheClear(Request $request)
    {
        try {
            Artisan::call('cache:clear');
            return redirect()->back()->with('_success_msg', trans('s3base/core::core.cache-clear'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('_error_msg', $ex->getMessage());
        }
    }
}
