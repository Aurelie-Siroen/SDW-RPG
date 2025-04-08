<?php
// vendor\bin\phpunit tests\unit\entity\CharacterTest.php
namespace App\tests\unit\entity;

use App\Entity\Character;
use PHPUnit\Framework\TestCase;
use App\Entity\Combat;
use App\Service\Randomizer;

class CharacterTest extends TestCase {

    //  Test 1 : Création d’un personnage avec des stats précises
    public function testCharacterCreation() {
        
        $warrior = new Character('Warrior', 3, 3, 1);// On crée un personnage de type Warrior avec STR = 3, CON = 3, LVL = 1

        // Vérifie que les stats calculées correspondent aux formules définies :
        
        $this->assertEquals(18, $warrior->getHP());// HP = 10 + (3*2) + (1*2) = 18
        $this->assertEquals(6, $warrior->getAttack());// ATK = 2 + (3*1) + (1*1) = 6
        $this->assertEquals(3, $warrior->getDefense());// DEF = 1 + (3*0.5) + (1*0.5) = 3
    }

    //  Test 2 : Gain d’expérience et montée de niveau
    public function testLevelUp() {
        
        $character = new Character('Mage', 5, 1);// Création d'un personnage Mage au niveau 1, avec STR = 5, CON = 1
        $character->gainExperience(100);// On  donne 100 XP → devrait suffire pour passer au niveau 2
        $this->assertEquals(2, $character->getLevel());// Vérifie que le niveau est bien passé à 2
        $this->assertEquals(16, $character->getHp());// HP = 10 + (1*2) + (2*2) = 16
        $this->assertEquals(10, $character->getAttack());// ATK = 2 + (6*1) + (2*1) = 10 (STR a augmenté à 6 lors du level-up)
    }

    //  Test 3 : Gain d’XP partiel puis complet (passage de niveau)
    public function testexpgain() {
        $character = new Character('Rogue', 4, 2);
        $character->gainExperience(50);        // On gagne 50 XP, le personnage reste au niveau 1
        $this->assertEquals(50, $character->getExperience());
        $character->gainExperience(60);        // On gagne 60 XP supplémentaires → total = 110 → level up
        $this->assertEquals(2, $character->getLevel());        
    }

    //  Test 4 : Calcul des dégâts en combat (Combat statique, sans attaque réelle)
    public function testdamage() {
        $attacker = new Character('Mage', 5, 1, 1);     // ATK = 2 + 5 + 1 = 8
        $defender = new Character('Warrior', 3, 3, 1);  // DEF = 1 + 1.5 + 0.5 = 3

        $damage = Combat::calculateDamage($attacker, $defender);        //  dégâts infligés par l’attaquant au défenseur
        $hitChance = Combat::calculateHitChance($attacker, $defender);       
        $this->assertGreaterThanOrEqual(1, $damage);        //  les dégâts ne soient jamais < 1

        $randomizer = $this->createMock(Randomizer::class);
        $randomizer->method('randomize')->willReturn(80);
        assert($randomizer instanceof Randomizer);
        
        $combat = new Combat($randomizer);
        $combat->calculateDamage2(...);
    }

}
