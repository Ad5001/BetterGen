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

use pocketmine\utils\Random;
use pocketmine\level\generator\populator\Populator;

abstract class AmountPopulator extends Populator {
	protected $baseAmount = 0;
	protected $randomAmount = 0;
	
	/*
	 * Crosssoftware class for random amount
	 */
	
	/*
	 * Sets the random addition amount
	 * @param $amount int
	 */
	public function setRandomAmount(int $amount) {
		$this->randomAmount = $amount;
	}
	
	/*
	 * Sets the base addition amount
	 * @param $amount int
	 */
	public function setBaseAmount(int $amount) {
		$this->baseAmount = $amount;
	}
	
	/*
	 * Returns the amount based on random
	 * @param $random Random
	 */
	public function getAmount(Random $random) {
		return $this->baseAmount + $random->nextRange(0, $this->randomAmount + 1);
	}
	
	/*
	 * Returns the base amount
	 * @return int
	 */
	public function getBaseAmount(): int {
		return $this->baseAmount;
	}
	
	/*
	 * Returns the random additional amount
	 * @return int
	 */
	public function getRandomAmount(): int {
		return $this->randomAmount;
	}
}