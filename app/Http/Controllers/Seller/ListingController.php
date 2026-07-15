<?php

namespace App\Http\Controllers\Seller;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Enums\ListingStatus; // Pastikan Enum dipanggil 

class ListingController
{
    // Fungsi Pembantu: Membuat Slug Unik [cite: 10]
    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (Listing::where('slug', $slug)->exists()) { // [cite: 11]
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    // ALUR BARU FEATURED: Seller mengajukan status Premium ke Admin
    public function requestFeatured($id)
    {
        // 1. Cari iklan milik user yang sedang login 
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id);

        // 2. Validasi: Iklan harus berstatus ACTIVE (menggunakan Enum bawaan kodemu) 
        if ($listing->status !== ListingStatus::ACTIVE) {
            return redirect()->back()->with('error', 'Fitur Premium hanya bisa diajukan jika status iklan sudah Aktif!');
        }

        // 3. Validasi: Pastikan belum pernah mengajukan atau sedang aktif [cite: 14]
        if ($listing->featured_status === 'pending') {
            return redirect()->back()->with('error', 'Iklan ini sedang dalam antrean konfirmasi Premium oleh Admin.');
        }

        if ($listing->featured_status === 'approved') { // [cite: 15]
            return redirect()->back()->with('error', 'Iklan ini sudah berstatus Premium Aktif!');
        }

        // 4. Ubah status menjadi pending [cite: 15]
        $listing->update([
            'featured_status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Pengajuan Premium berhasil dikirim! Menunggu konfirmasi manual dari Admin.'); // [cite: 16]
    }

    public function create()
    {
        return view('seller.create'); // [cite: 16]
    }

    public function store(Request $request)
    {
        $request->validate([ // [cite: 17]
            'title' => 'required|string|max:255',
            'ign' => 'required|string|max:255',
            'uid' => 'required|string|unique:listings,uid',
            'whatsapp' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'level' => 'required|integer|between:1,400',
            'rank_mp' => 'required|string',
            'rank_br' => 'required|string',
            'login_type' => 'required|in:Garena,Facebook',

            'mythic_weapon_count' => 'nullable|integer|min:0',
            'prestige_weapon_count' => 'nullable|integer|min:0',
            'legendary_weapon_count' => 'nullable|integer|min:0',
            'mythic_character_count' => 'nullable|integer|min:0',
            'legendary_character_count' => 'nullable|integer|min:0',
            'legendary_vehicle_count' => 'nullable|integer|min:0',

            'thumbnail' => 'required|image|mimes:jpeg,png,webp|max:5120',
            'lobby_image' => 'required|image|mimes:jpeg,png,webp|max:5120',
            'weapon_image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
            'character_image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $data = $request->all(); // [cite: 18]
        $data['user_id'] = Auth::id();
        $data['slug'] = $this->generateUniqueSlug($request->title);

        $data['border_s1'] = $request->has('border_s1');
        $data['damascus'] = $request->has('damascus');

        $images = ['thumbnail', 'lobby_image', 'weapon_image', 'character_image', 'vehicle_image']; // [cite: 19]
        foreach ($images as $img) {
            if ($request->hasFile($img)) {
                $data[$img] = $request->file($img)->store('listings', 'public');
            }
        }

        $data['status'] = ListingStatus::PENDING; // Menggunakan Enum [cite: 19, 20]
        $data['ad_type'] = 'Gratis'; // [cite: 20]
        $data['expires_at'] = null;
        Listing::create($data);

        return redirect()->route('seller.dashboard')->with('success', 'Iklan berhasil dikirim dan menanti peninjauan Admin.'); // [cite: 20]
    }

    public function edit($id)
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id); // [cite: 21]
        return view('seller.edit', compact('listing'));
    }

    public function update(Request $request, $id)
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id); // [cite: 21]

        $request->validate([ // [cite: 22]
            'title' => 'required|string|max:255',
            'ign' => 'required|string|max:255',
            'uid' => 'required|string|unique:listings,uid,' . $listing->id,
            'whatsapp' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'level' => 'required|integer|between:1,400',
            'rank_mp' => 'required|string',
            'rank_br' => 'required|string',
            'login_type' => 'required|in:Garena,Facebook',

            'mythic_weapon_count' => 'nullable|integer|min:0',
            'prestige_weapon_count' => 'nullable|integer|min:0',
            'legendary_weapon_count' => 'nullable|integer|min:0',
            'mythic_character_count' => 'nullable|integer|min:0',
            'legendary_character_count' => 'nullable|integer|min:0',
            'legendary_vehicle_count' => 'nullable|integer|min:0',

            'thumbnail' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
            'lobby_image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
            'weapon_image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
            'character_image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
            'vehicle_image' => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        $data = $request->except(['thumbnail', 'lobby_image', 'weapon_image', 'character_image', 'vehicle_image', 'slug', 'user_id']); // [cite: 23]

        if ($request->title !== $listing->title) {
            $data['slug'] = $this->generateUniqueSlug($request->title);
        }

        $data['border_s1'] = $request->has('border_s1');
        $data['damascus'] = $request->has('damascus'); // [cite: 24]

        $images = ['thumbnail', 'lobby_image', 'weapon_image', 'character_image', 'vehicle_image']; // [cite: 24]
        foreach ($images as $img) {
            if ($request->hasFile($img)) {
                $data[$img] = $request->file($img)->store('listings', 'public'); // [cite: 25]
            }
        }

        // Otomatis kembalikan status ke Pending & Hapus catatan penolakan jika iklan diedit [cite: 26]
        $data['status'] = ListingStatus::PENDING;
        $data['review_notes'] = null;

        $listing->update($data); // [cite: 26]
        return redirect()->route('seller.dashboard')->with('success', 'Revisi iklan berhasil dikirim! Sedang menunggu peninjauan ulang Admin.'); // [cite: 27]
    }

    public function destroy($id)
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id); // [cite: 27]
        $listing->delete();
        return redirect()->route('seller.dashboard')->with('success', 'Iklan berhasil dihapus.'); // [cite: 28]
    }

    public function renew($id)
    {
        $listing = Listing::where('user_id', Auth::id())->findOrFail($id); // [cite: 28]

        // Perpanjang masa aktif iklan biasa ke ACTIVE 
        $listing->update([
            'status' => ListingStatus::ACTIVE,
            'expires_at' => now()->addDays(30)
        ]);

        return redirect()->back()->with('success', 'Masa aktif iklan "' . $listing->title . '" berhasil diperpanjang 30 hari ke depan!'); // [cite: 30]
    }
}