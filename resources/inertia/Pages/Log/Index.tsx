import { Head, Link } from '@inertiajs/react';
import type { Log, Paginator } from '@/types';

interface Props {
    logs: Paginator<Log>;
    severities: string[];
}

export default function Index({ logs, severities }: Props) {
    return (
        <>
            <Head title="Log" />
            <div className="mb-4 flex flex-wrap items-center justify-between gap-4">
                <nav className="flex gap-1">
                    {logs.links.map((link, index) =>
                        link.url ? (
                            <Link
                                key={index}
                                href={link.url}
                                preserveScroll
                                className={`rounded border px-2 py-1 ${link.active ? 'font-bold' : ''}`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <span
                                key={index}
                                className="rounded border px-2 py-1 opacity-50"
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ),
                    )}
                </nav>
                <div className="flex gap-1">
                    <Link href={route('log.index')} className="rounded border px-2 py-1">
                        All
                    </Link>
                    {severities.map((severity) => (
                        <Link key={severity} href={route('log.index', { severity })} className="rounded border px-2 py-1">
                            {severity}
                        </Link>
                    ))}
                </div>
            </div>
            <ul className="divide-y">
                {logs.data.map((log) => (
                    <li key={log.id} className="py-2">
                        <div className="font-semibold">{log.name}</div>
                        {log.summary && <p className="text-sm">{log.summary}</p>}
                        <time className="text-xs opacity-70" title={log.created_at_formatted}>
                            {log.created_at_diff}
                        </time>
                    </li>
                ))}
            </ul>
        </>
    );
}
