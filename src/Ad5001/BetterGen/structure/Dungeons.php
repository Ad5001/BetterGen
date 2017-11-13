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

use Ad5001\BetterGen\utils\BuildingUtils;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\PopulatorObject;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class Dungeons extends PopulatorObject {
	public $overridable = [
			Block::AIR => true,
			17 => true,
			Block::SNOW_LAYER => true,
			Block::LOG2 => true 
	];
	/** @var int */
	protected $height;
	
	/**
	 * Places a bush
	 *
	 * @param ChunkManager $level
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param Random $random
	 * @return void
	 */
	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$xDepth = 3 + $random->nextBoundedInt(3);
		$zDepth = 3 + $random->nextBoundedInt(3);
		// echo "Building dungeon at $x, $y, $z\n";
		// Building walls
		list($pos1, $pos2) = BuildingUtils::minmax(new Vector3($x + $xDepth, $y, $z + $zDepth), new Vector3($x - $xDepth, $y + 5, $z - $zDepth));
		for($y = $pos1->y; $y >= $pos2->y; $y--) {
			for($x = $pos1->x; $x >= $pos2->x; $x--) {
				for($z = $pos1->z; $z >= $pos2->z; $z--) { // Cleaning the area first 
					$level->setBlockIdAt($x, $y, $z, Block::AIR);
				}
				// Starting random walls.
				if($random->nextBoolean()) {
					$level->setBlockIdAt($x, $y, $pos1->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $y, $pos1->z, Block::COBBLESTONE);
				}
				if($random->nextBoolean()) {
					$level->setBlockIdAt($x, $y, $pos2->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $y, $pos2->z, Block::COBBLESTONE);
				}
			}
			for($z = $pos1->z; $z >= $pos2->z; $z--) {
				if($random->nextBoolean()) {
					$level->setBlockIdAt($pos1->x, $y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($pos1->x, $y, $z, Block::COBBLESTONE);
				}
				if($random->nextBoolean()) {
					$level->setBlockIdAt($pos2->x, $y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($pos2->x, $y, $z, Block::COBBLESTONE);
				}
			}
		}
		// Bottom & top
		for($x = $pos1->x; $x >= $pos2->x; $x--) {
			for($z = $pos1->z; $z >= $pos2->z; $z--) {
				if($random->nextBoolean()) {
					$level->setBlockIdAt($x, $pos1->y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $pos1->y, $z, Block::COBBLESTONE);
				}
				if($random->nextBoolean()) {
					$level->setBlockIdAt($x, $pos2->y, $z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($x, $pos2->y, $z, Block::COBBLESTONE);
				}
			}
		}
		// Setting the spawner
		$level->setBlockIdAt($x, $y + 1, $z, Block::MOB_SPAWNER);
	}
}