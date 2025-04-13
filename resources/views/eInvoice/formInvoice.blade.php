@extends('layouts._header')
@section('title','Invoice')

@section('content')
<head>
    <link rel="stylesheet" type="text/css" href="assets/akun_bizz/css/pola_20210813ogHiRe.css" />
    <link rel="stylesheet" type="text/css" href="assets/akun_bizz/css/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/akun_bizz/css/jquery-ui.theme.css" />
    <link rel="stylesheet" type="text/css" href="assets/akun_bizz/css/jquery-ui.structure.min.css" />
  
    <script type="text/javascript" src="assets/akun_bizz/js/jquery-1.12.2.min.js"></script>
    <script type="text/javascript" src="assets/akun_bizz/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="assets/akun_bizz/js/datepicker-id.js"></script>
    <script type="text/javascript" src="assets/akun_bizz/js/jquery.number.min.js"></script>
    <script type="text/javascript" src="assets/akun_bizz/js/datepicker-id.js"></script>
    <script type="text/javascript" src="assets/akun_bizz/js/jquery.number.min.js"></script>
    <script type="text/javascript" src="assets/akun_bizz/js/lang_ID2.js"></script>
    <script type="text/javascript" src="https://www.googletagmanager.com/gtag/js?id=G-14R49MJD9G" async></script>
    <script type="text/javascript" src="assets/akun_bizz/js/jquery.fileupload.js"></script>
    <script type="text/javascript" src="assets/akun_bizz/js/sideout_8884498234.js"></script>
    
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-14R49MJD9G');
</script>
<script type="text/javascript">
   var gotobookvar='?';var eksporpdfvar='ekspor_pdf/kas.php/?book=248083&language=id';var eksporxlsvar='ekspor_xls/kas.php/?book=248083&language=id';var date_a='AM';var date_i='35';var date_hk='05';var active_book='248083';var date_full='24 Juni 2024';var global_reg='560544f7974aae5af46ed178724f7807fbaa3d8833d7ff0753ac7e063f8907ed';var global_sub='a7e063f85d2dc2e9c353f3bb0d104ad2fe7abdbdfba907ed44f7974aae5af46e';var global_dist='bdfbaa7e063f8907ed44f7974aae5a5d2dc2e9c353f3bb0d104ad2fe7abdf46e';var global_send='53ac5605a7e063f89073d8833d7f8724f7807fbf07ed44f7974aae5af46ed17a';var global_var='bff6f3dea47ad9af6a8cbc2f5974aac9';var global_ajax='5d4ad2fe7abdbdfbaa7e063f8907e2dc2e9c353f3bb0d10d44f7974aae5af46e';var global_stat='3d8805d178724f7807fbaa7e063f8907ed44f7974aa3d8805e5af46e';var global_post='e7abdbdfbaa7e063f8907ed44f75d2dc2e9c353f3bb0d104ad2f974aae5af46e';var global_json='c353f3bb0d104ad2fe7abdbd5d2dc2e9fbaa7e063f8907ed44f7974aae5af46e';var is_login=true;var is_ID = true;var format_nominal = '0,00';var invoiceidvar = '0';var sgetinvoice = false;var sgetinvoicethok = false;var snoticefirst = false;</script>
    <script type="text/javascript" src="assets/akun_bizz/js/general.min.js">
    </script>
<!-- Alexa Code -->
<!--
<script type="text/javascript">
_atrk_opts = { atrk_acct:"C7dko1IWNa10cN", domain:"akun.biz",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=C7dko1IWNa10cN" style="display:none" height="1" width="1" alt="" /></noscript>
-->
<!-- Google Tag Manager -->
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5GVSG47');</script>
<!-- End Google Tag Manager -->
<!-- TikTok -->
<script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

  ttq.load('CMC1G3BC77U6KDSKHL10');
  ttq.page();
}(window, document, 'ttq');

