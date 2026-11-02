<?php

namespace App\Http\Middleware;

use App\Models\Datum;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    #[\Override]
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'breadcrumbs' => $request->route()->breadcrumbs()->toCollection(),
            // ULIDs sort by creation time, so this changes whenever a new reading is stored.
            // The frontend polls it to decide when to reload the page's props.
            'latest_reading_id' => fn (): ?string => Datum::max('id'),
        ]);
    }
}
