import type { ReactNode } from 'react';

interface Props {
    title: ReactNode;
    description?: ReactNode;
    actions?: ReactNode;
}

export default function PageHeader({ title, description, actions }: Props) {
    return (
        <div className="flex flex-wrap items-end justify-between gap-4">
            <div>
                <h1 className="font-heading text-2xl font-semibold tracking-tight">{title}</h1>
                {description && <p className="text-sm text-muted-foreground">{description}</p>}
            </div>
            {actions}
        </div>
    );
}
