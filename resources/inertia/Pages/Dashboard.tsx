import { Head, Link } from '@inertiajs/react';
import type { Device, Figure } from '@/types';

interface Props {
    devices: Device[];
    figures: Figure[];
}

export default function Dashboard({ devices, figures }: Props) {
    return (
        <>
            <Head title="Dashboard" />
            <section className="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {figures
                    .filter((figure) => figure.dashboard)
                    .map((figure) => (
                        <div key={figure.id} className="rounded border p-4">
                            <div className="text-sm opacity-70">{figure.device?.name}</div>
                            <div className="font-semibold">{figure.name}</div>
                            <div className="text-2xl">{figure.last_reading?.value_string ?? '—'}</div>
                        </div>
                    ))}
            </section>
            <section className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {devices.map((device) => (
                    <Link key={device.id} href={route('device.show', device.id)} className="rounded border p-4">
                        <div className="font-semibold">{device.name}</div>
                        {device.summary && <p className="text-sm opacity-70">{device.summary}</p>}
                    </Link>
                ))}
            </section>
        </>
    );
}
