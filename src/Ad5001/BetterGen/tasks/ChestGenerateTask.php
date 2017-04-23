<?php

namespace Ad5001\BetterGen\tasks;

use Ad5001\BetterGen\Main;
use Ad5001\BetterGen\loot\LootTable;
use pocketmine\block\Block;
use pocketmine\scheduler\PluginTask;
use pocketmine\level\Position;
use pocketmine\item\Item;

/*
 * ChestGenerateTask from BetterGen
 * Copyright (C) Ad5001 2017
 * 
 * @author Ad5001
 * @link https://en.ad5001.eu
 */


class ChestGenerateTask extends PluginTask {
	protected $block;
	protected $pos;
	protected $item;
	
	/*
	 * Constructs the class
	 */
	public function __construct(Main $main, Block $block, Position $pos, Item $item) {
		parent::__construct($main);
		$this->block = $block;
		$this->pos = $pos;
		$this->item = $item;
	}
	
	/*
	 * Runs when the delay comes
	 */
	public function onRun($currentTick) {
		$this->block->place($this->item, $this->pos->getLevel()->getBlock($this->pos), $this->block, 0, 0, 0, 0);
		$inv = $this->pos->getLevel()->getTile($this->pos);
		LootTable::fillChest($inv->getInventory(), $this->pos);
	}
}