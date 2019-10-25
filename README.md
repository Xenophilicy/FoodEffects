# FoodEffects
[![](https://poggit.pmmp.io/shield.state/FoodEffects)](https://poggit.pmmp.io/p/FoodEffects)
[![HitCount](http://hits.dwyl.io/Xenophilicy/FoodEffects.svg)](http://hits.dwyl.io/Xenophilicy/FoodEffects)
![](https://img.shields.io/discord/490677165289897995.svg?style=flat-square)

# [![Xenophilicy](https://file.xenoservers.net/Resources/GitHub-Resources/foodeffects.png)]()

## Information
With this plugin, you are able to assign different effects to items so that when a player consumes that item, they are given the effect for a set duration at a set effect level! All of these options can be set in the *items.json* file. The format for the item listing can be found in both the *config.yml* file and down below if you ever get confused. You can also find examples inside the default *items.json* file. If you would like a video example of the plugin in action, click [here](https://youtu.be/SbITnMk8jVE)

## Item Formatting
```json
{
    "Items":{
        "Item-ID":[
            [Effect-ID,Effect-Amplifier,Effect-Duration],
        ]
    }
}
```

## Item Listing Example

Desired configuration:
* When steak(364) is consumed, give blindness(15) level 1 for 10 seconds and strength(5) level 2 for 30 seconds
* When an apple(260) is consumed, give speed(1) level 3 for 15 seconds and infinite(0) night vision(16) level 1

Configuration in JSON:
```json
{
    "Items":{
        "364":[
            [15,1,10],
            [5,2,30]
        ],
        "260":[
            [1,3,15],
            [16,1,0]
        ]
    }
}
```

***

## FoodEffects Details
* **API:** 3.0.0+
* **Version:** 1.0.0
* **Basic Description:** Enables customizable effects for consuming items
* *Easy to edit items.json file*
* *Simple code for editing and debugging*
***

## Dependencies
**DevTools → https://github.com/pmmp/PocketMine-DevTools** *(If you are using the plugin folder method)*

## Credits
* [Xenophilicy](https://github.com/Xenophilicy/)
