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

namespace Ad5001\BetterGen\biome;

use Ad5001\BetterGen\Main;
use Ad5001\BetterGen\populator\CactusPopulator;
use Ad5001\BetterGen\populator\DeadbushPopulator;
use Ad5001\BetterGen\populator\SugarCanePopulator;
use pocketmine\block\Block;
use pocketmine\block\GoldOre;
use pocketmine\level\generator\normal\biome\SandyBiome;

class BetterMesaPlains extends SandyBiome {
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

		$ores = Main::isOtherNS() ? new \pocketmine\level\generator\normal\populator\Ore() : new \pocketmine\level\generator\populator\Ore();
		$ores->setOreTypes([ 
				Main::isOtherNS() ? new \pocketmine\level\generator\normal\object\OreType(new GoldOre (), 2, 8, 0, 32) : new \pocketmine\level\generator\object\OreType(new GoldOre (), 2, 8, 0, 32) 
		]);
		
		$this->addPopulator($cactus);
		$this->addPopulator($deadBush);
		$this->addPopulator($sugarCane);
		$this->addPopulator($ores);
		
		$this->setElevation(62, 67);
		// $this->setElevation(66, 70);
		
		$this->temperature = 0.6;
		$this->rainfall = 0;
		$this->setGroundCover([ 
				Block::get(Block::SAND, 1),
				Block::get(Block::SAND, 1),
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
	public function getName(): string {
		return "BetterMesaPlains";
	}
	
	/*
	 * Returns biome id
	 */
	public function getId() {
		return 40;
	}
}