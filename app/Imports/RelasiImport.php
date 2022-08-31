<?php

namespace App\Imports;

use App\Models\MhRelasi;
use App\Models\MhRelasiJenis;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class RelasiImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @var int
     */
    private $nomormhusaha;
    /**
     * @var int
     */
    private $nomormhadmin;
    /**
     * @var int
     */
    private $nomormhcabang;

    public function __construct(int $nomormhusaha, int $nomormhcabang, int $nomormhadmin)
    {
        $this->nomormhusaha = $nomormhusaha;
        $this->nomormhcabang = $nomormhcabang;
        $this->nomormhadmin = $nomormhadmin;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $relasi = new MhRelasi([
            'nomormhcabang' => $this->nomormhcabang,
            'nomormhusaha' => $this->nomormhusaha,
            'nomormhrelasijenis' => MhRelasiJenis::where('nama', 'LIKE', $row['jenis_relasi'])->value('nomor'),
            'nama' => $row['nama'],
            'pkp' => $row['pkp'],
            'alamat' => $row['alamat'],
            'kode_pos' => $row['kode_pos'],
            'fax' => $row['fax'],
            'telepon' => $row['telepon'],
            'email' => $row['email'],
            'npwp' => $row['npwp'],
            'kontak_nama' => $row['nama_kontak'],
            'kontak_email' => $row['email_kontak'],
            'kontak_telepon' => $row['telepon_kontak'],
            'dibuat_oleh' => $this->nomormhadmin,
        ]);

        $relasi->save();

        DB::select('CALL sp_disimpan_mhrelasi(?, ?, ?)', array($relasi->nomor, 'add', $this->nomormhadmin));

        return $relasi;
    }

    public function rules(): array
    {
        return [
            'jenis_relasi' => 'required|exists:mhrelasijenis,nama',
            'pkp' => 'required|boolean',
        ];
    }
}
