<?php
namespace eDroid\ChatToDiscord\tasks;

use eDroid\ChatToDiscord\main as CTD;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class sendMessage extends AsyncTask {
    private $data;
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
    public function onCompletion(Server $server){
        $ctd = $server->getPluginManager()->getPlugin('ChatToDiscord');
        if(!$ctd instanceof CTD && !$ctd->isEnabled()) return;
        if($this->getResult()[0] === true) $ctd->getLogger()->warning("Error sending message to Discord with curl: " . $this->getResult()[1]);
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
        $response = curl_exec($curl);
        if($response === false){
            $this->setResult(array(true, curl_error($curl)));
        }else{
            $this->setResult(array(false, ""));
        }
    }
}
