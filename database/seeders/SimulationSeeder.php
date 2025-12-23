<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Journal;
use App\Models\Journals;
use App\Models\AccountType;
use App\Models\AccountTypes;
use App\Models\JournalEntry;
use App\Models\JournalEntries;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimulationSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // ==========================================
            // 1. BUAT TIPE AKUN (Master Data)
            // ==========================================
            $typeAset = AccountTypes::firstOrCreate(
                ['name' => 'Aset'],
                ['normal_balance' => 'debit']
            );

            $typePendapatan = AccountTypes::firstOrCreate(
                ['name' => 'Pendapatan'],
                ['normal_balance' => 'credit']
            );

            // ==========================================
            // 2. BUAT AKUN / COA (Master Data)
            // ==========================================

            // Akun 101: Kas (Aset)
            $akunKas = Account::firstOrCreate(
                ['code' => '101'],
                [
                    'name' => 'Kas',
                    'account_type_id' => $typeAset->id,
                    'is_active' => true
                ]
            );

            // Akun 102: Piutang Anggota (Aset)
            $akunPiutang = Account::firstOrCreate(
                ['code' => '102'],
                [
                    'name' => 'Piutang Anggota',
                    'account_type_id' => $typeAset->id,
                    'is_active' => true
                ]
            );

            // Akun 401: Pendapatan Bunga (Pendapatan)
            $akunBunga = Account::firstOrCreate(
                ['code' => '401'],
                [
                    'name' => 'Pendapatan Bunga Pinjaman Anggota',
                    'account_type_id' => $typePendapatan->id,
                    'is_active' => true
                ]
            );

            // ==========================================
            // 3. INPUT TRANSAKSI (JURNAL)
            // ==========================================

            // --- JURNAL 1: Pencairan Pembiayaan ---
            // Tanggal: 15 Juni 2025
            // Total: 30.000.000

            $jurnal1 = Journals::create([
                'journal_number'   => 'TRX-202506-001',
                'transaction_date' => '2025-06-15',
                'description'      => 'Pencairan pembiayaan anggota A tenor 5 bulan',
                'status'           => 'posted', // Langsung posted agar masuk buku besar
                'created_by'       => 1 // Asumsi ID user admin = 1
            ]);

            // Entry 1: Piutang Anggota (Debit) - Bertambah
            JournalEntries::create([
                'journal_id' => $jurnal1->id,
                'account_id' => $akunPiutang->id,
                'debit'      => 30000000,
                'credit'     => 0
            ]);

            // Entry 2: Kas (Kredit) - Berkurang (Uang keluar)
            JournalEntries::create([
                'journal_id' => $jurnal1->id,
                'account_id' => $akunKas->id,
                'debit'      => 0,
                'credit'     => 30000000
            ]);

            // --- JURNAL 2: Pembayaran Angsuran Bulan 1 ---
            // Tanggal: 14 Juli 2025
            // Total Setor: 6.750.000
            // Rincian: Pokok (6.000.000) + Bunga (750.000)

            $jurnal2 = Journals::create([
                'journal_number'   => 'TRX-202507-001',
                'transaction_date' => '2025-07-14',
                'description'      => 'Pembayaran angsuran pinjaman bulan pertama Anggota A',
                'status'           => 'posted',
                'created_by'       => 1
            ]);

            // Entry 1: Kas (Debit) - Uang Masuk Total
            JournalEntries::create([
                'journal_id' => $jurnal2->id,
                'account_id' => $akunKas->id,
                'debit'      => 6750000, // 6jt Pokok + 750rb Bunga
                'credit'     => 0
            ]);

            // Entry 2: Piutang Anggota (Kredit) - Mengurangi Pokok Hutang
            JournalEntries::create([
                'journal_id' => $jurnal2->id,
                'account_id' => $akunPiutang->id,
                'debit'      => 0,
                'credit'     => 6000000 // 30jt / 5 bulan = 6jt
            ]);

            // Entry 3: Pendapatan Bunga (Kredit) - Mencatat Keuntungan
            JournalEntries::create([
                'journal_id' => $jurnal2->id,
                'account_id' => $akunBunga->id,
                'debit'      => 0,
                'credit'     => 750000 // 2.5% x 30jt = 750rb
            ]);
        });
    }
}
