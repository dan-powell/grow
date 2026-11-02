import { router, usePage, usePoll } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import type { SharedProps } from '@/types';

export const LIVE_READINGS_INTERVAL = 10_000;

/**
 * Keeps the current page up to date with new readings.
 *
 * Polls only the cheap shared `latest_reading_id` prop; when it changes on the same page,
 * reloads the page's props in place (scroll position and local state are preserved).
 */
export function useLiveReadings(interval = LIVE_READINGS_INTERVAL) {
    const { url, props } = usePage<SharedProps>();
    const latest = props.latest_reading_id;
    const seen = useRef({ url, latest });

    useEffect(() => {
        const previous = seen.current;
        seen.current = { url, latest };

        // A different URL means the user navigated, which already fetched fresh props.
        if (previous.url === url && previous.latest !== latest) {
            router.reload({ showProgress: false });
        }
    }, [url, latest]);

    usePoll(interval, { only: ['latest_reading_id'], showProgress: false });
}
