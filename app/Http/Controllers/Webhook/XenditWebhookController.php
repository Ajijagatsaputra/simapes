<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Xendit Webhook Received: ', $request->all());

        // Callback token verification (Optional but highly recommended)
        $callbackToken = env('XENDIT_CALLBACK_TOKEN');
        if ($callbackToken && $request->header('x-callback-token') !== $callbackToken) {
            Log::warning('Xendit Webhook invalid callback token.');
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $externalId = $request->input('external_id');
        $status = $request->input('status');

        // Extract pembayaran ID from external_id (format: "simapes-payment-{id}")
        if ($externalId && str_starts_with($externalId, 'simapes-payment-')) {
            $pembayaranId = str_replace('simapes-payment-', '', $externalId);
            $pembayaran = Pembayaran::find($pembayaranId);

            if ($pembayaran) {
                if (in_array($status, ['PAID', 'SETTLED'])) {
                    if ($pembayaran->status !== 'verified') {
                        DB::beginTransaction();
                        try {
                            $pembayaran->update([
                                'status' => 'verified',
                                'verified_at' => now(),
                                'metode_pembayaran' => strtolower($request->input('payment_method', 'xendit')),
                            ]);

                            $pesanan = $pembayaran->pesanan;
                            if ($pesanan) {
                                $pesanan->recalculatePembayaran();
                                $pesanan->recalculateItemCoverage();
                            }

                            DB::commit();
                            Log::info("Pembayaran #{$pembayaranId} verified via Xendit Webhook.");
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error("Error updating pembayaran #{$pembayaranId} via Webhook: " . $e->getMessage());
                            return response()->json(['message' => 'Internal Server Error'], 500);
                        }
                    }
                } elseif ($status === 'EXPIRED') {
                    DB::beginTransaction();
                    try {
                        $pembayaran->update([
                            'status' => 'rejected',
                            'catatan' => 'Xendit Invoice Expired',
                        ]);
                        DB::commit();
                        Log::info("Pembayaran #{$pembayaranId} marked as expired/rejected.");
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error("Error marking pembayaran #{$pembayaranId} expired: " . $e->getMessage());
                    }
                }
            } else {
                Log::warning("Pembayaran not found for ID: {$pembayaranId}");
            }
        }

        return response()->json(['message' => 'Success']);
    }
}
