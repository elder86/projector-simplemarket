<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class GumpController extends Controller
{
    public function sendEvent()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://wax.cryptolions.io/v1/chain/get_info');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $responseRaw = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        if (Response::HTTP_OK === $responseCode) {
            $response = json_decode($responseRaw, true);
            if ($response && array_key_exists('last_irreversible_block_id', $response)) {

                $analytics = new Analytics();

                $analytics->setProtocolVersion('1')
                    ->setTrackingId('UA-12797630-1')
                    ->setClientId('2133506694.1448249699')
                    ->setUserId('123');

                $analytics->setEventCategory('Transaction')
                    ->setEventAction('LastIrreversibleBlockId')
                    ->setEventLabel($response['last_irreversible_block_id'])
                    ->sendEvent();
            }
        }

        return 'Test';
    }
}
