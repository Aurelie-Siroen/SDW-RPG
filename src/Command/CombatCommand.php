<?php

namespace App\Command;

use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:combat', //php bin/console app:combat
    description: 'Add a short description for your command',
)]
class CombatCommand extends Command
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $character = $this->getPlayerCharacter();
        $enemy = $this->generateEnemy();

        $output->writeln("⚔️ Combat starts: {$character->getName()} vs {$enemy['name']}");
        $output->writeln("");

        $winner = $this->fight($character, $enemy, $output);

        $output->writeln("🏆 Winner: $winner");
        return Command::SUCCESS;
    }

    private function getPlayerCharacter(): Character
    {
        return new Character('Hero', 0,0);
    }

    private function generateEnemy(): array
    {
        // AI enemy with random but balanced stats
        return [
            'name' => 'Goblin',
            'strength' => rand(2, 4),
            'constitution' => rand(2, 4),
            'hp' => 10 + (rand(2, 4) * 2),
            'attack' => 2 + rand(2, 4),
            'defense' => 1 + (rand(2, 4) * 0.5),
            'level' => 1,
        ];
    }

    private function fight(Character $character, array $enemy, OutputInterface $output): string
    {
        $playerHp = $character->getHp(); // recupere la vie du personnage grace au gethp
        $enemyHp = 10 + ($enemy['constitution'] * 2) + ($enemy['level'] * 2); 
        $enemyAtk = 2 + $enemy['strength'] + $enemy['level']; // Attack = 2 + (STR × 1) + (LVL × 1)
        $enemyDef = 1 + ($enemy['constitution'] * 0.5) + ($enemy['level'] * 0.5); // Defense = 1 + (CON × 0.5) + (LVL × 0.5)
        $random = rand(80, 120) / 100; //Pour avoir des degats aleatoires

        $turn=1;

        while($playerHp > 0 && $enemyHp > 0){
        $output->writeln("Tour $turn");  
        // Player attacks
        $damageToEnemy = max((($character->getAttack() - $enemyDef) * $random) + 1, 1);
        // $damageToEnemy = max(($character->getAttack() - $enemyDef) * (rand(80, 120) / 100), 1);
        $enemyHp -= $damageToEnemy; 
        $output->writeln(" {$character->getName()} inflige " . $damageToEnemy . " dégâts du Goblin (La vie du hero restant : " . max(0, $enemyHp) . ")");

        if($enemyHp <= 0){
            break;
        }
        // Enemy attacks
        $damageToPlayer = max(($enemyAtk - $character->getDefense()) * (rand(80, 120) / 100), 1);
        $playerHp -= $damageToPlayer;
        $output->writeln("Goblin inflige " . (int)$damageToPlayer . " dégâts à {$character->getName()} (La vie du Goblin restant : " . max(0, $playerHp) . ")");        $output->writeln("");
        $turn++;
        }

        // Check HP and return winner
        return $playerHp > 0 ? $character->getName() : $enemy['name'];
        // return 'winner?';
    }
}
