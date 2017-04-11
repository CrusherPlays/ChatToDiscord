<?php
namespace eDroid\ChatToDiscord\action;

use eDroid\ChatToDiscord\main as ChatToDiscord;

class sendMessage {
    function __construct(ChatToDiscord $plugin, $webhook, $message){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $webhook,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode([
                "content" => $message,
                "username" => (string) $plugin->username,
                "avatar_url" => $plugin->avatar
            ])
        ));
        $response = curl_exec($curl);
        $error = curl_error($curl);
        if($response === false){
            $plugin->getLogger()->warning("Error sending message to Discord: {$error}");
        }
    }
}