<?php

namespace App\Observers;

use App\Models\TransaksiKas;
use App\Models\Kas;

class TransaksiKasObserver
{
    /**
     * Handle the TransaksiKas "created" event.
     */
    public function created(TransaksiKas $transaksi): void
    {
        $kas = Kas::find($transaksi->id_kas);
        
        if ($kas) {
            if ($transaksi->jenis_transaksi == 'MASUK') {
                $kas->increment('saldo', $transaksi->nominal);
            } else {
                $kas->decrement('saldo', $transaksi->nominal);
            }
        }
    }

    /**
     * Handle the TransaksiKas "updated" event.
     */
    public function updated(TransaksiKas $transaksi): void
    {
        // Only if nominal or jenis_transaksi changed
        if ($transaksi->isDirty('nominal') || $transaksi->isDirty('jenis_transaksi')) {
             $kas = Kas::find($transaksi->id_kas);
             if (!$kas) return;

             $oldNominal = $transaksi->getOriginal('nominal');
             $newNominal = $transaksi->nominal;
             
             // Revert old effect
             if ($transaksi->getOriginal('jenis_transaksi') == 'MASUK') {
                 $kas->decrement('saldo', $oldNominal);
             } else {
                 $kas->increment('saldo', $oldNominal);
             }

             // Apply new effect
             if ($transaksi->jenis_transaksi == 'MASUK') {
                 $kas->increment('saldo', $newNominal);
             } else {
                 $kas->decrement('saldo', $newNominal);
             }
        }
    }

    /**
     * Handle the TransaksiKas "deleted" event.
     */
    public function deleted(TransaksiKas $transaksi): void
    {
        $kas = Kas::find($transaksi->id_kas);

        if ($kas) {
            // Reverse logic
            if ($transaksi->jenis_transaksi == 'MASUK') {
                $kas->decrement('saldo', $transaksi->nominal);
            } else {
                $kas->increment('saldo', $transaksi->nominal);
            }
        }
    }
}
