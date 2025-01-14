<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\M_AccessUser;
use Illuminate\Support\Facades\DB;

class ExportExcel implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $page;

    public function __construct($page, $customHeadings)
    {
        $this->page           = $page;
        $this->customHeadings = $customHeadings;
    }

    public function collection()
    {
        return $this->getDataForExport();
    }

    protected function getDataForExport()
    {
        $q = $this->query($this->page);
        return $q;
    }

    public function headings(): array
    {
        return $this->customHeadings;
    }

    function query($page)
    {
        if ($page == 'user_access') {
            $query          = M_AccessUser::select('*')->get();
            $sequenceNumber = 1;

            foreach ($query as $value) {
                $merge[]        = [
                    'No'             => $sequenceNumber++,
                    'Division'       => $value->nama_divisi,
                    'Owner Division' => $value->nama_divisi_owner,
                    'Start Date'     => date('d/m/Y', strtotime($value->start_date)),
                    'End Date'       => date('d/m/Y', strtotime($value->end_date)),
                    'Created By'     => $value->created_by,
                    'Created Date'   => date('d/m/Y', strtotime($value->created_date)),
                    'Status'         => $value->status == "A" ? 'Active' : 'Inactive',
                ];
            }

            $merge = !empty($merge) ? $merge : [];
            $q     = collect($merge);

            return $q;
        } else if ($page == 'report_visitor_event') {
            $query          = DB::table('v_report')->get();
            $sequenceNumber = 1;

            foreach ($query as $value) {
                $merge[]        = [
                    'No'                        => $sequenceNumber++,
                    'Event'                     => $value->title_event,
                    'Divisi Event Name'         => $value->nama_divisi,
                    'Source Original Name Cust' => $value->name_mst_cust,
                    'Name'                      => $value->nama_registrasi_event,
                    'Gender'                    => $value->gender_registrasi,
                    'E-mail'                    => $value->email_registrasi,
                    'WhatsApp Number'           => $value->tlp_registrasi,
                    'Institution'               => $value->invitaion_registrasi,
                    'Institution Name'          => $value->invitation_name_registrasi,
                    'Approve Date'              => date('d-m-Y H:i', strtotime($value->tgl_approve_registrasi)),
                    'Approve By'                => $value->approveby_registrasi,
                    'Visit Date'                => date('d-m-Y H:i', strtotime($value->tgl_visit)),
                    'Source'                    => $value->source_visitor,
                ];
            }

            $merge = !empty($merge) ? $merge : [];
            $q     = collect($merge);

            return $q;
        } else {
            $queryVisitorEvent = visitorEvent();
            $sequenceNumber    = 1;
            if ($page == "cms") {
                $queryMasterEvent = masterEvent_1();
            } else {
                $queryMasterEvent = masterEvent_2($page);
            }

            if (!empty($queryVisitorEvent) && !empty($queryMasterEvent)) {
                foreach ($queryVisitorEvent as $visitor) {
                    foreach ($queryMasterEvent as $event) {
                        if ($visitor->event_id == $event->id_event) {
                            if ($page == "cms") {
                                $merge[] = [
                                    'RowNumber'         => $sequenceNumber++,
                                    'full_name'         => $visitor->full_name,
                                    'email'             => $visitor->email,
                                    'gender'            => $visitor->gender,
                                    'account_instagram' => $visitor->account_instagram,
                                    'event_name'        => ucwords($event->title),
                                    'mobile'            => $visitor->mobile,
                                    'type_invitation'   => $visitor->type_invitation,
                                    'invitation_name'   => $visitor->invitation_name,
                                    'barcode_no'        => $visitor->barcode_no,
                                    'scan_date'         => $visitor->scan_date,
                                    'email_status'      => $visitor->flag_email == 0 ? 'Not Delivered' : 'Delivered',
                                    'whatsapp_status'   => $visitor->flag_whatsapp == 0 ? 'Not Delivered' : 'Delivered',
                                    'source_visitor'    => $visitor->source_visitor,
                                    'status_approval'   => $visitor->flag_approval == 1 ? 'Approve' : 'Waiting',
                                    'approve_by'        => $visitor->approve_by,
                                    'approve_date'      => $visitor->approve_date,
                                ];
                            } else if ($visitor->jenis_event == "A") {
                                $merge[] = [
                                    'RowNumber'         => $sequenceNumber++,
                                    'full_name'         => $visitor->full_name,
                                    'email'             => $visitor->email,
                                    'gender'            => $visitor->gender,
                                    'account_instagram' => $visitor->account_instagram,
                                    'event_name'        => ucwords($event->title),
                                    'mobile'            => $visitor->mobile,
                                    'type_invitation'   => $visitor->type_invitation,
                                    'invitation_name'   => $visitor->invitation_name,
                                    'barcode_no'        => $visitor->barcode_no,
                                    'scan_date'         => $visitor->scan_date,
                                    'email_status'      => $visitor->flag_email == 0 ? 'Not Delivered' : 'Delivered',
                                    'whatsapp_status'   => $visitor->flag_whatsapp == 0 ? 'Not Delivered' : 'Delivered',
                                    'source_visitor'    => $visitor->source_visitor,
                                    'status_approval'   => $visitor->flag_approval == 1 ? 'Approve' : 'Waiting',
                                    'approve_by'        => $visitor->approve_by,
                                    'approve_date'      => $visitor->approve_date,
                                ];
                            } else {
                                $merge[] = [
                                    'RowNumber'         => $sequenceNumber++,
                                    'full_name'         => $visitor->full_name,
                                    'email'             => $visitor->email,
                                    'gender'            => $visitor->gender,
                                    'account_instagram' => $visitor->account_instagram,
                                    'event_name'        => ucwords($event->title),
                                    'mobile'            => $visitor->mobile,
                                    'type_invitation'   => $visitor->type_invitation,
                                    'invitation_name'   => $visitor->invitation_name,
                                    'barcode_no'        => $visitor->barcode_no,
                                    'scan_date'         => $visitor->scan_date,
                                    'email_status'      => $visitor->flag_email == 0 ? 'Not Delivered' : 'Delivered',
                                    'whatsapp_status'   => $visitor->flag_whatsapp == 0 ? 'Not Delivered' : 'Delivered',
                                    'source_visitor'    => $visitor->source_visitor,
                                    'status_approval'   => $visitor->flag_approval == 1 ? 'Approve' : 'Waiting',
                                    'approve_by'        => $visitor->approve_by,
                                    'approve_date'      => $visitor->approve_date,
                                ];
                            }
                        }
                    }
                }
            }

            $merge = !empty($merge) ? $merge : [];
            $q     = collect($merge);

            return $q;
        }
    }
}
