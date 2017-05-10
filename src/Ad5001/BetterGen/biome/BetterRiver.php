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

namespace Ad5001\BetterGen\biome;

use pocketmine\level\generator\biome\Biome;
use pocketmine\block\Block;

class BetterRiver extends Biome {
	public function __construct() {
		$this->clearPopulators ();
		
		$this->setGroundCover([ 
				Block::get(Block::SAND, 0 ),
				Block::get(Block::SAND, 0 ),
				Block::get(Block::SAND, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ),
				Block::get(Block::SANDSTONE, 0 ) 
		]);
		
		$this->setElevation(60, 60);
		
		$this->temperature = 0.5;
		$this->rainfall = 0.7;
	}
	public function getName() {
		return "BetterRiver";
	}
	
	/*
	 * Returns the ID relativly.
	 */
	public function getId() {
		return Biome::RIVER;
	}
}