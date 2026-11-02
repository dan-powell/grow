import { Head } from '@inertiajs/react';
import {
    CategoryScale,
    Chart as ChartJS,
    Filler,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    TimeSeriesScale,
    Title,
    Tooltip,
    type ChartOptions,
} from 'chart.js';
import 'chartjs-adapter-moment';
import { ChartLine } from 'lucide-react';
import { useMemo } from 'react';
import { Line } from 'react-chartjs-2';
import PageHeader from '@/Components/PageHeader';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/Components/ui/empty';
import type { Datum, Device, Figure } from '@/types';

ChartJS.register(Title, Tooltip, Legend, Filler, LineElement, LinearScale, TimeSeriesScale, PointElement, CategoryScale);

interface Props {
    device: Device;
    figure: Figure;
    readings: Datum[];
}

/** Resolve a shadcn theme token (e.g. `--primary`) so Chart.js, which draws on canvas, can use it. */
function themeColor(token: string, alpha?: number): string {
    const value = getComputedStyle(document.documentElement).getPropertyValue(token).trim();

    return alpha === undefined ? value : `color-mix(in oklch, ${value} ${alpha * 100}%, transparent)`;
}

export default function History({ device, figure, readings }: Props) {
    const format = (value: number) =>
        `${figure.prefix ?? ''}${Number.isInteger(value) ? value : value.toFixed(2)}${figure.suffix ?? ''}`;

    const stats = useMemo(() => {
        if (readings.length === 0) {
            return null;
        }
        const values = readings.map((reading) => reading.value_calibrated);

        return {
            latest: readings[0],
            min: Math.min(...values),
            max: Math.max(...values),
            average: values.reduce((sum, value) => sum + value, 0) / values.length,
        };
    }, [readings]);

    const { data, options } = useMemo(() => {
        const muted = themeColor('--muted-foreground');
        const grid = themeColor('--border');

        return {
            data: {
                datasets: [
                    {
                        label: figure.name,
                        data: readings.map((reading) => ({ x: reading.timestamp, y: reading.value_calibrated })),
                        borderColor: themeColor('--primary'),
                        backgroundColor: themeColor('--primary', 0.12),
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointRadius: 0,
                        pointHoverRadius: 4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: { label: (context) => format(context.parsed.y ?? 0) },
                    },
                },
                scales: {
                    x: {
                        type: 'time',
                        time: { displayFormats: { quarter: 'MMM YYYY' } },
                        grid: { display: false },
                        ticks: { color: muted, maxRotation: 0, autoSkipPadding: 16 },
                    },
                    y: {
                        grid: { color: grid },
                        border: { display: false },
                        ticks: { color: muted },
                    },
                },
            } satisfies ChartOptions<'line'>,
        };
    }, [figure, readings]);

    return (
        <>
            <Head title={`${device.name} - ${figure.name}`} />
            <div className="flex flex-col gap-6">
                <PageHeader title={figure.name} description={`${device.name} · last 30 days`} />
                {stats ? (
                    <>
                        <dl className="grid grid-cols-2 gap-4 lg:grid-cols-4">
                            {[
                                ['Latest', stats.latest.value_string],
                                ['Minimum', format(stats.min)],
                                ['Maximum', format(stats.max)],
                                ['Average', format(stats.average)],
                            ].map(([label, value]) => (
                                <Card key={label} size="sm">
                                    <CardContent>
                                        <dt className="text-xs text-muted-foreground">{label}</dt>
                                        <dd className="text-2xl font-semibold tracking-tight tabular-nums">{value}</dd>
                                    </CardContent>
                                </Card>
                            ))}
                        </dl>
                        <Card>
                            <CardHeader>
                                <CardTitle>History</CardTitle>
                                <CardDescription>{readings.length} readings</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="h-80 sm:h-96">
                                    <Line data={data} options={options} />
                                </div>
                            </CardContent>
                        </Card>
                    </>
                ) : (
                    <Empty className="border border-dashed bg-background">
                        <EmptyHeader>
                            <EmptyMedia variant="icon">
                                <ChartLine />
                            </EmptyMedia>
                            <EmptyTitle>No readings</EmptyTitle>
                            <EmptyDescription>There are no readings for {figure.name} in the last 30 days.</EmptyDescription>
                        </EmptyHeader>
                    </Empty>
                )}
            </div>
        </>
    );
}
