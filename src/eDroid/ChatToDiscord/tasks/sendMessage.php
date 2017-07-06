<?php
namespace eDroid\ChatToDiscord\tasks;

use pocketmine\scheduler\AsyncTask;

class sendMessage extends AsyncTask {
    public $data;
    public function __construct($data){
        $this->data = $data;
    }
    public function onRun(){
        foreach($this->data["webhooks"] as $hook){
            $this->send([
                "webhook" => $hook,
                "username" => $this->data["user"]["name"],
                "avatar" => $this->data["user"]["avatar"]
            ], $this->data["message"]);
        }
    }
    public function send($data, $message){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $data["webhook"],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode([
                "content" => $message,
                "username" => $data["username"],
                "avatar_url" => $data["avatar"]
            ])
        ));
        curl_exec($curl);
    }
}
