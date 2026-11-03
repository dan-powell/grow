import {
    Chart as ChartJS,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    TimeSeriesScale,
    Tooltip,
    type ChartData,
    type ChartOptions,
} from 'chart.js';
import 'chartjs-adapter-moment';
import { useMemo } from 'react';
import { Line } from 'react-chartjs-2';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { seriesToken, themeColor } from '@/lib/chart';
import { figureLabel } from '@/lib/figures';
import type { Figure, ReadingPoint } from '@/types';

ChartJS.register(Tooltip, Legend, LineElement, LinearScale, TimeSeriesScale, PointElement);

interface Props {
    figures: Figure[];
    /** Readings keyed by figure ID. */
    readings: Record<string, ReadingPoint[]>;
}

interface UnitGroup {
    unit: string;
    figures: { figure: Figure; color: string; points: ReadingPoint[] }[];
}

function formatValue(figure: Figure, value: number): string {
    return `${figure.prefix ?? ''}${Number.isInteger(value) ? value : value.toFixed(2)}${figure.suffix ?? ''}`;
}

/**
 * Figures in different units can't share a y-scale, so each unit (prefix + suffix) gets its own
 * panel. Colours follow the figure's position on the device, so they stay put across panels.
 */
function groupByUnit(figures: Figure[], readings: Props['readings']): UnitGroup[] {
    const groups = new Map<string, UnitGroup>();
    figures.forEach((figure, index) => {
        const points = readings[figure.id] ?? [];
        if (points.length === 0) {
            return;
        }
        const unit = [figure.prefix, figure.suffix].filter(Boolean).join(' … ') || 'No unit';
        const group = groups.get(unit) ?? { unit, figures: [] };
        group.figures.push({ figure, color: themeColor(seriesToken(index)), points });
        groups.set(unit, group);
    });

    return [...groups.values()];
}

export default function DeviceReadingsChart({ figures, readings }: Props) {
    const { groups, range, muted, grid, foreground } = useMemo(() => {
        const groups = groupByUnit(figures, readings);
        const times = groups.flatMap((group) => group.figures.flatMap(({ points }) => points.map((point) => Date.parse(point.x))));

        return {
            groups,
            // Shared bounds keep the panels' time axes lined up with each other
            range: { min: Math.min(...times), max: Math.max(...times) },
            muted: themeColor('--muted-foreground'),
            grid: themeColor('--border'),
            foreground: themeColor('--foreground'),
        };
    }, [figures, readings]);

    return (
        <Card>
            <CardHeader>
                <CardTitle>Readings</CardTitle>
                <CardDescription>All figures, last 30 days</CardDescription>
            </CardHeader>
            <CardContent className="flex flex-col gap-6">
                {groups.length === 0 ? (
                    <p className="text-sm text-muted-foreground">There are no readings in the last 30 days.</p>
                ) : (
                    groups.map((group) => {
                        const data: ChartData<'line', ReadingPoint[]> = {
                            datasets: group.figures.map(({ figure, color, points }) => ({
                                label: figureLabel(figure),
                                data: points,
                                borderColor: color,
                                backgroundColor: color,
                                borderWidth: 2,
                                tension: 0.3,
                                pointRadius: 0,
                                pointHoverRadius: 4,
                            })),
                        };
                        const options: ChartOptions<'line'> = {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'nearest', axis: 'x', intersect: false },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { color: foreground, usePointStyle: true, pointStyle: 'line', boxWidth: 16 },
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (context) =>
                                            `${context.dataset.label}: ${formatValue(group.figures[context.datasetIndex].figure, context.parsed.y ?? 0)}`,
                                    },
                                },
                            },
                            scales: {
                                x: {
                                    type: 'time',
                                    ...range,
                                    grid: { display: false },
                                    ticks: { color: muted, maxRotation: 0, autoSkipPadding: 16 },
                                },
                                y: {
                                    grid: { color: grid },
                                    border: { display: false },
                                    ticks: { color: muted },
                                },
                            },
                        };

                        return (
                            <section key={group.unit} className="flex flex-col gap-2">
                                {groups.length > 1 && <h3 className="text-sm font-medium text-muted-foreground">{group.unit}</h3>}
                                <div className="h-64 sm:h-72">
                                    <Line data={data} options={options} />
                                </div>
                            </section>
                        );
                    })
                )}
            </CardContent>
        </Card>
    );
}
