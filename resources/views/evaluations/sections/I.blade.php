<div class="section-content">
    <h4 class="mb-4">BAHAGIAN I - MAKLUMAT PEGAWAI</h4>
    
    <table class="table table-bordered">
        <tbody>
            <tr>
                <th width="30%">Nama</th>
                <td>{{ $evaluation->pyd->name }}</td>
            </tr>
            <tr>
                <th>Jawatan dan Gred</th>
                <td>{{ $evaluation->pyd->jawatan }} ({{ $evaluation->pyd->gred }})</td>
            </tr>
            <tr>
                <th>Kementerian/Jabatan</th>
                <td>{{ $evaluation->pyd->kementerian_jabatan }}</td>
            </tr>
            <tr>
                <th>Kumpulan PYD</th>
                <td>{{ $evaluation->pyd->pydGroup->nama_kumpulan }}</td>
            </tr>
        </tbody>
    </table>
</div>