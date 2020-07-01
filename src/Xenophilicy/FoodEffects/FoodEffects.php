<?php
# MADE BY:
#  __    __                                          __        __  __  __                     
# /  |  /  |                                        /  |      /  |/  |/  |                    
# $$ |  $$ |  ______   _______    ______    ______  $$ |____  $$/ $$ |$$/   _______  __    __ 
# $$  \/$$/  /      \ /       \  /      \  /      \ $$      \ /  |$$ |/  | /       |/  |  /  |
#  $$  $$<  /$$$$$$  |$$$$$$$  |/$$$$$$  |/$$$$$$  |$$$$$$$  |$$ |$$ |$$ |/$$$$$$$/ $$ |  $$ |
#   $$$$  \ $$    $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$ |  $$ |$$ |$$ |$$ |$$ |      $$ |  $$ |
#  $$ /$$  |$$$$$$$$/ $$ |  $$ |$$ \__$$ |$$ |__$$ |$$ |  $$ |$$ |$$ |$$ |$$ \_____ $$ \__$$ |
# $$ |  $$ |$$       |$$ |  $$ |$$    $$/ $$    $$/ $$ |  $$ |$$ |$$ |$$ |$$       |$$    $$ |
# $$/   $$/  $$$$$$$/ $$/   $$/  $$$$$$/  $$$$$$$/  $$/   $$/ $$/ $$/ $$/  $$$$$$$/  $$$$$$$ |
#                                         $$ |                                      /  \__$$ |
#                                         $$ |                                      $$    $$/ 
#                                         $$/                                        $$$$$$/

namespace Xenophilicy\FoodEffects;

use pocketmine\entity\{Effect, EffectInstance};
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerInteractEvent, PlayerItemConsumeEvent};
use pocketmine\plugin\PluginBase;

class FoodEffects extends PluginBase implements Listener{

    private const CONFIG_VERSION = "1.2.0";

    private static $settings;
    private static $consumables;
    private static $nonConsumables;

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        self::$settings = $this->getConfig()->getAll();
        $configVersion = self::$settings["VERSION"];
        if(version_compare(self::CONFIG_VERSION, $configVersion, "gt")){
            $this->getLogger()->critical("You have updated FoodEffects but have an outdated config! Please delete your old config to continue using FoodEffects!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $oldFile = $this->getDataFolder() . "items.json";
        if(file_exists($oldFile)){
            $this->getLogger()->critical("You have updated FoodEffects but are still using an outdated file. Please transfer your entries from 'items.json' to the new config file using the provided format to continue using FoodEffects!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        self::$consumables = $this->getConfig()->get("Consumables");
        self::$nonConsumables = $this->getConfig()->get("Non-Consumables");
        $itemGroups = [self::$consumables, self::$nonConsumables];
        foreach($itemGroups as $itemGroup){
            foreach($itemGroup as $item => $values){
                $effects = $values["Effects"];
                $name = isset($values["Name"]) ? $values["Name"] : false;
                if($name && !is_string($name)){
                    $this->getLogger()->warning("Invalid item name found! Item names must be strings, disabling plugin!");
                    $this->getServer()->getPluginManager()->disablePlugin($this);
                    return;
                }
                if(strpos($item, ":") === false || count($itemVal = explode(":", $item)) !== 2){
                    $this->getLogger()->warning("Invalid item ID found! Items must have an ID and a damage value, disabling plugin!");
                    $this->getServer()->getPluginManager()->disablePlugin($this);
                    return;
                }
                if(!is_numeric($itemVal[0]) || !is_numeric($itemVal[1])){
                    $this->getLogger()->warning("Invalid item ID found! Invalid item ID: " . $item . " not supported, disabling plugin!");
                    $this->getServer()->getPluginManager()->disablePlugin($this);
                    return;
                }
                foreach($effects as $effValues){
                    $subscripts = [0, 1, 2];
                    foreach($subscripts as $subscript){
                        if(!isset($effValues[$subscript])){
                            $this->getLogger()->warning("Invalid effect string found, you must have 3 values for each effect. Effect for item ID: " . $item . " is invalid, disabling plugin!");
                            $this->getServer()->getPluginManager()->disablePlugin($this);
                            return;
                        }
                    }
                    if(!is_numeric($effValues[0])){
                        $this->getLogger()->warning("Invalid effect string found, all effect related arguments must be numerical! Invalid argument: " . $effValues[0] . " not supported, disabling plugin!");
                        $this->getServer()->getPluginManager()->disablePlugin($this);
                        return;
                    }
                    if(!is_numeric($effValues[0]) || !is_numeric($effValues[1]) || !is_numeric($effValues[2])){
                        if($effValues[0] <= 26 && $effValues[0] >= 1){
                            if(!$effValues[1] <= 255){
                                $this->getLogger()->warning("Invalid effect amplifier found, must be no greater than 255! Effect amplifier: " . $effValues[1] . " not supported, disabling plugin!");
                                $this->getServer()->getPluginManager()->disablePlugin($this);
                                return;
                            }
                        }else{
                            $this->getLogger()->warning("Invalid effect ID found, please see the ID list in the config.yml! Effect: " . $effValues[0] . " not found, disabling plugin!");
                            $this->getServer()->getPluginManager()->disablePlugin($this);
                            return;
                        }
                    }
                }
            }
        }
    }

    public function onItemConsume(PlayerItemConsumeEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        $idFull = $item->getId() . ":" . $item->getDamage();
        if(!(round($player->getFood()) < 20) && self::$settings["Require-Hunger"]) return;
        foreach(self::$consumables as $key => $values){
            $effects = $values["Effects"];
            $name = isset($values["Name"]) ? $values["Name"] : false;
            if($name && $item->getCustomName() !== $name) continue;
            if($idFull !== (string)$key) continue;
            if(!self::$settings["Affects-Hunger"]) $event->setCancelled();
            foreach($effects as $effValues){
                $effectInstance = new EffectInstance(Effect::getEffect($effValues[0]));
                $duration = $effValues[2] > 0 ? $effValues[2] * 20 : 2147483647;
                $player->addEffect($effectInstance->setAmplifier($effValues[1])->setDuration($duration));
            }
        }
    }

    public function onItemInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        $idFull = $item->getId() . ":" . $item->getDamage();
        foreach(self::$nonConsumables as $key => $values){
            $effects = $values["Effects"];
            $name = isset($values["Name"]) ? $values["Name"] : false;
            if($name && $item->getCustomName() !== $name) continue;
            if($idFull !== (string)$key) continue;
            $player->getInventory()->setItemInHand($item->pop());
            foreach($effects as $effValues){
                $effectInstance = new EffectInstance(Effect::getEffect($effValues[0]));
                $duration = $effValues[2] > 0 ? $effValues[2] * 20 : 2147483647;
                $player->addEffect($effectInstance->setAmplifier($effValues[1])->setDuration($duration));
            }
        }
    }
}
