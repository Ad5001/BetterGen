<?php

/*
 * WellPopulator from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */
namespace Ad5001\BetterGen\populator;

use pocketmine\utils\Random;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use Ad5001\BetterGen\structure\Well;
use Ad5001\BetterGen\populator\AmountPopulator;

class WellPopulator extends AmountPopulator {
	protected $level;
	
	/*
	 * Populate the chunk
	 * @param $level pocketmine\level\ChunkManager
	 * @param $chunkX int
	 * @param $chunkZ int
	 * @param $random pocketmine\utils\Random
	 */
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		if ($random->nextBoundedInt ( 1000 ) > 25)
			return; // ~1 chance / 1000 due to building limitations.
		$well = new Well ();
		$x = $random->nextRange ( $chunkX << 4, ($chunkX << 4) + 15 );
		$z = $random->nextRange ( $chunkZ << 4, ($chunkZ << 4) + 15 );
		$y = $this->getHighestWorkableBlock ( $x, $z ) - 1;
		if ($well->canPlaceObject ( $level, $x, $y, $z, $random ))
			$well->placeObject ( $level, $x, $y, $z, $random );
	}
	
	/*
	 * Gets the top block (y) on an x and z axes
	 * @param $x int
	 * @param $z int
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for($y = 127; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt ( $x, $y, $z );
			if ($b === Block::SAND) {
				break;
			}
		}
		
		return ++ $y;
	}
}