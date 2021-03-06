<?php

namespace OneSignal;

use GuzzleHttp\Client;

class Devices {
    private $tags;
    private $apiUrl = 'https://onesignal.com/api/v1/players/';
    private $appId;
    private $apiKey;
    private $largeIcon;
    private $playerId;

    public function __construct($playerId = null) {
        $this->playerId = $playerId;
		$tenant = config('onesignal.tenant');
		if($tenant) {
			$this->appId = config($tenant.'.'.\Tenant::getCurrentTenant().'.onesignal.app_id');
			$this->apiKey = config($tenant.'.'.\Tenant::getCurrentTenant().'.onesignal.api_key');
			$this->largeIcon = config($tenant.'.'.\Tenant::getCurrentTenant().'.onesignal.large_icon');
		} else {
			$this->appId = env('ONESIGNAL_APP_ID');
			$this->apiKey = env('ONESIGNAL_API_KEY');
			$this->largeIcon = config('onesignal.large_icon');	
		}
    }

    public function playerId($playerId) {
        $this->playerId = $playerId;

        return $this;
    }

    public function tags(array $tags) {
        $this->tags = $tags;

        return $this;
    }

    public function send() {

        $client = new Client();

        try {
            $response = $client->put($this->apiUrl . $this->playerId,
                [
                    'headers' => [
                        'Authorization' => 'Basic ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'app_id' => $this->appId,
                        'tags' => $this->tags
                    ],
                ]);

            return json_decode($response->getBody());
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
