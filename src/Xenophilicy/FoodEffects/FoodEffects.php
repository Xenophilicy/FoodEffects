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
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\entity\{Effect,EffectInstance};

class FoodEffects extends PluginBase implements Listener {

    private $config;

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("config.yml");
        $this->saveResource("items.json");
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        $this->json = new Config($this->getDataFolder()."items.json", Config::JSON);
        $this->getLogger()->info("FoodEffects has been enabled!");
        $version = $this->config->get("VERSION");
        if($version != "1.0.0"){
            $this->getLogger()->warning("You have updated FoodEffects but have an invalid config version!");
        }
        $this->list = $this->json->get("Items");
        foreach($this->list as $key => $values){
            if(!is_numeric($key)){
                $this->getLogger()->warning("Invalid item ID found, must be a numeric value! Invalid item ID: $key");
            }
            foreach($values as $value){
                foreach($value as $test){
                    if(!is_numeric($test)){
                        $this->getLogger()->warning("Invalid effect string found, all effect related arguments must be numerical! Invalid argument: ".$test);
                    }
                }
                if(!is_numeric($value[0]) || !is_numeric($value[1]) || !is_numeric($value[2])){
                    if($value[0] <= 26 && $value[0] >= 1){
                        if(!$value[1] <= 255){
                            $this->getLogger()->warning("Invalid effect amplifier found, must be no greater than 255! Invalid effet amplifier: ".$value[1]);
                        }
                    } else{
                        $this->getLogger()->warning("Invalid effect ID found, please see the ID list in the config.yml! Invalid effect: ".$value[0]);
                    }
                }
            }
        }
    }

    public function onItemConsume(PlayerItemConsumeEvent $event){
        $player = $event->getPlayer();
        $item = $event->getItem();
        foreach($this->list as $key => $values){
            if($item->getId() == (int)$key){
                foreach($values as $value){
                    $effect = new EffectInstance(Effect::getEffect($value[0]));
                    $player->addEffect($effect->setAmplifier($value[1])->setDuration($value[2]*20));
                }
            }
        }
    }
}
