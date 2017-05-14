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

namespace Ad5001\BetterGen\populator;

use Ad5001\BetterGen\structure\Bush;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\utils\Random;

class BushPopulator extends AmountPopulator {
	/** @var ChunkManager */
	protected $level;
	protected $type;

	/**
	 * Constructs the class
	 * @param $type int
	 */
	public function __construct($type = 0) {
		$this->type = $type;
	}

	/**
	 * Populate the chunk
	 * @param $level pocketmine\level\ChunkManager
	 * @param $chunkX int
	 * @param $chunkZ int
	 * @param $random pocketmine\utils\Random
	 */
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		for ($i = 0; $i < $amount; $i++) {
			$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
			$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if ($y === -1) {
				continue;
			}
			$tree = new TreePopulator::$types[$this->type]();
			$bush = new Bush($tree->leafBlock, $tree->leafType ?? $tree->type);
			$bush->placeObject($level, $x, $y, $z, $random);
		}
	}

	/**
	 * Gets the top block (y) on an x and z axes
	 * @param $x int
	 * @param $z int
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for ($y = Level::Y_MAX - 1; $y > 0; --$y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::DIRT or $b === Block::GRASS or $b === Block::PODZOL) {
				break;
			} elseif ($b !== 0 and $b !== Block::SNOW_LAYER) {
				return -1;
			}
		}

		return ++$y;
	}
}