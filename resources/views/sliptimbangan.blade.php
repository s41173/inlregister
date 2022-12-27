<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Slip Timbangan</title>
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
              <th><img style="width: 90px;" src="../images/LOGO-INL.png"/></th>
              <th style="width: 380px;" colspan="3" >
                  PT. INDUSTRI NABATI LESTARI<br>
                  <span style="font-size: 12px;">
                    Special Economic Zone. Sei Mangkei Kav 2-3 Bosar Maligas Regency , Simalungun North Sumatra 21184 Indonesia.<br>
                  </span>
                  SLIP TIMBANGAN
                </th> 
              <th style="font-size: 12px;">
                No. Dokumen<br>
                {{ $no_dokumen }}<br>
                Revisi
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
No. Flat Kendaraan : {{ $reg->no_polisi }}<br>
Nama Supir : {{ $reg->driver_name }}<br>
              </td>
            </tr>
          </table>

          <table style="width:100%">
              <tr style="background: #bfc6c5;color: black;">
                <td style="text-align:center;width:100px;"><b>Jenis</b></td>
                <td style="text-align:center;width:200px;"><b>Pengirim</b></td>
                <td style="text-align:center"><b>Tanggal/Jam</b></td>
                <td style="text-align:center;width:100px;"><b>Berat(Kg)</b></td>
                <td style="text-align:center"><b>#</b></td>
              </tr>            
              <tr>
                  <td rowspan="3" style="width: 250px;">{{ $reg->product_name }} / {{ $reg->default_code }}</td>
                  <td rowspan="3">{{ $reg->partner_name }}</td>
                  <td>{{ $tim->tgl_timbang1 }}</td>
                  <td style="text-align: center">
                    {{ ($reg->jenis == "Incoming") ? "Bruto" : "Tarra" }} :
                    {{ ($reg->jenis == "Incoming") ? $tim->bruto : $tim->tarra }}
                  </td>
                  <td style="text-align: center">1  </td>
              </tr>  
              <tr>
                <td>{{ $tim->tgl_timbang2 }}</td>
                <td style="text-align: center">
                  {{ ($reg->jenis == "Incoming") ? "Tarra" : "Bruto" }} :
                  {{ ($reg->jenis == "Incoming") ? $tim->tarra : $tim->bruto }}
                </td>
                <td style="text-align: center">2</td>
              </tr>
              <tr>
                <td></td>
                <td style="text-align: center">Netto : {{ $tim->netto }}</td>
                <td></td>
              </tr>
          </table>

          <table style="width:100%">
            <tr>
              <td colspan="6"><br></td>
            </tr>
          <tr>
                <td colspan="4">
                  No. Tiket   : {{ $reg->no_ticket }}<br>
                  From/To     : {{ $reg->partner_name }}<br>
                  Quality     : {{ $reg->product_uom_qty }} KG <br>
                  No. Kontrak : {{ $reg->origin }}<br>
                  Delivery Note : {{ $reg->origin }}<br>
                  Delivery Order : {{ $reg->no_do }}<br>
                  Transporter : {{ $reg->transporter }}<br>
                </td>
                <td colspan="1">
                  Petugas
                  <br>
                  <br>
                  <br>
                  -------
                </td>
                <td colspan="1">
                  Supir
                  <br>
                  <br>
                  <br>
                  -------
                </td>
            </tr>          
            <tr>
                <td colspan="4"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
            </tr>
          </table>
    </body>
</html>
