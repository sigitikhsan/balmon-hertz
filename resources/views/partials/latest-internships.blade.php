<section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-2" data-reveal>
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Lowongan Terbaru</h2>
            <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Peluang magang dan PKL segar minggu ini</p>
        </div>
        @if($latestInternships->isNotEmpty())
            <a href="{{ route('jobs.index') }}" class="group inline-flex items-center gap-1 text-xs font-medium px-3 py-1 bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100/70 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">Lihat Semua <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12" /></svg></a>
        @endif
    </div>

    @if($latestInternships->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
            <p class="font-semibold text-slate-900">Belum ada lowongan minggu ini</p>
            <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Silakan kembali lagi nanti.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" data-stagger>
            @foreach($latestInternships as $job)
                <div class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition duration-200" data-reveal>
                    <div>
                        <p class="text-xs font-normal text-slate-600 leading-relaxed">{{ $job->field->name ?? 'Balmon' }}</p>
                        <h4 class="font-semibold text-slate-900 mb-6 line-clamp-2 text-base mt-1">{{ $job->title }}</h4>
                    </div>

                    <button type="button" data-modal-open="job-modal-{{ $job->id }}" class="w-full inline-flex items-center justify-center py-2.5 px-4 min-h-[44px] bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                        Lihat Selengkapnya
                    </button>
                </div>

                <div id="job-modal-{{ $job->id }}" class="job-modal hidden fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="job-modal-title-{{ $job->id }}">
                    <div class="absolute inset-0 bg-slate-900/60" data-modal-close></div>
                    <div class="relative bg-white rounded-xl border border-slate-200 max-w-lg w-full p-6 sm:p-8 max-h-[90vh] overflow-y-auto">
                        <p class="text-xs font-normal text-slate-600 leading-relaxed">{{ $job->field->name ?? 'Balmon' }}</p>
                        <h2 id="job-modal-title-{{ $job->id }}" class="font-bold text-slate-900 text-xl mt-1">{{ $job->title }}</h2>
                        <div class="flex flex-wrap gap-2 mt-3">
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-md">{{ $job->type }}</span>
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded-md">{{ $job->duration }}</span>
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-md">Kuota {{ $job->quota }}</span>
                        </div>
                        @if($job->description)
                            <h3 class="mt-4 font-bold text-slate-900 text-sm">Deskripsi</h3>
                            <p class="mt-1 text-sm font-normal text-slate-600 leading-relaxed">{{ $job->description }}</p>
                        @endif
                        @if($job->responsibilities)
                            <h3 class="mt-4 font-bold text-slate-900 text-sm">Job Responsibility</h3>
                            <p class="mt-1 text-sm font-normal text-slate-600 leading-relaxed">{{ $job->responsibilities }}</p>
                        @endif
                        @if($job->requirements)
                            <h3 class="mt-4 font-bold text-slate-900 text-sm">What We're Looking For</h3>
                            <p class="mt-1 text-sm font-normal text-slate-600 leading-relaxed">{{ $job->requirements }}</p>
                        @endif
                        @if($job->jurusan)
                            <h3 class="mt-4 font-bold text-slate-900 text-sm">Jurusan</h3>
                            <p class="mt-1 text-sm font-normal text-slate-600 leading-relaxed">{{ $job->jurusan }}</p>
                        @endif
                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('apply.create', $job->slug) }}" class="flex-1 inline-flex items-center justify-center py-2.5 px-4 min-h-[44px] bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">Daftar</a>
                            <button type="button" data-modal-close class="flex-1 inline-flex items-center justify-center py-2.5 px-4 min-h-[44px] bg-white border border-slate-200 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-50 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">Tutup</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>

<script>
(function () {
    var lastFocus = null;
    function openModal(modal) {
        lastFocus = document.activeElement;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        var focusable = modal.querySelector('a, button:not([data-modal-close])');
        if (focusable) focusable.focus();
    }
    function closeModal(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (lastFocus) lastFocus.focus();
    }
    document.querySelectorAll('[data-modal-open]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var modal = document.getElementById(btn.getAttribute('data-modal-open'));
            if (modal) openModal(modal);
        });
    });
    document.querySelectorAll('.job-modal').forEach(function (modal) {
        modal.querySelectorAll('[data-modal-close]').forEach(function (el) {
            el.addEventListener('click', function () { closeModal(modal); });
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.job-modal:not(.hidden)').forEach(closeModal);
        }
    });
})();
</script>
