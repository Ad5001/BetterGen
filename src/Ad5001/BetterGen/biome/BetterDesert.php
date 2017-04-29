<?php

/*
 * BetterDesert from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
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
		return "Better Desert";
	}
	
	/*
	 * Returns biome id
	 */
	public function getId() {
		return Biome::DESERT;
	}
}