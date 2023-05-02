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
    case PIPES = 'assets/icons/pipes.png';
    case TOMATO = 'assets/icons/tomato.png';
    case CARROT = 'assets/icons/carrot.png';
    case POTATO = 'assets/icons/potato.png';
    case LETTUCE = 'assets/icons/lettuce.png';
    case PEPPER = 'assets/icons/paprika.png';
    case SPINACH = 'assets/icons/spinach.png';
    case PAKCHOI = 'assets/icons/bok-choy.png';
    case CHILLI = 'assets/icons/chili-pepper.png';
    case SPROUT = 'assets/icons/sprout.png';
    case BATTERY = 'assets/icons/charging-battery.png';
    case PUMP = 'assets/icons/pumphouse.png';
    case SCALLIONS = 'assets/icons/scallions.png';
    case ONION = 'assets/icons/onion.png';
    case POTTED = 'assets/icons/potted-plant.png';
    case PEAS = 'assets/icons/peas.png';
    case CABBAGE = 'assets/icons/cabbage.png';
    case BAG = 'assets/icons/vegetables-bag.png';
    case BROCOLLI = 'assets/icons/broccoli.png';
    case THYME = 'assets/icons/thyme.png';
    case WATERMELON = 'assets/icons/whole-watermelon.png';
    case STRAWBERRY = 'assets/icons/strawberry.png';
    case CUCUMBER = 'assets/icons/cucumber.png';
    case MINT = 'assets/icons/mint.png';
    case BASIL = 'assets/icons/basil.png';
    case PARSLEY = 'assets/icons/parsley.png';
}
