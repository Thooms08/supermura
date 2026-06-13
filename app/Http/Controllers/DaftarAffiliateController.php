<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Affiliator;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DaftarAffiliateController extends Controller
{
    public function index(Request $request)
    {
        $referral_code = $request->query('ref');
        $biayaSetting  = Setting::where('key', 'biaya_pendaftaran')->first();
        $biaya         = $biayaSetting->value  ?? 0;
        $no_rek        = $biayaSetting->no_rek ?? null;
        $qris          = $biayaSetting->qris   ?? null;

        return view('daftar-affiliate', compact('biaya', 'no_rek', 'qris', 'referral_code'));
    }

    public function store(Request $request)
    {
        $biaya = Setting::where('key', 'biaya_pendaftaran')->first()->value ?? 0;
        
        // Ambil nominal komisi dinamis dari setting admin (Value dari database)
        $nominalKomisi = Setting::where('key', 'komisi_rekrut')->first()->value ?? 0;

        $metodeValid = $biaya > 0 ? 'required|in:transfer,qris' : 'nullable';

        $rules = [
            'id_unik'           => 'required|string|max:8|unique:affiliators,id_unik',
            'kode_referral'     => 'nullable|string|max:8',
            'nama'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'no_whatsapp'       => 'required|string|max:20',
            'domisili'          => 'required|string|max:255',
            'password'          => 'required|string|min:6|confirmed',
            'metode_pembayaran' => $metodeValid,
        ];

        if ($biaya > 0 && $request->metode_pembayaran === 'transfer') {
            $rules['bukti_transfer'] = 'required|image|mimes:jpg,jpeg,png|max:2048';
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // 1. Buat Akun User
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'affiliator',
            ]);

            if ($request->filled('kode_referral')) {
                $referrer = Affiliator::where('id_unik', strtoupper($request->kode_referral))->first();
                
                if ($referrer) {
                    // Pastikan jika null diubah dulu jadi 0 agar matematika SQL-nya jalan
                    if (is_null($referrer->komisi_rekrut)) {
                        $referrer->komisi_rekrut = 0;
                    }
                    
                    $referrer->komisi_rekrut += $nominalKomisi;
                    $referrer->save(); 
                }
            }

            // 3. Handle File Upload
            $fileName = null;
            if ($request->hasFile('bukti_transfer')) {
                $file = $request->file('bukti_transfer');
                $fileName = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                Storage::disk('public')->putFileAs('bukti-daftar-affiliator', $file, $fileName);
            }

            // 4. Simpan Data Affiliator Baru
            Affiliator::create([
                'user_id'           => $user->id,
                'id_unik'           => strtoupper($request->id_unik),
                'kode_referral'     => $request->kode_referral ? strtoupper($request->kode_referral) : null,
                'nama'              => $request->nama,
                'no_whatsapp'       => $request->no_whatsapp,
                'domisili'          => $request->domisili,
                'metode_pembayaran' => $request->metode_pembayaran ?? null,
                'bukti_transfer'    => $fileName,
                'nominal_tunai'     => 0,
                'status'            => 'aktif',
            ]);

            DB::commit();
            return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda sudah aktif.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mendaftar: ' . $e->getMessage());
        }
    }
}