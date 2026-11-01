import { Link } from '@inertiajs/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { Button } from '@/Components/ui/button';
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem } from '@/Components/ui/pagination';
import type { Paginator as PaginatorData } from '@/types';

/**
 * Renders a Laravel length-aware paginator. The first and last entries of
 * `links` are always Previous/Next; the rest are page numbers or "..." separators.
 */
export default function Paginator<T>({ paginator }: { paginator: PaginatorData<T> }) {
    if (paginator.last_page <= 1) {
        return null;
    }

    const pages = paginator.links.slice(1, -1);

    return (
        <Pagination>
            <PaginationContent>
                <PaginationItem>
                    <PageButton href={paginator.prev_page_url} label="Previous page">
                        <ChevronLeft />
                    </PageButton>
                </PaginationItem>
                {pages.map((link, index) => (
                    <PaginationItem key={index} className="hidden sm:list-item">
                        {link.url ? (
                            <PageButton href={link.url} active={link.active}>
                                {link.label}
                            </PageButton>
                        ) : (
                            <PaginationEllipsis />
                        )}
                    </PaginationItem>
                ))}
                <PaginationItem className="px-2 text-sm text-muted-foreground sm:hidden">
                    {paginator.current_page} / {paginator.last_page}
                </PaginationItem>
                <PaginationItem>
                    <PageButton href={paginator.next_page_url} label="Next page">
                        <ChevronRight />
                    </PageButton>
                </PaginationItem>
            </PaginationContent>
        </Pagination>
    );
}

interface PageButtonProps {
    href: string | null;
    active?: boolean;
    label?: string;
    children: React.ReactNode;
}

function PageButton({ href, active = false, label, children }: PageButtonProps) {
    if (!href) {
        return (
            <Button variant="ghost" size="icon" disabled aria-label={label}>
                {children}
            </Button>
        );
    }

    return (
        <Button
            variant={active ? 'outline' : 'ghost'}
            size="icon"
            nativeButton={false}
            render={<Link href={href} preserveScroll aria-label={label} aria-current={active ? 'page' : undefined} />}
        >
            {children}
        </Button>
    );
}
