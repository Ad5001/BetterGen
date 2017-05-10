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
namespace Ad5001\BetterGen\biome;

use pocketmine\level\generator\normal\biome\SandyBiome;
use pocketmine\level\generator\biome\Biome;
use pocketmine\block\Block;
use Ad5001\BetterGen\populator\TemplePopulator;
use Ad5001\BetterGen\populator\WellPopulator;
use Ad5001\BetterGen\populator\CactusPopulator;
use Ad5001\BetterGen\populator\DeadbushPopulator;
use Ad5001\BetterGen\populator\SugarCanePopulator;

class BetterDesert extends SandyBiome implements Mountainable {
	public function __construct() {
		$deadBush = new DeadbushPopulator ();
		$deadBush->setBaseAmount(1);
		$deadBush->setRandomAmount(2);
		
		$cactus = new CactusPopulator ();
		$cactus->setBaseAmount(1);
		$cactus->setRandomAmount(2);
		
		$sugarCane = new SugarCanePopulator ();
		$sugarCane->setRandomAmount(20);
		$sugarCane->setBaseAmount(3);
		
		$temple = new TemplePopulator ();
		
		$well = new WellPopulator ();
		
		$this->addPopulator($cactus);
		$this->addPopulator($deadBush);
		$this->addPopulator($sugarCane);
		$this->addPopulator($temple);
		$this->addPopulator($well);
		
		$this->setElevation(63, 70);
		// $this->setElevation(66, 70);
		
		$this->temperature = 0.5;
		$this->rainfall = 0;
		$this->setGroundCover([ 
				Block::get(Block::SAND, 0 ),
				Block::get(Block::SAND, 0 ),
				Block::get(Block::SAND, 0 ),
				Block::get(Block::SAND, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ) 
		]);
	}
	public function getName(): string {
		return "BetterDesert";
	}
	
	/*
	 * Returns biome id
	 */
	public function getId() {
		return Biome::DESERT;
	}
}