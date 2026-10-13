<section id="katalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-2">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Lowongan Terbaru</h2>
            <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Peluang magang dan PKL segar minggu ini</p>
        </div>
        @if($latestInternships->isNotEmpty())
            <span class="text-xs font-medium px-3 py-1 bg-blue-50 text-blue-600 rounded-full">Menampilkan {{ $latestInternships->count() }} Lowongan Terbaru</span>
        @endif
    </div>

    @if($latestInternships->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
            <p class="font-semibold text-slate-900">Belum ada lowongan minggu ini</p>
            <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Silakan kembali lagi nanti atau daftar untuk mendapat kabar terbaru.</p>
            <a href="{{ route('register') }}" class="mt-4 inline-flex items-center justify-center px-5 py-2.5 min-h-[44px] bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">Daftar untuk update</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestInternships as $job)
                <div class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition duration-200">
                    <div>
                        <h4 class="font-semibold text-slate-900 mb-2 line-clamp-2 text-base">{{ $job->title }}</h4>
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-md">{{ $job->type }}</span>
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-xs font-medium rounded-md">{{ $job->duration }}</span>
                        </div>
                    </div>

                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center py-2.5 px-4 min-h-[44px] bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                        Lihat Selengkapnya
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</section>
