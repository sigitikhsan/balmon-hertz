<section id="katalog" class="relative py-20 sm:py-24 bg-white overflow-hidden">
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        <div class="absolute top-0 right-0 w-72 h-72 bg-[#EAF3FF] rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-[#EAF3FF]/70 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-10" data-reveal>
            <div class="max-w-2xl">
                <div class="flex items-center gap-4">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-[#08245C] tracking-tight">Lowongan Terbaru</h2>
                    <div class="hidden sm:block flex-1 h-px bg-gradient-to-r from-[#1265ED]/40 to-transparent max-w-[200px]" aria-hidden="true"></div>
                </div>
                <p class="mt-3 text-base text-[#52637F] leading-relaxed">Peluang magang dan PKL segar minggu ini</p>
            </div>
            @if($latestInternships->isNotEmpty())
                <a href="{{ route('jobs.index') }}" class="group inline-flex items-center gap-2 self-start text-sm font-semibold text-[#1265ED] hover:text-[#0f56cc] transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-offset-2 rounded-lg">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12" /></svg>
                </a>
            @endif
        </div>

        <div class="bg-white border border-[#DCEBFF] rounded-2xl shadow-[0_8px_30px_rgba(8,36,92,0.04)] p-6 sm:p-8 lg:p-10" data-reveal>
            @if($latestInternships->isEmpty())
                <div class="py-10 sm:py-14 text-center">
                    <div class="relative inline-flex items-center justify-center mb-6">
                        <div class="w-24 h-24 rounded-3xl bg-[#EAF3FF] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[#1265ED]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -right-2 w-10 h-10 rounded-full bg-white border border-[#DCEBFF] shadow-sm flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#1265ED]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>
                    <p class="text-lg sm:text-xl font-bold text-[#08245C]">Belum ada lowongan hingga kini</p>
                    <p class="text-sm sm:text-base text-[#52637F] mt-2">Silakan kembali lagi nanti</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6" data-stagger>
                    @foreach($latestInternships as $job)
                        <div class="bg-white rounded-2xl border border-[#DCEBFF] p-6 flex flex-col justify-between hover:shadow-[0_12px_30px_rgba(8,36,92,0.08)] hover:-translate-y-1 transition-all duration-300" data-reveal>
                            <div>
                                <p class="text-xs font-medium text-[#52637F]">{{ $job->field->name ?? 'Balmon' }}</p>
                                <h4 class="font-bold text-[#08245C] mt-1 mb-5 line-clamp-2 text-base leading-snug">{{ $job->title }}</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2.5 py-1 bg-[#EAF3FF] text-[#1265ED] text-xs font-medium rounded-md">{{ $job->type }}</span>
                                    <span class="px-2.5 py-1 bg-slate-100 text-[#52637F] text-xs font-medium rounded-md">Kuota {{ $job->quota }}</span>
                                </div>
                            </div>
                            <button type="button" data-modal-open="job-modal-{{ $job->id }}" class="mt-6 w-full inline-flex items-center justify-center py-2.5 px-4 min-h-[44px] bg-[#08245C] hover:bg-[#1265ED] text-white text-sm font-medium rounded-xl transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-offset-2">
                                Lihat Selengkapnya
                            </button>
                        </div>

                        <div id="job-modal-{{ $job->id }}" class="job-modal hidden fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="job-modal-title-{{ $job->id }}">
                            <div class="absolute inset-0 bg-[#08245C]/55 backdrop-blur-sm" data-modal-close></div>
                            <div class="relative bg-white rounded-2xl border border-[#DCEBFF] max-w-lg w-full p-6 sm:p-8 max-h-[90vh] overflow-y-auto shadow-2xl">
                                <p class="text-xs font-medium text-[#52637F]">{{ $job->field->name ?? 'Balmon' }}</p>
                                <h2 id="job-modal-title-{{ $job->id }}" class="font-bold text-[#08245C] text-xl mt-1">{{ $job->title }}</h2>
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="px-2.5 py-1 bg-slate-100 text-[#52637F] text-xs font-medium rounded-md">{{ $job->type }}</span>
                                    <span class="px-2.5 py-1 bg-[#EAF3FF] text-[#1265ED] text-xs font-medium rounded-md">{{ $job->duration }}</span>
                                    <span class="px-2.5 py-1 bg-slate-100 text-[#52637F] text-xs font-medium rounded-md">Kuota {{ $job->quota }}</span>
                                </div>
                                @if($job->description)
                                    <h3 class="mt-4 font-bold text-[#08245C] text-sm">Deskripsi</h3>
                                    <p class="mt-1 text-sm text-[#52637F] leading-relaxed">{{ $job->description }}</p>
                                @endif
                                @if($job->responsibilities)
                                    <h3 class="mt-4 font-bold text-[#08245C] text-sm">Job Responsibility</h3>
                                    <p class="mt-1 text-sm text-[#52637F] leading-relaxed">{{ $job->responsibilities }}</p>
                                @endif
                                @if($job->requirements)
                                    <h3 class="mt-4 font-bold text-[#08245C] text-sm">What We're Looking For</h3>
                                    <p class="mt-1 text-sm text-[#52637F] leading-relaxed">{{ $job->requirements }}</p>
                                @endif
                                @if($job->jurusan)
                                    <h3 class="mt-4 font-bold text-[#08245C] text-sm">Jurusan</h3>
                                    <p class="mt-1 text-sm text-[#52637F] leading-relaxed">{{ $job->jurusan }}</p>
                                @endif
                                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('apply.create', $job->slug) }}" class="flex-1 inline-flex items-center justify-center py-2.5 px-4 min-h-[44px] bg-[#1265ED] hover:bg-[#0f56cc] text-white text-sm font-medium rounded-xl transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-offset-2">Daftar</a>
                                    <button type="button" data-modal-close class="flex-1 inline-flex items-center justify-center py-2.5 px-4 min-h-[44px] bg-white border border-[#DCEBFF] text-[#08245C] text-sm font-medium rounded-xl hover:bg-[#F7FAFF] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#1265ED] focus-visible:ring-offset-2">Tutup</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

<script>
(function () {
    var lastFocus = null;
    function openModal(modal) { lastFocus = document.activeElement; modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; var focusable = modal.querySelector('a, button:not([data-modal-close])'); if (focusable) focusable.focus(); }
    function closeModal(modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; if (lastFocus) lastFocus.focus(); }
    document.querySelectorAll('[data-modal-open]').forEach(function (btn) { btn.addEventListener('click', function () { var modal = document.getElementById(btn.getAttribute('data-modal-open')); if (modal) openModal(modal); }); });
    document.querySelectorAll('.job-modal').forEach(function (modal) { modal.querySelectorAll('[data-modal-close]').forEach(function (el) { el.addEventListener('click', function () { closeModal(modal); }); }); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { document.querySelectorAll('.job-modal:not(.hidden)').forEach(closeModal); } });
})();
</script>
