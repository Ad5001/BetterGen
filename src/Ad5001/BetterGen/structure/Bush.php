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
	/** @var int[] */
	protected $leaf;
	/** @var int */
	protected $height;
	
	/**
	 * Constructs the class
	 *
	 * @param int $leafId
	 * @param int $leafData
	 */
	public function __construct($leafId = Block::LEAVES, $leafData = 0) {
		$this->leaf = [ 
				$leafId,
				$leafData 
		];
	}
	
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
		$number = $random->nextBoundedInt(6);
		$pos = new Vector3($x, $y, $z);
		$this->placeLeaf($pos->x, $pos->y, $pos->z, $level);
		for($i = 0; $i < $number; $i ++) {
			$transfer = $random->nextBoolean ();
			$direction = $random->nextBoundedInt(6);
			$newPos = $pos->getSide($direction);
			if ($transfer)
				$pos = $newPos;
			$this->placeLeaf($newPos->x, $newPos->y, $newPos->z, $level);
		}
	}
	
	/**
	 * Places a leaf
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param ChunkManager $level
	 * @return void
	 */
	public function placeLeaf($x, $y, $z, ChunkManager $level) {
		if (isset($this->overridable[$level->getBlockIdAt($x, $y, $z)]) && ! isset($this->overridable[$level->getBlockIdAt($x, $y - 1, $z)])) {
			$level->setBlockIdAt($x, $y, $z, $this->leaf[0]);
			$level->setBlockDataAt($x, $y, $z, $this->leaf[1]);
		}
	}
}