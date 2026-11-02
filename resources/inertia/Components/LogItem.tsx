import { CircleCheck, Info, TriangleAlert, type LucideIcon } from 'lucide-react';
import RelativeTime from '@/Components/RelativeTime';
import { cn } from '@/lib/utils';
import type { Log } from '@/types';

const severityStyles: Record<string, { icon: LucideIcon; className: string }> = {
    warning: { icon: TriangleAlert, className: 'bg-amber-500/10 text-amber-600 dark:text-amber-400' },
    info: { icon: Info, className: 'bg-sky-500/10 text-sky-600 dark:text-sky-400' },
    success: { icon: CircleCheck, className: 'bg-primary/10 text-primary' },
};

export default function LogItem({ log }: { log: Log }) {
    const severity = severityStyles[log.severity ?? ''] ?? severityStyles.info;
    const Icon = severity.icon;

    return (
        <li className="flex items-start gap-4 px-(--card-spacing) py-4">
            <span className={cn('flex size-9 shrink-0 items-center justify-center rounded-full', severity.className)}>
                <Icon className="size-4" />
                <span className="sr-only">{log.severity}</span>
            </span>
            <div className="min-w-0 flex-1">
                <p className="font-medium">{log.name}</p>
                {log.summary && <p className="text-sm text-muted-foreground">{log.summary}</p>}
            </div>
            <RelativeTime
                diff={log.created_at_diff}
                formatted={log.created_at_formatted}
                className="shrink-0 text-xs whitespace-nowrap text-muted-foreground"
            />
        </li>
    );
}
