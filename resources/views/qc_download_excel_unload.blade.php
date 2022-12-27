<p>LAPORAN QCPASS UNLOADING</p>
<table>
    <thead>
    <tr>
        <th>Kontrak</th>
        <th>PKS</th>
        <th>No.Polisi</th>
        <th>FFA</th>
        <th>MnI</th>
        <th>IV</th>
        <th>LC</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{ $row->origin }}</td>
            <td>{{ $row->partner_name }}</td>
            <td>{{ $row->no_polisi }}</td>
            <td>{{ $row->ffa }}</td>
            <td>{{ $row->mni }}</td>
            <td>{{ $row->iv }}</td>
            <td>{{ $row->lc }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
