<?php

namespace App\Enum;

use ArchTech\Enums\{InvokableCases, Names, Options, From};

enum Icons: string
{
    use InvokableCases, Names, Options, From;

    case TEMPERATURE = 'assets/icons/thermometer.png';
    case PRESSURE = 'assets/icons/atmospheric-pressure.png';
    case HUMIDITY = 'assets/icons/humidity.png';
    case LIGHT = 'assets/icons/sun.png';
    case MOISTURE = 'assets/icons/hygrometer.png';
    case TOMATO = 'assets/icons/tomato.png';
    case CARROT = 'assets/icons/carrot.png';
    case POTATO = 'assets/icons/potato.png';
    case LETTUCE = 'assets/icons/lettuce.png';
    case PEPPER = 'assets/icons/paprika.png';
    case SPINACH = 'assets/icons/spinach.png';
    case PAKCHOI = 'assets/icons/bok-choi.png';
    case CHILLI = 'assets/icons/chili-pepper.png';
}
