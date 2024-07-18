<?php

namespace App\Http\Controllers\Frontend;

use App\Faq;
use App\Partner;
use Carbon\Carbon;
use App\RoadBlockMessage;
use App\WebsiteSlider;
use GuzzleHttp\Psr7\Request;

class WebsiteController extends CommonController
{
    protected $website = [];

    public function index()
    {
        $this->website['faqs'] = Faq::orderBy('order')->take(5)->get();

        $partners = Partner::where('hide', 0)->inRandomOrder()->take(10)->get();

        $this->website['partners'] = $partners;


        $this->website['road_block_notice'] = RoadBlockMessage::latest()->where("status", 1)->first();

        $this->website['sliders'] = WebsiteSlider::where('hide', 0)->orderBy('order')->get();

        return view("{$this->viewPath}.index", $this->website);
    }

    public function faq()
    {
        $this->website['faqs'] = Faq::all();
        return view("{$this->viewPath}.faq", $this->website);
    }

    public function register()
    {
        return view("{$this->viewPath}.index", $this->website);
    }


    public function deliveryPilot()
    {
        return view("{$this->viewPath}.delivery-pilot", $this->website);
    }

    public function career()
    {
        return view("{$this->viewPath}.career", $this->website);
    }

    public function tac()
    {
        return view("{$this->viewPath}.tac", $this->website);
    }

    public function policy()
    {
        return view("{$this->viewPath}.policy", $this->website);
    }

    public function showLocation($lat = "", $lang = "")
    {
        if ($_GET['lat'] == "" || $_GET['lang'] == "") {
            die("Request error");
        }
        $this->website['lat'] = $_GET['lat'];
        $this->website['lang'] = $_GET['lang'];
        return view("{$this->viewPath}.location.location", $this->website);
    }

    public function download()
    {
        //Detect special conditions devices
        $iPod    = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
        $webOS   = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");
        $windows   = stripos($_SERVER['HTTP_USER_AGENT'], "Windows");

        //do something with this information
        if ($iPod || $iPhone || $iPad) {
            return redirect('https://www.apple.com/app-store/');
        } else if ($Android) {
            return redirect('https://play.google.com/store/apps/details?id=com.gogo20.user');
        } else if ($webOS || $windows) {
            return redirect('https://gogo20.com/#download');
        } else {
            return redirect('https://gogo20.com/#download');
        }
    }
}
