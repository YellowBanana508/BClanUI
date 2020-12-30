<?php

namespace Clan;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\level\particle\{DustParticle, FlameParticle, FloatingTextParticle, EntityFlameParticle, CriticalParticle, ExplodeParticle, HeartParticle, LavaParticle, MobSpawnParticle, SplashParticle};
use pocketmine\event\player\PlayerMoveEvent;

class Main extends PluginBase implements Listener {
	
	public $plugin;

	public function onEnable(){
		$this->getLogger()->info("Enable");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");	
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command is available in-game only!");
            return false;
        }
  }
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "clanui":
            $this->FormClan($sender);
            return true;
        }
        return true;
	}
	
	 public function FormClan($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              $sender->addTitle("§aExiting.....");
              break;
              case 1:
			  $this->Create($sender);
			  break;
			  case 2:
			  $this->Demote($sender);
			  break;
			  case 3:
		      $this->Deposit($sender);
			  break;
			  case 4:
			  $this->Info($sender);
			  break;
			  case 5:
			  $this->Invite($sender);
			  break;
			  case 6:
			  $this->Accept($sender);
			  break;
			  case 7:
			  $this->Join($sender);
			  break;
			  case 8:
			  $command = "clan leave";
			  $this->getServer()->getCommandMap()->dispatch($sender, $command);
			  break;
              case 9:
              $this->Leader($sender);
              break;
              case 10:
              $this->Chat($sender);
              break;
              case 11:
              $this->Kick($sender);
              break;
              case 12:
			  $command = "clan delete";
			  $this->getServer()->getCommandMap()->dispatch($sender, $command);
			  break;
			  case 13:
			  $this->Promote($sender);
			  break;
			  case 14:
			  $this->Withdraw($sender);
			  break;
          }
        });
        $config = $this->getConfig();
        $name = $sender->getName();
        $form->setTitle("§c§lManage your Clan");
        $form->setContent("How can we help you?");
		$form->addButton("§c§lExit");
		$form->addButton("§c§lCreate Clan");
		$form->addButton("§c§lDemote Member");
		$form->addButton("§c§lDeposit Money");
		$form->addButton("§a§lClan Info");
		$form->addButton("§a§lInvite Member");
		$form->addButton("§6§lAccept Clan Invitation");
        $form->addButton("§a§lSwitch Chat");
		$form->addButton("§e§lJoin Clan\nOnly for staff!");
        $form->addButton("§a§lLeave Clan");
        $form->addButton("§a§lPromote Memberto Leader");
		$form->addButton("§b§lSend message to Clan");
		$form->addButton("§d§lKick a Member");
		$form->addButton("§c§lDelete Clan");
		$form->addButton("§c§lPromote a Member");
		$form->addButton("§c§lWithdraw Money");
        $form->sendToPlayer($sender);
	}
	
	public function Create($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan create $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fCreate Clan");
		$form->addInput("§b*Enter your clan's name!");
		$form->sendToPlayer($player);
	}
	public function Demote($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan demote $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fDemote a Member");
		$form->addInput("§b*Enter the name of player!");
		$form->sendToPlayer($player);
	}
	public function Deposit($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan deposit $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fDeposit Money");
		$form->addInput("§b*Enter amount of money to deposit!");
		$form->sendToPlayer($player);
	}
	public function Info($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan info $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fClan Information");
		$form->addInput("§b*Enter clan's name!");
		$form->sendToPlayer($player);
	}
	public function Invite($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan invite $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fCreate Clan");
		$form->addInput("§b*Enter the name of player!");
		$form->sendToPlayer($player);
	}
	public function Join($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan join $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fCreate Clan");
		$form->addInput("§b*Enter clan's name!");
		$form->sendToPlayer($player);
	}
	public function Leader($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan leader $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fMake Leader");
		$form->addInput("§b*Enter the name of player to promote!");
		$form->sendToPlayer($player);
	}
	public function Chat($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan chat $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fChat in Clan");
		$form->addInput("§b*Enter the message to send to clan!");
		$form->sendToPlayer($player);
	}
	public function Kick($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan kick $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fKick a Member");
		$form->addInput("§b*Enter the name of player to kick!");
		$form->sendToPlayer($player);
	}
	public function Promote($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan promote $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fPromote a Member!");
		$form->addInput("§b*Enter the name of player to promote!");
		$form->sendToPlayer($player);
	}
	public function Withdraw($player){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createCustomForm(function(Player $player, $data){
			$result = $data[0];
			if($result === null){
				return true;
			}
			$cmd = "clan withdraw $data[0]";
			$this->getServer()->getCommandMap()->dispatch($player, $cmd);
		});
		$form->setTitle("§fWithdraw Money");
		$form->addInput("§b*Enter amount of money to withdraw!");
		$form->sendToPlayer($player);
	}
}
