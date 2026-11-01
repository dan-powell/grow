import { Link } from '@inertiajs/react';
import { Clock, MapPin, TriangleAlert } from 'lucide-react';
import RelativeTime from '@/Components/RelativeTime';
import { Badge } from '@/Components/ui/badge';
import { Card, CardAction, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { cn } from '@/lib/utils';
import type { Device } from '@/types';

interface Props {
    device: Device;
    /** Render as a static summary (e.g. on the device's own page) rather than a link. */
    asHeader?: boolean;
}

export default function DeviceCard({ device, asHeader = false }: Props) {
    const card = (
        <Card
            className={cn(
                'h-full',
                asHeader ? 'sm:flex-row sm:items-center sm:py-0' : 'transition-shadow group-hover:shadow-lg group-hover:ring-primary/40',
            )}
        >
            {device.image_src && (
                <img
                    src={device.image_src}
                    alt=""
                    className={cn('aspect-video w-full object-cover', asHeader && 'sm:aspect-square sm:w-48 sm:rounded-none!')}
                />
            )}
            <div className={cn('flex flex-1 flex-col gap-(--card-spacing)', asHeader && 'sm:py-(--card-spacing)')}>
                <CardHeader>
                    <CardTitle className={cn(asHeader ? 'text-2xl' : 'text-lg')}>
                        {asHeader ? <h1>{device.name}</h1> : device.name}
                    </CardTitle>
                    {device.summary && <CardDescription>{device.summary}</CardDescription>}
                    {device.alerted && (
                        <CardAction>
                            <Badge variant="destructive">
                                <TriangleAlert data-icon="inline-start" />
                                Alert
                            </Badge>
                        </CardAction>
                    )}
                </CardHeader>
                <CardContent className="flex flex-wrap gap-x-4 gap-y-1 text-xs text-muted-foreground">
                    {device.location && (
                        <span className="inline-flex items-center gap-1">
                            <MapPin className="size-3.5" />
                            {device.location}
                        </span>
                    )}
                    {device.last_reading && (
                        <span className="inline-flex items-center gap-1">
                            <Clock className="size-3.5" />
                            Last reading{' '}
                            <RelativeTime
                                diff={device.last_reading.timestamp_diff}
                                formatted={device.last_reading.timestamp_formatted}
                            />
                        </span>
                    )}
                </CardContent>
            </div>
        </Card>
    );

    if (asHeader) {
        return card;
    }

    return (
        <Link
            href={route('device.show', device.id)}
            className="group rounded-4xl outline-none focus-visible:ring-3 focus-visible:ring-ring/50"
        >
            {card}
        </Link>
    );
}
