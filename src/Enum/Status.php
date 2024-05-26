<?php

namespace App\Enum;

enum Status: string
{
    case ToDo = 'To DO';
    case Doing = 'Doing';
    case Done = 'Done';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::ToDo => 'A faire',
            self::Doing => 'En cours',
            self::Done => 'Terminé',
        };
    }
}