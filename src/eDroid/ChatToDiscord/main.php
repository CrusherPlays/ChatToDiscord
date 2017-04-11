<?php
namespace eDroid\ChatToDiscord;

use eDroid\ChatToDiscord\action\sendMessage;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

class main extends PluginBase implements Listener {
    public function onLoad(){
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
    }
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Enabled");
        if(is_array($this->getConfig()->get('webhooks'))) {
            if(count($this->getConfig()->get('webhooks')) <= 0) {
                $this->getLogger()->warning('You need to add a discord webhook in order for ChatToDiscord to work.');
                $this->getServer()->getPluginManager()->disablePlugin($this);
            }else{
                $this->webhooks = $this->getConfig()->get('webhooks');
            }
        }else{
            $this->webhooks = [$this->getConfig()->get('webhooks')];
        }
        $this->username = ($this->getConfig()->get('username') === "" ? "ChatToDiscord by eDroid" : $this->getConfig()->get('username'));
        $this->avatar = ($this->getConfig()->get('avatar') === "" ? "https://lh3.googleusercontent.com/_4zBNFjA8S9yjNB_ONwqBvxTvyXYdC7Nh1jYZ2x6YEcldBr2fyijdjM2J5EoVdTpnkA=w300" : $this->getConfig()->get('avatar'));
        $this->message_format = ($this->getConfig()->get('message_format') === "" ? "{player}: {message}" : $this->getConfig()->get('message_format'));
    }
    public function onDisable(){
        $this->getLogger()->info("Disabled");
    }

    public function onChat(PlayerCommandPreprocessEvent $event){
        $message = $event->getMessage();
        $player = $event->getPlayer()->getName();
        $message = str_replace(["{player}", "{message}"], [$player, $message], $this->message_format);
        foreach($this->webhooks as $hook){
            new sendMessage($this, $hook, $message);
        }
    }
}