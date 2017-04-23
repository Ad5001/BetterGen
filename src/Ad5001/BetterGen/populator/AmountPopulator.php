<?php

/*
 * AmountPopulator from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
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
		return $this->baseAmount + $random->nextRange ( 0, $this->randomAmount + 1 );
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