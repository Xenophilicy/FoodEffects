# FoodEffects
[![](https://poggit.pmmp.io/shield.state/FoodEffects)](https://poggit.pmmp.io/p/FoodEffects)
[![HitCount](http://hits.dwyl.io/Xenophilicy/FoodEffects.svg)](http://hits.dwyl.io/Xenophilicy/FoodEffects)
![](https://img.shields.io/discord/490677165289897995.svg?style=flat-square)

# [![Xenophilicy](https://file.xenoservers.net/Resources/GitHub-Resources/foodeffects.png)]()

## Information
With this plugin, you are able to assign different effects to items so that when a player interacts with or consumes that item, they are given the effect for a set duration at a set effect level! The format along with some examples for the item listing can be found in both the *config.yml* file and down below if you ever get confused. If you would like a video example of the plugin in action, click [here](https://youtu.be/SbITnMk8jVE)

## Item Formatting
```yaml
"Item-ID:Item-Damage":
    - [Effect-ID,Effect-Amplifier,Effect-Duration]
    - [Effect-ID,Effect-Amplifier,Effect-Duration]
    - [Effect-ID,Effect-Amplifier,Effect-Duration]
```

## Item Listing Example
Desired configuration:
* When steak(ID = 364) is consumed, give blindness(ID = 15) level 1 for 10 seconds and strength(ID = 5) level 2 for 30 seconds
* When an apple(ID = 260) is consumed, give speed(ID = 1) level 3 for 15 seconds and night vision(ID = 16) level 1 infinitely(0)
* When interacting with a jungle sapling(ID = 6:3), give slowness(ID = 2) level 1 for 60 seconds, fire resistance(ID = 12) level 1 for 30 seconds, and speed(ID = 1) level 3 for 5 seconds
* When interacting with a red mushroom(ID = 40), give slowness(ID = 2) level 1 for 5 seconds

Configuration in config.yml:
```yaml
Consumables:
  "364":
    - [15,1,10]
    - [5,2,30]
  "260":
    - [1,3,15]
    - [16,1,0]

Non-Consumables:
  "6:3": 
    - [2,1,60]
    - [12,1,30]
    - [1,3,5]
  "40":
    - [2,1,5]
```

***

## FoodEffects Details
* **API:** 3.0.0+
* **Version:** 1.1.0
* **Basic Description:** Enables customizable effects for interacting with and consuming items
* *Easy to edit config.yml file*
* *Simple code for editing and debugging*
***

## Dependencies
**DevTools → https://github.com/pmmp/PocketMine-DevTools** *(If you are using the plugin folder method)*

## Credits
* [Xenophilicy](https://github.com/Xenophilicy/)
