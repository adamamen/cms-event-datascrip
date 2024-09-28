<?php

namespace App\Exports;

use App\Models\M_VisitorEvent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

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
        // dd('1');
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
                            // dd('1');
                            $merge[] = [
                                'RowNumber'         => $sequenceNumber++,
                                'ticket_no'         => $visitor->ticket_no,
                                'title'             => $event->title,
                                'full_name'         => $visitor->full_name,
                                'mobile'            => $visitor->mobile,
                                'email'             => $visitor->email,
                                'address'           => $visitor->address,
                                'no_invoice'        => $visitor->no_invoice,
                                'sn_product'        => $visitor->sn_product,
                                'status_pembayaran' => $visitor->status_pembayaran,
                                'metode_bayar'      => $visitor->metode_bayar,
                                'created_at'        => $visitor->created_at,
                                'jenis_event'       => $visitor->jenis_event == 'A' ? 'Berbayar' : 'Non Berbayar',
                            ];
                        } else if ($visitor->jenis_event == "A") {
                            // dd('2');
                            $merge[] = [
                                'RowNumber'         => $sequenceNumber++,
                                'ticket_no'         => $visitor->ticket_no,
                                'title'             => $event->title,
                                'full_name'         => $visitor->full_name,
                                'mobile'            => $visitor->mobile,
                                'email'             => $visitor->email,
                                'address'           => $visitor->address,
                                'no_invoice'        => $visitor->no_invoice,
                                'sn_product'        => $visitor->sn_product,
                                'status_pembayaran' => $visitor->status_pembayaran,
                                'metode_bayar'      => $visitor->metode_bayar,
                                'created_at'        => $visitor->created_at,
                                'jenis_event'       => $visitor->jenis_event == 'A' ? 'Berbayar' : 'Non Berbayar',
                            ];
                        } else {
                            // dd('3');
                            $merge[] = [
                                'RowNumber'         => $sequenceNumber++,
                                'full_name'         => $visitor->full_name,
                                'email'             => $visitor->email,
                                'gender'            => $visitor->gender,
                                'account_instagram' => $visitor->account_instagram,
                                'mobile'            => $visitor->mobile,
                                'type_invitation'   => $visitor->type_invitation,
                                'invitation_name'   => $visitor->invitation_name,
                                'barcode_no'        => $visitor->barcode_no,

                                // $merge[] = [
                                //     'RowNumber'  => $sequenceNumber++,
                                //     'ticket_no'  => $visitor->ticket_no,
                                //     'title'      => $event->title,
                                //     'full_name'  => $visitor->full_name,
                                //     'mobile'     => $visitor->mobile,
                                //     'email'      => $visitor->email,
                                //     'address'    => $visitor->address,
                                //     'created_at' => $visitor->created_at,
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
