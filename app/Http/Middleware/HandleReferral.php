<?php
// Simpan di session jika ada parameter ref di URL
if ($request->has('ref')) {
    session(['referrer_id' => $request->query('ref')]);
}