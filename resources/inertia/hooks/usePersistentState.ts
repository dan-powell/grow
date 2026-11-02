import { useEffect, useState } from 'react';

/**
 * useState backed by localStorage, so the value survives page reloads.
 * `parse` receives the stored JSON and must return a valid value (falling back to defaults),
 * because stored data may be stale or from an older version of the shape.
 * Storage can be unavailable (private mode, blocked site data); the hook then behaves like useState.
 */
export function usePersistentState<T>(key: string, initial: T, parse: (stored: unknown) => T) {
    const [value, setValue] = useState<T>(() => {
        try {
            const stored = window.localStorage.getItem(key);

            return stored === null ? initial : parse(JSON.parse(stored));
        } catch {
            return initial;
        }
    });

    useEffect(() => {
        try {
            window.localStorage.setItem(key, JSON.stringify(value));
        } catch {
            // Ignore: persistence is a convenience, not a requirement.
        }
    }, [key, value]);

    return [value, setValue] as const;
}
