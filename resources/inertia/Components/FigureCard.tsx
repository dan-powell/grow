import { Link } from '@inertiajs/react';
import { Clock, Cpu, TriangleAlert } from 'lucide-react';
import RangeBar from '@/Components/RangeBar';
import RelativeTime from '@/Components/RelativeTime';
import { Badge } from '@/Components/ui/badge';
import { Card, CardAction, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/Components/ui/card';
import { Tooltip, TooltipContent, TooltipTrigger } from '@/Components/ui/tooltip';
import type { Figure } from '@/types';

interface Props {
    figure: Figure;
    deviceId?: number;
    showDevice?: boolean;
}

export default function FigureCard({ figure, deviceId, showDevice = false }: Props) {
    const reading = figure.last_reading;
    const alertCount = figure.active_alerts.length;

    return (
        <Link
            href={route('device.figure.history', [deviceId ?? figure.device_id, figure.id])}
            className="group rounded-4xl outline-none focus-visible:ring-3 focus-visible:ring-ring/50"
        >
            <Card className="h-full transition-shadow group-hover:shadow-lg group-hover:ring-primary/40">
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
                            <CardTitle className="truncate">{figure.name}</CardTitle>
                            {figure.summary && <CardDescription className="line-clamp-2">{figure.summary}</CardDescription>}
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
                                    {alertCount === 1 ? '1 active alert' : `${alertCount} active alerts`}
                                </TooltipContent>
                            </Tooltip>
                        </CardAction>
                    )}
                </CardHeader>
                <CardContent className="mt-auto flex flex-col gap-2">
                    <div className="text-4xl font-semibold tracking-tight tabular-nums">
                        {reading?.value_string ?? <span className="text-muted-foreground">—</span>}
                    </div>
                    <div className="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted-foreground">
                        {reading && (
                            <span className="inline-flex items-center gap-1">
                                <Clock className="size-3.5" />
                                <RelativeTime diff={reading.timestamp_diff} formatted={reading.timestamp_formatted} />
                            </span>
                        )}
                        {showDevice && figure.device && (
                            <span className="inline-flex items-center gap-1">
                                <Cpu className="size-3.5" />
                                {figure.device.name}
                            </span>
                        )}
                    </div>
                </CardContent>
                {reading && reading.range_percentage !== null && (
                    <CardFooter>
                        <RangeBar reading={reading} />
                    </CardFooter>
                )}
            </Card>
        </Link>
    );
}
