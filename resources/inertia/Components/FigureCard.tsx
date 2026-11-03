import { Link } from '@inertiajs/react';
import { Clock, Cpu, Gauge, TriangleAlert } from 'lucide-react';
import RangeBar from '@/Components/RangeBar';
import RelativeTime from '@/Components/RelativeTime';
import { Badge } from '@/Components/ui/badge';
import { Card, CardAction, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/Components/ui/tooltip';
import { describeSubjectAlert, formatTimestamp } from '@/lib/alerts';
import { figureLabel } from '@/lib/figures';
import type { Figure } from '@/types';

interface Props {
    figure: Figure;
    deviceId?: string;
    showDevice?: boolean;
}

export default function FigureCard({ figure, deviceId, showDevice = false }: Props) {
    const reading = figure.last_reading;
    const subject = figure.current_subject;
    const summary = subject?.summary ?? figure.summary;
    const alertCount = figure.active_alerts.length;
    const hasRange = reading !== null && reading.range_percentage !== null;

    return (
        <Link
            href={route('device.figure.history', [deviceId ?? figure.device_id, figure.id])}
            className="group rounded-4xl outline-none focus-visible:ring-3 focus-visible:ring-ring/50"
        >
            <Card className="h-full flex-row gap-0 transition-shadow group-hover:shadow-lg group-hover:ring-primary/40">
                {hasRange && (
                    <div className="shrink-0 pl-(--card-spacing)">
                        <RangeBar reading={reading} />
                    </div>
                )}
                <div className="flex min-w-0 flex-1 flex-col gap-(--card-spacing)">
                    <CardHeader>
                        <div className="flex items-center gap-3">
                            {figure.icon_src && (
                                <div className="relative shrink-0">
                                    <img src={figure.icon_src} alt="" className="size-12 object-contain" />
                                    {figure.icon_small_src && (
                                        <img
                                            src={figure.icon_small_src}
                                            alt=""
                                            className="absolute -right-1 -bottom-1 size-5 object-contain"
                                        />
                                    )}
                                </div>
                            )}
                            <div className="min-w-0">
                                <CardTitle className="truncate">{figureLabel(figure)}</CardTitle>
                                {summary && <CardDescription className="line-clamp-2">{summary}</CardDescription>}
                            </div>
                        </div>
                        {alertCount > 0 && (
                            <CardAction>
                                <Tooltip>
                                    <TooltipTrigger render={<span />}>
                                        <Badge variant="destructive">
                                            <TriangleAlert data-icon="inline-start" />
                                            {alertCount}
                                        </Badge>
                                    </TooltipTrigger>
                                    <TooltipContent>
                                        <ul className="flex flex-col gap-0.5">
                                            {figure.active_alerts.map((alert) => (
                                                <li key={alert.id}>
                                                    <span className="font-medium">{describeSubjectAlert(alert, figure)}</span>
                                                    {alert.activated && <> since {formatTimestamp(alert.activated)}</>}
                                                </li>
                                            ))}
                                        </ul>
                                    </TooltipContent>
                                </Tooltip>
                            </CardAction>
                        )}
                    </CardHeader>
                    <CardContent className="mt-auto flex flex-col gap-2">
                        <div className="text-4xl font-semibold tracking-tight tabular-nums">
                            {reading?.value_string ?? <span className="text-muted-foreground">—</span>}
                        </div>
                        <div className="flex flex-col gap-1 text-xs text-muted-foreground">
                            <span className="flex items-center gap-1">
                                <Clock className="size-3.5 shrink-0" />
                                {reading ? (
                                    <RelativeTime diff={reading.timestamp_diff} formatted={reading.timestamp_formatted} />
                                ) : (
                                    'No readings'
                                )}
                            </span>
                            {subject && (
                                <span className="flex min-w-0 items-center gap-1">
                                    <Gauge className="size-3.5 shrink-0" />
                                    <span className="truncate">{figure.name}</span>
                                </span>
                            )}
                            {showDevice && (
                                <span className="flex min-w-0 items-center gap-1">
                                    <Cpu className="size-3.5 shrink-0" />
                                    <span className="truncate">{figure.device?.name}</span>
                                </span>
                            )}
                        </div>
                    </CardContent>
                </div>
            </Card>
        </Link>
    );
}
