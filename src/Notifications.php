<?php

namespace OneSignal;

use GuzzleHttp\Client;

class Notifications {
    private $appId;
    private $apiKey;
    private $includePlayerIds;
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
        $this->largeIcon = config('onesignal.large_icon');
    }

    public function contents(array $contents) {
        foreach ($contents as $k => $v) {
            $this->contents[$k] = $v;
        }

        return $this;
    }

    public function headings(array $headings) {
        foreach ($headings as $k => $v) {
            $this->headings[$k] = $v;
        }

        return $this;
    }

    public function includePlayerIds(array $includePlayerIds) {
        $this->includePlayerIds = '[';

        foreach ($includePlayerIds as $k => $v) {
            $this->includePlayerIds .= "'$v', ";
        }

        $this->includePlayerIds .= ']';

        return $this;
    }

    public function url(String $url) {
        $this->url = $url;

        return $this;
    }

    public function largeIcon(String $largeIcon) {
        $this->largeIcon = $largeIcon;

        return $this;
    }

    public function bigPicture(String $bigPicture) {
        $this->bigPicture = $bigPicture;

        return $this;
    }

    public function includedSegment($includedSegment) {
        $this->includedSegment = $includedSegment;

        return $this;
    }

    public function data(array $data) {
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
                        'include_player_ids[]' => $this->includePlayerIds,
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