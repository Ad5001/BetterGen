<?php
/**
 *  ____             __     __                    ____
 * /\  _`\          /\ \__ /\ \__                /\  _`\
 * \ \ \L\ \     __ \ \ ,_\\ \ ,_\     __   _ __ \ \ \L\_\     __     ___
 *  \ \  _ <'  /'__`\\ \ \/ \ \ \/   /'__`\/\`'__\\ \ \L_L   /'__`\ /' _ `\
 *   \ \ \L\ \/\  __/ \ \ \_ \ \ \_ /\  __/\ \ \/  \ \ \/, \/\  __/ /\ \/\ \
 *    \ \____/\ \____\ \ \__\ \ \__\\ \____\\ \_\   \ \____/\ \____\\ \_\ \_\
 *     \/___/  \/____/  \/__/  \/__/ \/____/ \/_/    \/___/  \/____/ \/_/\/_/
 * Tomorrow's pocketmine generator.
 * @author Ad5001
 * @link https://github.com/Ad5001/BetterGen
 */

namespace Ad5001\BetterGen\structure;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\Object;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Bush extends Object {
	public $overridable = [
		Block::AIR => true,
		17 => true,
		Block::SNOW_LAYER => true,
		Block::LOG2 => true
	];
	protected $leaf;
	protected $height;

	/**
	 * Constructs the class
	 * @param $leafId int
	 * @param $leafData int
	 */
	public function __construct($leafId = Block::LEAVES, $leafData = 0) {
		$this->leaf = [
			$leafId,
			$leafData
		];
	}

	/**
	 * Places a bush
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
		for ($i = 0; $i < $number; $i++) {
			$transfer = $random->nextBoolean();
			$direction = $random->nextBoundedInt(6);
			$newPos = $pos->getSide($direction);
			if ($transfer)
				$pos = $newPos;
			$this->placeLeaf($newPos->x, $newPos->y, $newPos->z, $level);
		}
	}

	/**
	 * Places a leaf
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $level pocketmine\level\ChunkManager
	 */
	public function placeLeaf($x, $y, $z, ChunkManager $level) {
		if (isset($this->overridable[$level->getBlockIdAt($x, $y, $z)]) && !isset($this->overridable[$level->getBlockIdAt($x, $y - 1, $z)])) {
			$level->setBlockIdAt($x, $y, $z, $this->leaf[0]);
			$level->setBlockDataAt($x, $y, $z, $this->leaf[1]);
		}
	}
}