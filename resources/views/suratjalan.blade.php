<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Surat Jalan</title>
        <link rel="icon" href="images/logo_favicon.png" type="image/ico" />
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style media="all">
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
              <th style="width: 380px;" colspan="3">
                PT. INDUSTRI NABATI LESTARI<br>
                <span style="font-size: 12px;">
                  Special Economic Zone. Sei Mangkei Kav 2-3 Bosar Maligas Regency , Simalungun North Sumatra 21184 Indonesia.<br>
                <span>
                  FORMULIR SURAT JALAN
                </th> 
              <th style="font-size: 12px;">
                No. Dokumen<br>
                {{ $no_dokumen }}<br>
                No. Revisi<br>
                {{ $no_revisi }}<br>
              </th>
              <th style="font-size: 12px;">
                Tgl. Berlaku<br>
                {{ $tgl_berlaku }}<br>
                Halaman<br>
                1 dari 1
              </th>
            </tr>
            <tr>
              <td colspan="6">
Kepada :<br>
{{ $reg->partner_name }}<br>
{{ $reg->alamat }}<br>
<br>
No. PO      : {{ $reg->no_po }}<br>
No. DO. INL : {{ $reg->no_documen_do }}<br>
No. Kontrak : {{ $reg->origin }}<br>
No. Iso Tank : -<br>
No. Flat Kendaraan : {{ $reg->no_polisi }}<br>
Nama Supir : {{ $reg->driver_name }}<br>
From Tank : -<br>

              </td>
            </tr>
            </table>

            <table style="width:100%">              
              <tr>
                <td style="text-align: center;width: 10px;"><b>No.</b></td>
                <td style="text-align: center;width: 30px;" ><b>Nama Barang</b></td>
                <td style="text-align: center;width: 200px; "><b>Spesifikasi</b></td>
                <td style="text-align: center"><b>Jumlah</b></td>
                <td style="text-align: center"><b>Satuan</b></td>
                <td style="text-align: center;"><b>Keterangan</b></td>
              </tr>            
            <tr>
                <td>1</td>
                <td style="width: 250px;">{{ $reg->product_name }}<br>{{ $reg->default_code }}</td>
                <td>
                  FFA : {{ $load->ffa }}<br>
                  M & I : {{ $load->mni }}<br>
                  LC : {{ $load->lc }}<br>
                  IV : {{ $load->iv }}
                </td>
                <td style="text-align: center">{{ $tim->netto }}</td>
                <td style="text-align: center">Kg</td>
                <td style="width: 300px;">
                  No.Segel INL <br>
                  1 #{{ $load->segel1 }}<br>
                  2 #{{ $load->segel2 }}<br>
                  3 #{{ $load->segel3 }}<br>
                  No.Segel Expedisi<br>
                  No.Segel Bea Cukai<br>
                  {{ $reg->no_segel_bc }}
                </td>
              </tr>

              <tr>
                <td colspan="6"><br></td>
              </tr>
          </table>

          <table style="width:100%">
            <tr>
              <td colspan="2"><img src="../images/cap_pass.png">Timbangan</td>
              <td colspan="2"><img src="../images/cap_pass.png">QCPass</td>
              <td colspan="2"><img src="../images/cap_pass.png">Loading</td>
            </tr>
          </table>          

          <table style="width:100%">
            <tr>
                <td colspan="6">
                    <br>
                    <b><i>Harap di cek dan diterima dengan baik</b></i>
                    <br>
                </td>
              </tr>
              <tr>
                <td colspan="2">Pengirim/Pemilik Barang</td>
                <td colspan="2">Pengangkut/Supir</td>
                <td colspan="2">Penerima/Tanggal</td>
              </tr>          
              <tr>
                <td colspan="2"><br><br><br>-----------------------<br></td>
                <td colspan="2"><br><br><br>-----------------------<br></td>
                <td colspan="2"><br><br><br>-----------------------<br></td>
              </tr>             
              <tr>
                <td colspan="2">PT. INDUSTI NABATI LESTARI</td>
                <td colspan="2">{{ $reg->driver_name }}</td>
                <td colspan="2">....</td>
              </tr>                                   
            {{-- <tr>
              <td>John</td>
              <td>Doe</td>
              <td>Doe</td>
              <td>Doe</td>
              <td>Doe</td>
              <td>80</td>
            </tr> --}}
          </table>

          
    </body>
</html>
