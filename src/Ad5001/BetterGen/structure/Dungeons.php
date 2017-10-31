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
		$xDepth = 2 + $random->nextBoundedInt(4);
		$zDepth = 2 + $random->nextBoundedInt(4);
		echo "Building dungeon at $x, $y, $z\n";
		BuildingUtils::fillCallback(new Vector3($x + $xDepth, $y, $x + $zDepth), new Vector3($x - $xDepth, $y + 5, $z - $zDepth), function($v3, $level, $v3n2, $xDepth, $zDepth, $random) {
			if($v3->x == $v3n2->x + $xDepth || 
			$v3->x == $v3n2->x - $xDepth || 
			$v3->y == $v3n2->y || 
			$v3->y == $v3n2->y + 5 || 
			$v3->z == $v3n2->z + $zDepth || 
			$v3->z == $v3n2->z - $zDepth) {
				if($random->nextBoolean()) {
					$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::MOSS_STONE);
				} else {
					$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::COBBLESTONE);
				}
			} else {
				$level->setBlockIdAt($v3->x, $v3->y, $v3->z, Block::AIR);
			}
		}, $level, new Vector3($x, $y, $z), $xDepth, $zDepth, $random);
		$level->setBlockIdAt($x, $y + 1, $z, Block::MOB_SPAWNER);
	}
}