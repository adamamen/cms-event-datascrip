<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportExcel;
use Maatwebsite\Excel\Facades\Excel;

class ReportVisitorEventController extends Controller
{
    public function index($page)
    {
        $type_menu   = 'report_visitor_event';
        $masterEvent = masterEvent($page);
        $titleUrl    = !empty($masterEvent) ? $masterEvent[0]['title_url'] : 'cms';
        $data        = DB::table('v_report')->get();

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
        $customHeadings = [
            'No',
            'Event',
            'Divisi Event Name',
            'Source Original Name Cust',
            'Name',
            'Gender',
            'E-mail',
            'WhatsApp Number',
            'Institution',
            'Institution Name',
            'Approve Date',
            'Approve By',
            'Visit Date',
            'Source',
        ];

        $filename = 'Report Visitor Event.xlsx';

        return Excel::download(new ExportExcel('report_visitor_event', $customHeadings), $filename);
    }
}
