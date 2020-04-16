<?php

namespace MinekCz\TotemEffect;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\plugin\PluginBase as Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
use pocketmine\entity\{Effect, EffectInstance};
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\tag\StringTag;



class Main extends PluginBase implements Listener {

    public function onEnable() : void{
        $this->getServer()->GetPluginManager()->registerEvents($this, $this);
        
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
    }
    
    public function onJoin(PlayerJoinEvent $event) {
        
        $player = $event->GetPlayer();

        $command = "rca " . $player->getName() . " jointotem";
        $this->getServer()->dispatchCommand(new ConsoleCommandSender, $command);

    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label,array $args) :bool {
        switch($cmd->getName()){
            case "jointotem":
                if($sender instanceof Player) {

                    $this->JoinTotem($sender);

                }
            break;
            case "titletotem":
                if($sender instanceof Player) {

                    $this->TitleTotem($sender);

                }
            break;
            case "totemeffect":
                if($sender instanceof Player) {

                    $this->TotemEffect($sender);

                }
            break;
            case "gettotem":
                if($sender instanceof Player) {

                   $item = Item::get(450,0,1);
                   $item->SetCustomName($this->getConfig()->get("totem_name"));
                   $item->setNamedTagEntry(new StringTag($this->getConfig()->get("totem_tag")));
                   $sender->getInventory()->addItem($item);

                }
            break;

        }
        return true;
    }
    
    public function JoinTotem(Player $player) {

        $player->getInventory()->setItemInHand(Item::get(450,0,1));

        $player->broadcastEntityEvent(ActorEventPacket::CONSUME_TOTEM);

        $te = new LevelEventPacket();
        $te->evid = LevelEventPacket::EVENT_SOUND_TOTEM;
        $te->position = $player->add(0, $player->eyeHeight, 0);
        $te->data = 0;
        $player->dataPacket($te);
        $player->addTitle($this->getConfig()->get("jointitle"), $this->getConfig()->get("joinsubtitle"), 5, 15, 5);

        $player->getInventory()->setItemInHand(Item::get(0,0,1));

    }

    public function TitleTotem(Player $player) {

        $player->getInventory()->setItemInHand(Item::get(450,0,1));

        $player->broadcastEntityEvent(ActorEventPacket::CONSUME_TOTEM);

        $te = new LevelEventPacket();
        $te->evid = LevelEventPacket::EVENT_SOUND_TOTEM;
        $te->position = $player->add(0, $player->eyeHeight, 0);
        $te->data = 0;
        $player->dataPacket($te);
        $player->addTitle($this->getConfig()->get("totemtitle"), $this->getConfig()->get("totemsubtitle"), 5, 15, 5);

        $player->getInventory()->setItemInHand(Item::get(0,0,1));

    }

    public function TotemEffect(Player $player) {

        $player->getInventory()->setItemInHand(Item::get(450,0,1));

        $player->broadcastEntityEvent(ActorEventPacket::CONSUME_TOTEM);

        $te = new LevelEventPacket();
        $te->evid = LevelEventPacket::EVENT_SOUND_TOTEM;
        $te->position = $player->add(0, $player->eyeHeight, 0);
        $te->data = 0;
        $player->dataPacket($te);

        $player->getInventory()->setItemInHand(Item::get(0,0,1));

    }

    public function onClick(PlayerInteractEvent $event) {

        $player = $event->getPlayer();
        $item = $event->getItem();

        if($item->GetNamedTag()->hasTag($this->getConfig()->get("totem_tag"))) {

            $player->getInventory()->setItemInHand(Item::get(450,0,1));

            $player->broadcastEntityEvent(ActorEventPacket::CONSUME_TOTEM);
    
            $te = new LevelEventPacket();
            $te->evid = LevelEventPacket::EVENT_SOUND_TOTEM;
            $te->position = $player->add(0, $player->eyeHeight, 0);
            $te->data = 0;
            $player->dataPacket($te);
            $player->getInventory()->setItemInHand(Item::get(0,0,1));
            $player->removeAllEffects();
            
            $player->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), (10*20), (2), (false)));
            $player->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE), (20*20), (2), (false)));
            
        }


    }
}