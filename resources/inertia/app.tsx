import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import AppLayout from '@/Layouts/AppLayout';

const appName = document.getElementsByTagName('title')[0]?.innerText || 'Grow';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.tsx');

        return pages[`./Pages/${name}.tsx`]() as never;
    },
    layout: () => AppLayout,
    setup({ el, App, props }) {
        if (el) {
            createRoot(el).render(<App {...props} />);
        }
    },
    progress: {
        color: '#ff0000',
        showSpinner: true,
    },
});
