import type { Datum } from '@/types';

/** Vertical meter, filling from the bottom, showing where a reading sits between the figure's range_min and range_max. */
export default function RangeBar({ reading }: { reading: Datum }) {
    if (reading.range_percentage === null) {
        return null;
    }

    const percentage = Math.min(100, Math.max(0, reading.range_percentage));

    return (
        <div
            className="flex h-full w-2 flex-col justify-end overflow-hidden rounded-full bg-muted"
            role="meter"
            aria-orientation="vertical"
            aria-valuemin={0}
            aria-valuemax={100}
            aria-valuenow={Math.round(percentage)}
        >
            <div
                className="w-full rounded-full bg-primary transition-[height]"
                style={{ height: `${percentage}%`, backgroundColor: reading.range_color ?? undefined }}
            />
        </div>
    );
}
