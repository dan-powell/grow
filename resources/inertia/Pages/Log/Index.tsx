import { Head, Link, usePage } from '@inertiajs/react';
import { ListChecks } from 'lucide-react';
import LogItem from '@/Components/LogItem';
import PageHeader from '@/Components/PageHeader';
import Paginator from '@/Components/Paginator';
import { Button } from '@/Components/ui/button';
import { Card } from '@/Components/ui/card';
import { Empty, EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/Components/ui/empty';
import type { Log, Paginator as PaginatorData } from '@/types';

interface Props {
    logs: PaginatorData<Log>;
    severities: string[];
}

export default function Index({ logs, severities }: Props) {
    const { url } = usePage();
    const currentSeverity = new URLSearchParams(url.split('?')[1] ?? '').get('severity');
    const filters: { label: string; severity: string | null }[] = [
        { label: 'All', severity: null },
        ...severities.map((severity) => ({ label: severity, severity })),
    ];

    return (
        <>
            <Head title="Log" />
            <div className="flex flex-col gap-6">
                <PageHeader
                    title="Log"
                    description={logs.total > 0 ? `Showing ${logs.from}–${logs.to} of ${logs.total} entries` : undefined}
                    actions={
                        <nav aria-label="Filter by severity" className="flex flex-wrap gap-1">
                            {filters.map(({ label, severity }) => (
                                <Button
                                    key={label}
                                    size="sm"
                                    variant={severity === currentSeverity ? 'default' : 'outline'}
                                    className="capitalize"
                                    nativeButton={false}
                                    render={<Link href={route('log.index', severity ? { severity } : {})} preserveScroll />}
                                >
                                    {label}
                                </Button>
                            ))}
                        </nav>
                    }
                />
                {logs.data.length > 0 ? (
                    <Card className="py-0">
                        <ul className="divide-y">
                            {logs.data.map((log) => (
                                <LogItem key={log.id} log={log} />
                            ))}
                        </ul>
                    </Card>
                ) : (
                    <Empty className="border border-dashed bg-background">
                        <EmptyHeader>
                            <EmptyMedia variant="icon">
                                <ListChecks />
                            </EmptyMedia>
                            <EmptyTitle>No log entries</EmptyTitle>
                            <EmptyDescription>Nothing has been logged{currentSeverity ? ` at ${currentSeverity} level` : ''} yet.</EmptyDescription>
                        </EmptyHeader>
                    </Empty>
                )}
                <Paginator paginator={logs} />
            </div>
        </>
    );
}
