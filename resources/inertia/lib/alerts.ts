import type { Device, Figure, SubjectAlert } from '@/types';

const timestampFormat = new Intl.DateTimeFormat(undefined, { dateStyle: 'medium', timeStyle: 'short' });

/** A local, human-readable date and time for an ISO timestamp. */
export function formatTimestamp(timestamp: string): string {
    return timestampFormat.format(new Date(timestamp));
}

/** What a threshold alert is watching for, e.g. "Below 30%", using the figure's prefix/suffix. */
export function describeSubjectAlert(alert: SubjectAlert, figure: Figure): string {
    return `${alert.lower ? 'Below' : 'Above'} ${figure.prefix ?? ''}${alert.value}${figure.suffix ?? ''}`;
}

/** What a device's late-reading alert means, e.g. "No readings for over 2 hours". */
export function describeDeviceAlert(device: Device): string {
    const minutes = device.alert_timeout;
    if (!minutes) {
        return 'No recent readings';
    }
    const [amount, unit] = minutes % 60 === 0 ? [minutes / 60, 'hour'] : [minutes, 'minute'];

    return `No readings for over ${amount} ${unit}${amount === 1 ? '' : 's'}`;
}
