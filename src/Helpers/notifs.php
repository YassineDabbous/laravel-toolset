<?php
    //use Berkayk\OneSignal\OneSignalFacade as OneSignal;
    function pushNotification($title, $msg, $ids = [], $data = [], $filters = [], $group = null)
    {
        $content = array(
            "en" => $title . ': ' . $msg,
        );
        $heading = array(
            "en" => env('APP_NAME'),
        );
        $fields = array(
            'url' => env('WEB_DOMAIN'),
            'contents' => $content,
            'headings' => $heading,
            //'collapse_id'=> '1',
            //'android_group'  => 'TESTGROUP',
            //'android_group_message' => array("en" => '2 message')
            //'largeIcon' => 'https://cdn4.iconfinder.com/data/icons/iconsimple-logotypes/512/github-512.png',
        );
        if ($group != null) {
            $fields['android_group'] = $group;
        }
        if (count($data)) {
            $fields['data'] = $data;
        }
        if (count($ids)) {
            $fields['include_player_ids'] = $ids;
        } elseif (count($filters)) {
            $fields['filters'] = $filters;
        } else {
            $fields['included_segments'] = array('All');
        }
        \Berkayk\OneSignal\OneSignalFacade::sendNotificationCustom($fields);
    }

