import { Head, Link } from '@inertiajs/react';
import type { Device } from '@/types';

export default function Show({ device }: { device: Device }) {
    return (
        <>
            <Head title={device.name} />
            <section className="mb-6">
                <h1 className="text-2xl font-bold">{device.name}</h1>
                {device.summary && <p className="opacity-70">{device.summary}</p>}
            </section>
            <section className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {device.figures?.map((figure) => (
                    <Link
                        key={figure.id}
                        href={route('device.figure.history', [device.id, figure.id])}
                        className="rounded border p-4"
                    >
                        <div className="font-semibold">{figure.name}</div>
                        <div className="text-2xl">{figure.last_reading?.value_string ?? '—'}</div>
                        {figure.last_reading && <div className="text-sm opacity-70">{figure.last_reading.timestamp_diff}</div>}
                    </Link>
                ))}
            </section>
        </>
    );
}
