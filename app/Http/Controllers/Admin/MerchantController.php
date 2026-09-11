<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MerchantController extends Controller
{
    /**
     * Tampilkan daftar merchant beserta status verifikasinya.
     */
    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');

        abort_unless(in_array($status, ['pending', 'verified', 'rejected', 'all'], true), 404);

        $merchants = Merchant::with('user', 'menus')
            ->when($status !== 'all', fn ($q) => $q->where('verification_status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'pending' => Merchant::where('verification_status', 'pending')->count(),
            'verified' => Merchant::where('verification_status', 'verified')->count(),
            'rejected' => Merchant::where('verification_status', 'rejected')->count(),
            'all' => Merchant::count(),
        ];

        return view('admin.merchant.index', compact('merchants', 'status', 'counts'));
    }

    /**
     * Setujui merchant yang mengajukan verifikasi.
     */
    public function approve(Merchant $merchant): RedirectResponse
    {
        abort_if($merchant->verification_status === 'verified', 409, 'Merchant sudah terverifikasi.');

        $merchant->update(['verification_status' => 'verified']);

        return back()->with('saved', 'Merchant "'.$merchant->company_name.'" berhasil diverifikasi.');
    }

    /**
     * Tolak merchant yang mengajukan verifikasi.
     */
    public function reject(Request $request, Merchant $merchant): RedirectResponse
    {
        abort_if($merchant->verification_status === 'verified', 409, 'Merchant sudah terverifikasi.');

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $merchant->update([
            'verification_status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return back()->with('saved', 'Verifikasi merchant "'.$merchant->company_name.'" ditolak.');
    }

    /**
     * Kembalikan merchant yang ditolak menjadi pending (ajukan ulang).
     */
    public function reapply(Merchant $merchant): RedirectResponse
    {
        abort_if($merchant->verification_status === 'verified', 409, 'Merchant sudah terverifikasi.');

        $merchant->update([
            'verification_status' => 'pending',
            'rejection_reason' => null,
        ]);

        return back()->with('saved', 'Merchant "'.$merchant->company_name.'" dikembalikan ke status pending.');
    }
}
