import { Link, usePage } from '@inertiajs/react';
import { ListChecks, Sprout } from 'lucide-react';
import { Fragment, type ReactNode } from 'react';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb';
import { Button } from '@/Components/ui/button';
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/Components/ui/tooltip';
import { LIVE_READINGS_INTERVAL, useLiveReadings } from '@/hooks/useLiveReadings';
import type { SharedProps } from '@/types';

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

export default function AppLayout({ children }: { children: ReactNode }) {
    const { breadcrumbs } = usePage<SharedProps>().props;
    useLiveReadings();

    return (
        <TooltipProvider>
            <div className="flex min-h-screen flex-col bg-muted/40">
                <header className="sticky top-0 z-40 border-b bg-background/80 backdrop-blur">
                    <div className="mx-auto flex h-14 max-w-7xl items-center gap-4 px-4 sm:px-6">
                        <Link href={route('dashboard.show')} className="flex items-center gap-2 font-heading font-semibold">
                            <span className="flex size-7 items-center justify-center rounded-full bg-primary text-primary-foreground">
                                <Sprout className="size-4" />
                            </span>
                            <span className="hidden sm:inline">Grow Log</span>
                        </Link>
                        <Breadcrumb className="min-w-0 flex-1">
                            <BreadcrumbList className="flex-nowrap">
                                {breadcrumbs.map((breadcrumb, index) => (
                                    <Fragment key={breadcrumb.url}>
                                        {index > 0 && <BreadcrumbSeparator />}
                                        <BreadcrumbItem className="min-w-0">
                                            {breadcrumb.is_current_page ? (
                                                <BreadcrumbPage className="truncate">{breadcrumb.title}</BreadcrumbPage>
                                            ) : (
                                                <BreadcrumbLink className="truncate" render={<Link href={breadcrumb.url} />}>
                                                    {breadcrumb.title}
                                                </BreadcrumbLink>
                                            )}
                                        </BreadcrumbItem>
                                    </Fragment>
                                ))}
                            </BreadcrumbList>
                        </Breadcrumb>
                        <Tooltip>
                            <TooltipTrigger
                                render={<span className="flex items-center gap-1.5 text-xs text-muted-foreground" />}
                            >
                                <span className="relative flex size-2">
                                    <span className="absolute inline-flex size-full animate-ping rounded-full bg-primary opacity-75 motion-reduce:hidden" />
                                    <span className="relative inline-flex size-2 rounded-full bg-primary" />
                                </span>
                                <span className="hidden sm:inline">Live</span>
                            </TooltipTrigger>
                            <TooltipContent>
                                Checks for new readings every {LIVE_READINGS_INTERVAL / 1000} seconds
                            </TooltipContent>
                        </Tooltip>
                        <Button variant="ghost" size="sm" nativeButton={false} render={<Link href={route('log.index')} />}>
                            <ListChecks data-icon="inline-start" />
                            <span className="hidden sm:inline">Log</span>
                        </Button>
                    </div>
                </header>
                <main className="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 sm:py-8">{children}</main>
                <footer className="border-t bg-background">
                    <p className="mx-auto max-w-7xl px-4 py-6 text-[0.7rem] leading-relaxed text-muted-foreground sm:px-6">
                        Icons by{' '}
                        <a href="https://icons8.com" target="_blank" rel="noreferrer" className="underline-offset-2 hover:underline">
                            Icons8
                        </a>
                        :{' '}
                        {iconCredits.map(([name, url], index) => (
                            <Fragment key={url}>
                                {index > 0 && ', '}
                                <a href={url} target="_blank" rel="noreferrer" className="underline-offset-2 hover:underline">
                                    {name}
                                </a>
                            </Fragment>
                        ))}
                    </p>
                </footer>
            </div>
        </TooltipProvider>
    );
}
