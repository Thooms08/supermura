<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Affiliator;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AdminAffiliatorController extends Controller
{
    public function index()
    {
        $affiliators = Affiliator::with('user')->latest()->get();
        $biayaSetting = Setting::where('key', 'biaya_pendaftaran')->first();
        $biaya        = $biayaSetting->value    ?? 0;
        $no_rek       = $biayaSetting->no_rek   ?? null;
        $qris         = $biayaSetting->qris     ?? null;
        $komisi_rekrut = Setting::where('key', 'komisi_rekrut')->first()->value ?? 0;

        return view('admin.data-affiliator', compact('affiliators', 'biaya', 'no_rek', 'qris', 'komisi_rekrut'));
    }

    public function store(Request $request)
{
    $biaya = Setting::where('key', 'biaya_pendaftaran')->first()->value ?? 0;

    $rules = [
        'id_unik' => 'required|string|max:8|unique:affiliators,id_unik',
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'no_whatsapp' => 'required|string|max:20',
        'domisili' => 'required|string|max:255',
    ];

    if ($biaya > 0) {
        $rules['metode_pembayaran'] = 'required|in:tunai,transfer,qris';
        if ($request->metode_pembayaran == 'transfer') {
            $rules['bukti_transfer'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        } elseif ($request->metode_pembayaran == 'tunai') {
            $rules['nominal_tunai'] = 'required|numeric|min:' . $biaya;
        }
        // qris: tidak perlu upload bukti, cukup pilih metode
    }

    $request->validate($rules);

    try {
        DB::beginTransaction();

        // 1. Simpan ke tabel Users
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'affiliator',
        ]);

        // 2. Handle Upload Bukti Transfer
        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $fileName = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('bukti-daftar-affiliator', $file, $fileName);
            $buktiPath = $fileName;
        }

        // 3. Simpan ke tabel Affiliators
        Affiliator::create([
            'user_id' => $user->id,
            'id_unik' => strtoupper($request->id_unik),
            'nama' => $request->nama,
            'no_whatsapp' => $request->no_whatsapp,
            'domisili' => $request->domisili,
            'metode_pembayaran' => $request->metode_pembayaran ?? null,
            'bukti_transfer' => $buktiPath,
            'nominal_tunai' => $request->nominal_tunai ?? 0,
            'status' => 'aktif',
        ]);

        DB::commit();
        return back()->with('success', 'Affiliator dan Akun User berhasil dibuat!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    // Method lainnya tetap sama...
    public function updateStatus($id)
    {
        $aff = Affiliator::findOrFail($id);
        $aff->status = $aff->status == 'aktif' ? 'nonaktif' : 'aktif';
        $aff->save();
        return back()->with('success', 'Status berhasil diubah!');
    }

    public function updateFee(Request $request)
    {
        $request->validate([
            'biaya'  => 'required|numeric|min:0',
            'no_rek' => 'required|string|max:100',
            'qris'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $setting = Setting::firstOrNew(['key' => 'biaya_pendaftaran']);
        $setting->value  = $request->biaya;
        $setting->no_rek = $request->no_rek;

        if ($request->hasFile('qris')) {
            // Hapus file QRIS lama jika ada
            if ($setting->qris) {
                Storage::disk('public')->delete('qris/' . $setting->qris);
            }
            $file     = $request->file('qris');
            $fileName = 'qris_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->putFileAs('qris', $file, $fileName);
            $setting->qris = $fileName;
        }

        $setting->save();

        return back()->with('success', 'Biaya pendaftaran berhasil diperbarui!');
    }

    public function search(Request $request)
    {
        $search = $request->get('query');
        $affiliators = Affiliator::with('user')
            ->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%$search%")
                  ->orWhere('no_whatsapp', 'LIKE', "%$search%")
                  ->orWhere('domisili', 'LIKE', "%$search%")
                  ->orWhere('status', 'LIKE', "%$search%");
            })->latest()->get();

        return view('admin.partials.affiliator-list', compact('affiliators'))->render();
    }
    public function updateKomisiRekrut(Request $request)
    {
        $request->validate([
            'komisi_rekrut' => 'required|numeric|min:0'
        ]);

        Setting::updateOrCreate(
            ['key' => 'komisi_rekrut'],
            ['value' => $request->komisi_rekrut]
        );

        return back()->with('success', 'Nominal komisi rekrut berhasil diperbarui!');
    }
}
