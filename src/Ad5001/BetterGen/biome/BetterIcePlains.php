<?php

/*
 * BetterIcePlains from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */

namespace Ad5001\BetterGen\biome;

use pocketmine\level\generator\normal\biome\SnowyBiome;
use pocketmine\level\generator\biome\Biome;
use pocketmine\block\Block;
use Ad5001\BetterGen\populator\IglooPopulator;
use Ad5001\BetterGen\Main;

class BetterIcePlains extends SnowyBiome implements Mountainable {
	public function __construct() {
		parent::__construct ();
		$this->setGroundCover([ 
				Block::get(Block::SNOW, 0 ),
				Block::get(Block::GRASS, 0 ),
				Block::get(Block::DIRT, 0 ),
				Block::get(Block::DIRT, 0 ),
				Block::get(Block::DIRT, 0 ) 
		]);
		$this->addPopulator(new IglooPopulator ());
				
		$tallGrass = Main::isOtherNS() ? new \pocketmine\level\generator\normal\populator\TallGrass() : new \pocketmine\level\generator\populator\TallGrass();
		$tallGrass->setBaseAmount(3);
		
		$this->addPopulator($tallGrass);
		
		$this->setElevation(63, 74);
		
		$this->temperature = 0.05;
		$this->rainfall = 0.8;
	}
	public function getName() {
		return "Better Ice Plains";
	}
	
	/*
	 * Returns biome's id.
	 */
	public function getId() {
		return Biome::ICE_PLAINS;
	}
}