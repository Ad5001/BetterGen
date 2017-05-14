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

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\Server;
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
	 * Public function to generate loot. A {@link: \pocketmine\utils\Random} can be passed.
	 * @param Random|null $random
	 * @return Item[]
	 */
	public function createLoot(Random $random = null) {
		return self::getRandomLoot($random);
	}

	/**
	 * Internal function. Serves as actual file reader + sub-table loader
	 * Do _NOT_ use this in the source, use LootTable::createLoot instead
	 * @param Random|null $random
	 * @return Item[]
	 */
	private function getRandomLoot(Random $random = null) {
		if (is_null($random)) $random = new Random(microtime());
		$array = [];
		$items = [];
		foreach ($this->lootFile->get("pools") as $rolls) {
			$maxrolls = $rolls["rolls"];//TODO: $rolls["conditions"]
			while ($maxrolls > 0) {
				$maxrolls--;
				foreach ($rolls["entries"] as $index => $entries) {
					$array[] = $entries["weight"]??1;
				}
			}
			$val = $rolls["entries"][$this->getRandomWeightedElement($array)];
			//typecheck
			if ($val["type"] == "loot_table") {
				$loottable = new self(new Config(Server::getInstance()->getFilePath() . "src/pocketmine/resources/" . $val["name"] . ".json", Config::JSON, []));
				$items = array_merge($items, $loottable->getRandomLoot());
				unset($loottable);
			} elseif ($val["type"] == "item") {
				print $val["name"] . PHP_EOL;
				//name fix
				$val["name"] = self::fixItemName($val["name"]);
				$item = Item::fromString($val["name"]);
				if (isset($val["functions"])) {
					foreach ($val["functions"] as $function) {
						switch ($functionname = $function["function"]) {
							case "set_damage": {
								if ($item instanceof Tool) $item->setDamage(mt_rand($function["damage"]["min"] * $item->getMaxDurability(), $function["damage"]["max"] * $item->getMaxDurability()));
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
		return $items;
	}

	/**
	 * TODO: Make random actually useful here.
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
		//TODO add a switch-case here
		return $name;
	}
}