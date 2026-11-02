import { Tooltip, TooltipContent, TooltipTrigger } from '@/Components/ui/tooltip';

interface Props {
    diff: string;
    formatted: string;
    className?: string;
}

/** Human-friendly "x minutes ago" with the full timestamp in a tooltip. */
export default function RelativeTime({ diff, formatted, className }: Props) {
    return (
        <Tooltip>
            <TooltipTrigger render={<time className={className} />}>{diff}</TooltipTrigger>
            <TooltipContent>{formatted}</TooltipContent>
        </Tooltip>
    );
}
