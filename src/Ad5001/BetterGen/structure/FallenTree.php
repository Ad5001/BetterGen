<?php

/*
 * FallenTree from BetterGen
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
use pocketmine\level\generator\object\Tree;
use pocketmine\level\generator\normal\object\Tree as Tree2;
use pocketmine\level\generator\object\Object;

class FallenTree extends Object {
	public $overridable = [ 
			Block::AIR => true,
			17 => true,
			Block::SNOW_LAYER => true,
			Block::LOG2 => true 
	];
	protected $tree;
	protected $direction;
	
	/*
	 * Constructs the class
	 * @param 	$tree Tree
	 * @throws 	Exeption
	 */
	public function __construct($tree) {
		if(!is_subclass_of($tree, Tree::class) && !is_subclass_of($tree, Tree2::class)) {
			throw new Exception("Argument 1 passed to \\Ad5001\\BetterGen\\structure\\FallenTree must be an instance of pocketmine\\level\\generator\\normal\\object\\Tree or pocketmine\\level\\generator\\object\\Tree. Instance of " . get_class($tree) . " given.");
		}
		$this->tree = $tree;
	}
	
	/*
	 * Places a fallen tree
	 * @param $level pocketmine\level\ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $random pocketmine\utils\Random
	 */
	public function canPlaceObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$randomHeight = round($random->nextBoundedInt(6) - 3);
		$this->length = $this->tree->trunkHeight + $randomHeight;
	}
	
	/*
	 * Places a fallen tree
	 * @param $level pocketmine\level\ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 */
	public function placeObject(ChunkManager $level, $x, $y, $z) {
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