<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\ProgresProduksi;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProgresProduksiController extends Controller
{
    public function show($id)
    {
        $pesanan = Pesanan::with(['details.produk', 'progresProduksis'])->findOrFail($id);

        if (!in_array($pesanan->status, ['dikerjakan', 'selesai'])) {
            return redirect()->route('admin.pesanan.index')
                ->with('error', 'Progres produksi hanya dapat dikelola saat status pesanan "Dikerjakan" atau "Selesai".');
        }

        $totalPcs = $pesanan->details->sum('total_item');
        return view('admin.pesanan.progres', compact('pesanan', 'totalPcs'));
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $totalPcs = $pesanan->details->sum('total_item');

        $request->validate([
            'stages' => 'required|array|min:1',
            'stages.*.tahapan' => 'required|string|max:100',
            'stages.*.jumlah_pcs' => "required|integer|min:0|max:{$totalPcs}",
            'stages.*.dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'stages.*.catatan' => 'nullable|string|max:1000',
            'stages.*.id' => 'nullable|integer|exists:progres_produksis,id',
        ], [
            'stages.*.jumlah_pcs.max' => "Jumlah pcs pada setiap tahapan tidak boleh melebihi total target pesanan ({$totalPcs} pcs).",
        ]);
        $stages = $request->input('stages', []);

        DB::beginTransaction();
        try {
            $submittedIds = [];

            foreach ($stages as $index => $stageData) {
                $stageId = $stageData['id'] ?? null;

                $updateData = [
                    'pesanan_id' => $pesanan->id,
                    'tahapan' => $stageData['tahapan'],
                    'jumlah_pcs' => $stageData['jumlah_pcs'],
                    'catatan' => $stageData['catatan'] ?? null,
                ];

                // Handle file upload
                if ($request->hasFile("stages.{$index}.dokumentasi")) {
                    $file = $request->file("stages.{$index}.dokumentasi");
                    // Store file in public/dokumentasi
                    $path = $file->store('dokumentasi', 'public');
                    $updateData['dokumentasi'] = $path;

                    // If there was an old file, delete it
                    if ($stageId) {
                        $existingStage = ProgresProduksi::find($stageId);
                        if ($existingStage && $existingStage->dokumentasi) {
                            Storage::disk('public')->delete($existingStage->dokumentasi);
                        }
                    }
                } elseif (isset($stageData['existing_dokumentasi'])) {
                    // Retain old file if no new upload
                    $updateData['dokumentasi'] = $stageData['existing_dokumentasi'];
                }

                if ($stageId) {
                    $progress = ProgresProduksi::findOrFail($stageId);
                    $progress->update($updateData);
                    $submittedIds[] = $progress->id;
                } else {
                    $progress = ProgresProduksi::create($updateData);
                    $submittedIds[] = $progress->id;
                }
            }

            // Delete any stages that were removed
            $stagesToDelete = ProgresProduksi::where('pesanan_id', $pesanan->id)
                ->whereNotIn('id', $submittedIds)
                ->get();

            foreach ($stagesToDelete as $oldStage) {
                if ($oldStage->dokumentasi) {
                    Storage::disk('public')->delete($oldStage->dokumentasi);
                }
                $oldStage->delete();
            }

            ActivityLog::log('Memperbarui progres produksi pesanan: ' . $pesanan->no_pesanan, 'Pesanan', $pesanan->id);

            DB::commit();
            return redirect()->route('admin.pesanan.index')->with('success', 'Progres produksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui progres: ' . $e->getMessage())->withInput();
        }
    }
}
