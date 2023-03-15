<?php

namespace App\Enum;

use ArchTech\Enums\{From, InvokableCases, Names, Options};

enum Icons: string
{
    use From;
    use InvokableCases;
    use Names;
    use Options;

    case TEMPERATURE = 'assets/icons/thermometer.png';
    case PRESSURE = 'assets/icons/atmospheric-pressure.png';
    case HUMIDITY = 'assets/icons/hygrometer.png';
    case LIGHT = 'assets/icons/sun.png';
    case MOISTURE = 'assets/icons/moisture.png';
    case TOMATO = 'assets/icons/tomato.png';
    case CARROT = 'assets/icons/carrot.png';
    case POTATO = 'assets/icons/potato.png';
    case LETTUCE = 'assets/icons/lettuce.png';
    case PEPPER = 'assets/icons/paprika.png';
    case SPINACH = 'assets/icons/spinach.png';
    case PAKCHOI = 'assets/icons/bok-choy.png';
    case CHILLI = 'assets/icons/chili-pepper.png';
}
