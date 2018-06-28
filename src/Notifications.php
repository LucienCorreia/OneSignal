<?php

namespace OneSignal;

use GuzzleHttp\Client;

class Notifications {
    private $appId;
    private $apiKey;
    private $includePlayerId;
    private $contents = [];
    private $largeIcon;
    private $url;
    private $bigPicture;
    private $apiUrl = 'https://onesignal.com/api/v1/notifications';
    private $headings = [];
    private $includedSegment;
    private $data = [];

    public function __construct() {
        $this->appId = env('ONESIGNAL_APP_ID');
        $this->apiKey = env('ONESIGNAL_API_KEY');
        $this->largeIcon = config('onesignal.large_icon', null);
    }

    public function contents(array $contents): OneSignal {
        foreach ($contents as $k => $v) {
            $this->contents[$k] = $v;
        }

        return $this;
    }

    public function headings(array $headings): OneSignal {
        foreach ($headings as $k => $v) {
            $this->headings[$k] = $v;
        }

        return $this;
    }

    public function includePlayerId($includePlayerId): OneSignal {
        $this->includePlayerId = $includePlayerId;

        return $this;
    }

    public function url(String $url): OneSignal {
        $this->url = $url;

        return $this;
    }

    public function largeIcon(String $largeIcon): OneSignal {
        $this->largeIcon = $largeIcon;

        return $this;
    }

    public function bigPicture(String $bigPicture): OneSignal {
        $this->bigPicture = $bigPicture;

        return $this;
    }

    public function includedSegment($includedSegment): OneSignal {
        $this->includedSegment = $includedSegment;

        return $this;
    }

    public function data(array $data): OneSignal {
        foreach ($data as $k => $v) {
            $this->data[$k] = $v;
        }

        return $this;
    }

    public function send() {
        $client = new Client();

        try {
            $response = $client->post($this->apiUrl,
                [
                    'headers' => [
                        'Authorization' => 'Basic ' . $this->apiKey,
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                    'form_params' => [
                        'app_id' => $this->appId,
                        'contents' => $this->contents,
                        'large_icon' => $this->largeIcon,
                        'url' => $this->url,
                        'big_picture' => $this->bigPicture,
                        'include_player_ids[]' => $this->includePlayerId,
                        'included_segments[]' => $this->includedSegment,
                        'headings' => $this->headings,
                        'data' => $this->data,
                    ],
                ]);

            return json_decode($response->getBody()->getContents());
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}