<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Response;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class SendGAEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ga:send_event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a marketing email to a user';

    /**
     * @var Analytics
     */
    private $analytics;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        for ($i = 0; $i<300; $i++) {
            $response = $this->getApiResponse();
            if ($response && array_key_exists('last_irreversible_block_id', $response)) {
                $this->sendGaEvent($response['last_irreversible_block_id']);
                var_dump($i, $response['last_irreversible_block_id']);
                sleep(1);
            }
        }


        return true;
    }

    /**
     * @return array|null
     */
    private function getApiResponse(): ?array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://wax.cryptolions.io/v1/chain/get_info');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $responseRaw = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        if (Response::HTTP_OK === $responseCode) {
            $response = json_decode($responseRaw, true);
            if ($response) {
                return $response;
            }
        }

        return null;
    }

    /**
     * @param string $value
     */
    private function sendGaEvent(string $value): void
    {
        $this->getAnalytics()->setEventCategory('Transaction')
            ->setEventAction('LastIrreversibleBlockId')
            ->setEventLabel($value)
            ->sendEvent();
    }

    /**
     * @return Analytics
     */
    private function getAnalytics(): Analytics
    {
        if (!$this->analytics) {
            $this->analytics = new Analytics();

            $this->analytics->setProtocolVersion('1')
                ->setTrackingId('UA-12797630-1')
                ->setClientId('2133506694.1448249699')
                ->setUserId('123');
        }

        return $this->analytics;
    }
}
