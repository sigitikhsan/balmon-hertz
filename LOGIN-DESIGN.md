# Dokumentasi Desain UI: Sistem Magang & PKL Hertz

Dokumen ini berisi panduan acuan untuk mengimplementasikan desain UI portal pendaftaran magang/PKL Hertz ke dalam lingkungan **Laravel Blade**.

## 1. Struktur Layout Utama
Layout menggunakan konsep **Split-Screen** yang responsif.

*   **Wrapper Utama:** `flex flex-col md:flex-row h-screen w-full`
*   **Sisi Kiri (Branding Panel):** 
    *   Kelas: `w-full md:w-1/2 flex flex-col justify-center px-8 py-12 md:px-16`
    *   Background: Gunakan *gradient* atau *solid color* sesuai identitas brand.
*   **Sisi Kanan (Form Panel):** 
    *   Kelas: `w-full md:w-1/2 flex flex-col justify-center px-6 py-10 sm:px-12`
    *   Background: White (`bg-white`)

## 2. Komponen Branding (Sisi Kiri)
### Badge Header
```html
<div class="inline-flex items-center gap-2 rounded-full bg-white/70 px-4 py-1.5 text-xs font-bold tracking-wider text-sky-800 shadow-sm backdrop-blur-md">
    <span class="relative flex h-2 w-2">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-600"></span>
    </span>
    PORTAL MAGANG & PKL HERTZ
</div>
```

### Tipografi
*   **Headline:** `text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#063966]`
*   **Sub-headline (Gradient):** Gunakan `bg-gradient-to-r from-sky-600 to-blue-800 bg-clip-text text-transparent`
*   **Body Text:** `text-sm sm:text-base text-[#456d97] leading-relaxed`

## 3. Komponen Form (Sisi Kanan)
### Input Field
Gunakan *wrapper* dengan gaya berikut agar konsisten:
```html
<div class="field-wrap flex items-center border border-sky-100 rounded-lg bg-sky-50/50 focus-within:border-sky-500 focus-within:ring-2 focus-within:ring-sky-100">
    <span class="px-3 text-sky-600"><!-- Ikon --></span>
    <input type="text" class="w-full bg-transparent border-0 py-3 text-sm text-blue-900 focus:ring-0">
</div>
```

## 4. Panduan Implementasi Laravel Blade
1.  **Layout Utama:** Gunakan `layouts/app.blade.php` dengan `<div class="md:grid md:grid-cols-2 h-screen">`.
2.  **Asset:** Pastikan file gambar logo disimpan di `public/images/` dan panggil dengan `{{ asset('images/logo.png') }}`.
3.  **Tailwind:** Pastikan `tailwind.config.js` sudah mencakup warna brand Hertz yang digunakan.
4.  **Responsivitas:** 
    *   Gunakan `md:grid-cols-2` untuk membagi layar pada desktop.
    *   Pada layar kecil (`mobile`), gunakan `flex-col` agar konten kiri (branding) berada di atas form.

## 5. Ringkasan Warna (Brand Palette)
*   **Primary Blue:** `#063966`
*   **Light Blue (Bg):** `#eaf5ff` / `#bde0fe`
*   **Text Muted:** `#456d97`
*   **Accent Sky:** `#0284c7` (sky-600)

---
*Catatan: Desain ini menekankan pada tipografi yang kuat dan *whitespace* yang luas untuk kesan modern dan profesional.*
