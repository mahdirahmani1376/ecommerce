<?php

namespace App\Http\Controllers;

use App\Models\Seo;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function seoSetting()
    {
        $seo = Seo::find(1);
        return view('backend.seo.seo_update',compact('seo'));
    }
}
