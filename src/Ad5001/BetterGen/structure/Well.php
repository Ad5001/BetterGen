<?php


/*
 * Well from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */


namespace Ad5001\BetterGen\structure;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use pocketmine\level\generator\object\Object;


class Well extends Object {
	public $overridable = [ 
			Block::AIR => true,
			6 => true,
			17 => true,
			18 => true,
			Block::DANDELION => true,
			Block::POPPY => true,
			Block::SNOW_LAYER => true,
			Block::LOG2 => true,
			Block::LEAVES2 => true,
			Block::CACTUS => true 
	];
	protected $directions = [ 
			[ 
					1,
					1 
			],
			[ 
					1,
					- 1 
			],
			[ 
					- 1,
					- 1 
			],
			[ 
					- 1,
					1 
			] 
	];
	
	/*
	 * Checks if a well is placeable
	 * @param $level pocketmine\level\ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $random pocketmine\utils\Random
	 * @return bool
	 */
	public function canPlaceObject(ChunkManager $level, $x, $y, $z, Random $random) {
		for($xx = $x - 2; $xx <= $x + 2; $xx ++)
			for($yy = $y; $yy <= $y + 3; $yy ++)
				for($zz = $z - 2; $zz <= $z + 2; $zz ++)
					if (! isset ( $this->overridable [$level->getBlockIdAt ( $xx, $yy, $zz )] ))
						return false;
		return true;
	}
	
	/*
	 * Places a well
	 * @param $level pocketmine\level\ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $random pocketmine\utils\Random
	 */
	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$this->level = $level;
		foreach ( $this->directions as $direction ) {
			// Building pillard
			for($yy = $y; $yy < $y + 3; $yy ++)
				$this->placeBlock ( $x + $direction [0], $yy, $z + $direction [1], Block::SANDSTONE );
			
			// Building corners
			$this->placeBlock ( $x + ($direction [0] * 2), $y, $z + $direction [1], Block::SANDSTONE );
			$this->placeBlock ( $x + $direction [0], $y, $z + ($direction [1] * 2), Block::SANDSTONE );
			$this->placeBlock ( $x + ($direction [0] * 2), $y, $z + ($direction [1] * 2), Block::SANDSTONE );
			
			// Building slabs on the sides. Places two times due to all directions.
			$this->placeSlab ( $x + ($direction [0] * 2), $y, $z );
			$this->placeSlab ( $x, $y, $z + ($direction [1] * 2) );
			
			// Placing water.Places two times due to all directions.
			$this->placeBlock ( $x + $direction [0], $y, $z, Block::WATER );
			$this->placeBlock ( $x, $y, $z + $direction [1], Block::WATER );
		}
		
		// Finitions
		for($xx = $x - 1; $xx <= $x + 1; $xx ++)
			for($zz = $z - 1; $zz <= $z + 1; $zz ++)
				$this->placeSlab ( $xx, $y + 3, $zz );
		$this->placeSlab ( $x, $y + 3, $z, Block::SANDSTONE );
		$this->placeSlab ( $x, $y, $z, Block::WATER );
	}
	
	/*
	 * Places a slab
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @return void
	 */
	public function placeSlab($x, $y, $z) {
		$this->level->setBlockIdAt ( $x, $y, $z, 44 );
		$this->level->setBlockDataAt ( $x, $y, $z, 1 );
	}
	
	/*
	 * Places a slab
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $id int
	 * @return void
	 */
	public function placeBlock($x, $y, $z, $id) {
		$this->level->setBlockIdAt ( $x, $y, $z, $id );
	}
}