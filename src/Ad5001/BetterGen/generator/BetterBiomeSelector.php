<?php

/*
 * BetterBiomeSelector from BetterGen
 * Copyright (C) Ad5001 2017
 * Licensed under the BoxOfDevs Public General LICENSE which can be found in the file LICENSE in the root directory
 * @author ad5001
 */
namespace Ad5001\BetterGen\generator;

use pocketmine\level\generator\biome\BiomeSelector;
use pocketmine\level\generator\biome\Biome;
use pocketmine\utils\Random;
use pocketmine\level\generator\noise\Simplex;

class BetterBiomeSelector extends BiomeSelector {
	
	/** @var Biome */
	private $fallback;
	
	/** @var Simplex */
	private $temperature;
	/** @var Simplex */
	private $rainfall;
	
	/** @var Biome[] */
	private $biomes = [ ];
	private $map = [ ];
	private $lookup;
	public function __construct(Random $random, callable $lookup, Biome $fallback) {
		$this->fallback = $fallback;
		$this->lookup = $lookup;
		$this->temperature = new Simplex ( $random, 2, 1 / 16, 1 / 512 );
		$this->rainfall = new Simplex ( $random, 2, 1 / 16, 1 / 512 );
	}
	public function recalculate() {
	} // Using our own system, No need for that
	public function addBiome(Biome $biome) {
		$this->biomes [$biome->getId ()] = $biome;
	}
	public function getTemperature($x, $z) {
		return ($this->temperature->noise2D ( $x, $z, true ) + 1) / 2;
	}
	public function getRainfall($x, $z) {
		return ($this->rainfall->noise2D ( $x, $z, true ) + 1) / 2;
	}
	
	/**
	 *
	 * @param
	 *        	$x
	 * @param
	 *        	$z
	 *        	
	 * @return Biome
	 */
	public function pickBiome($x, $z) {
		$temperature = ($this->getTemperature ( $x, $z ));
		$rainfall = ($this->getRainfall ( $x, $z ));
		
		$biomeId = BetterNormal::getBiome ( $temperature, $rainfall );
		// $biomeId = new \Ad5001\BetterGen\biome\BetterDesert();
		$b = (($biomeId instanceof Biome) ? $biomeId : ($this->biomes [$biomeId] ?? $this->fallback));
		return $b;
	}
}