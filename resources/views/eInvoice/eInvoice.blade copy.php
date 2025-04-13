
<!DOCTYPE html>
<html lang="en, id">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      Invoice No. {!! $data['invnumb'] !!}
    </title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    
    <link rel="stylesheet" href="assets/css/invoice.css" />
  </head>
  <body>
    <style>
    </style>
      <section class="wrapper-invoice">
      <!-- switch mode rtl by adding class rtl on invoice class -->
      <div class="invoice">
        <div class="invoice-information">
          <p><b>Invoice No.</b> : {{ $data['invnumb']}}</p>
          <p><b>Tanggal</b> : {{ $data['invdate']}}</p>
          <p><b>Jatuh Tempo</b> : {{ $data['invduedate']}}</p>

        </div>
        <!-- logo brand invoice -->
        <div class="invoice-logo-brand" >
          <!-- <h2>Tampsh.</h2> -->
          <img src="assets/images/kas.jpg" alt="" >
        </div>
        <!-- invoice head -->
        <div class="invoice-head">
          <div class="head client-info">
            <p>{{$data['compname']}}</p>
            <p>{{$data['compaddress1']}}</p>
            <p>{{$data['compaddress2']}}</p>
            <p>{{$data['compaddress3']}}</p>
          </div>
          <table>
            <tbody>
              <tr>
                <td style="width:420px;">
                  <p><b>di Tagih Kepada</b></p>
                  <p>{{$data['costname']}}</p>
                  <p>{{$data['costaddress1']}}</p>
                  <p>{{$data['costaddress2']}}</p>
                  <p>{{$data['costaddress3']}}</p>
                </td>
                <td>
                  <p><strong>di Kirim Kepada</strong></p>
                  <p>{{$data['shipname']}}</p>
                  <p>{{$data['shipaddress1']}}</p>
                  <p>{{$data['shipaddress2']}}</p>
                  <p>{{$data['shipaddress3']}}</p>
                </td>
              </tr>
              
            </tbody>
          </table>

          
        </div>
        <!-- invoice body-->
        <div class="invoice-body">
          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Deskripsi</th>
                <th>Jml</th>
                <th>Harga Satuan <b>(Rp.)</b></th>
                <th>Total <b>(Rp.)</th>
              </tr>
            </thead>
            <tbody>   
          @foreach ($details as $items)
              <tr>
                  <td>{{ $items['invid'] }}</td>
                  <td>{{ $items['invdesc'] }}</td>
                  <td>{{ $items['invqty'] }}</td>
                  <td>Rp. {{ number_format($items['unitprice'], 0, ',', '.') }}</td>
                  <td>Rp. {{ number_format($items['linetotal'], 0, ',', '.') }}</td>
              </tr>
          @endforeach
            </tbody>
          </table>
          <div class="flex-table">
            <div class="flex-column">
              <div class="stamps">
                @if(isset($data['invpaidstamp']))
                    <img src="{{ public_path('assets/akun_bizz/images/stamp_paid_id.png') }}" width="160" height="116" />
                @endif
                @if(isset($data['invurgentstamp']))
                    <img src="{{ public_path('assets/akun_bizz/images/stamp_urgent_id.png') }}" width="160" height="116" />
                @endif
                @if(isset($data['invpastduestamp']))
                    <img src="{{ public_path('assets/akun_bizz/images/stamp_past_id.png') }}" width="160" height="116" />
                @endif
                @if(isset($data['invfinalstamp']))
                    <img src="{{ public_path('assets/akun_bizz/images/stamp_final.png') }}" width="160" height="116" />
                @endif
                @if(isset($data['invreceivedstamp']))
                    <img src="{{ public_path('assets/akun_bizz/images/stamp_received_id.png') }}" width="160" height="116" />
                @endif
                @if(isset($data['invapprovedstamp']))
                    <img src="{{ public_path('assets/akun_bizz/images/stamp_approved_id.png') }}" width="160" height="116" />
                @endif
            </div>
                <table class="table-subtotal">
                    <tbody>
                      <tr>
                          @if ($data['invtax'] == 0)
                          <td>Pajak</td>
                          <td>Rp.{{ number_format($data['invtax'], 0, ',', '.') }}</td>                      
                          @else
                          <td>Pajak</td>
                              <td>Rp.{{ number_format($data['invtax'], 0, ',', '.') }}</td>
                            @endif
                        </tr>
                        <tr>
                          @if ($data['invcredit'] == 0)
                          @else
                          <td>Credit</td>
                              {{-- <td>Rp.{{ number_format($data['invtax'], 0, ',', '.') }}</td> --}}
                              <td>Rp.{{ number_format($data['invcredit'], 0, ',', '.') }}</td>
                          @endif
                        </tr>
                        <tr>
                          @if ($data['invdiscount'] == 0)
                          @else
                          <td>Credit</td>
                              <td>Rp.{{ number_format($data['invdiscount'], 0, ',', '.') }}</td>
                          @endif
                        </tr>
                        <tr>
                            <td>Subtotal</td>
                            <td>Rp.{{ number_format($data['invsubtotal'], 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

          <!-- invoice total  -->
          <div class="invoice-total-amount">
            {{-- <p>Total : &nbsp;&nbsp;&nbsp; Rp.{{$data['invtotal']}}</p> --}}
            <p>Total : &nbsp;&nbsp;&nbsp; Rp. {{ number_format($data['invtotal'], 0, ',', '.') }}</p>
          </div>
        </div>
        <!-- invoice footer -->
        <div class="invoice-footer">
          {{-- <p>Catatan : <br> {{ $data['invnote']}}</p> --}}
          @if( $data['invnote'] == null )
            {{-- <p>Tidak ada</p> --}}
          @else
          <p>Catatan : <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {!! $data['invnote'] !!}</p>
          @endif
        </div>
      </div>
    </section>
    <div class="copyright">
      <p><span></span></p>
    </div>
  </body>
</html>
