<?php
    namespace ps88\psarea\Commands\Land;

    use nlog\StormCore\StormPlayer;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\Loaders\Field\fieldloader;
    use ps88\psarea\PSAreaMain;

    class LandDelShareCommand extends Command {

        /** @var PSAreaMain */
        private $owner;

        /**
         * FieldAddShareCommand constructor.
         * @param string $name
         * @param PSAreaMain $owner
         * @param string $description
         * @param string|null $usageMessage
         * @param array $aliases
         */
        public function __construct(PSAreaMain $owner, string $name = "dellandshare", string $description = "Delete Land Shared Player", string $usageMessage = "/dellandshare [player] [id]", $aliases = ['Player', 'Id']) {
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
            $a = (!isset($args[1])) ? $this->owner->landloader->getAreaByPosition($sender) : $this->owner->landloader->getAreaById($args[1]);
            if ($a == \null) {
                $sender->sendMessage(PSAreaMain::get("not-registered"));
                return \true;
            }
            if ($a->owner == \null) {
                $sender->sendMessage(PSAreaMain::get("not-yours", \true, ["@type", "land"]));
                return \true;
            }
            if ($a->owner->getName() !== $sender->getName()) {
                $sender->sendMessage(PSAreaMain::get("not-yours", \true, ["@type", "land"]));
                return \true;
            }
            if (!isset($args[1])) {
                $sender->sendMessage($this->getUsage());
                return \true;
            }
            $pl = Server::getInstance()->getPlayer($args[0]);
            if ($pl == \null) {
                $sender->sendMessage(PSAreaMain::get("doesnt-exist"));
                return \true;
            }
            $a->delShare($pl);
            $sender->sendMessage("You del {$pl->getName()} at {$a->getLandnum()} land");
            return \true;
        }
    }