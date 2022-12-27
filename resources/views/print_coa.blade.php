<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>COA</title>
        <link rel="icon" href="images/logo_favicon.png" type="image/ico" />
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }
            table, th, td {
                border: 1px solid black;
                border-collapse: collapse;
            }
        </style>
    </head>
    <body>
        <table style="width:100%">
            <tr>
              <th style="width: 30px;"><img style="width: 90px;" src="../images/LOGO-INL.png"/></th>
              <th colspan="3" style="width: 380px;">
                  PT. INDUSTRI NABATI LESTARI<br>
                  <span style="font-size: 12px;">
                  Special Economic Zone. Sei Mangkei Kav 2-3 Bosar Maligas Regency , Simalungun North Sumatra 21184 Indonesia.<br>
                  CERTIFICATE OF ANALYSIS
                  </span>
                </th> 
              <th style="font-size: 12px;">
                No. Dokumen<br>
                {{ $no_dokumen_coa }}<br>
                No. Revisi<br>
                {{ $no_revisi_coa }}

              </th>
              <th style="font-size: 12px;">
                Tgl. Berlaku<br>
                {{ $tgl_berlaku_coa }}<br>
                Halaman <br>
                1 s.d. 1
              </th>
            </tr>
            <tr>
              <td colspan="6">
<p style="text-align: center">
CERTIFICATE OF ANALYSIS<br>
{{ $load->no_surat_coa }}{{ $format_surat }}{{date('Y')}}<br>
</p>

<p style="text-align: left">
  TO : {{ $reg->destination }}<br>
  DATE : {{ $load->loading_tgl }}<br>
  PRODUCT : {{ $reg->product_name }}<br>
  SOURCE : {{ $load->no_tanki }}<br>
  CONTRACT NO : {{ $reg->no_contract }}
</p>

              </td>
            </tr>  
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
        </table>

        <table style="width:100%">
              <tr style="background: #bfc6c5;color: black;">
                <td colspan="2" style="text-align: center"><b>PARAMETER</b></td>
                <td colspan="2" style="text-align: center"><b>METHOD</b></td>
                <td colspan="2" style="text-align: center"><b>RESULT</b></td>
              </tr>   
              <tr>
                <td colspan="2"><b>{{ $ffa->detail_parameter }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $ffa->method }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $load->ffa }}</b></td>
              </tr>                         
              <tr>
                <td colspan="2"><b>{{ $mni->detail_parameter }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $mni->method }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $load->mni }}</b></td>
              </tr>   
              <tr>
                <td colspan="2"><b>{{ $iv->detail_parameter }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $iv->method }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $load->iv }}</b></td>
              </tr>   
              <tr>
                <td colspan="2"><b>{{ $lc->detail_parameter }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $lc->method }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $load->lc }}</b></td>
              </tr>   
              <tr>
                <td colspan="2"><b>{{ $melting->detail_parameter }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $melting->method }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $load->melting_point }}</b></td>
              </tr>                                             

              <tr>
                <td colspan="2"><b>{{ $cloud->detail_parameter }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $cloud->method }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $load->cloud_point }}</b></td>
              </tr>                                             
              <tr>
                <td colspan="2"><b>{{ $saponifiable->detail_parameter }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $saponifiable->method }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $load->saponifiable_matter }}</b></td>
              </tr>                                             
              <tr>
                <td colspan="2"><b>{{ $peroxide->detail_parameter }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $peroxide->method }}</b></td>
                <td colspan="2" style="text-align: center"><b>{{ $load->peroxide_value }}</b></td>
              </tr>                                         
          </table>

          <table style="width:100%">
          <tr>
            <td colspan="6">
              <br>
              We here by certify the analysis of the sample as shown above.<br>
              <br>
              <img src="../images/ttd_qcpass.png">
            </td>
          </tr>
          </table>
    </body>
</html>
