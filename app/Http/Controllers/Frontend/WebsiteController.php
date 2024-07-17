<?php

namespace App\Http\Controllers\Frontend;

use App\Faq;
use App\RoadBlockMessage;

class WebsiteController extends CommonController
{
    protected $website = [];

    public function index()
    {
        $this->website['faqs'] = Faq::orderBy('order')->take(5)->get();
        $this->website['road_block_notice'] = RoadBlockMessage::where("id", 1)->where("status", 1)->get();
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
}
