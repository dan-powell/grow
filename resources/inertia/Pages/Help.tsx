import { Head, Link } from '@inertiajs/react';
import { BellRing, Cpu, Gauge, Leaf, type LucideIcon } from 'lucide-react';
import { Fragment } from 'react';
import PageHeader from '@/Components/PageHeader';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';

/** Icons8 attribution, required by their free licence for the figure/device icons. */
const iconCredits: [name: string, url: string][] = [
    ['Hygrometer', 'https://icons8.com/icon/15365/hygrometer'],
    ['Thermometer', 'https://icons8.com/icon/15350/thermometer'],
    ['Humidity', 'https://icons8.com/icon/13001/humidity'],
    ['Atmospheric Pressure', 'https://icons8.com/icon/15344/atmospheric-pressure'],
    ['Sun', 'https://icons8.com/icon/15352/sun'],
    ['Paprika', 'https://icons8.com/icon/18075/paprika'],
    ['Bok Choy', 'https://icons8.com/icon/Ynaia0d7j5Am/bok-choy'],
    ['Spinach', 'https://icons8.com/icon/qIF4XIx-ZSGt/spinach'],
    ['Potato', 'https://icons8.com/icon/20756/potato'],
    ['Carrot', 'https://icons8.com/icon/12865/carrot'],
    ['Tomato', 'https://icons8.com/icon/18102/tomato'],
    ['Chili Pepper', 'https://icons8.com/icon/15573/chili-pepper'],
    ['Pipelines', 'https://icons8.com/icon/73818/pipelines'],
    ['Sprout', 'https://icons8.com/icon/19656/sprout'],
    ['Parsley', 'https://icons8.com/icon/wrgV9nSA6k5f/parsley'],
    ['Mint', 'https://icons8.com/icon/rwvFV6ECv3E0/mint'],
    ['Basil', 'https://icons8.com/icon/WRfXwryHkOg0/basil'],
    ['Cucumber', 'https://icons8.com/icon/20758/cucumber'],
    ['Whole Watermelon', 'https://icons8.com/icon/Vh5AxBulOk9L/whole-watermelon'],
    ['Thyme', 'https://icons8.com/icon/xrQnyagkvVrx/thyme'],
    ['Broccoli', 'https://icons8.com/icon/18042/broccoli'],
    ['Vegetables Bag', 'https://icons8.com/icon/6qkfySkko5F9/vegetables-bag'],
    ['Cabbage', 'https://icons8.com/icon/20986/cabbage'],
    ['Peas', 'https://icons8.com/icon/18956/peas'],
    ['Potted Plant', 'https://icons8.com/icon/19190/potted-plant'],
    ['Onion', 'https://icons8.com/icon/18072/onion'],
    ['Scallions', 'https://icons8.com/icon/nlIIz9nKHTpf/scallions'],
    ['Pumphouse', 'https://icons8.com/icon/Swgs9lrgWMvb/pumphouse'],
    ['Charging Battery', 'https://icons8.com/icon/11843/charging-battery'],
    ['Artichoke', 'https://icons8.com/icon/64432/artichoke'],
    ['Asparagus', 'https://icons8.com/icon/20111/asparagus'],
    ['Avocado', 'https://icons8.com/icon/18039/avocado'],
    ['Beet', 'https://icons8.com/icon/13299/beet'],
    ['Cauliflower', 'https://icons8.com/icon/37346/cauliflower'],
    ['Celery', 'https://icons8.com/icon/19021/celery'],
    ['Chard', 'https://icons8.com/icon/ohqhNGw8bzij/chard'],
    ['Corn', 'https://icons8.com/icon/13285/corn'],
    ['Eggplant', 'https://icons8.com/icon/20874/eggplant'],
    ['Edamame', 'https://icons8.com/icon/GksGioZECTDi/edamame'],
    ['Finocchio', 'https://icons8.com/icon/95388/finocchio'],
    ['Garlic', 'https://icons8.com/icon/13329/garlic'],
    ['Ginger', 'https://icons8.com/icon/Ma3KY155-UYT/ginger'],
    ['Kale', 'https://icons8.com/icon/XLjMk0hshjcG/kale'],
    ['Kohlrabi', 'https://icons8.com/icon/20946/kohlrabi'],
    ['Leek', 'https://icons8.com/icon/20945/leek'],
    ['Mushroom', 'https://icons8.com/icon/12864/mushroom'],
    ['Olive', 'https://icons8.com/icon/20947/olive'],
    ['Parsnip', 'https://icons8.com/icon/2Xdbj1usKJAK/parsnip'],
    ['Pumpkin', 'https://icons8.com/icon/17358/pumpkin'],
    ['Radish', 'https://icons8.com/icon/20944/radish'],
    ['Squash', 'https://icons8.com/icon/Ee19Pnbp97qi/squash'],
    ['Sweet Potato', 'https://icons8.com/icon/20133/sweet-potato'],
    ['Zucchini', 'https://icons8.com/icon/64431/zucchini'],
    ['Bay Leaf', 'https://icons8.com/icon/eawEaoSiFiJn/bay-leaf'],
    ['Whole Apple', 'https://icons8.com/icon/ngSSMNrR5GFG/whole-apple'],
    ['Apricot', 'https://icons8.com/icon/45429/apricot'],
    ['Banana', 'https://icons8.com/icon/18957/banana'],
    ['Black Berry', 'https://icons8.com/icon/eRcNxL8GobXe/black-berry'],
    ['Blueberry', 'https://icons8.com/icon/gliul3LO5hl8/blueberry'],
    ['Cherry', 'https://icons8.com/icon/19527/cherry'],
    ['Coconut', 'https://icons8.com/icon/24979/coconut'],
    ['Dragon Fruit', 'https://icons8.com/icon/20757/dragon-fruit'],
    ['Grapefruit', 'https://icons8.com/icon/vyOxJUtbftSh/grapefruit'],
    ['Grapes', 'https://icons8.com/icon/18051/grapes'],
    ['Kiwi', 'https://icons8.com/icon/18065/kiwi'],
    ['Citrus', 'https://icons8.com/icon/18044/citrus'],
    ['Lime', 'https://icons8.com/icon/8TFvFB07FQAd/lime'],
    ['Lychee', 'https://icons8.com/icon/3xTXKv357TYu/lychee'],
    ['Mango', 'https://icons8.com/icon/92144/mango'],
    ['Melon', 'https://icons8.com/icon/19528/melon'],
    ['Orange', 'https://icons8.com/icon/57233/orange'],
    ['Papaya', 'https://icons8.com/icon/30852/papaya'],
    ['Peach', 'https://icons8.com/icon/17954/peach'],
    ['Pear', 'https://icons8.com/icon/18079/pear'],
    ['Pineapple', 'https://icons8.com/icon/18080/pineapple'],
    ['Plum', 'https://icons8.com/icon/19526/plum'],
    ['Pomegranate', 'https://icons8.com/icon/18083/pomegranate'],
    ['Raspberry', 'https://icons8.com/icon/19529/raspberry'],
    ['Bamboo', 'https://icons8.com/icon/36977/bamboo'],
    ['Bonsai', 'https://icons8.com/icon/3R5LT7W45VT7/bonsai'],
    ['Cactus', 'https://icons8.com/icon/18043/cactus'],
    ['Lavender', 'https://icons8.com/icon/6jgc5DucBQYB/lavender'],
    ['Lotus', 'https://icons8.com/icon/36895/lotus'],
    ['Orchid', 'https://icons8.com/icon/62474/orchid'],
    ['Palm Tree', 'https://icons8.com/icon/18073/palm-tree'],
    ['Rose', 'https://icons8.com/icon/31968/rose'],
    ['Potted Plant', 'https://icons8.com/icon/OKMC0MrrQY5I/potted-plant'],
    ['Sunflower', 'https://icons8.com/icon/PDCkbr_yBwSU/sunflower'],
    ['Violet Flower', 'https://icons8.com/icon/Ran0k53sIfb2/violet-flower'],
];

