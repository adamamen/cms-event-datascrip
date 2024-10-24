<?php

namespace App\Http\Middleware;

use App\Models\M_MasterEvent;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    // protected function redirectTo($request)
    // {
    //     if (! $request->expectsJson()) {
    //         // return route('login');
    //         return '/canon';
    //     }
    // }

    protected function redirectTo($request)
    {
        if ($request->is('visitor-event/*') || $request->is('whatsapp-event/*') || $request->is('email-event/*')) {
            $page  = $request->segment(2);
            $event = M_MasterEvent::select('title_url')
                ->where('status', 'A')
                ->where('title_url', $page)
                ->first();

            if ($event) {
                return '/' . $event->title_url;
            }

            if (empty($event)) {
                $page_1  = $request->segment(3);
                $event_1 = M_MasterEvent::select('title_url')
                    ->where('status', 'A')
                    ->where('title_url', $page_1)
                    ->first();

                if ($event_1) {
                    return '/' . $event_1->title_url;
                }
            }
        }

        return route('login');
    }
}
