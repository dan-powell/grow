import { Deferred, Head } from '@inertiajs/react';
import { Gauge } from 'lucide-react';
import DeviceAlerts from '@/Components/DeviceAlerts';
import DeviceCard from '@/Components/DeviceCard';
import DeviceReadingsChart from '@/Components/DeviceReadingsChart';
import FigureCard from '@/Components/FigureCard';
import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/Components/ui/empty';
import type { Device, ReadingPoint } from '@/types';

interface Props {
    device: Device;
    /** Deferred: loaded after the page renders. */
    readings?: Record<string, ReadingPoint[]>;
}

export default function Show({ device, readings }: Props) {
    const figures = device.figures ?? [];

    return (
        <>
            <Head title={device.name} />
            <div className="flex flex-col gap-8">
                <DeviceCard device={device} asHeader />
                <DeviceAlerts device={device} />
                {figures.length > 0 ? (
                    <>
                        <section className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            {figures.map((figure) => (
                                <FigureCard key={figure.id} figure={figure} deviceId={device.id} />
                            ))}
                        </section>
                        <Deferred data="readings" fallback={<div className="h-80 animate-pulse rounded-xl bg-muted" />}>
                            <DeviceReadingsChart figures={figures} readings={readings ?? {}} />
                        </Deferred>
                    </>
                ) : (
                    <Empty className="border border-dashed bg-background">
                        <EmptyHeader>
                            <EmptyMedia variant="icon">
                                <Gauge />
                            </EmptyMedia>
                            <EmptyTitle>No figures</EmptyTitle>
                            <EmptyDescription>This device isn't reporting any figures yet.</EmptyDescription>
                        </EmptyHeader>
                    </Empty>
                )}
            </div>
        </>
    );
}