</script>
<!-- end TikTok -->
</head>
<div class="my-2">
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif
	<div class="bloktengah" id="blok_kas">
    
		<div class="kastop">
            <div class="kastitle notetitle">
                <div class="mainicon">
                    <img src="assets/akun_bizz/images/invoice-icon.png" width="52" height="52" alt="notes">
                </div>
                <div class="kastitlekanan">
                    <div class="judulkas">e-Invoice</div>
                    <div class="desckas">Buat Invoice PDF dan kirim ke pelanggan</div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>

<div class="kasbody" id="bodyinvoice">
    <div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
        <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
            <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"
                role="tab" tabindex="0" aria-controls="tabs-1" aria-labelledby="ui-id-1"
                aria-selected="true" aria-expanded="true"
                aria-selected="false" aria-expanded="false">
                <a href="?tool=invoice" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">Buat Invoice</a>
            </li>
            {{-- <li class="ui-state-default ui-corner-top "
                role="tab" tabindex="-1" aria-controls="tabs-2" aria-labelledby="ui-id-2"
                aria-selected="true" aria-expanded="true">
                <a href="?tool=invoice&invoice=book" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">Buku Invoice</a>
            </li> --}}
            <!--nambah baru disini untuk daftar client-->
            {{-- <li class="ui-state-default ui-corner-top "
                role="tab" tabindex="-1" aria-controls="tabs-2" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false">
                <a href="?tool=invoice&invoice=client" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">Daftar Pelanggan</a>
            </li> --}}
             <!--akhir daftar client-->        
                </ul>
    <div id="tabs-1" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel"
            aria-hidden="false" style="display: block;">
  
        <div class="reportbox" id="invoicebox">
            <h3 class="h3report" id="h3allcash">Buat Invoice</h3>
            <div class="invoiceframe">
            <form name="invoiceform" id="invoiceform" method="post" action="ekspor_pdf/invoice.php" target="_blank">
                @csrf
                <input name="invoicesend" id="invoicesend" type="hidden" value="37d95246171fa90bfe30eec5be1c52fa" />
                <!-- Company, date, invoice# -->
                <div class="invoiceline" id="invoiceline_1">
                    <div class="invleft">
                        <div class="complogo">
                            <div class="userlogo" id="userlogo">
                                {{-- <img id="current-logo" src="assets/akun_bizz/images/invoice-default-logo.jpg" width="240" height="180" alt="Current logo">
                                <input type="file" name="logo_file" id="logo_file" accept="image/jpeg, image/png, image/jpg" onchange="previewImage(event)"> --}}
                              </div>
                                <div class="notif" id="uploaderror"></div>
            
                        </div>
                        <div class="compinput">
                            <input type="text" style="width: 160px;" name="compname" id="compname" value=""
                                placeholder="Nama Perusahaan Anda"/>*<br/>
                            <input type="text" style="width: 240px;" name="compaddress1" id="compaddress1" placeholder="Alamat Anda baris 1"  value=""/>*<br/>
                            <input type="text" style="width: 240px;" name="compaddress2" id="compaddress2" placeholder="Alamat Anda baris 2"  value=""/><br/>
                            <input type="text" style="width: 240px;" name="compaddress3" id="compaddress3" placeholder="Alamat Anda baris 3"  value=""/>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="inright invnumb">
                        
                        <input type="checkbox" id="generate_nomor" name="generate_nomor" checked>
                        <p>Ceklis jika auto nomor invoice</p>
                        <strong>Invoice No.</strong>*
                        <input type="text" style="width: 148px; font-weight: 700;" name="invnumb" id="invnumber" value="{{ old('nomor_nota') }}"
                            onFocus="javascript:this.value==this.defaultValue ? this.value = '' : ''"
                            onBlur="javascript:this.value == '' ? this.value = this.defaultValue : ''"/><br/>
                        
                        Tanggal Invoice*                       
                        <input type="date" class="date" style="width: 148px;" name="invdate" value="<?php echo date('d-M-Y'); ?>" id="invdate"/><br>
                        {{-- <input type="text" datepicker_id style="width: 148px; font-weight: 700;" name="invdate" id="invdate" value="23 Juni 2024"
                        onFocus="javascript:this.value==this.defaultValue ? this.value = '' : ''"
                        onBlur="javascript:this.value == '' ? this.value = this.defaultValue : ''"/><br/> --}}
                        Jatuh Tempo*
                        <?php
                        // Mengambil nilai invduedate dari request atau data yang tersedia
                        $invduedate = isset($data['invduedate']) ? $data['invduedate'] : '';
                        
                        // Mengubah format dari "23 Juni 2024" menjadi "2024-06-23" (ISO format)
                        $isoDate = date('d-M-Y', strtotime($invduedate));
                        ?>
                        {{-- <input type="date" class="datepicker_id" style="width: 148px;" name="invduedate" value="<?php echo date('d-M-Y'); ?>" id="invduedate"/> --}}
                        <input type="date" class="date" style="width: 148px;" name="invduedate" value="<?php echo date('d-M-Y'); ?>" id="invduedate"/><br>
                        <strong>Kategori.</strong>*
                        <select type="text" style="width: 148px; font-weight: 700;" name="kategori" id="kategori" value="Kategori"
                            onFocus="javascript:this.value==this.defaultValue ? this.value = '' : ''"
                            onBlur="javascript:this.value == '' ? this.value = this.defaultValue : ''"/><br/>
                            @foreach ($linkCategori as $item)
                            <option value="{{$item->name}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        {{-- <input type="date" datepicker_id style="width: 148px; font-weight: 700;" name="invduedate" id="invduedate" value="23 Juni 2024"
                        onFocus="javascript:this.value==this.defaultValue ? this.value = '' : ''"
                        onBlur="javascript:this.value == '' ? this.value = this.defaultValue : ''"/><br/> --}}
                    </div>
                    <div class="clear"></div>
                </div>
                <!-- Bill to, Ship to -->
                <div class="invoiceline" id="invoiceline_2">
                    <div class="invleft">
                        <h4>Ditagih kepada</h4>
                        <input type="text" style="width: 160px;" name="costname" id="costname" placeholder="Nama Pelanggan *"/><br/>
                        <input type="text" style="width: 240px;" name="costaddress1" id="costaddress1" placeholder="Alamat Pelanggan baris 1 *"/><br/>
                        <input type="text" style="width: 240px;" name="costaddress2" id="costaddress2" placeholder="Alamat Pelanggan baris 2"/><br/>
                        <input type="text" style="width: 240px;" name="costaddress3" id="costaddress3" placeholder="Alamat Pelanggan baris 3"/>
                        <input type="hidden" value="0" id="costid" />
                    </div>
                    <div class="inright">
                        <div class="invcheck">
                            <input name="useshipto" id="useshipto" type="checkbox" value="1" onchange="checkship()" /> Aktifkan kolom &quot;Dikirim kepada&quot;                </div>
                        <div class="invcoverarea" id="invcoverarea">
                            <div class="invcover" id="shiptocover"></div>
                            <h4>Dikirim kepada</h4>
                            <input type="text" style="width: 160px;" name="shipname" id="shipname" placeholder="Nama Penerima"/><br/>
                            <input type="text" style="width: 240px;" name="shipaddress1" id="shipaddress1" placeholder="Alamat Penerima baris 1"/><br/>
                            <input type="text" style="width: 240px;" name="shipaddress2" id="shipaddress2" placeholder="Alamat Penerima baris 2"/><br/>
                            <input type="text" style="width: 240px;" name="shipaddress3" id="shipaddress3" placeholder="Alamat Penerima baris 3"/>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <!-- start Invoice item -->
                <div class="invoiceline" id="invoiceline_3">
                <div class="scrolltabinv">
        <table width="100%" border="0" class="invtab" id="invtab">
            <tr>
                <th scope="col" class="invthid">ID*</th>
                <th scope="col">Deskripsi*</th>
                <th scope="col" class="invthqty">Jumlah</th>
                <th scope="col" class="unitthprice">Harga Satuan</th>
                <th scope="col" class="thlinetotal">Total</th>
                <th scope="col">X</th>
            </tr>
            <tr class="rowinvitem" id="rowinvitem_1">
                <td><input type="text" class="center invid" style="width: 48px;" name="invid[]" id="invid_1" value="1"/></td>
                <td><textarea name="invdesc[]" class="invdesc" id="invdesc_1"></textarea></td>
                <td><input type="text" class="center invqty" style="width: 42px;" name="invqty[]" id="qty_1" value="1" onchange="linetotal(1)"/></td>
                <td>
                    <input type="text" class="unitprice right jnumber" value="0,00" onkeyup="linetotal(1)" name="unitprice[]" id="unitprice_1"
                        style="width:124px;" onFocus="javascript:this.value==this.defaultValue ? this.value = '' : ''"
                        onBlur="javascript:this.value == '' ? this.value = this.defaultValue : ''"
                        />
                </td>
                <td>
                    <input type="text" class="linetotal right jnumber" value="0,00" readonly="readonly" name="linetotal[]" id="linetotal_1"/>
                </td>
                <td class="center tdinvdel">
                    <img src="assets/akun_bizz/images/delete.png" width="20" height="20" alt="delete" title="Hapus item ini" onclick="delrow(1)"
                        class="pointer none" id="invdel_1"/>
                </td>
            </tr>
        </table></div>
        <div class="invaddline">
            <input class="invaddbutton" id="invaddbutton" type="button" value="+ Tambah Item" onclick="addinvrow(2)"
                title="Tambah baris item"/>
        </div>
        <div class="invtotalarea" id="invtotalarea">
        <table width="100%" border="0" class="invtab" id="invtabtotal">
            <tr>
                <td class="right">Sub Total</td>
                <td width="128">
                    <input type="text" style="width: 128px;" class="jnumber right" readonly="readonly" name="invsubtotal" id="invsubtotal" value="0"/>
                </td>
                <td width="46" class="center" style="vertical-align: middle; background: #e7e7e7;">&nbsp;</td>
            </tr>
            <tr>
                <td class="right" style="position: relative;">
                    Sudah Dibayar (-)
                    <div class="invcover creditcover"></div>
                </td>
                <td id="td_creditcover" style="position: relative;">
                    <input type="text" style="width: 128px;" class="jnumber right" name="invcredit" id="invcredit" value="0"
                        onchange="alltotal()"
                        onFocus="javascript:this.value==this.defaultValue ? this.value = '' : ''"
                        onBlur="javascript:this.value == '' ? this.value = this.defaultValue : ''" />
                    <div class="invcover creditcover"></div>
                </td>
                <td class="center" style="vertical-align: middle; background: #e7e7e7;">
                    <input name="invcreditcheck" id="invcreditcheck" type="checkbox" value="1" title="Aktifkan kolom Sudah Dibayar" onchange="lowcover('creditcover')"/>
                </td>
            </tr>
            <tr>
                <td class="right" style="position: relative;">
                    Diskon (-)
                    <div class="invcover discountcover"></div>
                </td>
                <td id="td_discountcover" style="position: relative;">
                    <input type="text" style="width: 128px;" class="jnumber right" name="invdiscount" id="invdiscount" value="0"
                        onchange="alltotal()"
                        onFocus="javascript:this.value==this.defaultValue ? this.value = '' : ''"
                        onBlur="javascript:this.value == '' ? this.value = this.defaultValue : ''" />
                    <div class="invcover discountcover"></div>
                </td>
                <td class="center" style="vertical-align: middle; background: #e7e7e7;">
                    <input name="invdiscountcheck" id="invdiscountcheck" type="checkbox" value="0"
                        title="Aktifkan kolom Diskon" onchange="lowcover('discountcover')"/>
                </td>
            </tr>
            <tr>
                <td class="right" style="position: relative;">
                    Pajak (+)
                    <div class="invcover taxcover none"></div>
                </td>
                <td id="td_taxcover" style="position: relative;">
                    <input type="text" style="width: 128px;" class="jnumber right" name="invtax" id="invtax" value="0"
                        onchange="alltotal()"
                        onFocus="javascript:this.value==this.defaultValue ? this.value = '' : ''"
                        onBlur="javascript:this.value == '' ? this.value = this.defaultValue : ''" />
                    <div class="invcover taxcover none"></div>
                </td>
                <td class="center" style="vertical-align: middle; background: #e7e7e7;">
                    <input name="invtaxcheck" id="invtaxcheck" type="checkbox" checked value="0" title="Aktifkan kolom Pajak" onchange="lowcover('taxcover')"/>
                </td>
            </tr>
            <tr>
                <td class="right" style="position: relative;">
                    Biaya Pengiriman (+)
                    <div class="invcover shipcover"></div>
                </td>
                <td id="td_shipcover" style="position: relative;">
                    <input type="text" style="width: 128px;" class="jnumber right" name="invship" id="invship" value="0"
                        onchange="alltotal()"
                        onFocus="javascript:this.value==this.defaultValue ? this.value = '' : ''"
                        onBlur="javascript:this.value == '' ? this.value = this.defaultValue : ''" />
                    <div class="invcover shipcover"></div>
                </td>
                <td class="center" style="vertical-align: middle; background: #e7e7e7;">
                    <input name="invshipcheck" id="invshipcheck" type="checkbox" value="0" title="Aktifkan kolom Biaya Pengiriman"
                        onchange="lowcover('shipcover')"/>
                </td>
            </tr>
            <tr>
                <td class="right invtotal"><strong>TOTAL</strong></td>
                <td class="invtotal">
                    <input type="text" style="width: 128px; font-weight: 700;" class="jnumber right" name="invtotal" id="invtotal"
                        value="0" readonly="readonly"
                        onFocus="javascript:this.value==this.defaultValue ? this.value = '' : ''"
                        onBlur="javascript:this.value == '' ? this.value = this.defaultValue : ''" />
                </td>
                <td class="center" style="vertical-align: middle; background: #e7e7e7;">&nbsp;</td>
            </tr>
        </table>
            <img src="assets/akun_bizz/images/stamp_paid.png" width="160" height="116" class="invstampimg invstampimg_paid none" id="invstampimg_paid_en" />
            <img src="assets/akun_bizz/images/stamp_paid_id.png" width="160" height="116" class="invstampimg invstampimg_paid none" id="invstampimg_paid_id" />
            <img src="assets/akun_bizz/images/stamp_urgent.png" width="160" height="116" class="invstampimg invstampimg_urgent none" id="invstampimg_urgent_en" />
            <img src="assets/akun_bizz/images/stamp_urgent_id.png" width="160" height="116" class="invstampimg invstampimg_urgent none" id="invstampimg_urgent_id" />
            <img src="assets/akun_bizz/images/stamp_past.png" width="160" height="116" class="invstampimg invstampimg_past none" id="invstampimg_past_en" />
            <img src="assets/akun_bizz/images/stamp_past_id.png" width="160" height="116" class="invstampimg invstampimg_past none" id="invstampimg_past_id" />
            <img src="assets/akun_bizz/images/stamp_final.png" width="160" height="116" class="invstampimg invstampimg_final none" id="invstampimg_final_en" />
            <img src="assets/akun_bizz/images/stamp_final.png" width="160" height="116" class="invstampimg invstampimg_final none" id="invstampimg_final_id" />
            <img src="assets/akun_bizz/images/stamp_received.png" width="160" height="116" class="invstampimg invstampimg_received none" id="invstampimg_received_en" />
            <img src="assets/akun_bizz/images/stamp_received_id.png" width="160" height="116" class="invstampimg invstampimg_received none" id="invstampimg_received_id" />
            <img src="assets/akun_bizz/images/stamp_approved.png" width="160" height="116" class="invstampimg invstampimg_approved none" id="invstampimg_approved_en" />
            <img src="assets/akun_bizz/images/stamp_approved_id.png" width="160" height="116" class="invstampimg invstampimg_approved none" id="invstampimg_approved_id" />
        </div>
                </div>
            
                <!-- Additional Notes -->
                <div class="invoiceline" id="invoiceline_4">
                    <h4>Catatan</h4>
                    <textarea style="width: 98%; height: 64px;" name="invnote" id="invnote"
                        placeholder="Anda bisa menulis catatan tambahan untuk pelanggan Anda di sini."></textarea>
                </div>
                
                <!-- Stamp -->
                <div class="invoiceline" id="invoiceline_5">
                    <h4>Stempel</h4><br />
                    <div id="invstamp">
                    <div class="inpstamp"><input name="invpaidstamp" id="inv_paid_stamp" type="checkbox" value="1" onchange="stamp('paid')"/> Lunas </div>
                    <div class="inpstamp"><input name="invurgentstamp" id="inv_urgent_stamp" type="checkbox" value="1" onchange="stamp('urgent')"/> Segera </div>
                    <div class="inpstamp"><input name="invpastduestamp" id="inv_past_stamp" type="checkbox" value="1" onchange="stamp('past')"/> Jatuh Tempo </div>
                    <div class="inpstamp"><input name="invfinalstamp" id="inv_final_stamp" type="checkbox" value="1" onchange="stamp('final')"/> Final </div>
                    <div class="inpstamp"><input name="invreceivedstamp" id="inv_received_stamp" type="checkbox" value="1" onchange="stamp('received')"/> Dikirim </div>
                    <div class="inpstamp"><input name="invapprovedstamp" id="inv_approved_stamp" type="checkbox" value="1" onchange="stamp('approved')"/> Disetujui </div>            
                    </div>
                </div>
                
                <!-- Action -->
                <div class="invoiceline" id="invoiceline_6">
                    <div id="itemtest"></div>
                    <div class="invleft">
                        <input type="button" onclick="download_invoice()" class="invact" id="invdown" value="Download"
                            title="Download invoice ini"/>
                                        
                        <input type="button" onclick="kirim_invoice()" class="invact" id="invsend"
                            value="Kirim" title="Kirim sebagai email attachment ke pelanggan Anda."></input>
                        {{-- <span class="invact invnoactivebutton" id="invsave"
                            title="Simpan invoice ke dalam Buku Invoice. Hanya tersedia untuk Premium User.">
                            Simpan                </span> --}}
                    <img src="assets/akun_bizz/images/loader_01.gif" width="32" height="32" alt="please wait" class="invloader" id="invloader" />
                    </div>
                    <div class="inright">
                        <input type="button" class="invact" id="invrefresh" value="Reset" onClick="window.location.reload(true)"
                            title="Refresh halaman, reset semua isian Invoice."/>
                    </div>
                    <div class="clear"></div>
                    <div class="notif" id="notifbutton"></div>
                </div>
                        <div class="clear"></div>
            </form>
            </div>
        </div>


