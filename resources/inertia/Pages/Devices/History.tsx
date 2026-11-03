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
    type Plugin,
} from 'chart.js';
import 'chartjs-adapter-moment';
import { ChartLine } from 'lucide-react';
import { useMemo } from 'react';
import { Line } from 'react-chartjs-2';
import PageHeader from '@/Components/PageHeader';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/Components/ui/empty';
import { themeColor } from '@/lib/chart';
import { figureLabel } from '@/lib/figures';
import type { Datum, Device, Figure, FigureAssignment } from '@/types';

ChartJS.register(Title, Tooltip, Legend, Filler, LineElement, LinearScale, TimeSeriesScale, PointElement, CategoryScale);

interface Props {
    device: Device;
    figure: Figure;
    assignments: FigureAssignment[];
    readings: Datum[];
}

interface Marker {
    time: number;
    label: string;
}

/**
 * Points on the time axis where the measured subject changed: each assignment's start, plus
 * any end that isn't immediately followed by another assignment (the figure became unassigned).
 */
function assignmentMarkers(assignments: FigureAssignment[]): Marker[] {
    const starts = assignments.map((assignment) => ({
        time: Date.parse(assignment.started_at),
        label: assignment.subject?.name ?? 'Subject',
    }));
    const ends = assignments
        .filter((assignment) => assignment.ended_at !== null)
        .map((assignment) => Date.parse(assignment.ended_at!))
        .filter((time) => !starts.some((start) => start.time === time))
        .map((time) => ({ time, label: 'Unassigned' }));

    return [...starts, ...ends];
}

/** Draws a dashed vertical line and label at each marker inside the chart area. */
function markerPlugin(markers: Marker[], color: string): Plugin<'line'> {
    return {
        id: 'assignmentMarkers',
        afterDatasetsDraw(chart) {
            const { ctx, chartArea, scales } = chart;
            ctx.save();
            ctx.strokeStyle = color;
            ctx.fillStyle = color;
            ctx.setLineDash([4, 4]);
            ctx.font = '12px sans-serif';
            ctx.textBaseline = 'top';
            for (const marker of markers) {
                const x = scales.x.getPixelForValue(marker.time);
                if (x < chartArea.left || x > chartArea.right) {
                    continue;
                }
                ctx.beginPath();
                ctx.moveTo(x, chartArea.top);
                ctx.lineTo(x, chartArea.bottom);
                ctx.stroke();
                ctx.fillText(marker.label, x + 4, chartArea.top + 4);
            }
            ctx.restore();
        },
    };
}

export default function History({ device, figure, assignments, readings }: Props) {
    const label = figureLabel(figure);
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

    const { data, options, plugins } = useMemo(() => {
        const muted = themeColor('--muted-foreground');
        const grid = themeColor('--border');

        return {
            data: {
                datasets: [
                    {
                        label,
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
            plugins: [markerPlugin(assignmentMarkers(assignments), muted)],
        };
    }, [figure, label, assignments, readings]);

    return (
        <>
            <Head title={`${device.name} - ${label}`} />
            <div className="flex flex-col gap-6">
                <PageHeader
                    title={label}
                    description={[figure.current_subject && figure.name, device.name, 'last 30 days'].filter(Boolean).join(' · ')}
                />
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
                                    <Line data={data} options={options} plugins={plugins} />
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
                            <EmptyDescription>There are no readings for {label} in the last 30 days.</EmptyDescription>
                        </EmptyHeader>
                    </Empty>
                )}
            </div>
        </>
    );
}
