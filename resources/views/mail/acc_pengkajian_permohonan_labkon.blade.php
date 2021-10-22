<h1>Hallo {{ $created_by }}</h1>
<p>Permohonan pengujian Laboratorium Konstruksi dengan no permohonan {{ $no_permohonan }} a.n {{ $pemohon_name }}
    telah diterima
</p>
<p>Pesan dari penguji ({{ $penguji_name }}): {{ $catatan ? $catatan : '-' }}</p>
<p>Anda akan mendapatkan email pemberitahuan kembali saat terjadi perubahan status pengujian.</p>
