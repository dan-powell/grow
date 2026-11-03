import { Head } from '@inertiajs/react';
import { Gauge } from 'lucide-react';
import DeviceAlerts from '@/Components/DeviceAlerts';
import DeviceCard from '@/Components/DeviceCard';
import FigureCard from '@/Components/FigureCard';
import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/Components/ui/empty';
import type { Device } from '@/types';

export default function Show({ device }: { device: Device }) {
    const figures = device.figures ?? [];

    return (
        <>
            <Head title={device.name} />
            <div className="flex flex-col gap-8">
                <DeviceCard device={device} asHeader />
                <DeviceAlerts device={device} />
                {figures.length > 0 ? (
                    <section className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        {figures.map((figure) => (
                            <FigureCard key={figure.id} figure={figure} deviceId={device.id} />
                        ))}
                    </section>
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
