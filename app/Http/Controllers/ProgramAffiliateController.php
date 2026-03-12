<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class ProgramAffiliateController extends Controller
{
    public function index()
    {
        // Langsung ambil nilai dari kolom 'value' berdasarkan key 'biaya_pendaftaran'
        $biaya = Setting::where('key', 'biaya_pendaftaran')->value('value') ?? 0;

        return view('program-affiliate', compact('biaya'));
    }
}