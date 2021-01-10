<?php

namespace App\Http\Controllers;

use Irazasyed\LaravelGAMP\Facades\GAMP;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class GumpController extends Controller
{
    public function sendEvent()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://example.net/wp-login.php');


        $analytics = new Analytics();

        $analytics->setProtocolVersion('1')
            ->setTrackingId('UA-12797630-1')
            ->setClientId('2133506694.1448249699')
            ->setUserId('123');

        $analytics->setEventCategory('Transaction')
            ->setEventAction('LastRetrive')
            ->setEventLabel('111')
            ->sendEvent();



//        $gamp = GAMP::class;
//        $gamp->setEventCategory('Blog Post')
//            ->setEventAction('Create')
//            ->setEventLabel('Using GAMP In Laravel')
//            ->sendEvent();




        return 'Test';
    }
}
