import { Link, usePage } from '@inertiajs/react';
import { CircleHelp, ListChecks, Settings, Sprout } from 'lucide-react';
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
                        <Button variant="ghost" size="sm" nativeButton={false} render={<Link href={route('help.show')} />}>
                            <CircleHelp data-icon="inline-start" />
                            <span className="hidden sm:inline">Help</span>
                        </Button>
                        {/* The admin panel is Filament, not Inertia, so this is a full page load. */}
                        <Button
                            variant="ghost"
                            size="sm"
                            nativeButton={false}
                            render={<a href={route('filament.admin.pages.dashboard')} />}
                        >
                            <Settings data-icon="inline-start" />
                            <span className="hidden sm:inline">Admin</span>
                        </Button>
                    </div>
                </header>
                <main className="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 sm:py-8">{children}</main>
            </div>
        </TooltipProvider>
    );
}
