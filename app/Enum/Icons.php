<?php

declare(strict_types=1);

namespace App\Enum;

use ArchTech\Enums\From;
use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use Illuminate\Support\Str;

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
    case ARTICHOKE = 'assets/icons/artichoke.png';
    case ASPARAGUS = 'assets/icons/asparagus.png';
    case AVOCADO = 'assets/icons/avocado.png';
    case BEETROOT = 'assets/icons/beet.png';
    case CAULIFLOWER = 'assets/icons/cauliflower.png';
    case CELERY = 'assets/icons/celery.png';
    case CHARD = 'assets/icons/chard.png';
    case CORN = 'assets/icons/corn.png';
    case AUBERGINE = 'assets/icons/eggplant.png';
    case EDAMAME = 'assets/icons/edamame.png';
    case FENNEL = 'assets/icons/fennel.png';
    case GARLIC = 'assets/icons/garlic.png';
    case GINGER = 'assets/icons/ginger.png';
    case KALE = 'assets/icons/kale.png';
    case KOHLRABI = 'assets/icons/kohlrabi.png';
    case LEEK = 'assets/icons/leek.png';
    case MUSHROOM = 'assets/icons/mushroom.png';
    case OLIVE = 'assets/icons/olive.png';
    case PARSNIP = 'assets/icons/parsnip.png';
    case PUMPKIN = 'assets/icons/pumpkin.png';
    case RADISH = 'assets/icons/radish.png';
    case SQUASH = 'assets/icons/squash.png';
    case SWEETPOTATO = 'assets/icons/sweet-potato.png';
    case COURGETTE = 'assets/icons/zucchini.png';
    case BAYLEAF = 'assets/icons/bay-leaf.png';
    case APPLE = 'assets/icons/apple.png';
    case APRICOT = 'assets/icons/apricot.png';
    case BANANA = 'assets/icons/banana.png';
    case BLACKBERRY = 'assets/icons/blackberry.png';
    case BLUEBERRY = 'assets/icons/blueberry.png';
    case CHERRY = 'assets/icons/cherry.png';
    case COCONUT = 'assets/icons/coconut.png';
    case DRAGONFRUIT = 'assets/icons/dragon-fruit.png';
    case GRAPEFRUIT = 'assets/icons/grapefruit.png';
    case GRAPES = 'assets/icons/grapes.png';
    case KIWI = 'assets/icons/kiwi.png';
    case LEMON = 'assets/icons/lemon.png';
    case LIME = 'assets/icons/lime.png';
    case LYCHEE = 'assets/icons/lychee.png';
    case MANGO = 'assets/icons/mango.png';
    case MELON = 'assets/icons/melon.png';
    case ORANGE = 'assets/icons/orange.png';
    case PAPAYA = 'assets/icons/papaya.png';
    case PEACH = 'assets/icons/peach.png';
    case PEAR = 'assets/icons/pear.png';
    case PINEAPPLE = 'assets/icons/pineapple.png';
    case PLUM = 'assets/icons/plum.png';
    case POMEGRANATE = 'assets/icons/pomegranate.png';
    case RASPBERRY = 'assets/icons/raspberry.png';
    case BAMBOO = 'assets/icons/bamboo.png';
    case BONSAI = 'assets/icons/bonsai.png';
    case CACTUS = 'assets/icons/cactus.png';
    case LAVENDER = 'assets/icons/lavender.png';
    case LOTUS = 'assets/icons/lotus.png';
    case ORCHID = 'assets/icons/orchid.png';
    case PALM = 'assets/icons/palm-tree.png';
    case ROSE = 'assets/icons/rose.png';
    case SEEDLING = 'assets/icons/seedling.png';
    case SUNFLOWER = 'assets/icons/sunflower.png';
    case VIOLA = 'assets/icons/viola.png';

    /**
     * The URL of an icon chosen by name, or of a custom upload, which takes precedence.
     */
    public static function src(?string $name, ?string $custom = null): ?string
    {
        if ($custom) {
            return asset('storage/'.$custom);
        }

        return $name ? asset(self::fromName($name)()) : null;
    }

    /**
     * Select options keyed by name, labelled with a preview of each icon. Use with `allowHtml()`.
     *
     * @return array<string, string>
     */
    public static function selectOptions(): array
    {
        $icons = self::cases();
        usort($icons, fn (self $a, self $b): int => strcmp($a->name, $b->name));

        $options = [];
        foreach ($icons as $icon) {
            $options[$icon->name] = sprintf(
                '<span style="display: flex; align-items: center; gap: 0.5rem"><img src="%s" alt="" style="width: 1.5rem; height: 1.5rem; object-fit: contain">%s</span>',
                e(asset($icon->value)),
                e(Str::headline(strtolower($icon->name))),
            );
        }

        return $options;
    }
}
