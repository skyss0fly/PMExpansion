<?php

declare(strict_types=1);

namespace skh6075\pmexpansion;

use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\AsyncTask;
use skh6075\pmexpansion\expansion\BlockExpansion;

final class Loader extends PluginBase{
	public const VERSION_TYPE = "Development";

	protected function onEnable() : void{
		if(self::VERSION_TYPE === "Development"){
			$this->getLogger()->warning("The system version you are using is the one in development.");
			$this->getLogger()->warning("We are not responsible for any problems that arise when using this version.");
			$this->getLogger()->warning("Please use the latest version that has been developed. Link: https://github.com/skh6075/PMExpansion/tree/pm5");
		}

		$this->synchronizeExpansions();
	}

	private function synchronizeExpansions(): void{
		BlockExpansion::synchronize();

		$this->getServer()->getAsyncPool()->addWorkerStartHook(function(int $worker): void{
			$this->getServer()->getAsyncPool()->submitTaskToWorker(new class extends AsyncTask{
				public function onRun() : void{
					BlockExpansion::synchronize();
				}
			}, $worker);
		});
	}
}