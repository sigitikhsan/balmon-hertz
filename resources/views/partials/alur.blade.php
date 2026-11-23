{{-- TODO: konfirmasi alur dengan pihak Balmon, isi di bawah mengikuti kiriman pemilik dan bisa berubah --}}
<section id="alur" class="relative py-20 sm:py-24 bg-white overflow-hidden">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[48rem] h-48 bg-[#EAF3FF]/60 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14 sm:mb-16" data-reveal>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#08245C] tracking-tight">Alur Pendaftaran</h2>
            <p class="mt-3 text-base text-[#52637F] leading-relaxed">Tiga langkah dari daftar sampai mulai magang.</p>
        </div>

        <ol class="relative grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8" data-reveal>
            <!-- connecting line desktop -->
            <div class="hidden md:block absolute top-[52px] left-[16.67%] right-[16.67%] h-[3px] bg-[#DCEBFF] rounded-full" aria-hidden="true">
                <div class="absolute inset-0 bg-gradient-to-r from-[#1265ED]/30 via-[#1265ED]/50 to-[#1265ED]/30 rounded-full"></div>
            </div>

            <li class="relative flex flex-col items-center text-center">
                <span class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-[#1265ED] text-white font-bold text-sm shadow-[0_8px_20px_rgba(18,101,237,0.3)] z-10" aria-hidden="true">1</span>
                <div class="mt-5 w-20 h-20 rounded-full bg-white border-2 border-[#1265ED]/30 text-[#1265ED] flex items-center justify-center shadow-sm z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <p class="mt-5 text-lg font-bold text-[#08245C]">Lamar posisi</p>
                <p class="mt-2 text-sm text-[#52637F] leading-relaxed max-w-xs">Pilih posisi dan kirim lamaran.</p>
            </li>

            <li class="relative flex flex-col items-center text-center">
                <span class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-[#1265ED] text-white font-bold text-sm shadow-[0_8px_20px_rgba(18,101,237,0.3)] z-10" aria-hidden="true">2</span>
                <div class="mt-5 w-20 h-20 rounded-full bg-white border-2 border-[#1265ED]/30 text-[#1265ED] flex items-center justify-center shadow-sm z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="mt-5 text-lg font-bold text-[#08245C]">Seleksi</p>
                <p class="mt-2 text-sm text-[#52637F] leading-relaxed max-w-xs">Ikuti seleksi dan interview.</p>
            </li>

            <li class="relative flex flex-col items-center text-center">
                <span class="inline-flex items-center justify-center w-11 h-11 rounded-full bg-[#1265ED] text-white font-bold text-sm shadow-[0_8px_20px_rgba(18,101,237,0.3)] z-10" aria-hidden="true">3</span>
                <div class="mt-5 w-20 h-20 rounded-full bg-white border-2 border-[#1265ED]/30 text-[#1265ED] flex items-center justify-center shadow-sm z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                </div>
                <p class="mt-5 text-lg font-bold text-[#08245C]">Mulai magang</p>
                <p class="mt-2 text-sm text-[#52637F] leading-relaxed max-w-xs">Mulai sesuai penempatan.</p>
            </li>
        </ol>
    </div>
</section>
