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
 * @author Ad5001 <mail@ad5001.eu>, XenialDan <https://github.com/thebigsmileXD>
 * @link https://github.com/Ad5001/BetterGen
 * @category World Generator
 * @api 3.0.0
 * @version 1.1
 */

namespace Ad5001\BetterGen\structure;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\PopulatorObject;
use pocketmine\utils\Random;

class Cactus extends PopulatorObject {
	
	protected $totalHeight;

	/**
	 * Checks if a cactus is placeable
	 *
	 * @param ChunkManager $level
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param Random $random
	 * @return bool
	 */
	public function canPlaceObject(ChunkManager $level, int $x, int $y, int $z, Random $random): bool {
		$this->totalHeight = 1 + $random->nextBoundedInt(3);
		$below = $level->getBlockIdAt($x, $y - 1, $z);
		for($yy = $y; $yy <= $y + $this->totalHeight; $yy ++) {
			if ($level->getBlockIdAt($x, $yy, $z) !== Block::AIR || ($below !== Block::SAND && $below !== Block::CACTUS) || ($level->getBlockIdAt($x - 1, $yy, $z) !== Block::AIR || $level->getBlockIdAt($x + 1, $yy, $z) !== Block::AIR || $level->getBlockIdAt($x, $yy, $z - 1) !== Block::AIR || $level->getBlockIdAt($x, $yy, $z + 1) !== Block::AIR)) {
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Places a cactus
	 *
	 * @param ChunkManager $level
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @return void
	 */
	public function placeObject(ChunkManager $level, int $x, int $y, int $z) {
		for($yy = 0; $yy < $this->totalHeight; $yy ++) {
			if ($level->getBlockIdAt($x, $y + $yy, $z) != Block::AIR) {
				return;
			}
			$level->setBlockIdAt($x, $y + $yy, $z, Block::CACTUS);
		}
	}
}
