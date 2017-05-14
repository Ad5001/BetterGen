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
use pocketmine\utils\Random;


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
	/** @var ChunkManager */
	protected $level;
	protected $directions = [
		[
			1,
			1
		],
		[
			1,
			-1
		],
		[
			-1,
			-1
		],
		[
			-1,
			1
		]
	];

	/**
	 * Checks if a well is placeable
	 * @param $level ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $random Random
	 * @return bool
	 */
	public function canPlaceObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$this->level = $level;
		for ($xx = $x - 2; $xx <= $x + 2; $xx++)
			for ($yy = $y; $yy <= $y + 3; $yy++)
				for ($zz = $z - 2; $zz <= $z + 2; $zz++)
					if (!isset($this->overridable[$level->getBlockIdAt($xx, $yy, $zz)]))
						return false;
		return true;
	}

	/**
	 * Places a well
	 * @param $level ChunkManager
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $random Random
	 */
	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$this->level = $level;
		foreach ($this->directions as $direction) {
			// Building pillars
			for ($yy = $y; $yy < $y + 3; $yy++)
				$this->placeBlock($x + $direction [0], $yy, $z + $direction [1], Block::SANDSTONE);

			// Building corners
			$this->placeBlock($x + ($direction [0] * 2), $y, $z + $direction [1], Block::SANDSTONE);
			$this->placeBlock($x + $direction [0], $y, $z + ($direction [1] * 2), Block::SANDSTONE);
			$this->placeBlock($x + ($direction [0] * 2), $y, $z + ($direction [1] * 2), Block::SANDSTONE);

			// Building slabs on the sides. Places two times due to all directions.
			$this->placeBlock($x + ($direction [0] * 2), $y, $z, 44, 1);
			$this->placeBlock($x, $y, $z + ($direction [1] * 2), 44, 1);

			// Placing water.Places two times due to all directions.
			$this->placeBlock($x + $direction [0], $y, $z, Block::WATER);
			$this->placeBlock($x, $y, $z + $direction [1], Block::WATER);
		}

		// Final things
		for ($xx = $x - 1; $xx <= $x + 1; $xx++)
			for ($zz = $z - 1; $zz <= $z + 1; $zz++)
				$this->placeBlock($xx, $y + 3, $zz);
		$this->placeBlock($x, $y + 3, $z, Block::SANDSTONE, 1);
		$this->placeBlock($x, $y, $z, Block::WATER);
	}

	/**
	 * Places a slab
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $id int
	 * @return void
	 */
	public function placeBlock($x, $y, $z, $id = 0, $meta = 0) {
		$this->level->setBlockIdAt($x, $y, $z, $id);
		$this->level->setBlockDataAt($x, $y, $z, $meta);
	}
}