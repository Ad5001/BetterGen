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

namespace Ad5001\BetterGen\biome;

use Ad5001\BetterGen\Main;
use Ad5001\BetterGen\populator\IglooPopulator;
use pocketmine\block\Block;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\normal\biome\SnowyBiome;

class BetterIcePlains extends SnowyBiome implements Mountainable {
	public function __construct() {
		parent::__construct ();
		$this->setGroundCover([ 
				Block::get(Block::SNOW, 0),
				Block::get(Block::GRASS, 0),
				Block::get(Block::DIRT, 0),
				Block::get(Block::DIRT, 0),
				Block::get(Block::DIRT, 0) 
		]);
		$this->addPopulator(new IglooPopulator ());
				
		$tallGrass = Main::isOtherNS() ? new \pocketmine\level\generator\normal\populator\TallGrass() : new \pocketmine\level\generator\populator\TallGrass();
		$tallGrass->setBaseAmount(3);
		
		$this->addPopulator($tallGrass);
		
		$this->setElevation(63, 74);
		
		$this->temperature = 0.05;
		$this->rainfall = 0.8;
	}
	public function getName() {
		return "BetterIcePlains";
	}
	
	/**
	 * Returns the biomes' id.
	 * @return int biome id
	 */
	public function getId() {
		return Biome::ICE_PLAINS;
	}
}