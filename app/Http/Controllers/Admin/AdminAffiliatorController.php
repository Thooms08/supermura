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
        $biaya = Setting::where('key', 'biaya_pendaftaran')->first()->value ?? 0;
        $komisi_rekrut = Setting::where('key', 'komisi_rekrut')->first()->value ?? 0;
        
        return view('admin.data-affiliator', compact('affiliators', 'biaya', 'komisi_rekrut'));
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
        $rules['metode_pembayaran'] = 'required|in:tunai,transfer';
        if ($request->metode_pembayaran == 'transfer') {
            $rules['bukti_transfer'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
        } else {
            $rules['nominal_tunai'] = 'required|numeric|min:' . $biaya;
        }
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
        $request->validate(['biaya' => 'required|numeric|min:0']);
        Setting::updateOrCreate(
            ['key' => 'biaya_pendaftaran'],
            ['value' => $request->biaya]
        );
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
