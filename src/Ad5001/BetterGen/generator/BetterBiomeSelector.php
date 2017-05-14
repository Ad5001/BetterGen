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

namespace Ad5001\BetterGen\generator;

use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\biome\BiomeSelector;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\utils\Random;

class BetterBiomeSelector extends BiomeSelector {

	/** @var Biome */
	private $fallback;

	/** @var Simplex */
	private $temperature;
	/** @var Simplex */
	private $rainfall;

	/** @var Biome[] */
	private $biomes = [];
	private $lookup;

	public function __construct(Random $random, callable $lookup, Biome $fallback) {
		parent::__construct($random, $lookup, $fallback);
		$this->fallback = $fallback;
		$this->lookup = $lookup;
		$this->temperature = new Simplex($random, 2, 1 / 16, 1 / 512);
		$this->rainfall = new Simplex($random, 2, 1 / 16, 1 / 512);
	}

	public function recalculate() {
	} // Using our own system, No need for that

	public function addBiome(Biome $biome) {
		$this->biomes[$biome->getId()] = $biome;
	}

	/**
	 *
	 * @param
	 *            $x
	 * @param
	 *            $z
	 *
	 * @return Biome
	 */
	public function pickBiome($x, $z) {
		$temperature = ($this->getTemperature($x, $z));
		$rainfall = ($this->getRainfall($x, $z));

		$biomeId = BetterNormal::getBiome($temperature, $rainfall);
		$b = (($biomeId instanceof Biome) ? $biomeId : ($this->biomes[$biomeId] ?? $this->fallback));
		return $b;
	}

	public function getTemperature($x, $z) {
		return ($this->temperature->noise2D($x, $z, true) + 1) / 2;
	}

	public function getRainfall($x, $z) {
		return ($this->rainfall->noise2D($x, $z, true) + 1) / 2;
	}
}