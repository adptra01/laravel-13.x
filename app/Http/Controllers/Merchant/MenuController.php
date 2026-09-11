<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $merchant = Auth::user()->merchantProfile()->firstOrFail();

        $menus = $merchant->menus()
            ->when($request->search, fn ($query, $search) => $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            }))
            ->when($request->category, fn ($query, $category) => $query->where('category', $category))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = $merchant->menus()->distinct()->pluck('category')->filter()->values();

        return view('merchant.menu.index', compact('merchant', 'menus', 'categories'));
    }

    public function create(): View
    {
        return view('merchant.menu.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $merchant = Auth::user()->merchantProfile()->firstOrFail();

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $merchant->menus()->create($validated);

        return redirect()->route('merchant.menus.index')->with('saved', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        abort_unless($menu->merchant_id === Auth::user()->merchantProfile->id, 403);

        return view('merchant.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        abort_unless($menu->merchant_id === Auth::user()->merchantProfile->id, 403);

        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $menu->update($validated);

        return redirect()->route('merchant.menus.index')->with('saved', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        abort_unless($menu->merchant_id === Auth::user()->merchantProfile->id, 403);

        $menu->delete();

        return redirect()->route('merchant.menus.index')->with('saved', 'Menu berhasil dihapus.');
    }

    public function toggle(Menu $menu): RedirectResponse
    {
        abort_unless($menu->merchant_id === Auth::user()->merchantProfile->id, 403);

        $menu->update(['is_active' => ! $menu->is_active]);

        return back()->with('saved', 'Status menu berhasil diubah.');
    }
}
