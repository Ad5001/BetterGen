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
use Ad5001\BetterGen\populator\BushPopulator;
use Ad5001\BetterGen\populator\FallenTreePopulator;
use Ad5001\BetterGen\populator\TreePopulator;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\normal\biome\ForestBiome;


class BetterForest extends ForestBiome implements Mountainable {
	static $types = [
		"Oak Forest",
		"Birch Forest",
		"Sakura Forest"
	];
	static $ids = [
		Biome::FOREST,
		Biome::BIRCH_FOREST,
		Main::SAKURA_FOREST
	];

	public function __construct($type = 0, array $infos = [0.6, 0.5]) {
		parent::__construct($type);
		$this->clearPopulators();

		$this->type = $type;

		$bush = new BushPopulator($type);
		$bush->setBaseAmount(10);
		if (!\Ad5001\BetterGen\utils\CommonUtils::in_arrayi("Bushes", BetterNormal::$options["delStruct"])) $this->addPopulator($bush);

		$ft = new FallenTreePopulator($type);
		$ft->setBaseAmount(0);
		$ft->setRandomAmount(4);
		if (!\Ad5001\BetterGen\utils\CommonUtils::in_arrayi("FallenTrees", BetterNormal::$options["delStruct"])) $this->addPopulator($ft);

		$trees = new TreePopulator($type);
		$trees->setBaseAmount((null !== @constant(TreePopulator::$types[$type] . "::maxPerChunk")) ? constant(TreePopulator::$types[$type] . "::maxPerChunk") : 5);
		if (!\Ad5001\BetterGen\utils\CommonUtils::in_arrayi("Trees", BetterNormal::$options["delStruct"])) $this->addPopulator($trees);

		$tallGrass = Main::isOtherNS() ? new \pocketmine\level\generator\normal\populator\TallGrass () : new \pocketmine\level\generator\populator\TallGrass();
		$tallGrass->setBaseAmount(3);

		if (!\Ad5001\BetterGen\utils\CommonUtils::in_arrayi("TallGrass", BetterNormal::$options["delStruct"])) $this->addPopulator($tallGrass);

		$this->setElevation(63, 69);

		$this->temperature = $infos[0];
		$this->rainfall = $infos[1];
	}

	/**
	 * @param string $name
	 * @param string $treeClass
	 * @param array $infos
	 * @return bool
	 */
	public static function registerForest(string $name, string $treeClass, array $infos): bool {
		self::$types[] = str_ireplace("tree", "", explode("\\", $treeClass)[count(explode("\\", $treeClass))]) . " Forest";
		TreePopulator::$types[] = $treeClass;
		self::$ids[] = Main::SAKURA_FOREST + (count(self::$types) - 2);
		Main::register(Main::SAKURA_FOREST + (count(self::$types) - 2), new BetterForest(count(self::$types) - 1, $infos));
		return true;
	}

	public function getName() {
		return str_ireplace(" ", "", self::$types[$this->type]);
	}

	/**
	 * Returns the ID relatively.
	 * @return int
	 */
	public function getId() {
		return self::$ids[$this->type];
	}
}