<script type="text/javascript">
 document.addEventListener('DOMContentLoaded', function() {
    var inputdueDate = document.getElementById('invduedate');
    var inputDate = document.getElementById('invdate');
    
    // Mendapatkan tanggal hari ini
    var today = new Date();
    
    // Mendefinisikan array nama bulan
    var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                      "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    
    // Mendapatkan informasi tanggal, bulan, dan tahun dari today
    var day = today.getDate(); // tanggal
    var monthIndex = today.getMonth(); // index bulan (0 untuk Januari, 11 untuk Desember)
    var year = today.getFullYear(); // tahun
    
    // Membuat format tanggal "DD Month YYYY"
    var dateString = day + ' ' + monthNames[monthIndex] + ' ' + year;
    
    // Set nilai pada input date
    inputDate.valueAsDate = today;
    
    // Menambahkan satu bulan pada tanggal hari ini untuk dueDate
    var dueDate = new Date(today);
    dueDate.setMonth(today.getMonth() + 1);
    
    // Jika bulan melebihi 11 (Desember), maka tahun juga harus bertambah
    if (dueDate.getMonth() < today.getMonth()) {
        dueDate.setFullYear(today.getFullYear() + 1);
    }
    
    // Set nilai pada inputdueDate
    inputdueDate.valueAsDate = dueDate;
});
            $(document).ready(function() {
        
            var projects = [];    
            $( "#costname" ).autocomplete({
            minLength: 0,
            source: projects,
            focus: function( event, ui ) {
                $( "#costname" ).val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {
                $( "#costid" ).val(ui.item.value);  
                $( "#costname" ).val( ui.item.label );
                $( "#costaddress1" ).val( ui.item.address1 );
                $( "#costaddress2" ).val( ui.item.address2 );
                $( "#costaddress3" ).val( ui.item.address3 );
        
                return false;
            }
            })
            .autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
                .append( "<div>" + item.label + "<br/>" + item.address1 + "&nbsp;" + item.address2 + "&nbsp;" + item.address3 + "</div>" )
                .appendTo( ul );
            };
            
            if ( is_ID == true ) {
                    $('#unitprice_1, #linetotal_1').number( true, 2, ',', '.' );
                    $('#invsubtotal, #invcredit, #invdiscount, #invship, #invtotal, #invtax').number( true, 2, ',', '.' );
                } else {
                    $('#unitprice_1, #linetotal_1').number( true, 2 );
                    $('#invsubtotal, #invcredit, #invdiscount, #invship, #invtotal, #invtax').number( true, 2 );
                }

            });
            function previewImage(event) {
            // Ambil file yang dipilih oleh pengguna
            let file = event.target.files[0];

            // Validasi tipe file
            if (!file.type.match('image.*')) {
            alert("Hanya file gambar yang diizinkan!");
            return;
            }

            // Buat objek URL untuk file yang dipilih
            let reader = new FileReader();
            reader.onload = function(event) {
            // Tampilkan gambar di dalam elemen img dengan id current-logo
            let imgElement = document.getElementById('current-logo');
            imgElement.src = event.target.result;
            imgElement.width = 240; // Ukuran gambar bisa disesuaikan
            imgElement.height = 180;
            };
            
            // Baca konten dari file sebagai URL data
            reader.readAsDataURL(file);
        }

