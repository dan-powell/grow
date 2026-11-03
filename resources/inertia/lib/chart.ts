/** Resolve a shadcn theme token (e.g. `--primary`) so Chart.js, which draws on canvas, can use it. */
export function themeColor(token: string, alpha?: number): string {
    const value = getComputedStyle(document.documentElement).getPropertyValue(token).trim();

    return alpha === undefined ? value : `color-mix(in oklch, ${value} ${alpha * 100}%, transparent)`;
}

/** Number of categorical `--series-n` colours defined in `app.css`. */
export const SERIES_COUNT = 8;

/**
 * The colour token for the nth series (0-based). Slots are assigned in fixed order and never
 * cycled; anything past the palette falls back to muted grey rather than repeating a hue.
 */
export function seriesToken(index: number): string {
    return index < SERIES_COUNT ? `--series-${index + 1}` : '--muted-foreground';
}
