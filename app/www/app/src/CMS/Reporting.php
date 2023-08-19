<?php

namespace App\CMS;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\View\Requirements;

class Reporting extends LeftAndMain
{
    private static $menu_priority = 0.4;

    private static $url_segment = 'reporting';

    private static $menu_title = 'Reporting';

    private static $menu_icon_class = 'font-icon-tags';

    public function init()
    {
        parent::init();
        Requirements::javascript('vendor/elvanti/elvanti-coupons-and-promotions/client/dist/_dist_/couponAndPromoBuilder/index.js', ["type" => "module"]);
    }
}
