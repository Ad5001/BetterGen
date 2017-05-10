<?php
/**
 *  ____             __     __                    ____                       
 * /\  _`\          /\ \__ /\ \__                /\  _`\                     
 * \ \ \L\ \     __ \ \ ,_\\ \ ,_\     __   _ __ \ \ \L\_\     __     ___    
 *  \ \  _ <'  /'__`\\ \ \/ \ \ \/   /'__`\/\`'__\\ \ \L_L   /'__`\ /' _ `\  
 *   \ \ \L\ \/\  __/ \ \ \_ \ \ \_ /\  __/\ \ \/  \ \ \/, \/\  __/ /\ \/\ \ 
 *    \ \____/\ \____\ \ \__\ \ \__\\ \____\\ \_\   \ \____/\ \____\\ \_\ \_\
 *     \/___/  \/____/  \/__/  \/__/ \/____/ \/_/    \/___/  \/____/ \/_/\/_/
 * Tommorow's pocketmine generator.
 * @author Ad5001
 * @link https://github.com/Ad5001/BetterGen
 */

namespace Ad5001\BetterGen\loot;

use pocketmine\utils\Config;
use pocketmine\utils\Random;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\inventory\BaseInventory;
use pocketmine\nbt\NBT;

/*
 * This class is used for loot setting.
 * Please note that they AREN'T as powerfull as PC ones due to some implementations limitations.
 * Loot table format:
 * {
 * "max": Max number of loots (storable amount)
 * "example": {
 * "percentage": Chance of appearing(in percent)
 * "minCount": Minimal count
 * "maxCount": Maximal count
 * "id": Id of the item
 * "data": Item damage
 * "tags": {"display": {"Name": "Example NBT data"}}. This parameter is optionnal
 * "minStacks": If choosen, the minimum amount of stacks that can be found
 * "maxStacks": If choosen the maximum number of stacks that can be choosen
 * }
 * }
 */
class LootTable {
	const LOOT_NAMES = [
			"temple",
			"igloo",
			"mineshaft" 
	];
	const LOOT_SAVE = [
			"chest",
			"chest",
			"chest"
	];
	const LOOT_DESERT_TEMPLE = 0;
	const LOOT_IGLOO = 1;
	const LOOT_MINESHAFT = 2;
	
	/*
	 * Asyncronous loot table choosing
	 * @param $place pocketmine\math\Vector3
	 * @param $type int
	 * @param $random pocketmine\utils\Random
	 * @return void
	 */
	public static function buildLootTable(Vector3 $place, int $type, Random $random) {
		if($place->y < 1) return; // Making sure sometimes that it doesn't write for nothing
		$cfg = new Config(self::getPluginFolder() . "processingLoots.json", Config::JSON);
		$lootsFromJson = json_decode(file_get_contents(self::getResourcesFolder() . "loots/" . self::LOOT_NAMES[$type] . ".json"), true);
		$loots =[];
		foreach($lootsFromJson as $loot) {
			if(is_array($loot) && $random->nextBoundedInt(101) < $loot["percentage"])
				$loots[] = $loot;
		}
		if($lootsFromJson["max"] < count($loots)) {
			while($lootsFromJson["max"] < count($loots))
				unset($loots[array_rand($loots)]);
		}
		$loots["saveAs"] = self::LOOT_SAVE[$type];
		$cfg->set($place->x . ";" . $place->y . ";" . $place->z, $loots);
		$cfg->save();
	}
	
	/*
	 * Syncronous inventory filling with loot table.
	 * @param $inv pocketmine\inventory\BaseInventory
	 * @param $pos pocketmine\math\Vector3
	 * @return void
	 */
	public static function fillChest(BaseInventory $inv, Vector3 $pos) {
		$cfg = new Config(self::getPluginFolder() . "processingLoots.json", Config::JSON);	
		if($cfg->exists($pos->x . ";" . $pos->y . ";" . $pos->z)) {
			$loots = $cfg->get($pos->x . ";" . $pos->y . ";" . $pos->z);
			$items = [];
			foreach($loots as $loot) {
				if(!is_array($loot)) continue;
				$randCount = rand($loot["minStacks"], $loot["maxStacks"]);
				for($i = 0; $i <= $randCount; $i++) {
					$rand = rand(0, count($loots));
					$items[$rand] = Item::get($loot["id"], $loot["data"], rand($loot["minCount"], $loot["maxCount"]));
					if(isset($loot["tags"])) $items[$rand]->setCompoundTag(NBT::fromJSON($loot["tags"]));
				}
			}
			$inv->setContents($items);
			$cfg->remove($pos->x . ";" . $pos->y . ";" . $pos->z);
			$cfg->save();
		}
	}
	
	/*
	 * Returns the plugins folder.
	 * @return string
	 */
	public static function getPluginFolder(): string {
		$dir = explode(DIRECTORY_SEPARATOR, __DIR__);
		$c = count($dir);
		unset($dir[$c - 1], $dir[$c - 2], $dir[$c - 3], $dir[$c - 4], $dir[$c - 5]);
		return str_ireplace("phar://", "", implode(DIRECTORY_SEPARATOR, $dir)) . DIRECTORY_SEPARATOR . "BetterGen" . DIRECTORY_SEPARATOR;
	}
	
	/*
	 * Returns the resources folder.
	 * @return string
	 */
	public static function getResourcesFolder(): string {
		$dir = explode(DIRECTORY_SEPARATOR, __DIR__);
		$c = count($dir);
		unset($dir[$c - 1], $dir[$c - 2], $dir[$c - 3], $dir[$c - 4]);
		return str_ireplace("phar://", "", implode(DIRECTORY_SEPARATOR, $dir)) . DIRECTORY_SEPARATOR . "resources" . DIRECTORY_SEPARATOR;
	}
}