<?php

namespace App\Service;

use App\Entity\Character;

class Randomizer {
public function randomize(int $min, int $max) {
    return rand($min, $max);
}
}