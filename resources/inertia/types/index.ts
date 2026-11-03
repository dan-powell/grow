/**
 * Shapes of the Eloquent models as serialised into Inertia props.
 * Primary and foreign keys are ULID strings.
 * Keep in sync with the #[Appends] / #[Fillable] attributes in app/Models.
 */

export interface Breadcrumb {
    title: string;
    url: string;
    is_current_page: boolean;
}

export interface SharedProps {
    breadcrumbs: Breadcrumb[];
    latest_reading_id: string | null;
    [key: string]: unknown;
}

/** A calibrated reading reduced to a chart point: ISO timestamp and value. */
export interface ReadingPoint {
    x: string;
    y: number;
}

export interface Datum {
    id: string;
    figure_id: string;
    name: string | null;
    value: number;
    value_calibrated: number;
    value_string: string;
    range_percentage: number | null;
    range_color: string | null;
    timestamp: string;
    timestamp_formatted: string;
    timestamp_date: string;
    timestamp_time: string;
    timestamp_diff: string;
    created_at_formatted: string;
    created_at_diff: string;
}

export interface SubjectAlert {
    id: string;
    subject_id: string;
    enabled: boolean;
    value: number;
    lower: boolean;
    activated: string | null;
}

/** The thing a figure measures, such as a plant. */
export interface Subject {
    id: string;
    name: string;
    summary: string | null;
    image_src: string | null;
    icon_src: string | null;
}

/** A period during which a figure measured a subject; `ended_at` is null while current. */
export interface FigureAssignment {
    id: string;
    figure_id: string;
    subject_id: string;
    started_at: string;
    ended_at: string | null;
    subject?: Subject;
}

/** A sensor channel on a device. Its `name` describes the sensor; see `figureLabel()` for display. */
export interface Figure {
    id: string;
    device_id: string;
    name: string;
    key: string;
    summary: string | null;
    icon: string | null;
    icon_src: string | null;
    icon_small_src: string | null;
    prefix: string | null;
    suffix: string | null;
    dashboard: boolean;
    range_min: number | null;
    range_max: number | null;
    order: number;
    last_reading: Datum | null;
    active_alerts: SubjectAlert[];
    current_subject: Subject | null;
    device?: Device;
}

export interface Device {
    id: string;
    name: string;
    nickname: string | null;
    summary: string | null;
    location: string | null;
    image_src: string | null;
    alerted: boolean;
    /** Minutes without a reading before the late-reading alert triggers. */
    alert_timeout: number | null;
    alert_activated: string | null;
    alert_activated_formatted: string | null;
    order: number;
    last_reading: Datum | null;
    figures?: Figure[];
}

export interface Log {
    id: string;
    name: string;
    summary: string | null;
    severity: string | null;
    icon: string | null;
    device_id: string | null;
    created_at_formatted: string;
    created_at_date: string;
    created_at_time: string;
    created_at_diff: string;
}

export interface Paginator<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    prev_page_url: string | null;
    next_page_url: string | null;
    links: { url: string | null; label: string; active: boolean }[];
}
