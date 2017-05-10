<?php
/**
 *  ____             __     __                    ____                       
 * /\  _`\          /\ \__ /\ \__                /\  _`\                     
 * \ \ \L\ \     __ \ \ ,_\\ \ ,_\     __   _ __ \ \ \L\_\     __     ___    
 *  \ \  _ <'  /'__`\\ \ \/ \ \ \/   /'__`\/\`'__\\ \ \L_L   /'__`\ /' _ `\  
 *   \ \ \L\ \/\  __/ \ \ \_ \ \ \_ /\  __/\ \ \/  \ \ \/, \/\  __/ /\ \/\ \ 
 *    \ \____/\ \____\ \ \__\ \ \__\\ \____\\ \_\   \ \____/\ \____\\ \_\ \_\
 *     \/___/  \/____/  \/__/  \/__/ \/____/ \/_/    \/___/  \/____/ \/_/\/_/
 * Tommorow's pocketmine generator.
 * @author Ad5001
 * @link https://github.com/Ad5001/BetterGen
 */
 
namespace Ad5001\BetterGen\populator;

use pocketmine\utils\Random;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use Ad5001\BetterGen\utils\BuildingUtils;
use Ad5001\BetterGen\populator\AmountPopulator;

class LakePopulator extends AmountPopulator {
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
		$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
		$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
		$ory = $random->nextRange(20, 63); // Water level
		$y = $ory;
		for($i = 0; $i < 4; $i ++) {
			$x += $random->nextRange(- 1, 1);
			$y += $random->nextRange(- 1, 1);
			$z += $random->nextRange(- 1, 1);
			if ($level->getBlockIdAt($x, $y, $z ) !== Block::AIR)
				BuildingUtils::buildRandom($this->level, new Vector3($x, $y, $z ), new Vector3(5, 5, 5 ), $random, Block::get(Block::WATER ));
		}
		for($xx = $x - 8; $xx <= $x + 8; $xx ++)
			for($zz = $z - 8; $zz <= $z + 8; $zz ++)
				for($yy = $ory + 1; $yy <= $y + 3; $yy ++)
					if ($level->getBlockIdAt($xx, $yy, $zz ) == Block::WATER)
						$level->setBlockIdAt($xx, $yy, $zz, Block::AIR);
	}
	
	/*
	 * Gets the top block (y) on an x and z axes
	 * @param $x int
	 * @param $z int
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for($y = 127; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::DIRT or $b === Block::GRASS or $b === Block::PODZOL) {
				break;
			} elseif ($b !== 0 and $b !== Block::SNOW_LAYER) {
				return - 1;
			}
		}
		
		return $y++;
	}
}