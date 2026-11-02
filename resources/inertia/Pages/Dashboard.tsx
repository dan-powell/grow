import { Head, useRemember } from '@inertiajs/react';
import { Cpu, SearchX } from 'lucide-react';
import { useMemo } from 'react';
import DeviceCard from '@/Components/DeviceCard';
import FigureCard from '@/Components/FigureCard';
import FigureToolbar from '@/Components/FigureToolbar';
import PageHeader from '@/Components/PageHeader';
import { Button } from '@/Components/ui/button';
import { Empty, EmptyContent, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/Components/ui/empty';
import { applyFigureFilters, defaultFigureFilters, type FigureFilters } from '@/lib/figures';
import type { Device, Figure } from '@/types';

interface Props {
    devices: Device[];
    figures: Figure[];
}

export default function Dashboard({ devices, figures }: Props) {
    const dashboardFigures = useMemo(() => figures.filter((figure) => figure.dashboard), [figures]);
    const [filters, setFilters] = useRemember<FigureFilters>(defaultFigureFilters, 'Dashboard/figureFilters');
    const visibleFigures = useMemo(() => applyFigureFilters(dashboardFigures, filters), [dashboardFigures, filters]);
    const figureDevices = useMemo(
        () => devices.filter((device) => dashboardFigures.some((figure) => figure.device_id === device.id)),
        [devices, dashboardFigures],
    );

    return (
        <>
            <Head title="Dashboard" />
            <div className="flex flex-col gap-10">
                <PageHeader title="Dashboard" description="Latest readings from your devices." />
                {dashboardFigures.length > 0 && (
                    <section className="flex flex-col gap-4">
                        <FigureToolbar filters={filters} onChange={setFilters} devices={figureDevices} />
                        <p className="text-xs text-muted-foreground" aria-live="polite">
                            Showing {visibleFigures.length} of {dashboardFigures.length} figures
                        </p>
                        {visibleFigures.length > 0 ? (
                            <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                                {visibleFigures.map((figure) => (
                                    <FigureCard key={figure.id} figure={figure} showDevice />
                                ))}
                            </div>
                        ) : (
                            <Empty className="border border-dashed bg-background">
                                <EmptyHeader>
                                    <EmptyMedia variant="icon">
                                        <SearchX />
                                    </EmptyMedia>
                                    <EmptyTitle>No matching figures</EmptyTitle>
                                    <EmptyDescription>Try a different search or clear the filters.</EmptyDescription>
                                </EmptyHeader>
                                <EmptyContent>
                                    <Button variant="outline" onClick={() => setFilters(defaultFigureFilters)}>
                                        Reset filters
                                    </Button>
                                </EmptyContent>
                            </Empty>
                        )}
                    </section>
                )}
                <section className="flex flex-col gap-4">
                    <h2 className="font-heading text-lg font-semibold tracking-tight">Devices</h2>
                    {devices.length > 0 ? (
                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            {devices.map((device) => (
                                <DeviceCard key={device.id} device={device} />
                            ))}
                        </div>
                    ) : (
                        <Empty className="border border-dashed bg-background">
                            <EmptyHeader>
                                <EmptyMedia variant="icon">
                                    <Cpu />
                                </EmptyMedia>
                                <EmptyTitle>No devices yet</EmptyTitle>
                                <EmptyDescription>Add a device in the admin panel to start logging readings.</EmptyDescription>
                            </EmptyHeader>
                        </Empty>
                    )}
                </section>
            </div>
        </>
    );
}
