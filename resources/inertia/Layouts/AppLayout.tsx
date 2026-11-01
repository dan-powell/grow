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
import { TooltipProvider } from '@/Components/ui/tooltip';
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
];

export default function AppLayout({ children }: { children: ReactNode }) {
    const { breadcrumbs } = usePage<SharedProps>().props;

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
