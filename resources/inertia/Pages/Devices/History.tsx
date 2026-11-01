import { Head } from '@inertiajs/react';
import {
    CategoryScale,
    Chart as ChartJS,
    Legend,
    LinearScale,
    LineElement,
    PointElement,
    TimeSeriesScale,
    Title,
    Tooltip,
} from 'chart.js';
import 'chartjs-adapter-moment';
import { Line } from 'react-chartjs-2';
import type { Datum, Device, Figure } from '@/types';

ChartJS.register(Title, Tooltip, Legend, LineElement, LinearScale, TimeSeriesScale, PointElement, CategoryScale);

interface Props {
    device: Device;
    figure: Figure;
    readings: Datum[];
}

export default function History({ device, figure, readings }: Props) {
    const data = {
        datasets: [
            {
                label: figure.name,
                data: readings.map((reading) => ({ x: reading.timestamp, y: reading.value_calibrated })),
            },
        ],
    };

    return (
        <>
            <Head title={`${device.name} - ${figure.name}`} />
            <section>
                <Line
                    data={data}
                    options={{
                        scales: {
                            x: { type: 'time', time: { displayFormats: { quarter: 'MMM YYYY' } } },
                        },
                    }}
                />
            </section>
        </>
    );
}
