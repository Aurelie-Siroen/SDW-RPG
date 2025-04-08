<?php
//vendor\bin\phpunit tests\unit\Service\CombatTest.php
namespace App\Tests\Unit\Service;

use App\Entity\Character;
use App\Entity\Combat;
use PHPUnit\Framework\TestCase;

class CombatTest extends TestCase
{
    public function testStaticDamage() // les diferents methodes de combat
    {
        $attacker = new Character('Mage', 5, 1, 1);//ATK = 2 + 5 + 1 = 8
        $defender = new Character('Warrior', 3, 3, 1); //DEF = 1 + (3*0.5) + (1*0.5) = 3

        $damage = Combat::calculateDamage($attacker, $defender);
        $this->assertGreaterThanOrEqual(1, $damage); // Vérifie les degats
    }
    //Test de hit chance
    public function testStaticHitChance()
    {
        $attacker = new Character('Rogue', 4, 2, 1);
        $defender = new Character('Mage', 5, 1, 1);

        $hitChance = Combat::calculateHitChance($attacker, $defender); // calcule la chance de touché

        $this->assertGreaterThanOrEqual(50, $hitChance);
        $this->assertLessThanOrEqual(95, $hitChance);
    }

}