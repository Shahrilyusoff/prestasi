<div class="section-content">
    <h4 class="mb-4">BAHAGIAN VII - JUMLAH MARKAH KESELURUHAN</h4>
    
    <p class="text-muted mb-4">Pegawai Penilai dikehendaki mencatatkan jumlah markah keseluruhan yang diperolehi oleh PYD dalam bentuk peratus (%) berdasarkan jumlah markah bagi setiap Bahagian yang diberi markah</p>
    
    @php
        $totalScores = $evaluation->calculateTotalScore();
    @endphp
    
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th width="60%">Markah Keseluruhan</th>
                <th width="20%">PPP (%)</th>
                <th width="20%">PPK (%)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Jumlah Markah</strong></td>
                <td>{{ number_format($totalScores['ppp'], 2) }}</td>
                <td>{{ number_format($totalScores['ppk'], 2) }}</td>
            </tr>
            <tr>
                <td><strong>Markah Purata</strong></td>
                <td colspan="2" class="text-center">{{ number_format($totalScores['purata'], 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    <div class="alert alert-info mt-4">
        <h5 class="alert-heading">Interpretasi Markah</h5>
        <ul class="mb-0">
            <li>90-100: Cemerlang</li>
            <li>80-89: Sangat Baik</li>
            <li>70-79: Baik</li>
            <li>60-69: Memuaskan</li>
            <li>50-59: Lemah</li>
            <li>0-49: Tidak Memuaskan</li>
        </ul>
    </div>
</div>