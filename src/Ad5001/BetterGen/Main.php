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

namespace Ad5001\BetterGen;

use pocketmine\plugin\PluginBase;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\Generator;
use pocketmine\level\generator\normal\Normal;
use pocketmine\event\level\ChunkPopulateEvent;
use Ad5001\BetterGen\generator\BetterNormal;
use Ad5001\BetterGen\biome\BetterForest;
use Ad5001\BetterGen\loot\LootTable;
use pocketmine\utils\Config;
use pocketmine\block\Block;
use pocketmine\tile\Chest;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\tile\Tile;
use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockBreakEvent;

class Main extends PluginBase implements \pocketmine\event\Listener {
	const PREFIX = "§l§o§b[§r§l§2Better§aGen§o§b]§r§f ";
	const SAKURA_FOREST = 100; // Letting some place for future biomes.
	
	/*
	 * Called when the plugin enables
	 */
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		Generator::addGenerator(BetterNormal::class, "betternormal");
		if($this->isOtherNS()) $this->getLogger()->warning("Tesseract detected. Note that some parts of the generator could not work properly");
		@mkdir($this->getDataFolder());
		if(! file_exists(LootTable::getPluginFolder(). "processingLoots.json"))
			file_put_contents(LootTable::getPluginFolder(). "processingLoots.json", "{}");
	}
	
	/*
	 * Called when the plugin disables
	 */
	public function onDisable() {
	}
	
	/*
	 * Called when one of the defined commands of the plugin has been called
	 * @param $sender \pocketmine\command\CommandSender
	 * @param $cmd \pocketmine\command\Command
	 * @param $label mixed
	 * @param $args array
	 * return bool
	 */
	public function onCommand(\pocketmine\command\CommandSender $sender, \pocketmine\command\Command $cmd, $label, array $args): bool {
		switch($cmd->getName()) {
			case "createworld" : // /createworld <name> [generator = betternormal] [seed = rand()] [options(json)]
				switch(count($args)) {
					case 0 :
						return false;
						break;
					case 1 : // /createworld <name>
						$name = $args [0];
						$generator = Generator::getGenerator("betternormal");
						$generatorName = "betternormal";
						$seed = $this->generateRandomSeed();
						$options = [ ];
						break;
					case 2 : // /createworld <name> [generator = betternormal]
						$name = $args [0];
						$generator = Generator::getGenerator($args [1]);
						if(Generator::getGeneratorName($generator) !== strtolower($args [1])) {
							$sender->sendMessage(self::PREFIX . "§4Could not find generator {$args[1]}. Are you sure it is registered?");
							return true;
						}
						$generatorName = strtolower($args [1]);
						$seed = $this->generateRandomSeed();
						$options = [ ];
						break;
					case 3 : // /createworld <name> [generator = betternormal] [seed = rand()]
						$name = $args [0];
						$generator = Generator::getGenerator($args [1]);
						if(Generator::getGeneratorName($generator) !== strtolower($args [1])) {
							$sender->sendMessage(self::PREFIX . "§4Could not find generator {$args[1]}. Are you sure it is registered?");
							return true;
						}
						$generatorName = strtolower($args [1]);
						if(preg_match("[^\d]", $args [2]) !== false) {
							$parts = str_split($args [2]);
							foreach($parts as $key => $str) {
								$parts [$key] = ord($str);
							}
							$seed = implode("", $parts);
						} else {
							$seed = $args [2];
						}
						$options = [ ];
						break;
					default : // /createworld <name> [generator = betternormal] [seed = rand()] [options(json)]
						$name = $args [0];
						$generator = Generator::getGenerator($args [1]);
						if(Generator::getGeneratorName($generator) !== strtolower($args [1])) {
							$sender->sendMessage(self::PREFIX . "§4Could not find generator {$args[1]}. Are you sure it is registered?");
							return true;
						}
						$generatorName = strtolower($args [1]);
						if($args[2] == "rand") $args[2] = $this->generateRandomSeed();
						if(preg_match("[^\d]", $args [2]) !== false) {
							$parts = str_split($args [2]);
							foreach($parts as $key => $str) {
								$parts [$key] = ord($str);
							}
							$seed = implode("", $parts);
						} else {
							$seed = $args [2];
						}
						unset($args [0], $args [1], $args [2]);
						$options = json_decode($args [3], true);
						if(! is_array($options)) {
							$sender->sendMessage(Main::PREFIX . "§4Invalid JSON for options.");
							return true;
						}
						break;
				}
				$options["preset"] = json_encode($options);
				if((int) $seed == 0/*String*/){
					$seed = $this->generateRandomSeed();
					echo "Rechoosen seed";
				}
				$this->getServer()->broadcastMessage(Main::PREFIX . "§aGenerating level $name with generator $generatorName and seed $seed..");
				$this->getServer()->generateLevel($name, $seed, $generator, $options);
				$this->getServer()->loadLevel($name);
				return true;
				break;
		}
	}
	
	/*
	 * Registers a forest type.
	 * @param $name string
	 * @param $treeClass string
	 * @params $infos Array(temperature, rainfall)
	 * @return bool
	 */
	public function registerForest(string $name, string $treeClass, array $infos): bool {
		if(! @class_exists($treeClass))
			return false;
		if(! @is_subclass_of($treeClass, "pocketmine\\level\\generator\\normal\\object\\Tree"))
			return false;
		if(count($infos) < 2 or ! is_float($infos [0]) or ! is_float($infos [1]))
			return false;
		return BetterForest::registerForest($name, $treeClass, $infos);
	}
	
	/*
	 * Registers a biome for the normal generator. Normal means(Biome::register) doesn't allow biome to be generated
	 * @param $id int
	 * @param $biome Biome
	 * @return void
	 */
	public static function registerBiome(int $id, Biome $biome) {
		BetterNormal::registerBiome($biome);
	}
	
	/*
	 * Generates a(semi) random seed.
	 * @return int
	 */
	public function generateRandomSeed(): int {
		return (int) round(rand(0, round(time()) / memory_get_usage(true)) * (int) str_shuffle("127469453645108") / (int) str_shuffle("12746945364"));
	}
	
	// Listener
	
	/*
	 * Checks after a chunk populates so we an add tiles and loot tables
	 * @param $event pocketmine\event\level\ChunkPopulateEvent
	 * @return int
	 */
	public function onChunkPopulate(ChunkPopulateEvent $event) {
		$cfg = new Config(LootTable::getPluginFolder() . "processingLoots.json", Config::JSON);
		foreach($cfg->getAll() as $key => $value) {
			list($x, $y, $z) = explode(";", $key);
			if($value["saveAs"] == "chest" && $event->getLevel()->getBlockIdAt($x, $y, $z) == Block::AIR ){
				$event->getLevel()->setBlockIdAt($x, $y, $z, Block::CHEST);
			} else {
				$cfg->remove($key);
				$cfg->save();
			}
		}
	}
	
	
	/*
	 * Checks when a player interacts with a loot chest to create it.
	 */
	public function onInteract(PlayerInteractEvent $event) {
		$cfg = new Config(LootTable::getPluginFolder() . "processingLoots.json", Config::JSON);
		if($event->getBlock()->getId() !== Block::CHEST) return;
		if(!$cfg->exists($event->getBlock()->getX() . ";" . $event->getBlock()->getY() . ";" . $event->getBlock()->getZ())) return;
		echo "Doing {$event->getBlock()}";
		$chest = new \pocketmine\block\Chest(0);
		$nbt = new CompoundTag("", [
				new ListTag("Items", []),
				new StringTag("id", Tile::CHEST),
				new IntTag("x", $event->getBlock()->x),
				new IntTag("y", $event->getBlock()->y),
				new IntTag("z", $event->getBlock()->z)
		]);
		$chest->setLevel($event->getBlock()->getLevel());
		$cItem = Item::get(Item::CHEST, 0);
		$cItem->setCustomName("§k(Fake)§r Minecart chest");
		$chest->place($cItem, $event->getBlock()->getLevel()->getBlock($event->getBlock()), $chest, 0, 0, 0, 0);
		$inv = $event->getBlock()->getLevel()->getTile($event->getBlock());
		LootTable::fillChest($inv->getInventory(), $event->getBlock());
	}
	
	
	/*
	 * Checks when a player breaks a loot chest which is not created to create it
	 */
	public function onBlockBreak(BlockBreakEvent $event) {
		$cfg = new Config(LootTable::getPluginFolder() . "processingLoots.json", Config::JSON);
		if($event->getBlock()->getId() !== Block::CHEST) return;
		if(!$cfg->exists($event->getBlock()->getX() . ";" . $event->getBlock()->getY() . ";" . $event->getBlock()->getZ())) return;
		echo "Doing {$event->getBlock()}";
		$chest = new \pocketmine\block\Chest(0);
		$nbt = new CompoundTag("", [
				new ListTag("Items", []),
				new StringTag("id", Tile::CHEST),
				new IntTag("x", $event->getBlock()->x),
				new IntTag("y", $event->getBlock()->y),
				new IntTag("z", $event->getBlock()->z)
		]);
		$chest->setLevel($event->getBlock()->getLevel());
		$cItem = Item::get(Item::CHEST, 0);
		$cItem->setCustomName("§k(Fake)§r Minecart chest");
		$chest->place($cItem, $event->getBlock()->getLevel()->getBlock($event->getBlock()), $chest, 0, 0, 0, 0);
		$inv = $event->getBlock()->getLevel()->getTile($event->getBlock());
		LootTable::fillChest($inv->getInventory(), $event->getBlock());
		$event->setCancelled();
	}

	/*
	* Check if it's a Tesseract like namespace
	* @return 	bool
	*/
	public static function isOtherNS() {
		try {
			return @class_exists("pocketmine\\level\\generator\\normal\\object\\OakTree");
		} catch(\Exception $e) {
			return false;
		}
	}
}
