<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrintPDFController extends Controller
{
    public function __construct()
    {
        $this->data_pekerjaan = (object)[
            'uptd_romawi' => 'III',
            'sub_kegiatan' => 'BOBONGKAR',
            'sppj_wilayah' => 'GARUT 2',
            'tanggal_hari' => '08-08-2021/Rabu',
            'ruas_jalan' => 'Ahmad Yani',
            'km_dari' => 12,
            'km_ke' => 15,
            'kab_kota' => 'Bandung Barat',
            'hal_dari' => 1,
            'hal_ke' => 2,
            'tenaga_kerja' => array([
                'jabatan' => 'Mandor',
                'satuan' => 'Hok',
                'jumlah' => 22
            ], [
                'jabatan' => 'Pekerja',
                'satuan' => 'Hok',
                'jumlah' => 43
            ], [
                'jabatan' => 'Mandor',
                'satuan' => 'Hok',
                'jumlah' => 22
            ], [
                'jabatan' => 'Pekerja',
                'satuan' => 'Hok',
                'jumlah' => 43
            ]),
            'material_tiba' => array([
                'jenis_material' => 'Agregat Klas A',
                'satuan' => 'M3',
                'kuantitas' => 25
            ], [
                'jenis_material' => 'Agregat Klas B',
                'satuan' => 'M3',
                'kuantitas' => 11
            ], [
                'jenis_material' => 'Pasir Aspal',
                'satuan' => 'Ton',
                'kuantitas' => 2
            ]),
            'hasil_dicapai' => (object)[
                'pemeliharaan_jalan' => array([
                    'jenis_pekerjaan' => 'Pembersihan Damija',
                    'satuan' => 'M3',
                    'perkiraan_kuantitas' => 25
                ], [
                    'jenis_pekerjaan' => 'Perawatan Selokan',
                    'satuan' => 'M3',
                    'perkiraan_kuantitas' => 11
                ], [
                    'jenis_pekerjaan' => 'Galian Saluran',
                    'satuan' => 'Ton',
                    'perkiraan_kuantitas' => 2
                ]),
                'pemeliharaan_jembatan' => array([
                    'jenis_pekerjaan' => 'Pengecetan',
                    'satuan' => 'M3',
                    'perkiraan_kuantitas' => 25
                ], [
                    'jenis_pekerjaan' => 'Pasangan Baru',
                    'satuan' => 'M3',
                    'perkiraan_kuantitas' => 11
                ], [
                    'jenis_pekerjaan' => 'Pembersihan Jembatan',
                    'satuan' => 'Ton',
                    'perkiraan_kuantitas' => 2
                ])
            ],
            'bahan_operasional' => array([
                'jenis_bahan_bakar' => 'Solar',
                'satuan' => 'Ltr',
                'kuantitas' => 25
            ], [
                'jenis_bahan_bakar' => 'Pertamax',
                'satuan' => 'Ltr',
                'kuantitas' => 11
            ], [
                'jenis_bahan_bakar' => 'Pertalite',
                'satuan' => 'Ltr',
                'kuantitas' => 2
            ]),
            'penghambat_pelaksanaan' => array([
                'jenis_gangguan_dan_cuaca' => 'Cerah',
                'waktu' => '-',
                'akibat' => '-'
            ], [
                'jenis_gangguan_dan_cuaca' => 'Berawan',
                'waktu' => '-',
                'akibat' => '-'
            ], [
                'jenis_gangguan_dan_cuaca' => 'Hujan Gerimis',
                'waktu' => '-',
                'akibat' => '-'
            ]),
            'peralatan' => array([
                'jenis_peralatan' => 'Dump Truck',
                'satuan' => 'Unit',
                'kuantitas' => 5
            ],[
                'jenis_peralatan' => 'Pick Up',
                'satuan' => 'Unit',
                'kuantitas' => 9
            ],[
                'jenis_peralatan' => 'Alat Bantu',
                'satuan' => 'Unit',
                'kuantitas' => 'set'
            ],),
            'pengamat' => 'SUKRIJAH',
            'ksppjj' => 'MARIAH',
            'usulan_pengamat' => 'LORAEIAHD BFHJWEABFJE FGYEAUGFS DHSJABFC SJKFHLA',
            'usulan_ksppjj' => 'fhjaesgfu feuaihf fhjkal hfjwla'
        ];
    }

    public function laporanPekerjaan()
    {
        // dd($this->data_pekerjaan);
        return view('pdf.templet_laporan_pekerjaan', ['data' => $this->data_pekerjaan]);
    }
}
