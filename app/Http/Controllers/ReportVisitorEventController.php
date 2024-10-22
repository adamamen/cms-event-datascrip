<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportVisitorEventController extends Controller
{
    public function index($page)
    {
        $type_menu   = 'report_visitor_event';
        $masterEvent = masterEvent($page);
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $data        = array();

        return view(
            'report_visitor_event.index',
            [
                'type_menu' => $type_menu,
                'titleUrl'  => $titleUrl,
                'page'      => $page,
                'data'      => $data,
            ]
        );
    }

    function export_excel()
    {
        //
    }
}
