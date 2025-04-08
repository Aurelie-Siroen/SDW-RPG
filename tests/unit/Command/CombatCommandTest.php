<?php

namespace App\Tests\Unit\Command;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use App\Command\CombatCommand;

class CombatCommandTest extends KernelTestCase
{
    public function testCombatCommandRunsSuccessfully()
    {
        // Démarre le kernel Symfony pour accéder aux services
        self::bootKernel();

        // Récupère la commande depuis le container
        $command = static::getContainer()->get(CombatCommand::class);

        // Prépare le testeur de commande
        $tester = new CommandTester($command);

        // Exécute la commande
        $tester->execute([]);

        // Récupère le texte affiché
        $output = $tester->getDisplay();

        // Vérifie que les textes de base sont bien présents
        $this->assertStringContainsString('⚔️ Combat starts', $output);
        $this->assertStringContainsString('🏆 Winner:', $output);
    }
}
