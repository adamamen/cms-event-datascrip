<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>{{ $val['product_invoice_no'] }}</title>
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0;  /* this affects the margin in the printer settings */
        }
    </style>

    <style type="text/css">        
        body {
            font-family: Tahoma, Geneva, sans-serif;
        }

        table.table-detail {
            margin-top: 30px;
        }

        table.table-detail th {
            padding: 8px;
        }

        table.table-detail td {
            padding: 8px;
        }

        table.table-signature {
            margin-top: 50px;
        }

        #watermark {
            position: absolute;
            bottom: 30%;
            left: 20%;
        }
    </style>
</head>

<body>
    <div id="watermark"><img src="{{ asset('images/original.jpg') }}" style="opacity: 0.1;"></div>
    <table width="100%" style="border: 0px solid #000;" align="center">
        <tr>
            <td colspan="3" style="height: 100px;"></td>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td><img src="{{ asset('images/logo_tipa_new.png') }}" width="300" height="168"></td>
                    </tr>
                    <tr>
                        <td>
                            Jl. Selaparang Blok B <br />Kemayoran Jakarta Pusat<br />
                            Phone : 021-6544515<br />
                            Fax : 021-65444555
                        </td>
                    </tr>
                </table>
            </td>
            <td valign="top">
                <table width="100%">
                    <tr>
                        <td colspan="3">
                            <h1></h1>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>:</td>
                        <td nowrap><?php echo date('d F Y H:i:s') ?></td>
                    </tr>
                    <tr>
                        <td>Invoice No.</td>
                        <td>:</td>
                        <td nowrap>{{ $val['product_invoice_no'] }}</td>
                    </tr>
                    <tr>
                        <td>Queue No.</td>
                        <td>:</td>
                        <td nowrap>{{ $val['visitor_que_no'] }}</td>
                    </tr>
                    <tr>
                        <td>Bill To</td>
                        <td>:</td>
                        <td nowrap>{{ $val['visitor_fullname'] }}</td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>:</td>
                        <td nowrap>{{ $val['visitor_mobile'] }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>:</td>
                        <td>{{ $val['visitor_address'] }}</td>
                    </tr>
                    <tr>
                        <td>Cashier Code</td>
                        <td>:</td>
                        <td nowrap>{{ $val['updated_by'] }}</td>
                    </tr>
                    <tr>
                        <td>Payment Method</td>
                        <td>:</td>
                        <td nowrap>{{ $val['visitor_payment_method'] }}</td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
    <table class="table-detail" width="100%" style="border: 0px solid #000;" align="center" cellpadding="0" cellspacing="0">
        <thead>
            <tr style="background-color: #cccccc;">
                <th align="left">DESCRIPTION</th>
                <th>QTY</th>
                <th align="right">AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            <tr style="background-color: #eeeeee;">
                <td>ROG Ally RC71L</td>
                <td align="center">1</td>
                <td align="right">11,299,000</td>
            </tr>
            <tr>
                <td colspan="3">SN : {{ $val['product_serial_no'] }}</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Thank you for your visit</strong></td>
                <td align="right" style="background-color: #eeeeee;"><strong>TOTAL :</strong></td>
                <td align="right" style="background-color: #eeeeee;"><strong>11,299,000</strong></td>
            </tr>
                <tr>
                    <td colspan="3"><small><i>*Please keep this receipt for item exchange</i></small></td>
                </tr>
        </tbody>
    </table>
    <table class="table-signature" width="100%" style="border: 0px solid #000;" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td colspan="3">Jakarta, <?php echo date('d F Y') ?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td>Received By</td>
            <td></td>
            <td align="center">Cashier</td>
        </tr>
        <tr>
            <td style="height: 150px;" valign="bottom">{{ $val['visitor_fullname'] }}</td>
            <td></td>
            <td align="center" style="height: 150px;" valign="bottom">{{ ucwords(strtolower(Auth::user()->full_name)) }}</td>
        </tr>
    </table>
</body>
<html>