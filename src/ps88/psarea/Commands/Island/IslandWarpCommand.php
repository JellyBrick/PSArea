<?php
    namespace ps88\psarea\Commands\Island;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\level\Position;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\Loaders\Island\IslandLoader;
    use ps88\psarea\PSAreaMain;

    class IslandWarpCommand extends Command {

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
        public function __construct(PSAreaMain $owner, string $name = "warpisland", string $description = "Warp to Island", string $usageMessage = "/warpisland [id]", $aliases = ['Id']) {
            parent::__construct($name, $description, $usageMessage, $aliases);
            $this->owner = $owner;
        }

        /**
         * @param CommandSender $sender
         * @param string $commandLabel
         * @param string[] $args
         *
         * @return mixed
         */
        public function execute(CommandSender $sender, string $commandLabel, array $args) {
            if (!$sender instanceof Player) {
                $sender->sendMessage("Only Player Can see this.");
                return;
            }
            $id = (int) $args[0];
            if (($a = $this->owner->islandloader->getAreaById($id)) == \null) {
                $sender->sendMessage("Not Registered");
                return;
            }
            if (!$a->Warp($sender)) {
                $sender->sendMessage("Cancelled by Plugin");
                return;
            }
            $sender->sendMessage("Warped to {$id} island");
            return;
        }
    }