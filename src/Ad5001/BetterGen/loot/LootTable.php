<?php
/**
 *  ____             __     __                    ____
 * /\  _`\          /\ \__ /\ \__                /\  _`\
 * \ \ \L\ \     __ \ \ ,_\\ \ ,_\     __   _ __ \ \ \L\_\     __     ___
 *  \ \  _ <'  /'__`\\ \ \/ \ \ \/   /'__`\/\`'__\\ \ \L_L   /'__`\ /' _ `\
 *   \ \ \L\ \/\  __/ \ \ \_ \ \ \_ /\  __/\ \ \/  \ \ \/, \/\  __/ /\ \/\ \
 *    \ \____/\ \____\ \ \__\ \ \__\\ \____\\ \_\   \ \____/\ \____\\ \_\ \_\
 *     \/___/  \/____/  \/__/  \/__/ \/____/ \/_/    \/___/  \/____/ \/_/\/_/
 * Tomorrow's pocketmine generator.
 * @author Ad5001
 * @link https://github.com/Ad5001/BetterGen
 */

namespace Ad5001\BetterGen\loot;

use Ad5001\BetterGen\Main;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\utils\Config;
use pocketmine\utils\Random;

class LootTable {

	/**
	 * @var null|Config The lootfile (.json) thats used
	 */
	private $lootFile = null;

	/**
	 * LootTable constructor.
	 * @param Config $lootFile
	 */
	public function __construct(Config $lootFile) {
		$this->lootFile = $lootFile;
	}

	/**
	 * Public function to generate loot. A {@link: \pocketmine\utils\Random} can be passed. Serves as file reader + sub-table loader
	 * Do _NOT_ use this in the source, use LootTable::createLoot instead
	 * @param Random|null $random
	 * @return Item[]
	 */
	public function getRandomLoot(Random $random = null) {
		if (is_null($random)) $random = new Random();
		$items = [];
		foreach ($this->lootFile->get("pools") as $rolls) {
			if (isset($rolls["rolls"]["min"]) && isset($rolls["rolls"]["max"])) $maxrolls = $random->nextRange($rolls["rolls"]["min"], $rolls["rolls"]["max"]);
			else $maxrolls = $rolls["rolls"];//TODO: $rolls["conditions"] //Example: looting swords
			while ($maxrolls > 0) {
				$array = [];
				$maxrolls--;
				foreach ($rolls["entries"] as $index => $entries) {
					$array[] = $entries["weight"]??1;
				}
				$val = $rolls["entries"][$this->getRandomWeightedElement($array)];
				//typecheck
				if ($val["type"] == "loot_table") {
					$loottable = new LootTable(new Config(Main::getInstance()->getDataFolder() . 'addon\\' . $val["name"] . ".json", Config::DETECT, []));
					$items = array_merge($items, $loottable->getRandomLoot($random));
					unset($loottable);
				} elseif ($val["type"] == "item") {
					//name fix
					$val["name"] = self::fixItemName($val["name"]);
					$item = Item::fromString($val["name"]);
					if (isset($val["functions"])) {
						foreach ($val["functions"] as $function) {
							switch ($functionname = $function["function"]) {
								case "set_damage": {
									if ($item instanceof Tool) $item->setDamage($random->nextRange($function["damage"]["min"] * $item->getMaxDurability(), $function["damage"]["max"] * $item->getMaxDurability()));
									else $item->setDamage($random->nextRange($function["damage"]["min"], $function["damage"]["max"]));
								}
									break;
								case "set_data": {
									//fish fix, blame mojang
									if ($item->getId() == Item::RAW_FISH) {
										switch ($function["data"]) {
											case 1:
												$item = Item::get(Item::RAW_SALMON, $item->getDamage(), $item->getCount(), $item->getCompoundTag());
												break;
											case 2:
												$item = Item::get(Item::CLOWN_FISH, $item->getDamage(), $item->getCount(), $item->getCompoundTag());
												break;
											case 3:
												$item = Item::get(Item::PUFFER_FISH, $item->getDamage(), $item->getCount(), $item->getCompoundTag());
												break;
											default:
												break;
										}
									} else $item->setDamage($function["data"]);
								}
									break;
								case "set_count": {
									$item->setCount($random->nextRange($function["count"]["min"], $function["count"]["max"]));
								}
									break;
								case "furnace_smelt": {
									/* TODO
									Mostly bound to conditions (burning)
								"conditions": [
									{
										"condition": "entity_properties",
										"entity": "this",
										"properties": {
											"on_fire": true
										}
									}
								]
									*/
								}
									break;
								case "enchant_randomly": {
									//TODO
								}
									break;
								case "enchant_with_levels": {
									//TODO
								}
									break;
								case "looting_enchant": {
									//TODO
								}
									break;
								default:
									assert("Unknown looting table function $functionname, skipping");
							}
						}
					}
					$items[] = $item;
				}
			}
		}
		return $items;
	}

	/**
	 * TODO: Make Random::class actually useful here.
	 * @param array $weightedValues
	 * @return mixed
	 */
	private function getRandomWeightedElement(array $weightedValues) {
		$array = array();
		foreach ($weightedValues as $key => $weight) {
			$array = array_merge(array_fill(0, $weight, $key), $array);
		}
		return $array[array_rand($array)];
	}

	/**
	 * Fixes the item names because #BlameMojang for not changing the id's from PC -> PE
	 * @param $name
	 * @return mixed
	 */
	private static function fixItemName($name) {
		switch ($name) {
			case 'minecraft:horsearmoriron':
				$name = 'minecraft:iron_horse_armor';
				break;
			case 'minecraft:horsearmorgold':
				$name = 'minecraft:gold_horse_armor';
				break;
			case 'minecraft:horsearmordiamond':
				$name = 'minecraft:diamond_horse_armor';
				break;
			default: {
			}
		}
		return $name;
	}
}