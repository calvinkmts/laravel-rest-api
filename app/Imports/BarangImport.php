<?php

namespace App\Imports;

use App\Models\MhBarang;
use App\Models\MhBarangKategori;
use App\Models\MhBarangSubkategori;
use App\Models\MhSatuan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BarangImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @var int
     */
    private $nomormhusaha;

    /**
     * @var int
     */
    private $nomormhadmin;

    public function __construct(int $nomormhusaha, int $nomormhadmin)
    {
        $this->nomormhusaha = $nomormhusaha;
        $this->nomormhadmin = $nomormhadmin;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $barang = new MhBarang([
            'nama' => $row['nama'],
            'nomormhusaha' => $this->nomormhusaha,
            'nomormhsatuan' => MhSatuan::where('nama', 'LIKE', $row['satuan'])->value('nomor'),
            'nomormhbarangkategori' => MhBarangKategori::where('nama', 'LIKE', $row['kategori'])->value('nomor'),
            'nomormhbarangsubkategori' => MhBarangSubkategori::where('nama', 'LIKE', $row['sub_kategori'])->value('nomor'),
            'harga_jual' => $row['harga_jual'],
            'harga_beli' => $row['harga_beli'],
            'varian' => $row['multivarian'],
            'detail' => $row['multivarian'] ? 0 : 1,
            'dibuat_oleh' => $this->nomormhadmin,
        ]);

        $barang->save();

        DB::select('CALL sp_disimpan_mhbarang(?, ?, ?)', array($barang->nomor, 'add', $this->nomormhadmin));

        return $barang;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            'satuan' => 'exists:mhsatuan,kode',
            'kategori' => 'exists:mhbarangkategori,nama',
            'sub_kategori' => 'exists:mhbarangsubkategori,nama',
            'multivarian' => 'required|boolean',
        ];
    }
}
