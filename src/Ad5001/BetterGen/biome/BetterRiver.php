<?php

/*
 * BetterRiver from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */

namespace Ad5001\BetterGen\biome;

use pocketmine\level\generator\biome\Biome;
use pocketmine\block\Block;

class BetterRiver extends Biome {
	public function __construct() {
		$this->clearPopulators ();
		
		$this->setGroundCover ( [ 
				Block::get ( Block::SAND, 0 ),
				Block::get ( Block::SAND, 0 ),
				Block::get ( Block::SAND, 0 ),
				Block::get ( Block::SANDSTONE, 0 ),
				Block::get ( Block::SANDSTONE, 0 ),
				Block::get ( Block::SANDSTONE, 0 ),
				Block::get ( Block::SANDSTONE, 0 ),
				Block::get ( Block::SANDSTONE, 0 ),
				Block::get ( Block::SANDSTONE, 0 ),
				Block::get ( Block::SANDSTONE, 0 ),
				Block::get ( Block::SANDSTONE, 0 ) 
		] );
		
		$this->setElevation ( 60, 60 );
		
		$this->temperature = 0.5;
		$this->rainfall = 0.7;
	}
	public function getName() {
		return "Better River";
	}
	
	/*
	 * Returns the ID relativly.
	 */
	public function getId() {
		return Biome::RIVER;
	}
}