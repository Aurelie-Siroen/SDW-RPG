<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Character
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private string $name;

    #[ORM\Column]
    private int $strength;

    #[ORM\Column]
    private int $constitution;

    #[ORM\Column]
    private int $experience = 0;

    #[ORM\Column]
    private int $level = 1;

    public function __construct(string $name, int $strength, int $constitution, int $level = 1)
    {
        $this->name = $name;
        $this->strength = $strength;
        $this->constitution = $constitution;
        $this->level = $level;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getStrength(): int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): self
    {
        $this->strength = $strength;
        return $this;
    }

    public function getConstitution(): int
    {
        return $this->constitution;
    }

    public function setConstitution(int $constitution): self
    {
        $this->constitution = $constitution;
        return $this;
    }
    public function getExperience(): int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = max(0, $experience); 
        return $this;
    }
    // ============================
    //         STATS
    // ============================
    public function getHp(): int
    {
        // HP = 10 + (1 * 2) + (2 * 2)
        //= 10 + 2 + 4
        //= 16

        return 10 + ($this->constitution * 2) + ($this->level * 2);
    }

    public function getAttack(): int
    {
        // Attack = 2 + (6 * 1) + (2 * 1)
        // = 2 + 6 + 2
        //= 10
        return 2 + ($this->strength * 1) + ($this->level * 1);
    }

    public function getDefense(): int
    {
        // TODO: Implement Defense calculation
        return 1 + ($this->constitution * 0.5) + ($this->level * 0.5);
    }

    // ============================
    //     PROGRESSION / XP
    // ============================
    public function getLevel(): int
    {
        return $this->level;
    }
    public function gainExperience(int $amount): void
    {
        $this->experience += $amount;
        $neededXP = 100 * $this->level;

        while ($this->experience >= $neededXP) {
            $this->experience -= $neededXP;
            $this->levelUp();
            $neededXP = 100 * $this->level;
        }
    }
    private function levelUp(): void
    {
        $this->level++;
        $this->strength++; // Par défaut, on augmente la force
    }
}
