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

namespace Ad5001\BetterGen\biome;

use pocketmine\block\Block;
use pocketmine\level\generator\biome\Biome;

class BetterRiver extends Biome {
	/**
	 * Constructs the class
	 */
	public function __construct() {
		$this->clearPopulators ();
		
		$this->setGroundCover([ 
				Block::get(Block::SAND, 0),
				Block::get(Block::SAND, 0),
				Block::get(Block::SAND, 0),
				Block::get(Block::SANDSTONE, 0),
				Block::get(Block::SANDSTONE, 0),
				Block::get(Block::SANDSTONE, 0),
				Block::get(Block::SANDSTONE, 0),
				Block::get(Block::SANDSTONE, 0),
				Block::get(Block::SANDSTONE, 0),
				Block::get(Block::SANDSTONE, 0),
				Block::get(Block::SANDSTONE, 0) 
		]);
		
		$this->setElevation(60, 60);
		
		$this->temperature = 0.5;
		$this->rainfall = 0.7;
	}

	/**
	 * Returns the biome name
	 *
	 * @return string
	 */
	public function getName(): string {
		return "BetterRiver";
	}
	
	/**
	 * Returns the biome id
	 *
	 * @return int
	 */
	public function getId(): int {
		return Biome::RIVER;
	}
}