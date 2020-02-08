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

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\player\{PlayerItemConsumeEvent,PlayerInteractEvent};
use pocketmine\entity\{Effect,EffectInstance};

class FoodEffects extends PluginBase implements Listener {

    private $config;
    private $consumables;
    private $nonConsumables;

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        $this->getLogger()->info("FoodEffects has been enabled!");
        $version = $this->config->get("VERSION");
        if($version != "1.1.0"){
            $this->getLogger()->critical("You have updated FoodEffects but have an outdated config! Please delete your old config to continue using FoodEffects!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $oldFile = $this->getDataFolder()."items.json";
        if(file_exists($oldFile)){
            $this->getLogger()->critical("You have updated FoodEffects but are still using an outdated file. Please tranfer your entries from 'items.json' to the new config file using the provided format to continue using FoodEffects!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $this->consumables = $this->config->get("Consumables");
        $this->nonConsumables = $this->config->get("Non-Consumables");
        $itemGroups = [$this->consumables,$this->nonConsumables];
        foreach($itemGroups as $itemGroup){
            foreach($itemGroup as $key => $effects){
                if(!is_numeric($key) && strpos($key, ":") > 1){
                    $this->getLogger()->warning("Invalid item ID found! Invalid item ID: ".$key." not supported, disabling plugin!");
                    $this->getServer()->getPluginManager()->disablePlugin($this);
                    return;
                }
                foreach($effects as $effValues){
                    $subscripts = [0,1,2];
                    foreach($subscripts as $subscript){
                        if(!isset($effValues[$subscript])){
                            $this->getLogger()->warning("Invalid effect string found, you must have 3 values for each effect. Effect for item ID: ".$key." is invalid, disabling plugin!");
                            $this->getServer()->getPluginManager()->disablePlugin($this);
                            return;
                        }
                    }
                    if(!is_numeric($effValues[0])){
                        $this->getLogger()->warning("Invalid effect string found, all effect related arguments must be numerical! Invalid argument: ".$value[0]." not supported, disabling plugin!");
                        $this->getServer()->getPluginManager()->disablePlugin($this);
                        return;
                    }
                    if(!is_numeric($effValues[0]) || !is_numeric($effValues[1]) || !is_numeric($effValues[2])){
                        if($effValues[0] <= 26 && $effValues[0] >= 1){
                            if(!$effValues[1] <= 255){
                                $this->getLogger()->warning("Invalid effect amplifier found, must be no greater than 255! Effect amplifier: ".$effValues[1]." not supported, disabling plugin!");
                                $this->getServer()->getPluginManager()->disablePlugin($this);
                                return;
                            }
                        } else{
                            $this->getLogger()->warning("Invalid effect ID found, please see the ID list in the config.yml! Effect: ".$effValues[0]." not found, disabling plugin!");
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
        $idFull = $item->getId().":".$item->getDamage();
        $id = $item->getId();
        if($player->getFood() < 20){
            foreach($this->consumables as $key => $effects){
                if($idFull == (string)$key || $id == (string)$key){
                    foreach($effects as $effValues){
                        $effectInstance = new EffectInstance(Effect::getEffect($effValues[0]));
                        $player->addEffect($effectInstance->setAmplifier($effValues[1])->setDuration($effValues[2]*20));
                    }
                }
            }
        }
    }

    public function onItemInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        $idFull = $item->getId().":".$item->getDamage();
        $id = $item->getId();
        foreach($this->nonConsumables as $key => $effects){
            if($idFull == (string)$key || $id == (string)$key){
                foreach($effects as $effValues){
                    $effectInstance = new EffectInstance(Effect::getEffect($effValues[0]));
                    $player->addEffect($effectInstance->setAmplifier($effValues[1])->setDuration($effValues[2]*20));
                }
            }
        }
    }
}
