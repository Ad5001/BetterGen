<?php 

namespace Ad5001\BetterGen\populator;

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use Ad5001\BetterGen\generator\BetterNormal;
use pocketmine\block\Block;
use pocketmine\level\generator\populator\Ore;
use pocketmine\level\generator\object\OreType;
use pocketmine\math\Vector3;
use pocketmine\block\CoalOre;
use pocketmine\block\IronOre;
use pocketmine\block\RedstoneOre;
use pocketmine\block\LapisOre;
use pocketmine\block\GoldOre;
use pocketmine\block\DiamondOre;

/*
 * FloatingIslandPopulator from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */


class FloatingIslandPopulator extends AmountPopulator {
	
	/*
	 * Populate the chunk
	 * @param $level 	pocketmine\level\ChunkManager
	 * @param $chunkX 	int
	 * @param $chunkZ 	int
	 * @param $random 	pocketmine\utils\Random
	 */
	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random) {
		$this->level = $level;
		if($this->getAmount($random) > 130) {
			$x = $random->nextRange(($chunkX << 4), ($chunkX << 4) + 15);
			$z = $random->nextRange(($chunkX << 4), ($chunkX << 4) + 15);
			$y = $random->nextRange($this->getHighestWorkableBlock($x, $z) < 96 ? $this->getHighestWorkableBlock($x, $z) + 20 : $this->getHighestWorkableBlock($x, $z), 126);
			$radius = $random->nextRange(5, 8);
			$height = $this->buildIslandBottomShape($level, new Vector3($x, $y, $z), $radius, $random);
			$this->populateOres($level, new Vector3($x, $y - 1, $z), $radius * 2, $height, $random);
			$chunk = $level->getChunk($chunkX, $chunkZ);
			$biome = BetterNormal::getBiomeById($chunk->getBiomeId($x % 16, $z % 16));
			$populators = $biome->getPopulators();
			foreach($populators as $populator) {
				$populator->populate($level, $chunkX, $chunkZ, $random);
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
			$b = $this->level->getBlockIdAt ( $x, $y, $z );
			if ($b === Block::DIRT or $b === Block::GRASS or $b === Block::PODZOL or $b === Block::SAND) {
				break;
			} elseif ($b !== 0 and $b !== Block::SNOW_LAYER) {
				return 90;
			}
		}
		
		return ++ $y;
	}
	
	
	
	/*
	 * Builds a an island shape
	 * @param 	$level 		pocketmine\level\ChunkManager
	 * @param 	$pos 		pocketmine\math\Vector3
	 * @param	$radius		int
	 * @param 	$random 	pocketmine\utils\Random
	 * @return 	void
	 */
	public function buildIslandBottomShape(ChunkManager $level, Vector3 $pos, int $radius, Random $random) {
		$pos = $pos->round();
		$xx = $pos->x;
		$zz = $z;
		$currentLen = 1;
		$isEdge = false;
		$hBound = 0;
		$current = 0;
		for($y = $pos->y - 1; $radius > 0; $y--) {
			for($x = $pos->x - $radius; $x <= $pos->x + $radius; $x++) {
				for($z = $pos->z - $radius; $z <= $pos->z + $radius; $z ++) {
					if(abs(abs($x - $pos->x) ** 2) + abs(abs($z - $pos->z) ** 2) == ($radius ** 2) * 0.67) {
						$isEdge = true;
					} else {
						$isEdge = false;
					}
					if(abs(abs($x - $pos->x) ** 2) + abs(abs($z - $pos->z) ** 2) <= ($radius ** 2) * 0.67 && $y < 128) { 
						if($chunk = $level->getChunk($x >> 4, $z >> 4)) {
							$biome = BetterNormal::getBiomeById($chunk->getBiomeId($x % 16, $z % 16));
							$block = $biome->getGroundCover()[$pos->y - $y - 1] ?? Block::get(Block::STONE);
							$block = $block->getId();
						} elseif($random->nextBoundedInt(5) == 0 && $isEdge) {
							$block = Block::AIR;
						} else {
							$block = Block::STONE;
						}
						$level->setBlockIdAt($x, $y, $z, $block ?? Block::STONE);
					}
				}
			}
			$current++;
			$oldHB = $hBound;
			$hBound = $random->nextFloat();
			if($current >= $currentLen + $hBound) {
				if($radius == 0) return;
				$current = 0;
				$currentLen += 0.3 * ($random->nextFloat() + 0.5);
				$radius--;
			}
		}
		return $pos->y - 1 - $y;
	}
	
	
	
	
	/*
	 * BPopulate the island with ores
	 * @param 	$level 		pocketmine\level\ChunkManager
	 * @param 	$pos 		pocketmine\math\Vector3 
	 * @param	$width		int
	 * @param 	$height 	int
	 * @param 	$random 	pocketmine\utils\Random
	 * @return 	void
	 */
	public function populateOres(ChunkManager $level, Vector3 $pos, int $width, int $height, Random $random) {
		$ores = new Ore ();
		$ores->setOreTypes ( [
				new OreType ( new CoalOre (), 20, 16, $pos->y - $height, $pos->y ),
				new OreType ( new IronOre (), 20, 8,  $pos->y - $height, $pos->y - round($height * 0.75)),
				new OreType ( new RedstoneOre (), 8, 7,  $pos->y - $height, $pos->y - round($height / 2)),
				new OreType ( new LapisOre (), 1, 6, $pos->y - $height, $pos->y - round($height / 2)),
				new OreType ( new GoldOre (), 2, 8, $pos->y - $height, $pos->y - round($height / 2)),
				new OreType ( new DiamondOre (), 1, 7, $pos->y - $height, $pos->y - round($height / 4))
		] );
		$ores->populate($level, $x >> 4, $z >> 4, $random);
	}
}