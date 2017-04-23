<?php

/*
 * IglooPopulator from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */
namespace Ad5001\BetterGen\populator;

use pocketmine\utils\Random;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use Ad5001\BetterGen\populator\AmountPopulator;
use Ad5001\BetterGen\structure\Igloo;

class IglooPopulator extends AmountPopulator {
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
		if ($random->nextBoundedInt ( 100 ) > 30)
			return;
		$igloo = new Igloo ();
		$x = $random->nextRange ( $chunkX << 4, ($chunkX << 4) + 15 );
		$z = $random->nextRange ( $chunkZ << 4, ($chunkZ << 4) + 15 );
		$y = $this->getHighestWorkableBlock ( $x, $z ) - 1;
		if ($igloo->canPlaceObject ( $level, $x, $y, $z, $random ))
			$igloo->placeObject ( $level, $x, $y, $z, $random );
	}
	
	/*
	 * Gets the top block (y) on an x and z axes
	 * @param $x int
	 * @param $z int
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for($y = 127; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt ( $x, $y, $z );
			if ($b === Block::DIRT or $b === Block::GRASS or $b === Block::PODZOL) {
				break;
			} elseif ($b !== 0 and $b !== Block::SNOW_LAYER) {
				return - 1;
			}
		}
		
		return ++ $y;
	}
}