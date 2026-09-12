<x-layouts.panel title="Pengaturan Situs">
    <div class="mx-auto space-y-6">
        <x-page-header
            eyebrow="Situs"
            title="Pengaturan Situs"
            description="Identitas website, logo, SEO, dan kontak yang tampil di halaman publik."
        />

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-neutral-900">
                <h2 class="mb-4 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Identitas</h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-ui.field>
                        <x-ui.label text="Nama Situs" for="site_name" :required="true" />
                        <x-ui.input id="site_name" name="site_name" value="{{ old('site_name', $settings->site_name) }}" required />
                        <x-ui.error name="site_name" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.label text="Judul Halaman (Title)" for="site_title" />
                        <x-ui.input id="site_title" name="site_title" value="{{ old('site_title', $settings->site_title) }}" placeholder="KateringKu — Marketplace Katering Kantor" />
                        <x-ui.error name="site_title" />
                    </x-ui.field>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-ui.field>
                        <x-ui.label for="logo">Logo Situs (PNG/JPG/WebP, maks 2MB)</x-ui.label>
                        @if ($settings->logo)
                            <img src="{{ Storage::url($settings->logo) }}" alt="Logo situs" class="mb-2 mt-1 h-12 w-auto rounded-md border border-neutral-200/70 bg-white object-contain p-1 dark:border-white/10" />
                        @endif
                        <input type="file" id="logo" name="logo" accept="image/*"
                            class="mt-1 block w-full text-sm text-neutral-600 file:mr-4 file:rounded-md file:border-0 file:bg-neutral-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white dark:text-neutral-400 dark:file:bg-white dark:file:text-neutral-900" />
                        <x-ui.error name="logo" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.label for="favicon">Favicon (PNG/ICO, maks 512KB)</x-ui.label>
                        @if ($settings->favicon)
                            <img src="{{ Storage::url($settings->favicon) }}" alt="Favicon situs" class="mb-2 mt-1 size-8 rounded border border-neutral-200/70 bg-white dark:border-white/10" />
                        @endif
                        <input type="file" id="favicon" name="favicon" accept=".png,.ico"
                            class="mt-1 block w-full text-sm text-neutral-600 file:mr-4 file:rounded-md file:border-0 file:bg-neutral-900 file:px-4 file:py-2 file:text-sm file:font-medium file:text-white dark:text-neutral-400 dark:file:bg-white dark:file:text-neutral-900" />
                        <x-ui.error name="favicon" />
                    </x-ui.field>
                </div>
            </div>

            <div class="rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-neutral-900">
                <h2 class="mb-4 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">SEO</h2>

                <x-ui.field>
                    <x-ui.label text="Meta Description" for="meta_description" />
                    <x-ui.textarea id="meta_description" name="meta_description" rows="3" placeholder="Marketplace katering kantor terpercaya…">{{ old('meta_description', $settings->meta_description) }}</x-ui.textarea>
                    <x-ui.error name="meta_description" />
                </x-ui.field>

                <div class="mt-4">
                    <x-ui.field>
                        <x-ui.label text="Meta Keywords" for="meta_keywords" />
                        <x-ui.input id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $settings->meta_keywords) }}" placeholder="katering kantor, nasi box, catering harian" />
                        <x-ui.error name="meta_keywords" />
                    </x-ui.field>
                </div>
            </div>

            <div class="rounded-box border border-neutral-200/70 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-neutral-900">
                <h2 class="mb-4 text-sm font-semibold tracking-tight text-neutral-900 dark:text-white">Kontak &amp; Sosial</h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-ui.field>
                        <x-ui.label text="Email Kontak" for="contact_email" />
                        <x-ui.input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $settings->contact_email) }}" placeholder="halo@kateringku.id" />
                        <x-ui.error name="contact_email" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.label text="Telepon" for="contact_phone" />
                        <x-ui.input id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings->contact_phone) }}" placeholder="0812-3456-7890" />
                        <x-ui.error name="contact_phone" />
                    </x-ui.field>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-ui.field>
                        <x-ui.label text="Instagram URL" for="instagram_url" />
                        <x-ui.input id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" placeholder="https://instagram.com/kateringku" />
                        <x-ui.error name="instagram_url" />
                    </x-ui.field>
                    <x-ui.field>
                        <x-ui.label text="Facebook URL" for="facebook_url" />
                        <x-ui.input id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" placeholder="https://facebook.com/kateringku" />
                        <x-ui.error name="facebook_url" />
                    </x-ui.field>
                </div>

                <div class="mt-4">
                    <x-ui.field>
                        <x-ui.label text="Alamat" for="address" />
                        <x-ui.input id="address" name="address" value="{{ old('address', $settings->address) }}" placeholder="Jl. Contoh No. 1, Jakarta" />
                        <x-ui.error name="address" />
                    </x-ui.field>
                </div>

                <div class="mt-4">
                    <x-ui.field>
                        <x-ui.label text="Teks Footer" for="footer_text" />
                        <x-ui.input id="footer_text" name="footer_text" value="{{ old('footer_text', $settings->footer_text) }}" placeholder="Marketplace katering untuk kebutuhan makan kantor." />
                        <x-ui.error name="footer_text" />
                    </x-ui.field>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <x-ui.button type="submit" icon="ps:check">Simpan Pengaturan</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.panel>
