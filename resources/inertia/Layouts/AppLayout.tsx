import { Link, usePage } from '@inertiajs/react';
import type { ReactNode } from 'react';
import type { SharedProps } from '@/types';

export default function AppLayout({ children }: { children: ReactNode }) {
    const { breadcrumbs } = usePage<SharedProps>().props;

    return (
        <div className="flex min-h-screen flex-col">
            <header className="flex items-center gap-4 border-b px-4 py-3">
                <span className="font-bold">Grow Log</span>
                <nav aria-label="breadcrumbs" className="flex-1">
                    <ol className="flex gap-2">
                        {breadcrumbs.map((breadcrumb) => (
                            <li key={breadcrumb.url}>
                                <Link
                                    href={breadcrumb.url}
                                    className={breadcrumb.is_current_page ? 'font-semibold' : 'opacity-70 hover:opacity-100'}
                                >
                                    {breadcrumb.title}
                                </Link>
                            </li>
                        ))}
                    </ol>
                </nav>
                <Link href={route('log.index')} aria-label="Log">
                    <span className="fa-solid fa-list-check" />
                </Link>
            </header>
            <main className="flex-1 p-4">{children}</main>
        </div>
    );
}