const concepts: { icon: LucideIcon; title: string; description: string }[] = [
    {
        icon: Cpu,
        title: 'Devices',
        description:
            'A physical unit, such as a microcontroller in a greenhouse, that regularly sends its sensor readings to Grow.',
    },
    {
        icon: Gauge,
        title: 'Figures',
        description:
            'One value a device reports, such as temperature or soil moisture. Each figure shows its latest reading, where it sits in its expected range, and a history chart.',
    },
    {
        icon: Leaf,
        title: 'Subjects',
        description:
            'The thing being measured, such as a plant. A sensor can be moved between plants, and each reading stays linked to the plant it was taken from.',
    },
    {
        icon: BellRing,
        title: 'Alerts',
        description:
            'Thresholds on a subject, such as moisture dropping below 30%, plus a warning when a device stops reporting. Alerts are emailed when they trigger and when they resolve.',
    },
];

const linkClassName = 'underline-offset-2 hover:underline';

export default function Help() {
    return (
        <>
            <Head title="Help" />
            <div className="flex flex-col gap-10">
                <PageHeader title="Help" description="What Grow Log is and how it fits together." />
                <section className="flex max-w-3xl flex-col gap-3 text-sm leading-relaxed">
                    <p>
                        Grow Log is a self-hosted dashboard for keeping an eye on plants and growing spaces. Sensor devices
                        post their readings to Grow, which stores and charts them, and sends an email when a value goes out
                        of range or a device goes quiet.
                    </p>
                    <p>
                        The <Link href={route('dashboard.show')} className={`font-medium ${linkClassName}`}>dashboard</Link>{' '}
                        shows the latest readings. Select a figure to see its history, or open the{' '}
                        <Link href={route('log.index')} className={`font-medium ${linkClassName}`}>log</Link> for recent
                        events such as alerts. Devices, figures, subjects and alerts are managed in the{' '}
                        <a href={route('filament.admin.pages.dashboard')} className={`font-medium ${linkClassName}`}>
                            admin panel
                        </a>
                        .
                    </p>
                </section>
                <section className="flex flex-col gap-4">
                    <h2 className="font-heading text-lg font-semibold tracking-tight">How it works</h2>
                    <div className="grid gap-4 sm:grid-cols-2">
                        {concepts.map(({ icon: Icon, title, description }) => (
                            <Card key={title}>
                                <CardHeader>
                                    <CardTitle className="flex items-center gap-2">
                                        <Icon className="size-4 text-primary" />
                                        {title}
                                    </CardTitle>
                                    <CardDescription>{description}</CardDescription>
                                </CardHeader>
                            </Card>
                        ))}
                    </div>
                </section>
                <section className="flex flex-col gap-4">
                    <h2 className="font-heading text-lg font-semibold tracking-tight">Credits</h2>
                    <Card>
                        <CardContent>
                            <p className="text-xs leading-relaxed text-muted-foreground">
                                Icons by{' '}
                                <a href="https://icons8.com" target="_blank" rel="noreferrer" className={linkClassName}>
                                    Icons8
                                </a>
                                :{' '}
                                {iconCredits.map(([name, url], index) => (
                                    <Fragment key={url}>
                                        {index > 0 && ', '}
                                        <a href={url} target="_blank" rel="noreferrer" className={linkClassName}>
                                            {name}
                                        </a>
                                    </Fragment>
                                ))}
                            </p>
                        </CardContent>
                    </Card>
                </section>
            </div>
        </>
    );
}
