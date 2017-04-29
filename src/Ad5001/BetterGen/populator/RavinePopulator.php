<?php

/*
 * RavinePopulator from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */
namespace Ad5001\BetterGen\populator;

use pocketmine\utils\Random;
use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use Ad5001\BetterGen\populator\AmountPopulator;
use Ad5001\BetterGen\utils\BuildingUtils;

class RavinePopulator extends AmountPopulator {
	protected $level;
	const NOISE = 250;
	
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
		if ($amount > 50) { // Only build one per chunk
			$depth = $random->nextBoundedInt(60 ) + 30; // 2Much4U?
			$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
			$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
			$y = $random->nextRange(5, $this->getHighestWorkableBlock($x, $z ));
			$deffX = $x;
			$deffZ = $z;
			$height = $random->nextRange(15, 30);
			$length = $random->nextRange(5, 12);
			for($i = 0; $i < $depth; $i ++) {
				$this->buildRavinePart($x, $y, $z, $height, $length, $random);
				$diffX = $x - $deffX;
				$diffZ = $z - $deffZ;
				if ($diffX > $length / 2)
					$diffX = $length / 2;
				if ($diffX < - $length / 2)
					$diffX = - $length / 2;
				if ($diffZ > $length / 2)
					$diffZ = $length / 2;
				if ($diffZ < - $length / 2)
					$diffZ = - $length / 2;
				if ($length > 10)
					$length = 10;
				if ($length < 5)
					$length = 5;
				$x += $random->nextRange(0 + $diffX, 2 + $diffX ) - 1;
				$y += $random->nextRange(0, 2 ) - 1;
				$z += $random->nextRange(0 + $diffZ, 2 + $diffZ ) - 1;
				$heigth += $random->nextRange(0, 2 ) - 1;
				$length += $random->nextRange(0, 2 ) - 1;
			}
		}
	}
	
	/*
	 * Gets the top block (y) on an x and z axes
	 * @param $x int
	 * @param $z int
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for($y = 127; $y > 0; -- $y) {
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
	 * Builds a ravine part
	 * @param $x int
	 * @param $y int
	 * @param $z int
	 * @param $height int
	 * @param $length int
	 * @param $random pocketmine\utils\Random
	 */
	protected function buildRavinePart($x, $y, $z, $height, $length, Random $random) {
		for($xx = $x - $length; $xx <= $x + $length; $xx ++) {
			for($yy = $y; $yy <= $y + $height; $yy ++) {
				for($zz = $z - $length; $zz <= $z + $length; $zz ++) {
					$oldXB = $xBounded;
					$xBounded = $random->nextBoundedInt(self::NOISE * 2 ) - self::NOISE;
					$oldZB = $zBounded;
					$zBounded = $random->nextBoundedInt(self::NOISE * 2 ) - self::NOISE;
					if ($xBounded > self::NOISE - 2) {
						$xBounded = 1;
					} elseif ($xBounded < - self::NOISE + 2) {
						$xBounded = -1;
					} else {
						$xBounded = $oldXB;
					}
					if ($zBounded > self::NOISE - 2) {
						$zBounded = 1;
					} elseif ($zBounded < - self::NOISE + 2) {
						$zBounded = -1;
					} else {
						$zBounded = $oldZB;
					}
					if (abs((abs($xx ) - abs($x )) ** 2 + (abs($zz ) - abs($z )) ** 2 ) < ((($length / 2 - $xBounded) + ($length / 2 - $zBounded)) / 2) ** 2 && $y > 0 && ! in_array($this->level->getBlockIdAt(( int ) round($xx ),(int ) round($yy ),(int ) round($zz ) ), BuildingUtils::TO_NOT_OVERWRITE ) && ! in_array($this->level->getBlockIdAt(( int ) round($xx ),(int ) round($yy + 1 ),(int ) round($zz ) ), BuildingUtils::TO_NOT_OVERWRITE )) {
						$this->level->setBlockIdAt(( int ) round($xx ),(int ) round($yy ),(int ) round($zz ), Block::AIR);
					}
				}
			}
		}
	}
}