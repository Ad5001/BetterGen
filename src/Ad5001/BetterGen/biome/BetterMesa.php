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
 * @author Ad5001 <mail@ad5001.eu>, XenialDan <https://github.com/thebigsmileXD>
 * @link https://github.com/Ad5001/BetterGen
 * @category World Generator
 * @api 3.0.0
 * @version 1.1
 */

namespace Ad5001\BetterGen\biome;

use Ad5001\BetterGen\Main;
use Ad5001\BetterGen\populator\CactusPopulator;
use Ad5001\BetterGen\populator\DeadbushPopulator;
use Ad5001\BetterGen\populator\SugarCanePopulator;
use Ad5001\BetterGen\populator\TreePopulator;
use Ad5001\BetterGen\generator\BetterNormal;
use pocketmine\block\Block;
use pocketmine\block\GoldOre;
use pocketmine\level\generator\normal\biome\SandyBiome;

class BetterMesa extends SandyBiome {

	/**
	 * Constructs the class
	 */
	public function __construct() {
		parent::__construct();
		$deadBush = new DeadbushPopulator ();
		$deadBush->setBaseAmount(1);
		$deadBush->setRandomAmount(2);
		
		$cactus = new CactusPopulator ();
		$cactus->setBaseAmount(1);
		$cactus->setRandomAmount(2);
		
		$sugarCane = new SugarCanePopulator ();
		$sugarCane->setRandomAmount(20);
		$sugarCane->setBaseAmount(3);
		
		$sugarCane = new TreePopulator ();
		$sugarCane->setRandomAmount(2);
		$sugarCane->setBaseAmount(0);
				
		$ores = Main::isOtherNS() ? new \pocketmine\level\generator\normal\populator\Ore() : new \pocketmine\level\generator\populator\Ore();
		$ores->setOreTypes([ 
				Main::isOtherNS() ? new \pocketmine\level\generator\normal\object\OreType(new GoldOre (), 2, 8, 0, 32) : new \pocketmine\level\generator\object\OreType(new GoldOre (), 2, 8, 0, 32) 
		]);
		
		if(!\Ad5001\BetterGen\utils\CommonUtils::in_arrayi("Cactus", BetterNormal::$options["delStruct"])) $this->addPopulator($cactus);
		if(!\Ad5001\BetterGen\utils\CommonUtils::in_arrayi("DeadBush", BetterNormal::$options["delStruct"])) $this->addPopulator($deadBush);
		if(!\Ad5001\BetterGen\utils\CommonUtils::in_arrayi("SugarCane", BetterNormal::$options["delStruct"])) $this->addPopulator($sugarCane);
		if(!\Ad5001\BetterGen\utils\CommonUtils::in_arrayi("Ores", BetterNormal::$options["delStruct"])) $this->addPopulator($ores);
		
		$this->setElevation(80, 83);
		// $this->setElevation(66, 70);
		
		$this->temperature = 0.8;
		$this->rainfall = 0;
		$this->setGroundCover([ 
				Block::get(Block::DIRT, 0),
				Block::get(Block::HARDENED_CLAY, 0),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::HARDENED_CLAY, 0),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 7),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::HARDENED_CLAY, 0),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 12),
				Block::get(Block::STAINED_HARDENED_CLAY, 12),
				Block::get(Block::STAINED_HARDENED_CLAY, 12),
				Block::get(Block::STAINED_HARDENED_CLAY, 14),
				Block::get(Block::STAINED_HARDENED_CLAY, 14),
				Block::get(Block::STAINED_HARDENED_CLAY, 14),
				Block::get(Block::STAINED_HARDENED_CLAY, 4),
				Block::get(Block::STAINED_HARDENED_CLAY, 7),
				Block::get(Block::STAINED_HARDENED_CLAY, 0),
				Block::get(Block::STAINED_HARDENED_CLAY, 7),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::HARDENED_CLAY, 0),
				Block::get(Block::HARDENED_CLAY, 0),
				Block::get(Block::HARDENED_CLAY, 0),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::STAINED_HARDENED_CLAY, 1),
				Block::get(Block::RED_SANDSTONE, 0),
				Block::get(Block::RED_SANDSTONE, 0),
				Block::get(Block::RED_SANDSTONE, 0),
				Block::get(Block::RED_SANDSTONE, 0),
				Block::get(Block::RED_SANDSTONE, 0),
				Block::get(Block::RED_SANDSTONE, 0),
				Block::get(Block::RED_SANDSTONE, 0),
				Block::get(Block::RED_SANDSTONE, 0) 
		]);
	}

	/**
	 * Returns the biome name
	 *
	 * @return string
	 */
	public function getName(): string {
		return "BetterMesa";
	}
	
	/**
	 * Returns biome id
	 * 
	 * @return int
	 */
	public function getId(): int {
		return 39;
	}
}
