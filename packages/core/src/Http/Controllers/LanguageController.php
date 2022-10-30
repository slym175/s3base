<?php

namespace S3base\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LanguageController extends Controller
{
    public function setLanguage(Request $request, $language)
    {
        try {
            app()->setLocale($language);
            session()->put('locale', $language);
            return redirect()->back()->with('_success_msg', trans('s3base/core::core.set-language'));
        } catch (\Exception $ex) {
            return redirect()->back()->with('_error_msg', $ex->getMessage());
        }
    }
}
