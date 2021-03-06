<?php

namespace OneSignal;

use GuzzleHttp\Client;

class Notifications {

    private $androidGroup = 'Novas notificações!';
    private $androidGroupMessage = [
        'en' => 'Você tem $[notif_count] novas notificações!'
    ];
    private $apiUrl = 'https://onesignal.com/api/v1/notifications';
    private $appId;
    private $apiKey;
    private $buttons = [];
    private $background;
    private $bigPicture;
    private $contents = [];
    private $data;
    private $headings = [];
    private $includePlayerIds = null;
    private $includedSegments = null;
    private $largeIcon;
    private $smallIcon;
    private $sendAfter;
    private $tag;
    private $url;
    private $webUrl;

    public function __construct() {
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

    public function button(array $button) {
        $this->buttons[] = $button;

        return $this;
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
        $this->includePlayerIds = $includePlayerIds;

        return $this;
    }

    public function includedSegments(array $includedSegments) {
        $this->includedSegments = $includedSegments;

        return $this;
    }

    public function url(string $url) {
        $this->url = $url;

        return $this;
    }

    public function webUrl(string $webUrl) {
        $this->webUrl = $webUrl;

        return $this;
    }

    public function smallIcon(string $smallIcon) {
        $this->smallIcon = $smallIcon;

        return $this;
    }

    public function bigPicture(string $bigPicture) {
        $this->bigPicture = $bigPicture;

        return $this;
    }

    public function background(array $background) {
        $this->background = $background;

        return $this;
    }

    public function tag($key, $value) {
        $this->tag[] = [
            'field' => 'tag',
            'key' => $key,
            'relation' => '=',
            'value' => $value
        ];

        return $this;
    }

    public function data(array $data) {
        foreach ($data as $k => $v) {
            $this->data[$k] = $v;
        }

        return $this;
    }

    public function sendAfter(string $sendAfter) {
        $this->sendAfter = $sendAfter;

        return $this;
    }

    public function send() {

        $client = new Client();
        
        if($this->includePlayerIds == null) {
            $this->includedSegments = ['Subscribed Users'];
        }

        try {
            $response = $client->post($this->apiUrl,
                [
                    'headers' => [
                        'Authorization' => 'Basic ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'app_id' => $this->appId,
                        'android_group' => $this->androidGroup,
                        'android_group_message' => $this->androidGroupMessage,
                        'buttons' => $this->buttons,
                        'contents' => $this->contents,
                        'small_icon' => $this->smallIcon,
                        'large_icon' => $this->largeIcon,
                        'url' => $this->url,
                        'big_picture' => $this->bigPicture,
                        'filters' => $this->tag,
                        'included_segments' => $this->includedSegments,
                        $this->includePlayerIds ? 'include_player_ids' : '' => $this->includePlayerIds,
                        'android_background_layout' => $this->background,
                        'headings' => $this->headings,
                        $this->data ? 'data' : '' => $this->data,
                        'send_after' => $this->sendAfter
                    ],
                ]);

            return json_decode($response->getBody());
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function cancel(string $idNotification) {

        $client = new Client();

        try {
            $response = $client->delete($this->apiUrl . '/' . $idNotification . '?app_id=' . $this->appId,
                [
                    'headers' => [
                        'Authorization' => 'Basic ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'http_errors' => false
                ]);

                return json_decode($response->getBody());
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function viewNotification(string $idNotification) {

        $client = new Client();

        try {
            $response = $client->get($this->apiUrl . '/' . $idNotification . '?app_id=' . $this->appId,
                [
                    'headers' => [
                        'Authorization' => 'Basic ' . $this->apiKey,
                        'Content-Type' => 'application/json',
                    ]
                ]);

                return json_decode($response->getBody());
        } catch(Exception $e) {
            return $e->getMessage();
        }
    }
}
