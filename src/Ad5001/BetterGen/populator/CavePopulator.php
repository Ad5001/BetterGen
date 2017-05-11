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

use pocketmine\level\Level;
use pocketmine\utils\Random;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use Ad5001\BetterGen\utils\BuildingUtils;
use Ad5001\BetterGen\populator\AmountPopulator;

class CavePopulator extends AmountPopulator {
	/** @var ChunkManager */
	protected $level;
	const STOP = false;
	const CONTINUE = true;
	
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
			$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
			$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $random->nextRange(10, $this->getHighestWorkableBlock($x, $z ));
			// echo "Generating cave at $x, $y, $z." . PHP_EOL;
			$this->generateCave($x, $y, $z, $random);
		}
		// echo "Finished Populating chunk $chunkX, $chunkZ !" . PHP_EOL;
		// Filling water & lava sources randomly
		for($i = 0; $i < $random->nextBoundedInt(5) + 3; $i ++) {
			$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
			$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $random->nextRange(10, $this->getHighestWorkableBlock($x, $z ));
			if ($level->getBlockIdAt($x, $y, $z ) == Block::STONE && ($level->getBlockIdAt($x + 1, $y, $z ) == Block::AIR || $level->getBlockIdAt($x - 1, $y, $z ) == Block::AIR || $level->getBlockIdAt($x, $y, $z + 1 ) == Block::AIR || $level->getBlockIdAt($x, $y, $z - 1 ) == Block::AIR) && $level->getBlockIdAt($x, $y - 1, $z ) !== Block::AIR && $level->getBlockIdAt($x, $y + 1, $z ) !== Block::AIR) {
				if ($y < 40 && $random->nextBoolean ()) {
					$level->setBlockIdAt($x, $y, $z, Block::LAVA);
				} else {
					$level->setBlockIdAt($x, $y, $z, Block::WATER);
				}
			}
		}
	}
	
	/*
	 * Gets the top block (y) on an x and z axes
	 * @param $x int
	 * @param $z int
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for($y = Level::Y_MAX; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::DIRT or $b === Block::GRASS or $b === Block::PODZOL or $b === Block::SAND or $b === Block::SNOW_BLOCK or $b === Block::SANDSTONE) {
				break;
			} elseif ($b !== 0 and $b !== Block::SNOW_LAYER and $b !== Block::WATER) {
				return - 1;
			}
		}
		
		return $y++;
	}
	
	/*
	 * Generates a cave
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $random pocketmine\utils\Random
	 * @return void
	 */
	public function generateCave($x, $y, $z, Random $random) {
		$generatedBranches = $random->nextBoundedInt(10 ) + 1;
		// echo "Num of branch left => " . $generatedBranches . PHP_EOL;
		foreach($gen = $this->generateBranch($x, $y, $z, 5, 3, 5, $random ) as $v3 ) {
			$generatedBranches --;
			if ($generatedBranches <= 0) {
				$gen->send(self::STOP);
			} else {
				$gen->send(self::CONTINUE);
			}
		}
	}
	/*
	 * Generates a cave branch.
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $length int
	 * @param $height int
	 * @param $depth int
	 * @param $random pocketmine\utils\Random
	 * @yield int
	 * @return void
	 */
	public function generateBranch($x, $y, $z, $length, $height, $depth, Random $random) {
		if (! (yield new Vector3($x, $y, $z ))) {
			for($i = 0; $i <= 4; $i ++) {
				BuildingUtils::buildRandom($this->level, new Vector3($x, $y, $z ), new Vector3($length - $i, $height - $i, $depth - $i ), $random, Block::get(Block::AIR ));
				$x += round(($random->nextBoundedInt(round(30 * ($length / 10) ) + 1 ) / 10 - 2));
				$yP = $random->nextRange(- 14, 14);
				if ($yP > 12) {
					$y ++;
				} elseif ($yP < - 12) {
					$y --;
				}
				$z += round(($random->nextBoundedInt(round(30 * ($depth / 10) ) + 1 ) / 10 - 1));
				return [ ];
			}
		}
		$repeat = $random->nextBoundedInt(25 ) + 15;
		while($repeat -- > 0 ) {
			// echo "Y => $y; H => $height; L => $length; D => $depth; R => $repeat" . PHP_EOL;
			BuildingUtils::buildRandom($this->level, new Vector3($x, $y, $z ), new Vector3($length, $height, $depth ), $random, Block::get(Block::AIR ));
			$x += round(($random->nextBoundedInt(round(30 * ($length / 10) ) + 1 ) / 10 - 2));
			$yP = $random->nextRange(- 14, 14);
			if ($yP > 12) {
				$y ++;
			} elseif ($yP < - 12) {
				$y --;
			}
			$z += round(($random->nextBoundedInt(round(30 * ($depth / 10) ) + 1 ) / 10 - 1));
			$height += $random->nextBoundedInt(3 ) - 1;
			$length += $random->nextBoundedInt(3 ) - 1;
			$depth += $random->nextBoundedInt(3 ) - 1;
			if ($height < 3)
				$height = 3;
			if ($length < 3)
				$length = 3;
			if ($height < 3)
				$height = 3;
			if ($height < 7)
				$height = 7;
			if ($length < 7)
				$length = 7;
			if ($height < 7)
				$height = 7;
			if ($random->nextBoundedInt(10 ) == 0) {
				foreach($generator = $this->generateBranch($x, $y, $z, $length, $height, $depth, $random ) as $gen ) {
					if (! (yield $gen))
						$generator->send(self::STOP);
				}
			}
		}
		return;
	}
}