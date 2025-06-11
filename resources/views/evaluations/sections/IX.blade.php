<div class="section-content">
    <h4 class="mb-4">BAHAGIAN IX - ULASAN KESELURUHAN DAN PENGESAHAN OLEH PEGAWAI PENILAI KEDUA</h4>
    
    <form action="{{ route('evaluations.update-section', $evaluation) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="section" value="IX">
        
        <div class="mb-3">
            <label class="form-label">1. Tempoh PYD bertugas di bawah pengawasan:</label>
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <input type="text" class="form-control" name="tempoh_penilaian_ppk_tahun" 
                           value="{{ $evaluation->tempoh_penilaian_ppk_mula ? $evaluation->tempoh_penilaian_ppk_mula->format('Y') : '' }}" 
                           {{ $editableSections['IX'] ? 'required' : 'readonly' }}>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <input type="text" class="form-control" name="tempoh_penilaian_ppk_bulan" 
                           value="{{ $evaluation->tempoh_penilaian_ppk_mula && $evaluation->tempoh_penilaian_ppk_tamat ? 
                                  $evaluation->tempoh_penilaian_ppk_mula->diffInMonths($evaluation->tempoh_penilaian_ppk_tamat) : '' }}" 
                           {{ $editableSections['IX'] ? 'required' : 'readonly' }}>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">2. PPK hendaklah memberi ulasan keseluruhan pencapaian prestasi PYD berasaskan ulasan keseluruhan oleh PPP</label>
            <textarea class="form-control" name="ulasan_keseluruhan_ppk" rows="5" 
                      {{ $editableSections['IX'] ? 'required' : 'readonly' }}>{{ $evaluation->ulasan_keseluruhan_ppk }}</textarea>
        </div>
        
        <div class="mb-3">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama PPK</label>
                    <input type="text" class="form-control" value="{{ $evaluation->ppk->name }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jawatan</label>
                    <input type="text" class="form-control" value="{{ $evaluation->ppk->jawatan }}" readonly>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Kementerian/Jabatan</label>
                    <input type="text" class="form-control" value="{{ $evaluation->ppk->kementerian_jabatan }}" readonly>
                </div>
            </div>
        </div>
        
        @if($editableSections['IX'])
        <div class="text-end mt-3">
            <button type="submit" class="btn btn-primary">Simpan Ulasan</button>
        </div>
        @endif
    </form>
</div>