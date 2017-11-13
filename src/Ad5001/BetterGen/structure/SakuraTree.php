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

namespace Ad5001\BetterGen\structure;

use Ad5001\BetterGen\Main;
use pocketmine\block\Block;
use pocketmine\block\Wood;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\Tree;
use pocketmine\utils\Random;

if (Main::isOtherNS()) {
	class_alias("pocketmine\\level\\generator\\normal\\object\\Tree", "Ad5001\\BetterGen\\structure\\Tree");
} else {
	class_alias("pocketmine\\level\\generator\\object\\Tree", "Ad5001\\BetterGen\\structure\\Tree");
}

class SakuraTree extends Tree {
	const TRUNK_POS = [  // Checks for trees trunks. Not automatically generated but there is no point of making more or less
		7 => [
			0,
			1
		], // 0 vertical, 1 horizontal and same goes for others...
		8 => [
			1,
			0
		],
		9 => [
			1,
			1
		],
		10 => [
			2,
			0
		],
		11 => [
			2,
			1
		],
		12 => [
			2,
			2
		],
		13 => [
			3,
			1
		]
	];
	const DIAG_LEAVES = [  // Diag poses of the leaves based on the height of the tree.X relative to $lastX and Z from $lastZ
		7 => [
			[
				4,
				4
			],
			[
				-4,
				4
			],
			[
				4,
				-4
			],
			[
				-4,
				-4
			]
		],
		8 => [
			[
				6,
				6
			],
			[
				-6,
				6
			],
			[
				6,
				-6
			],
			[
				-6,
				-6
			]
		],
		9 => [
			[
				6,
				6
			],
			[
				-6,
				6
			],
			[
				6,
				-6
			],
			[
				-6,
				-6
			]
		],
		10 => [
			[
				6,
				6
			],
			[
				-6,
				6
			],
			[
				6,
				-6
			],
			[
				-6,
				-6
			]
		],
		11 => [
			[
				7,
				7
			],
			[
				6,
				8
			],
			[
				8,
				6
			],
			[
				-7,
				7
			],
			[
				-6,
				8
			],
			[
				-8,
				6
			],
			[
				7,
				-7
			],
			[
				6,
				-8
			],
			[
				8,
				-6
			],
			[
				-7,
				-7
			],
			[
				-6,
				-8
			],
			[
				-8,
				-6
			]
		],
		12 => [
			[
				7,
				7
			],
			[
				6,
				8
			],
			[
				8,
				6
			],
			[
				-7,
				7
			],
			[
				-6,
				8
			],
			[
				-8,
				6
			],
			[
				7,
				-7
			],
			[
				6,
				-8
			],
			[
				8,
				-6
			],
			[
				-7,
				-7
			],
			[
				-6,
				-8
			],
			[
				-8,
				-6
			]
		],
		13 => [
			[
				7,
				7
			],
			[
				6,
				8
			],
			[
				8,
				6
			],
			[
				-7,
				7
			],
			[
				-6,
				8
			],
			[
				-8,
				6
			],
			[
				7,
				-7
			],
			[
				6,
				-8
			],
			[
				8,
				-6
			],
			[
				-7,
				-7
			],
			[
				-6,
				-8
			],
			[
				-8,
				-6
			]
		]
	];
	const ADDITIONAL_BLOCKS = [  // Blocks who aren't set to fully fill the tree. X relative to $lastX and Z from $lastZ
		7 => [],
		8 => [],
		9 => [],
		10 => [],
		11 => [
			[
				6,
				6
			],
			[
				7,
				6
			],
			[
				6,
				7
			],
			[
				-6,
				6
			],
			[
				-7,
				6
			],
			[
				-6,
				7
			],
			[
				6,
				-6
			],
			[
				7,
				-6
			],
			[
				6,
				-7
			],
			[
				-6,
				-6
			],
			[
				-7,
				-6
			],
			[
				-6,
				-7
			]
		],
		12 => [
			[
				6,
				6
			],
			[
				7,
				6
			],
			[
				6,
				7
			],
			[
				-6,
				6
			],
			[
				-7,
				6
			],
			[
				-6,
				7
			],
			[
				6,
				-6
			],
			[
				7,
				-6
			],
			[
				6,
				-7
			],
			[
				-6,
				-6
			],
			[
				-7,
				-6
			],
			[
				-6,
				-7
			]
		],
		13 => [
			[
				6,
				6
			],
			[
				7,
				6
			],
			[
				6,
				7
			],
			[
				-6,
				6
			],
			[
				-7,
				6
			],
			[
				-6,
				7
			],
			[
				6,
				-6
			],
			[
				7,
				-6
			],
			[
				6,
				-7
			],
			[
				-6,
				-6
			],
			[
				-7,
				-6
			],
			[
				-6,
				-7
			]
		]
	];
	const maxPerChunk = 2;

