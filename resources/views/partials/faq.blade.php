<section id="faq" class="relative py-20 sm:py-24 bg-[#F7FBFF] overflow-hidden">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute -top-16 right-0 w-72 h-72 bg-[#DCEBFF]/50 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-[#DCEBFF]/40 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-12 items-center">
            <!-- Left: heading + accordion -->
            <div class="lg:col-span-6">
                <div data-reveal>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#08245C] tracking-tight">Pertanyaan Umum</h2>
                    <p class="mt-3 text-base text-[#52637F] leading-relaxed max-w-xl">Jawaban atas hal yang paling sering ditanyakan pendaftar.</p>
                </div>

                <div class="mt-8 space-y-4" data-reveal>
                    <details class="group bg-white rounded-2xl border border-[#DCEBFF] shadow-[0_4px_20px_rgba(8,36,92,0.03)] overflow-hidden transition-all duration-200 hover:border-[#bfd6ff]">
                        <summary class="flex items-center justify-between gap-4 px-6 py-5 min-h-[60px] cursor-pointer list-none font-bold text-[#08245C] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-inset">
                            <span>Siapa saja yang boleh mengikuti program ini?</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1265ED] transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </summary>
                        <div class="px-6 pb-6 pt-0">
                            <p class="text-sm text-[#52637F] leading-relaxed">Mahasiswa, lulusan baru maupun yang sudah berpengalaman, serta siswa yang melaksanakan PKL dapat mengikuti program ini.</p>
                        </div>
                    </details>

                    <details class="group bg-white rounded-2xl border border-[#DCEBFF] shadow-[0_4px_20px_rgba(8,36,92,0.03)] overflow-hidden transition-all duration-200 hover:border-[#bfd6ff]">
                        <summary class="flex items-center justify-between gap-4 px-6 py-5 min-h-[60px] cursor-pointer list-none font-bold text-[#08245C] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-inset">
                            <span>Apakah pendaftaran dipungut biaya?</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#1265ED] transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                        </summary>
                        <div class="px-6 pb-6 pt-0">
                            <p class="text-sm text-[#52637F] leading-relaxed">Tidak. Pendaftaran tidak dipungut biaya.</p>
                        </div>
                    </details>
                </div>
            </div>

            <!-- Right: illustration -->
            <div class="lg:col-span-6 relative" data-reveal>
                <div class="relative mx-auto max-w-[32rem] lg:max-w-none">
                    <div class="absolute inset-x-8 top-10 bottom-4 bg-[#1265ED]/[0.06] rounded-[3rem] blur-2xl" aria-hidden="true"></div>
                    <div class="relative overflow-hidden rounded-[2rem]">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#EAF3FF]/25 via-transparent to-transparent z-[1] pointer-events-none" aria-hidden="true"></div>
                        <img src="{{ asset('images/landing/faq-illustration.png') }}" alt="Ilustrasi bagian pertanyaan umum" class="relative w-full h-auto max-h-[520px] object-contain object-center mx-auto drop-shadow-[0_16px_40px_rgba(8,36,92,0.08)]" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
