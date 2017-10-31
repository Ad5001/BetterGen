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
use pocketmine\level\generator\object\Tree as ObjectTree;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;


class FallenTree extends PopulatorObject {
	public static $overridable = [ 
			Block::AIR => true,
			6 => true,
			17 => true,
			18 => true,
			Block::DANDELION => true,
			Block::POPPY => true,
			Block::SNOW_LAYER => true,
			Block::LOG2 => true,
			Block::LEAVES2 => true,
			Block::CACTUS => true];
	/** @var Tree */
	protected $tree;
	/** @var int */
	protected $direction;
	/** @var Random */
	protected $random;
	/** @var int */
	protected $length = 0;

	/**
	 * Constructs the class
	 *
	 * @param ObjectTree $tree
	 */
	public function __construct(ObjectTree $tree) {
		$this->tree = $tree;
	}

	/**
	 * Checks the placement a fallen tree
	 *
	 * @param ChunkManager $level
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param Random $random
	 * @return void
	 */
	public function canPlaceObject(ChunkManager $level, $x, $y, $z, Random $random) {
		//echo "Checking at $x $y $z FallenTree\n";
		$randomHeight = round($random->nextBoundedInt($this->tree->treeHeight < 6 ? 6 : $this->tree->treeHeight) - ($this->tree->treeHeight < 6 ? 3 : $this->tree->treeHeight / 2));
		$this->length = ($this->tree->treeHeight ?? 5) + $randomHeight;
		$this->direction = $random->nextBoundedInt(4);
		$this->random = $random;
		switch ($this->direction) {
			case 0:
			case 1:// Z+
			$return = array_merge(BuildingUtils::fillCallback(new Vector3($x, $y, $z), new Vector3($x, $y, $z + $this->length), function($v3, $level) {
				if(!isset(\Ad5001\BetterGen\structure\FallenTree::$overridable[$level->getBlockIdAt($v3->x, $v3->y, $v3->z)])) {
					//echo "$v3 is not overwritable (" . $level->getBlockIdAt($v3->x, $v3->y, $v3->z) . ").\n";
					return false;
				}
			}, $level), BuildingUtils::fillCallback(new Vector3($x, $y - 1, $z), new Vector3($x, $y - 1, $z + $this->length), function($v3, $level) {
				if(isset(\Ad5001\BetterGen\structure\FallenTree::$overridable[$level->getBlockIdAt($v3->x, $v3->y, $v3->z)])) {
					//echo "$v3 is overwritable (" . $level->getBlockIdAt($v3->x, $v3->y, $v3->z) . ").\n";
					return false;
				}
			}, $level));
			if(in_array(false, $return, true)) {
				return false;
			}
			break;
			case 2:
			case 3: // X+
			$return = array_merge(BuildingUtils::fillCallback(new Vector3($x, $y, $z), new Vector3($x + $this->length, $y, $z), function($v3, $level) {
				if(!isset(\Ad5001\BetterGen\structure\FallenTree::$overridable[$level->getBlockIdAt($v3->x, $v3->y, $v3->z)])) {
					//echo "$v3 is not overwritable (" . $level->getBlockIdAt($v3->x, $v3->y, $v3->z) . ").\n";
					return false;
				}
			}, $level), BuildingUtils::fillCallback(new Vector3($x, $y - 1, $z), new Vector3($x + $this->length, $y - 1, $z), function($v3, $level) {
				if(isset(\Ad5001\BetterGen\structure\FallenTree::$overridable[$level->getBlockIdAt($v3->x, $v3->y, $v3->z)])) {
					//echo "$v3 is overwritable (" . $level->getBlockIdAt($v3->x, $v3->y, $v3->z) . ").\n";
					return false;
				}
			}, $level));
			if(in_array(false, $return, true)) {
				return false;
			}
			break;
		}
		return true;
	}

	/**
	 * Places a fallen tree
	 *
	 * @param ChunkManager $level
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @return void
	 */
	public function placeObject(ChunkManager $level, $x, $y, $z) {
		//echo "Placing at $x $y $z FallenTree D: $this->direction, L: $this->length\n";
		switch ($this->direction) {
			case 0:
				$level->setBlockIdAt($x, $y, $z, $this->tree->trunkBlock);
				$level->setBlockDataAt($x, $y, $z, $this->tree->type);
				$z += 2;
				break;
			case 1:// Z+
				BuildingUtils::fill($level, new Vector3($x, $y, $z), new Vector3($x, $y, $z + $this->length), Block::get($this->tree->trunkBlock, $this->tree->type + 8));
				BuildingUtils::fillRandom($level, new Vector3($x + 1, $y, $z), new Vector3($x + 1, $y, $z + $this->length), Block::get(Block::VINE), $this->random);
				BuildingUtils::fillRandom($level, new Vector3($x - 1, $y, $z), new Vector3($x - 1, $y, $z + $this->length), Block::get(Block::VINE), $this->random);
				break;
			case 2:
				$level->setBlockIdAt($x, $y, $z, $this->tree->trunkBlock);
				$level->setBlockDataAt($x, $y, $z, $this->tree->type);
				$x += 2;
				break;
			case 3: // X+
				BuildingUtils::fill($level, new Vector3($x, $y, $z), new Vector3($x + $this->length, $y, $z), Block::get($this->tree->trunkBlock, $this->tree->type + 4));
				BuildingUtils::fillRandom($level, new Vector3($x, $y, $z + 1), new Vector3($x + $this->length, $y, $z + 1), Block::get(Block::VINE), $this->random);
				BuildingUtils::fillRandom($level, new Vector3($x, $y, $z - 1), new Vector3($x + $this->length, $y, $z - 1), Block::get(Block::VINE), $this->random);
				break;
		}
		// Second call to build the last wood block
		switch ($this->direction) {
			case 1:
				$level->setBlockIdAt($x, $y, $z + $this->length + 2, $this->tree->trunkBlock);
				$level->setBlockDataAt($x, $y, $z + $this->length + 2, $this->tree->type);
				break;
			case 3:
				$level->setBlockIdAt($x + $this->length + 2, $y, $z, $this->tree->trunkBlock);
				$level->setBlockDataAt($x + $this->length + 2, $y, $z, $this->tree->type);
				break;
		}
	}

	/**
	 * Places a block
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param ChunkManager $level
	 * @return void
	 */
	public function placeBlock($x, $y, $z, ChunkManager $level) {
		if (isset(self::$overridable[$level->getBlockIdAt($x, $y, $z)]) && ! isset(self::$overridable[$level->getBlockIdAt($x, $y - 1, $z)])) {
			$level->setBlockIdAt($x, $y, $z, $this->trunk[0]);
			$level->setBlockDataAt($x, $y, $z, $this->trunk[1]);
		}
	}
}