document.addEventListener('DOMContentLoaded', function() {
    const nomorNotaInput = document.getElementById('invnumber');
    const generateCheckbox = document.getElementById('generate_nomor');

    function updateNomorNota() {
        if (generateCheckbox.checked) {
            fetch('/addPenjualan/generate-nomor-nota')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    nomorNotaInput.value = data.nomor_nota;
                    nomorNotaInput.setAttribute('readonly', 'readonly'); // Set readonly ketika generate dicentang
                })
                .catch(error => {
                    console.error('Error fetching nomor nota:', error);
                });
        } else {
            nomorNotaInput.value = '';  // Kosongkan input jika checkbox tidak dicentang
            nomorNotaInput.removeAttribute('readonly'); // Hapus readonly ketika generate tidak dicentang
        }
    }

    generateCheckbox.addEventListener('change', updateNomorNota);

    // Inisialisasi saat halaman dimuat
    updateNomorNota();
});

        </script>

    </div>
    {{-- <div id="tabs-2" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel"
        aria-hidden="false" style="display: block;">
        <h3 class="h3report" id="h3allcash">Buku Invoice</h3>
        <div class="invoiceframe">
        
        <div class="notifreport">Anda tidak memiliki invoice tersimpan.</div>
        </div>
    </div> --}}
    
    <div class="clear"></div>  
</div>	
</div>
</div>

@endsection
