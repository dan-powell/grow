import type { Device, Figure } from '@/types';

/** The name to show for a figure: what it is measuring, falling back to the sensor's own name. */
export function figureLabel(figure: Figure): string {
    return figure.current_subject?.name ?? figure.name;
}

export const figureSortOptions = {
    order: 'Default order',
    name: 'Name',
    value: 'Latest value',
    updated: 'Last updated',
    range: 'Range position',
} as const;

export type FigureSort = keyof typeof figureSortOptions;

export interface FigureFilters {
    search: string;
    deviceId: string;
    alertsOnly: boolean;
    sort: FigureSort;
    descending: boolean;
    groupByDevice: boolean;
}

export const defaultFigureFilters: FigureFilters = {
    search: '',
    deviceId: 'all',
    alertsOnly: false,
    sort: 'order',
    descending: false,
    groupByDevice: true,
};

/** Rebuild filters from untrusted stored data, keeping only well-typed fields and falling back to defaults. */
export function parseFigureFilters(stored: unknown): FigureFilters {
    const value = (typeof stored === 'object' && stored !== null ? stored : {}) as Record<string, unknown>;

    return {
        search: typeof value.search === 'string' ? value.search : defaultFigureFilters.search,
        deviceId: typeof value.deviceId === 'string' ? value.deviceId : defaultFigureFilters.deviceId,
        alertsOnly: typeof value.alertsOnly === 'boolean' ? value.alertsOnly : defaultFigureFilters.alertsOnly,
        sort:
            typeof value.sort === 'string' && value.sort in figureSortOptions
                ? (value.sort as FigureSort)
                : defaultFigureFilters.sort,
        descending: typeof value.descending === 'boolean' ? value.descending : defaultFigureFilters.descending,
        groupByDevice:
            typeof value.groupByDevice === 'boolean' ? value.groupByDevice : defaultFigureFilters.groupByDevice,
    };
}

/** Sort key per option; `null` means "no data" and always sorts last whatever the direction. */
const sortKeys: Record<FigureSort, (figure: Figure) => string | number | null> = {
    order: (figure) => figure.order,
    name: (figure) => figureLabel(figure).toLocaleLowerCase(),
    value: (figure) => figure.last_reading?.value_calibrated ?? null,
    updated: (figure) => (figure.last_reading ? Date.parse(figure.last_reading.timestamp) : null),
    range: (figure) => figure.last_reading?.range_percentage ?? null,
};

/** Split already-sorted figures into one group per device, in the given device order, skipping empty groups. */
export function groupFiguresByDevice(figures: Figure[], devices: Device[]): { device: Device; figures: Figure[] }[] {
    return devices
        .map((device) => ({ device, figures: figures.filter((figure) => figure.device_id === device.id) }))
        .filter((group) => group.figures.length > 0);
}

export function applyFigureFilters(figures: Figure[], filters: FigureFilters): Figure[] {
    const terms = filters.search.toLocaleLowerCase().split(/\s+/).filter(Boolean);
    const sortKey = sortKeys[filters.sort];
    const direction = filters.descending ? -1 : 1;

    return figures
        .filter((figure) => filters.deviceId === 'all' || figure.device_id === filters.deviceId)
        .filter((figure) => !filters.alertsOnly || figure.active_alerts.length > 0)
        .filter((figure) => {
            if (terms.length === 0) {
                return true;
            }
            const haystack = [figure.current_subject?.name, figure.name, figure.key, figure.summary, figure.device?.name]
                .filter(Boolean)
                .join(' ')
                .toLocaleLowerCase();

            return terms.every((term) => haystack.includes(term));
        })
        .sort((a, b) => {
            const left = sortKey(a);
            const right = sortKey(b);
            if (left === null || right === null) {
                return left === right ? 0 : left === null ? 1 : -1;
            }
            if (left === right) {
                return a.order - b.order;
            }

            return (left < right ? -1 : 1) * direction;
        });
}
