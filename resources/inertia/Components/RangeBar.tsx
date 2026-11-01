import type { Datum } from '@/types';

/** Horizontal meter showing where a reading sits between the figure's range_min and range_max. */
export default function RangeBar({ reading }: { reading: Datum }) {
    if (reading.range_percentage === null) {
        return null;
    }

    const percentage = Math.min(100, Math.max(0, reading.range_percentage));

    return (
        <div
            className="h-2 w-full overflow-hidden rounded-full bg-muted"
            role="meter"
            aria-valuemin={0}
            aria-valuemax={100}
            aria-valuenow={Math.round(percentage)}
        >
            <div
                className="h-full rounded-full bg-primary transition-[width]"
                style={{ width: `${percentage}%`, backgroundColor: reading.range_color ?? undefined }}
            />
        </div>
    );
}
