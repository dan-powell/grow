import type { Figure } from '@/types';

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
}

export const defaultFigureFilters: FigureFilters = {
    search: '',
    deviceId: 'all',
    alertsOnly: false,
    sort: 'order',
    descending: false,
};

/** Sort key per option; `null` means "no data" and always sorts last whatever the direction. */
const sortKeys: Record<FigureSort, (figure: Figure) => string | number | null> = {
    order: (figure) => figure.order,
    name: (figure) => figure.name.toLocaleLowerCase(),
    value: (figure) => figure.last_reading?.value_calibrated ?? null,
    updated: (figure) => (figure.last_reading ? Date.parse(figure.last_reading.timestamp) : null),
    range: (figure) => figure.last_reading?.range_percentage ?? null,
};

export function applyFigureFilters(figures: Figure[], filters: FigureFilters): Figure[] {
    const terms = filters.search.toLocaleLowerCase().split(/\s+/).filter(Boolean);
    const sortKey = sortKeys[filters.sort];
    const direction = filters.descending ? -1 : 1;

    return figures
        .filter((figure) => filters.deviceId === 'all' || String(figure.device_id) === filters.deviceId)
        .filter((figure) => !filters.alertsOnly || figure.active_alerts.length > 0)
        .filter((figure) => {
            if (terms.length === 0) {
                return true;
            }
            const haystack = [figure.name, figure.key, figure.summary, figure.device?.name]
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
