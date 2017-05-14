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

namespace Ad5001\BetterGen\populator;

use Ad5001\BetterGen\structure\FallenTree;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\utils\Random;


class FallenTreePopulator extends AmountPopulator {
	/** @var ChunkManager */
	protected $level;
	protected $type;
	/*
	 * Constructs the class
	 * @param $type int
	 */
	public function __construct(int $type = 0) {
		$this->type = $type;
		$this->setBaseAmount(1);
		$this->setRandomAmount(2);
	}

	/*
	 * Populate the chunk
	 * @param $level pocketmine\level\ChunkManager
	 * @param $chunkX int
	 * @param $chunkZ int
	 * @param $random pocketmine\utils\Random
	 */
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		$amount = $this->getAmount($random);
		$tree =  TreePopulator::$types[$this->type];
		$fallenTree = new \Ad5001\BetterGen\structure\FallenTree(
			new $tree()
		);
		for($i = 0; $i < $amount; $i++) {
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			$y = $this->getHighestWorkableBlock($x, $z);
			if(isset(FallenTree::$overridable[$level->getBlockIdAt($x, $y, $z)])) $y--; // Changing $y if 1 block to high.
			if ($y !== -1 and $fallenTree->canPlaceObject($level, $x, $y + 1, $z, $random)) {
				$fallenTree->placeObject($level, $x, $y + 1, $z);
			}
		}
	}

	/**
	 * Gets the top block (y) on an x and z axes
	 * @param $x
	 * @param $z
	 * @return int
	 */
	protected function getHighestWorkableBlock($x, $z){
		for($y = Level::Y_MAX - 1; $y > 0; --$y){
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if($b === Block::DIRT or $b === Block::GRASS){
				break;
			}elseif($b !== Block::AIR and $b !== Block::SNOW_LAYER){
				return -1;
			}
		}

		return ++$y;
	}
}