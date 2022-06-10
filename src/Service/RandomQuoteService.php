<?php

namespace App\Service;

class RandomQuoteService {

    
    public static function getQuote(): string {
        $quotes = [
            "Sólo hay una persona que puede decidir lo que voy a hacer, y soy yo mismo",
            "Sólo los soñadores mueven montañas",
            "Oh, sí... El pasado puede doler, pero tal como yo lo veo puedes huir de él o aprender",
            "Las causas perdidas son las únicas por las que vale la pena luchar",
            "Pensamos demasiado y sentimos muy poco…",
        ];

        return $quotes[array_rand($quotes)];
    }
}

