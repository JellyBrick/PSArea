<?php
    namespace ps88\psarea\Commands\ProtectWorld;

    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\PSAreaMain;

    class setProtectWorldCommand extends Command {
        /** @var PSAreaMain */
        private $owner;

        /**
         * IslandInfoCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "setprotectworld", string $description = "Set Protected World", string $usageMessage = "/setprotectworld [level] [isProtect(true)]", $aliases = ['level', 'isProtect']) {
            parent::__construct($name, $description, $usageMessage, $aliases);
            $this->owner = $owner;
        }

        /**
         * @param CommandSender $sender
         * @param string $commandLabel
         * @param string[] $args
         *
         * @return bool
         */
        public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
            if (!$sender instanceof Player) {
                $sender->sendMessage(PSAreaMain::get("only-player"));
                return \true;
            }
            if (!$sender->isOp()) {
                $sender->sendMessage(PSAreaMain::get("no-permission"));
                return true;
            }
            $level = (!isset($args[0])) ? $sender->getLevel() : Server::getInstance()->getLevelByName($args[0]);
            if ($level == \null) {
                $sender->sendMessage(PSAreaMain::get("cant-find-level"));
                return \true;
            }
            $b = (!isset($args[1]) or $args[1] == "true") ? \true : \false;
            $this->owner->protectworld->setLevelProtect($level, $b);
            $sender->sendMessage(PSAreaMain::get("world-will-be-protected", \true, ["@level", $level->getName()]));
            return \true;
        }
    }
