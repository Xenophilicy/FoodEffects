> # I have frozen my personal support for public plugins. The plugins will remain public on GitHub (though archived) for anyone that would like to keep them updated.  This my final decision on the matter. No support will be provided - you will be ignored - regardless of private or public inquiry.

# FoodEffects
[![](https://poggit.pmmp.io/shield.state/FoodEffects)](https://poggit.pmmp.io/p/FoodEffects)
[![HitCount](http://hits.dwyl.io/Xenophilicy/FoodEffects.svg)](http://hits.dwyl.io/Xenophilicy/FoodEffects)
[![Discord Chat](https://img.shields.io/discord/490677165289897995.svg)](https://discord.gg/hNVehXe)

# [![Xenophilicy](http://file.xenoservers.net/Resources/GitHub-Resources/foodeffects.png)]()

## Information
With this plugin, you are able to assign different effects to items so that when a player interacts with or consumes that item, they are given the effect for a set duration at a set effect level! The format along with some examples for the item listing can be found in both the *config.yml* file and down below if you ever get confused. If you would like a video example of the plugin in action, click [here](https://youtu.be/SbITnMk8jVE)

### [Click here to download FoodEffects from Poggit](https://poggit.pmmp.io/p/FoodEffects/)

## Item Formatting
```yaml
"Item-ID:Item-Damage:
    "Name": "Custom-Name"
    "Effects":
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
  "364:0":
    "Name": "§aAwesome §6Steak"
    "Effects":
      - [15,1,10]
      - [5,2,30]
  "260:0":
    "Effects":
      - [1,3,15]
      - [16,1,0]

Non-Consumables:
  "6:3":
    "Effects":
      - [2,1,60]
      - [12,1,30]
      - [1,3,5]
  "40:0":
    "Name": "§bLuck §cMushroom"
    "Effects":
      - [2,1,5]
```

***

## Dependencies
**DevTools → https://github.com/pmmp/PocketMine-DevTools** *(If you're running the plugin from source)*

## Credits
* [Xenophilicy](https://github.com/Xenophilicy/)
