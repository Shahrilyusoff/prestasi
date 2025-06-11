<div class="section-content">
    <h4 class="mb-4">BAHAGIAN VIII - ULASAN KESELURUHAN DAN PENGESAHAN OLEH PEGAWAI PENILAI PERTAMA</h4>
    
    <form action="{{ route('evaluations.update-section', $evaluation) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="section" value="VIII">
        
        <div class="mb-3">
            <label class="form-label">1. Tempoh PYD bertugas di bawah pengawasan:</label>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <input type="text" class="form-control" name="tempoh_penilaian_ppp_tahun" 
                           value="{{ $evaluation->tempoh_penilaian_ppp_mula ? $evaluation->tempoh_penilaian_ppp_mula->format('Y') : '' }}" 
                           {{ $editableSections['VIII'] ? 'required' : 'readonly' }}>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <input type="text" class="form-control" name="tempoh_penilaian_ppp_bulan" 
                           value="{{ $evaluation->tempoh_penilaian_ppp_mula && $evaluation->tempoh_penilaian_ppp_tamat ? 
                                  $evaluation->tempoh_penilaian_ppp_mula->diffInMonths($evaluation->tempoh_penilaian_ppp_tamat) : '' }}" 
                           {{ $editableSections['VIII'] ? 'required' : 'readonly' }}>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">2. Penilai Pertama hendaklah memberi ulasan keseluruhan prestasi PYD.</label>
            <div class="mb-3">
                <label class="form-label">i) Prestasi keseluruhan</label>
                <textarea class="form-control" name="ulasan_keseluruhan_ppp" rows="3" 
                          {{ $editableSections['VIII'] ? 'required' : 'readonly' }}>{{ $evaluation->ulasan_keseluruhan_ppp }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">ii) Kemajuan kerjaya</label>
                <textarea class="form-control" name="kemajuan_kerjaya_ppp" rows="3" 
                          {{ $editableSections['VIII'] ? 'required' : 'readonly' }}>{{ $evaluation->kemajuan_kerjaya_ppp }}</textarea>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">3. Adalah disahkan bahawa prestasi pegawai ini telah dimaklumkan kepada PYD</label>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama PPP</label>
                    <input type="text" class="form-control" value="{{ $evaluation->ppp->name }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jawatan</label>
                    <input type="text" class="form-control" value="{{ $evaluation->ppp->jawatan }}" readonly>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Kementerian/Jabatan</label>
                    <input type="text" class="form-control" value="{{ $evaluation->ppp->kementerian_jabatan }}" readonly>
                </div>
            </div>
        </div>
        
        @if($editableSections['VIII'])
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">Simpan Ulasan</button>
        </div>
        @endif
    </form>
</div>