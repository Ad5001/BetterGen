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

use Ad5001\BetterGen\generator\BetterNormal;
use Ad5001\BetterGen\Main;
use pocketmine\block\Block;
use pocketmine\block\CoalOre;
use pocketmine\block\DiamondOre;
use pocketmine\block\GoldOre;
use pocketmine\block\IronOre;
use pocketmine\block\LapisOre;
use pocketmine\block\RedstoneOre;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\normal\object\OreType as OreType2;
use pocketmine\level\generator\object\OreType;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;


class FloatingIslandPopulator extends AmountPopulator {
	/** @var ChunkManager */
	protected $level;

	/**
	 * Populates the chunk
	 *
	 * @param ChunkManager $level
	 * @param int $chunkX
	 * @param int $chunkZ
	 * @param Random $random
	 * @return void
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
			$biome = BetterNormal::$biomeById[$chunk->getBiomeId($x % 16, $z % 16)];
			$populators = $biome->getPopulators();
			foreach($populators as $populator) {
				$populator->populate($level, $chunkX, $chunkZ, $random);
			}
		} 
	}
	
	
	
	/**
	 * Gets the top block (y) on an x and z axes
	 * @param int $x
	 * @param int $z
	 */
	protected function getHighestWorkableBlock($x, $z) {
		for($y = Level::Y_MAX - 1; $y > 0; -- $y) {
			$b = $this->level->getBlockIdAt($x, $y, $z);
			if ($b === Block::DIRT or $b === Block::GRASS or $b === Block::PODZOL or $b === Block::SAND) {
				break;
			} elseif ($b !== 0 and $b !== Block::SNOW_LAYER) {
				return 90;
			}
		}
		
		return ++$y;
	}
	
	
	
	/**
	 * Builds an island bottom shape
	 *
	 * @param ChunkManager $level
	 * @param Vector3 $pos
	 * @param int $radius
	 * @param Random $random
	 * @return int Bottom place of the island
	 */
	public function buildIslandBottomShape(ChunkManager $level, Vector3 $pos, int $radius, Random $random) {
		$pos = $pos->round();
		$currentLen = 1;
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
							$biome = BetterNormal::$biomeById[$chunk->getBiomeId($x % 16, $z % 16)];
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
				if($radius == 0) return $pos->y;
				$current = 0;
				$currentLen += 0.3 * ($random->nextFloat() + 0.5);
				$radius--;
			}
		}
		return $pos->y - 1 - $y;
	}
	
	
	
	
	/**
	 * Populates the island with ores
	 *
	 * @param ChunkManager $level
	 * @param Vector3 $pos
	 * @param int $width
	 * @param int $height
	 * @param Random $random
	 * @return void
	 */
	public function populateOres(ChunkManager $level, Vector3 $pos, int $width, int $height, Random $random) {
		$ores = Main::isOtherNS() ? new \pocketmine\level\generator\normal\populator\Ore() : new \pocketmine\level\generator\populator\Ore();
		if(Main::isOtherNS()) $ores->setOreTypes([
				new OreType2(new CoalOre (), 20, 16, $pos->y - $height, $pos->y),
				new OreType2(new IronOre (), 20, 8,  $pos->y - $height, $pos->y - round($height * 0.75)),
				new OreType2(new RedstoneOre (), 8, 7,  $pos->y - $height, $pos->y - round($height / 2)),
				new OreType2(new LapisOre (), 1, 6, $pos->y - $height, $pos->y - round($height / 2)),
				new OreType2(new GoldOre (), 2, 8, $pos->y - $height, $pos->y - round($height / 2)),
				new OreType2(new DiamondOre (), 1, 7, $pos->y - $height, $pos->y - round($height / 4))
		]);
		if(!Main::isOtherNS()) $ores->setOreTypes([
				new OreType(new CoalOre (), 20, 16, $pos->y - $height, $pos->y),
				new OreType(new IronOre (), 20, 8,  $pos->y - $height, $pos->y - round($height * 0.75)),
				new OreType(new RedstoneOre (), 8, 7,  $pos->y - $height, $pos->y - round($height / 2)),
				new OreType(new LapisOre (), 1, 6, $pos->y - $height, $pos->y - round($height / 2)),
				new OreType(new GoldOre (), 2, 8, $pos->y - $height, $pos->y - round($height / 2)),
				new OreType(new DiamondOre (), 1, 7, $pos->y - $height, $pos->y - round($height / 4))
		]);
		$ores->populate($level, $pos->x >> 4, $pos->z >> 4, $random);//x z undefined
	}
}