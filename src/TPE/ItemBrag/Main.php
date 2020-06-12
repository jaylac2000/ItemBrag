<?php

namespace TPE\ItemBrag;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\item\enchantment\EnchantmentInstance;

class Main extends PluginBase implements Listener {
    private $myEnchants = [0 => "Protection",
        1 => "Fire_protection",
        2 => "Feather_falling",
        3 => "Blast_projection",
        4 => "Projectile_projection",
        5 => "thorns",
        6 => "Respiration",
        7 => "Depth_Strider",
        8 => "Aqua_affinity",
        9 => "Sharpness",
        10 => "Smite",
        11 => "Bane_of_arthropods",
        12 => "Knockback",
        13 => "Fire_aspect",
        14 => "Looting",
        15 => "Efficiency",
        16 => "Silk_touch",
        17 => "Unbreaking",
        18 => "Fortune",
        19 => "Power",
        20 => "Punch",
        21 => "Flame",
        22 => "Infinity",
        23 => "Luck_of_the_sea",
        24 => "Lure"];



    public $config;

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->config = $this->getConfig();
        $this->saveDefaultConfig();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
          if($this->config->get("brag-disabled") == false) {
            if($command->getName() == "brag") {
                if($sender instanceof Player) {
                    if ($sender->hasPermission("brag.allow")) {
                        $itemb = $sender->getInventory()->getItemInHand();
                        if($itemb->isNull()) {
                            $sender->sendMessage($this->config->get("message-sent-to-player-when-hand-is-empty"));
                            return false;
                        }
                        $item = $sender->getInventory()->getItemInHand()->getName();
                        $player = $sender->getName();
                        $itemc = $sender->getInventory()->getItemInHand()->getCount();

                        $enchantmentNames = array_map(function(EnchantmentInstance $enchantment) : string{
                            $translation = $this->myEnchants[$enchantment->getId()] ?? "";
                            if($translation !== ""){
                                return $this->getServer()->getLanguage()->translateString($translation);
                            }
                            return $enchantment->getType()->getName();
                        }, $itemb->getEnchantments());

                        $msg = "Enchants: ";
                    $itemb->getEnchantments();
                        $enchantmentLevels = array_map(function(EnchantmentInstance $enchantment) : int{
                            return $enchantment->getLevel();
                        },$itemb->getEnchantments());
                        $newArray = [];

                        for($i = 0; $i < count($enchantmentNames); $i++) {
                            $newArray[$i] = [$enchantmentNames[$i], $enchantmentLevels[$i]];
                        }
                        $message = TextFormat::YELLOW . "Enchants: ";
                        for($i = 0; $i < count($newArray); $i++) {
                            $message .= TextFormat::LIGHT_PURPLE . " {$newArray[$i][0]}: {$newArray[$i][1]}, ";
                        }

                        if($itemb->hasEnchantments()) {
                            if($sender->hasPermission("brag.allow.enchants")){
                                $this->getServer()->broadcastMessage(TextFormat::BOLD . TextFormat::GOLD . TextFormat::ITALIC . "BRAG " . TextFormat::RESET . TextFormat::AQUA . TextFormat::BOLD . "$player " . TextFormat::RESET . TextFormat::GREEN . "is bragging about " . TextFormat::BOLD . TextFormat::GREEN . "X" . TextFormat::RESET . TextFormat::GREEN . "$itemc " . TextFormat::RESET . TextFormat::GREEN . "of " . TextFormat::RESET . TextFormat::BOLD . "$item" . "\n" . TextFormat::RESET . "$message");

                            } else {
                                $sender->sendMessage($this->config->get("no-perms-message-enchants"));

                            }
                        }
                        if(!$itemb->hasEnchantments()) {
                            $this->getServer()->broadcastMessage(TextFormat::BOLD . TextFormat::GOLD . TextFormat::ITALIC . "BRAG " . TextFormat::RESET . TextFormat::AQUA . TextFormat::BOLD . "$player " . TextFormat::RESET . TextFormat::GREEN . "is bragging about " . TextFormat::BOLD . TextFormat::GREEN . "X" . TextFormat::RESET . TextFormat::GREEN . "$itemc " . TextFormat::RESET . TextFormat::GREEN . "of " . TextFormat::RESET . TextFormat::BOLD . "$item");
                        }

                    } else {
                        $sender->sendMessage($this->config->get("no-perms-message"));

                    }

                } else {
                    $sender->sendMessage("You can not run this command via console");
                }
            }
        } else {
            $sender->sendMessage($this->config->get("brag-feature-disabled-message"));
        }
          return true;
    }

}
// TextFormat::YELLOW . TextFormat::BOLD . $msg . TextFormat::RESET . TextFormat::LIGHT_PURPLE . implode(", ", $enchantmentNames));