	/** @var int */
	public $trunkHeight = 11;
	/** @var int */
	public $leafType;
	/** @var int */
	public $leaf2Type;

	/**
	 * Constructs the class
	 */
	public function __construct() {
		$this->trunkBlock = Block::LOG;
		$this->leafBlock = Block::AIR; // To remove bushes
		$this->realLeafBlock = Block::WOOL;
		$this->leafType = 6;
		$this->leaf2Type = 0;
		$this->type = Wood::OAK;
	}

	/**
	 * Builds a tree
	 *
	 * @param ChunkManager $level
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param Random $random
	 * @return void
	 */
	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random) {
		$this->random = $random;
		$percentage = $random->nextBoundedInt(100);
		if ($percentage > 10) {
			return;
		}
		$trunkHeight = 7 + $random->nextBoundedInt(7);

		$xDiff = $zDiff = 0;

		$direction = $random->nextBoundedInt(3); // Choosing building north east west south
		switch ($direction) {
			case 0 :
				$xDiff = 0;
				$zDiff = -1;
				break;
			case 1 :
				$xDiff = 0;
				$zDiff = 1;
				break;
			case 2 :
				$xDiff = -1;
				$zDiff = 0;
				break;
			case 3 :
				$xDiff = 1;
				$zDiff = 0;
				break;
		}
		list($vParts, $hParts) = self::TRUNK_POS[$trunkHeight];

		$this->setLog($level, $x, $y, $z);
		list($lastX, $lastY, $lastZ) = [
			$x,
			$y,
			$z
		];

		// Filling horizontally
		if ($hParts > 0) {
			for ($i = 0; $i < $hParts; $i++) {
				$lastX += $xDiff * 2;
				$lastY++;
				$lastZ += $zDiff * 2;
				$this->setLog($level, $lastX - $xDiff, $lastY, $lastZ - $zDiff);
				$this->setLog($level, $lastX, $lastY, $lastZ);
			}
		}

		// The middle block
		$lastX += $xDiff;
		$lastY++;
		$lastZ += $zDiff;
		$this->setLog($level, $lastX, $lastY, $lastZ);

		// Filling vertically
		if ($vParts > 0) {
			for ($i = 0; $i < $vParts; $i++) {
				$lastX += $xDiff;
				$lastY += 2;
				$lastZ += $zDiff;
				$this->setLog($level, $lastX, $lastY - 1, $lastZ);
				$this->setLog($level, $lastX, $lastY, $lastZ);
			}
		}
		// Filling the top trunk.
		$lastX += $xDiff;
		$lastY++;
		$lastZ += $zDiff;
		for ($i = 0; $i < 4; $i++) {
			$this->setLog($level, $lastX, $lastY + $i, $lastZ);
		}
		$lastY++;
		// Filling the branches.
		$branchLen2 = function ($base) {
			return ceil($base / 2);
		};

		$xd = $zd = 0;

		for ($dir = 0; $dir < 4; $dir++) {
			switch ($dir) {
				case 0 :
					$xd = 0;
					$zd = -1;
					break;
				case 1 :
					$xd = 0;
					$zd = 1;
					break;
				case 2 :
					$xd = -1;
					$zd = 0;
					break;
				case 3 :
					$xd = 1;
					$zd = 0;
					break;
			}

			$stickLen = round($trunkHeight / 3);
			$stickLen2 = call_user_func($branchLen2, $stickLen);
			$totalLength = $stickLen + $stickLen2; // Length of the stick
			$sideLen = $totalLength ** 2; // Side length

			$numForward = ($totalLength % 2 == 0) ? $totalLength - 1 : $totalLength;
			$lX1 = $lZ1 = $lX = $lZ = 0;

			// First branch part + first leave part
			for ($i = 1; $i < $stickLen + 1; $i++) {
				$lX1 = $lastX + ($xd * $i);
				$lZ1 = $lastZ + ($zd * $i);
				if ($zd !== 0)
					for ($x = $lX1 - $numForward; $x !== $lX1 + $numForward + 1; $x++) {
						$this->setLeave($level, $x, $lastY + 3, $lZ1, $random);
					}
				if ($xd !== 0)
					for ($z = $lZ1 - $numForward; $z !== $lZ1 + $numForward + 1; $z++) {
						$this->setLeave($level, $lX1, $lastY + 3, $z, $random);
					}
				$this->setLog($level, $lX1, $lastY, $lZ1);
			}

			// Second branch part. + second leave part
			for ($i = 1; $i < $stickLen + 1; $i++) {
				$lX = $lX1 + ($xd * $i);
				$lZ = $lZ1 + ($zd * $i);
				if ($zd !== 0)
					for ($x = $lX - $numForward; $x !== $lX + $numForward + 1; $x++) {
						$this->setLeave($level, $x, $lastY + 2, $lZ, $random);
					}
				if ($xd !== 0)
					for ($z = $lZ - $numForward; $z !== $lZ + $numForward + 1; $z++) {
						$this->setLeave($level, $lX, $lastY + 2, $z, $random);
					}
				$this->setLog($level, $lX, $lastY + 1, $lZ);
			}

			$lX += $xd;
			$lZ += $zd;
			// Leaves falling from the tree forward
			if ($lastZ !== $lZ) { // Z has changed, using X for setting
				for ($x = $lX - $numForward; $x <= $lX + $numForward; $x++) {
					$numDown = $random->nextBoundedInt(3) + 1;
					for ($y = $lastY + 1; $y > $lastY - $numDown; $y--)
						$this->setLeave($level, $x, $y, $lZ, $random);
				}
			} else { // Z have stayed, X has changed
				for ($z = $lZ - $numForward; $z <= $lZ + $numForward; $z++) {
					$numDown = $random->nextBoundedInt(3) + 1;
					for ($y = $lastY + 1; $y > $lastY + 1 - $numDown; $y--)
						$this->setLeave($level, $lX, $y, $z, $random);
				}
			}

			switch ($dir + 1) {
				case 4 :
					$xd2 = 0;
					$zd2 = -1;
					break;
				case 1 :
					$xd2 = 0;
					$zd2 = 1;
					break;
				case 2 :
					$xd2 = -1;
					$zd2 = 0;
					break;
				case 3 :
					$xd2 = 1;
					$zd2 = 0;
					break;
			}

			// Leaves falling from the tree diagonally
			foreach (self::DIAG_LEAVES[$trunkHeight] as $pos) {
				$numDown = $random->nextBoundedInt(3) + 1;
				for ($y = $lastY + 1; $y > $lastY - $numDown; $y--)
					$this->setLeave($level, $lastX + $pos[0], $y, $lastZ + $pos[1], $random);
			}

			// Additional leaves
			foreach (self::ADDITIONAL_BLOCKS[$trunkHeight] as $pos) {
				$this->setLeave($level, $lastX + $pos[0], $lastY + 2, $lastZ + $pos[1], $random);
			}
		}
	}

	/**
	 * Fills a log
	 *
	 * @param ChunkManager $level
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @return void
	 */
	public function setLog(ChunkManager $level, $x, $y, $z) {
		$level->setBlockIdAt($x, $y, $z, $this->trunkBlock);
		$level->setBlockDataAt($x, $y, $z, $this->type);
		if($this->random->nextBoundedInt(3) == 0){ // Setting a log near.
			$x += $this->random->nextBoundedInt(3) - 1;
			$z += $this->random->nextBoundedInt(3) - 1;
			$level->setBlockIdAt($x, $y, $z, $this->trunkBlock);
			$level->setBlockDataAt($x, $y, $z, $this->type);
		}
	}

	/**
	 * Fills leaves
	 *
	 * @param ChunkManager $level
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param Random $random
	 * @return void
	 */
	public function setLeave(ChunkManager $level, $x, $y, $z, Random $random) {
		$data = [
			$this->leafType,
			$this->leaf2Type
		] [( int)$random->nextBoolean()];
		$level->setBlockIdAt($x, $y, $z, $this->realLeafBlock);
		$level->setBlockDataAt($x, $y, $z, $data);
	}
}