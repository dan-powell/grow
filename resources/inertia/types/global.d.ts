import type { route as routeFn } from 'ziggy-js';

declare global {
    /** Ziggy's route() helper, exposed globally by the `@routes` Blade directive. */
    var route: typeof routeFn;
}
