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

use Ad5001\BetterGen\generator\BetterBiomeSelector;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\Level;
use pocketmine\utils\Random;

class DeadbushPopulator extends AmountPopulator {
	/** @var ChunkManager */
	protected $level;
	
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
		for($i = 0; $i < $amount; $i++) {
			$x = $random->nextRange($chunkX * 16, $chunkX * 16 + 15);
			$z = $random->nextRange($chunkZ * 16, $chunkZ * 16 + 15);
			if(!in_array($level->getChunk($chunkX, $chunkZ)->getBiomeId(abs($x % 16), ($z % 16)), [40, 39, Biome::DESERT])) continue;
			$y = $this->getHighestWorkableBlock($x, $z);
			if ($y !== -1 && $level->getBlockIdAt($x, $y - 1, $z) == Block::SAND) {
				$level->setBlockIdAt($x, $y, $z, Block::DEAD_BUSH);
				$level->setBlockDataAt($x, $y, $z, 1);
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
			if($b === Block::DIRT or $b === Block::GRASS or $b === Block::SAND or $b === Block::SANDSTONE or $b === Block::HARDENED_CLAY or $b === Block::STAINED_HARDENED_CLAY){
				break;
			}elseif($b !== Block::AIR){
				return -1;
			}
		}

		return ++$y;
	}
}