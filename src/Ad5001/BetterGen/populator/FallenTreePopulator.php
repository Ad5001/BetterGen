<?php

/*
 * FallenTreePopulator from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */

namespace Ad5001\BetterGen\populator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use Ad5001\BetterGen\structure\FallenTree;
use Ad5001\BetterGen\populator\AmountPopulator;

class FallenTreePopulator extends AmountPopulator {
	protected $level;
	protected $type;
	/*
	 * Constructs the class
	 * @param $type int
	 */
	public function __construct(int $type = 0) {
		
		$this->setBaseAmount(1);
		$this->setRandomAmount(2);
	}
	
	/*
	 * Populate the chunk
	 * @param $level pocketmine\level\ChunkManager
	 * @param $chunkX int
	 * @param $chunkZ int
	 * @param $random pocketmine\utils\Random
	 */
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		$tree =  TreePopulator::$types[$this->type];
		$fallenTree = new FallenTree(new $tree());
		for($i = 0; $i < $amount; $i++) {
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if ($y !== -1 and $fallenTree->canPlaceObject($level, $x, $y, $z, $random )) {
				$fallenTree->placeObject($level, $x, $y, $z, $random);
			}
		}
	}
	
	/*
	 * Gets the top block (y) on an x and z axes
	 * @param $x int
	 * @param $z int
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for($y = 127; $y >= 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b !== Block::AIR and $b !== Block::LEAVES and $b !== Block::LEAVES2) {
				break;
			}
		}
		return $y === 0 ? - 1 : $y++;
	}
}