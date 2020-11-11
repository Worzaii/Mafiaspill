<?php


namespace UserObject;

class Rank
{
    public int $xp;
    private array $map = [
        1 => [
            "name" => "Soldat",
            "max" => 25
        ],
        2 => [
            "name" => "Capo",
            "max" => 50
        ],
        3 => [
            "name" => "Underboss",
            "max" => 75
        ],
        4 => [
            "name" => "Boss",
            "max" => 100
        ],
        5 => [
            "name" => "Consigliere",
            "max" => 250
        ],
        6 => [
            "name" => "Don",
            "max" => 500
        ],
        7 => [
            "name" => "Mafioso",
            "max" => 750
        ],
        8 => [
            "name" => "Omerta",
            "max" => 900
        ],
        9 => [
            "name" => "Vendetta",
            "max" => 1250
        ],
        10 => [
            "name" => "Godfather",
            "max" => 1500
        ],
        11 => [
            "name" => "Legende",
            "max" => 1750
        ],
        12 => [
            "name" => "Legendarisk Don",
            "max" => 2000
        ]
    ];

    public function __construct($xp)
    {
        $this->xp = (float)$xp;
        $this->getRank();
        return $this;
    }

    public function getRank()
    {
        foreach ($this->map as $one => $two) {
            if ($this->xp < $two["max"]) {
                return $this->map[$one]["name"];
            }
        }
    }

    public function getRankID()
    {
        foreach ($this->map as $one => $two) {
            if ($this->xp < $two["max"]) {
                return $one;
            }
        }
    }

    public function getXP()
    {
        return $this->xp;
    }
}