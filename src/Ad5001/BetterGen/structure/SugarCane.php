<?php

/*
 * SugarCane from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */


namespace Ad5001\BetterGen\structure;

use pocketmine\block\Block;
use pocketmine\utils\Random;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\Object;

class SugarCane extends Object {
	
	/*
	 * Checks if a cactus is placeable
	 * @param $level pocketmine\level\ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $random pocketmine\utils\Random
	 */
	public function canPlaceObject(ChunkManager $level, int $x, int $y, int $z, Random $random): bool {
		$this->totalHeight = 1 + $random->nextBoundedInt ( 3 );
		$below = $level->getBlockIdAt ( $x, $y - 1, $z );
		if (($below == Block::SAND || $below == Block::GRASS) && ($level->getBlockIdAt ( $x + 1, $y - 1, $z ) == Block::WATER || $level->getBlockIdAt ( $x - 1, $y - 1, $z ) == Block::WATER || $level->getBlockIdAt ( $x, $y - 1, $z + 1 ) == Block::WATER || $level->getBlockIdAt ( $x, $y - 1, $z - 1 ) == Block::WATER)) {
			return true;
		}
		return false;
	}
	
	/*
	 * Places a cactus
	 * @param $level pocketmine\level\ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 */
	public function placeObject(ChunkManager $level, int $x, int $y, int $z) {
		for($yy = 0; $yy < $this->totalHeight; $yy ++) {
			if ($level->getBlockIdAt ( $x, $y + $yy, $z ) != Block::AIR) {
				return;
			}
			$level->setBlockIdAt ( $x, $y + $yy, $z, Block::SUGARCANE_BLOCK );
		}
	}
}