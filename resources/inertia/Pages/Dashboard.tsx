import { Head } from '@inertiajs/react';
import { Cpu } from 'lucide-react';
import DeviceCard from '@/Components/DeviceCard';
import FigureCard from '@/Components/FigureCard';
import PageHeader from '@/Components/PageHeader';
import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/Components/ui/empty';
import type { Device, Figure } from '@/types';

interface Props {
    devices: Device[];
    figures: Figure[];
}

export default function Dashboard({ devices, figures }: Props) {
    const dashboardFigures = figures.filter((figure) => figure.dashboard);

    return (
        <>
            <Head title="Dashboard" />
            <div className="flex flex-col gap-10">
                <PageHeader title="Dashboard" description="Latest readings from your devices." />
                {dashboardFigures.length > 0 && (
                    <section className="flex flex-col gap-4">
                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            {dashboardFigures.map((figure) => (
                                <FigureCard key={figure.id} figure={figure} showDevice />
                            ))}
                        </div>
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
