<?php

/*
 * Bush from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */

namespace Ad5001\BetterGen\structure;

use pocketmine\block\Leaves;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use pocketmine\math\Vector3;
use pocketmine\level\generator\object\Object;

class Bush extends Object {
	public $overridable = [ 
			Block::AIR => true,
			17 => true,
			Block::SNOW_LAYER => true,
			Block::LOG2 => true 
	];
	protected $trunk;
	protected $direction;
	
	/*
	 * Constructs the class
	 * @param $trunkId int
	 * @param $trunkData int
	 */
	public function __construct($trunk = Block::WOOD, $trunkData = 0) {
		$this->trunk = [ 
				$trunkId,
				$trunkData 
		];
	}
	
	/*
	 * Places a fallen tree
	 * @param $level pocketmine\level\ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $random pocketmine\utils\Random
	 */
	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$number = $random->nextBoundedInt(6);
		$pos = new Vector3($x, $y, $z);
		$this->placeLeaf($pos->x, $pos->y, $pos->z, $level);
		for($i = 0; $i < $number; $i ++) {
			$transfer = $random->nextBoolean ();
			$direction = $random->nextBoundedInt(4);
			$newPos = $pos->getSide($direction);
			if ($transfer)
				$pos = $newPos;
			$this->placeLeaf($newPos->x, $newPos->y, $newPos->z, $level);
		}
	}
	
	/*
	 * Places a Block
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $level pocketmine\level\ChunkManager
	 */
	public function placeBlock($x, $y, $z, ChunkManager $level) {
		if (isset($this->overridable [$level->getBlockIdAt($x, $y, $z )] ) && ! isset($this->overridable [$level->getBlockIdAt($x, $y - 1, $z )] )) {
			$level->setBlockIdAt($x, $y, $z, $this->trunk [0]);
			$level->setBlockDataAt($x, $y, $z, $this->trunk [1]);
		}
	}
}