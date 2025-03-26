<?php

namespace App\Entity;

use App\Entity\Character;
use App\Service\Randomizer;

class Combat
{
    public function __construct(
        private Randomizer $randomizer,
    )
    {
    }


    public static function calculateDamage(Character $attacker, Character $defender): int
    {
        $randomMultiplier = rand(80, 120) / 100; // 0.8 à 1.2
        $damage = max(($attacker->getAttack() - $defender->getDefense()) * $randomMultiplier, 1);
        return (int) $damage;
    }

    public static function calculateHitChance(Character $attacker, Character $defender): int
    {
        $hitChance = 75 + (($attacker->getStrength() - $defender->getConstitution()) * 3) + $attacker->getLevel();
        return max(50, min($hitChance, 95)); //  entre 50 et 95 %
    }

    public function calculateDamage2(Character $attacker, Character $defender): int
    {
        $randomMultiplier = $this->randomizer->randomize(80, 120) / 100; // 0.8 à 1.2
        $damage = max(($attacker->getAttack() - $defender->getDefense()) * $randomMultiplier, 1);
        return (int) $damage;
    }
}
