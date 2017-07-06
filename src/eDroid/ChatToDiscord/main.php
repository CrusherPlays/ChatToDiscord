<?php
namespace eDroid\ChatToDiscord;

use eDroid\ChatToDiscord\tasks\sendMessage;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class main extends PluginBase implements Listener {
    private $webhooks, $username, $avatar, $message_format;
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
                $this->webhooks = (array)$this->getConfig()->get('webhooks');
            }
        }else{
            $this->webhooks = [$this->getConfig()->get('webhooks')];
        }
        $this->username = ($this->getConfig()->get('username') == "" ? "ChatToDiscord by eDroid" : (string)$this->getConfig()->get('username'));
        $this->avatar = ($this->getConfig()->get('avatar') == "" ? "https://github.com/eDroiid/ChatToDiscord/raw/master/discord.png" : (string)$this->getConfig()->get('avatar'));
        $this->message_format = ($this->getConfig()->get('message_format') == "" ? "``{player}:` ```{message}```" : (string)$this->getConfig()->get('message_format'));
    }
    public function onDisable(){
        $this->getLogger()->info("Disabled");
    }

    public function onPlayerChat(PlayerChatEvent $event){
        if($event->isCancelled()) return;
        $message = str_replace(["§0", "§1", "§2", "§3", "§4", "§5", "§6", "§7", "§8", "§9", "§a", "§b", "§c", "§d", "§e", "§f", "§k", "§l", "§n", "§o", "§r"], "", $event->getMessage());
        $player = $event->getPlayer()->getName();
        $message = str_replace(["{player}", "{message}"], [$player, $message], $this->message_format);
        $this->getServer()->getScheduler()->scheduleAsyncTask(new sendMessage(
            [
                "webhooks" => $this->webhooks,
                "message" => $message,
                "user" => [
                    "name" => $this->username,
                    "avatar" => $this->avatar
                ]
            ]
        ));
    }
}
