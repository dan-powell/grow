import { Link } from '@inertiajs/react';
import { Clock, TriangleAlert } from 'lucide-react';
import { describeDeviceAlert, describeSubjectAlert, formatTimestamp } from '@/lib/alerts';
import { figureLabel } from '@/lib/figures';
import type { Device } from '@/types';

/** Lists a device's active alerts: its late-reading alert and its figures' threshold alerts. Renders nothing when all is well. */
export default function DeviceAlerts({ device }: { device: Device }) {
    const figureAlerts = (device.figures ?? []).flatMap((figure) =>
        figure.active_alerts.map((alert) => ({ alert, figure })),
    );

    if (!device.alerted && figureAlerts.length === 0) {
        return null;
    }

    return (
        <section
            aria-labelledby="device-alerts"
            className="flex flex-col gap-3 rounded-3xl border border-destructive/30 bg-destructive/5 p-4 sm:p-5"
        >
            <h2 id="device-alerts" className="flex items-center gap-2 font-heading font-semibold text-destructive">
                <TriangleAlert className="size-4" />
                Active alerts
            </h2>
            <ul className="flex flex-col gap-2 text-sm">
                {device.alerted && (
                    <li className="flex flex-wrap items-center gap-x-2 gap-y-0.5">
                        <Clock className="size-4 shrink-0 text-destructive" />
                        <span className="font-medium">{describeDeviceAlert(device)}</span>
                        {device.alert_activated && (
                            <span className="text-xs text-muted-foreground">
                                since {formatTimestamp(device.alert_activated)}
                            </span>
                        )}
                    </li>
                )}
                {figureAlerts.map(({ alert, figure }) => (
                    <li key={alert.id} className="flex flex-wrap items-center gap-x-2 gap-y-0.5">
                        <TriangleAlert className="size-4 shrink-0 text-destructive" />
                        <Link
                            href={route('device.figure.history', [device.id, figure.id])}
                            className="font-medium underline-offset-2 hover:underline"
                        >
                            {figureLabel(figure)}
                        </Link>
                        <span>{describeSubjectAlert(alert, figure)}</span>
                        {figure.last_reading && (
                            <span className="text-muted-foreground">(now {figure.last_reading.value_string})</span>
                        )}
                        {alert.activated && (
                            <span className="text-xs text-muted-foreground">since {formatTimestamp(alert.activated)}</span>
                        )}
                    </li>
                ))}
            </ul>
        </section>
    );
}
