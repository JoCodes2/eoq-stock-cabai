<?php

namespace App\Repositories;

use App\Http\Requests\PermintaanRequest;
use App\Interfaces\PermintaanInterfaces;
use App\Models\PermintaanModel;
use App\Models\ItemPermintaanModel;
use App\Models\StokKeluarModel;
use App\Models\MasterModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermintaanRepositories implements PermintaanInterfaces
{
    use HttpResponseTraits;

    protected $permintaanModel;

    public function __construct(PermintaanModel $permintaanModel)
    {
        $this->permintaanModel = $permintaanModel;
    }

    public function getAllData()
    {
        $data = $this->permintaanModel::with(['user', 'items.masterData'])->latest()->get();
        return $data->isEmpty() ? $this->dataNotFound() : $this->success($data);
    }

    public function createData(PermintaanRequest $request)
    {
        DB::beginTransaction();
        try {
            $totalHargaNota = 0;
            foreach ($request->items as $item) {
                $totalHargaNota += ($item['jumlah'] * $item['harga_satuan']);
            }

            $nomorPermintaan = 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $permintaan = $this->permintaanModel::create([
                'pengguna_id'      => $request->pengguna_id,
                'nomor_permintaan' => $nomorPermintaan,
                'total_harga_nota' => $totalHargaNota,
                'status'           => 'menunggu',
                'catatan'          => $request->catatan,
            ]);

            foreach ($request->items as $item) {
                $master = MasterModel::findOrFail($item['master_data_id']);

                ItemPermintaanModel::create([
                    'permintaan_id'  => $permintaan->id,
                    'master_data_id' => $item['master_data_id'],
                    'nama_cabai'     => $master->nama,
                    'satuan'         => $master->satuan,
                    'jumlah'         => $item['jumlah'],
                    'harga_satuan'   => $item['harga_satuan'],
                    'total_harga'    => $item['jumlah'] * $item['harga_satuan'],
                ]);
            }

            DB::commit();
            return $this->success($permintaan);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }

    public function getDataById($id)
    {
        $data = $this->permintaanModel::with(['user', 'items.masterData'])->find($id);
        return $data ? $this->success($data) : $this->dataNotFound();
    }

    public function getByNota($nomor_permintaan)
    {
        $data = $this->permintaanModel::with(['user', 'items.masterData'])
            ->where('nomor_permintaan', $nomor_permintaan)
            ->first();
        return $data ? $this->success($data) : $this->dataNotFound();
    }

    public function updateStatus($id, $status)
    {
        DB::beginTransaction();
        try {
            $permintaan = $this->permintaanModel::with('items')->findOrFail($id);

            if ($permintaan->status === 'menunggu' && $status === 'diproses') {
                foreach ($permintaan->items as $item) {
                    $master = MasterModel::findOrFail($item->master_data_id);

                    if ($master->jumlah < $item->jumlah) {
                        throw new \Exception("Stok {$master->nama} tidak mencukupi. Sisa: {$master->jumlah} {$master->satuan}.");
                    }

                    $master->jumlah -= $item->jumlah;
                    $master->save();

                    StokKeluarModel::create([
                        'master_data_id' => $item->master_data_id,
                        'permintaan_id'  => $permintaan->id,
                        'jumlah'         => $item->jumlah,
                        'keterangan'     => "Pesanan diproses: " . $permintaan->nomor_permintaan,
                        'dikeluarkan_pada' => now()
                    ]);
                }
                $permintaan->diproses_pada = now();
            }

            // Jika pesanan selesai
            if ($status === 'selesai') {
                $permintaan->selesai_pada = now();
            }

            $permintaan->status = $status;
            $permintaan->save();

            DB::commit();
            return $this->success($permintaan, "Status berhasil diperbarui ke " . $status);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400);
        }
    }

    public function deleteData($id)
    {
        DB::beginTransaction();
        try {
            $permintaan = $this->permintaanModel::findOrFail($id);

            if (!in_array($permintaan->status, ['menunggu', 'ditolak'])) {
                return $this->error("Data tidak bisa dihapus karena sudah diproses/selesai.", 403);
            }

            $permintaan->delete();

            DB::commit();
            return $this->success(null, "Permintaan berhasil dihapus.");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400);
        }
    }
}
