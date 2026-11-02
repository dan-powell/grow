import { ArrowDownWideNarrow, ArrowUpNarrowWide, Search, TriangleAlert, X } from 'lucide-react';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { defaultFigureFilters, figureSortOptions, type FigureFilters, type FigureSort } from '@/lib/figures';
import type { Device } from '@/types';

interface Props {
    filters: FigureFilters;
    onChange: (filters: FigureFilters) => void;
    devices: Device[];
}

export default function FigureToolbar({ filters, onChange, devices }: Props) {
    const update = (changes: Partial<FigureFilters>) => onChange({ ...filters, ...changes });
    const isFiltered =
        filters.search !== '' || filters.deviceId !== defaultFigureFilters.deviceId || filters.alertsOnly;
    const deviceItems = {
        all: 'All devices',
        ...Object.fromEntries(devices.map((device) => [device.id, device.name])),
    };

    return (
        <div className="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
            <div className="relative sm:max-w-xs sm:flex-1">
                <Search className="pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2 text-muted-foreground" />
                <Input
                    type="search"
                    value={filters.search}
                    onChange={(event) => update({ search: event.target.value })}
                    placeholder="Search figures…"
                    aria-label="Search figures"
                    className="pl-9"
                />
            </div>
            <div className="flex flex-wrap items-center gap-2">
                {devices.length > 1 && (
                    <Select
                        items={deviceItems}
                        value={filters.deviceId}
                        onValueChange={(deviceId) => update({ deviceId: deviceId ?? 'all' })}
                    >
                        <SelectTrigger aria-label="Filter by device" className="min-w-36">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            {Object.entries(deviceItems).map(([value, label]) => (
                                <SelectItem key={value} value={value}>
                                    {label}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                )}
                <Button
                    variant={filters.alertsOnly ? 'destructive' : 'outline'}
                    aria-pressed={filters.alertsOnly}
                    onClick={() => update({ alertsOnly: !filters.alertsOnly })}
                >
                    <TriangleAlert data-icon="inline-start" />
                    Alerts
                </Button>
                <div className="flex items-center gap-1">
                    <Select
                        items={figureSortOptions}
                        value={filters.sort}
                        onValueChange={(sort) => update({ sort: (sort ?? 'order') as FigureSort })}
                    >
                        <SelectTrigger aria-label="Sort figures by" className="min-w-40">
                            <SelectValue />
                        </SelectTrigger>
                        <SelectContent>
                            {Object.entries(figureSortOptions).map(([value, label]) => (
                                <SelectItem key={value} value={value}>
                                    {label}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    <Button
                        variant="outline"
                        size="icon"
                        aria-label={filters.descending ? 'Sort ascending' : 'Sort descending'}
                        onClick={() => update({ descending: !filters.descending })}
                    >
                        {filters.descending ? <ArrowDownWideNarrow /> : <ArrowUpNarrowWide />}
                    </Button>
                </div>
                {isFiltered && (
                    <Button
                        variant="ghost"
                        onClick={() =>
                            update({
                                search: defaultFigureFilters.search,
                                deviceId: defaultFigureFilters.deviceId,
                                alertsOnly: defaultFigureFilters.alertsOnly,
                            })
                        }
                    >
                        <X data-icon="inline-start" />
                        Clear
                    </Button>
                )}
            </div>
        </div>
    );
}
