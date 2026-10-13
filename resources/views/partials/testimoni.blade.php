{{-- Menunggu data real alumni dari pemilik. Tanpa data real, tampilkan empty state, bukan testimoni karangan. --}}
<section id="testimoni" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
    <div class="max-w-2xl mb-8">
        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pesan dan Kesan Alumni</h2>
        <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Cerita peserta yang telah menyelesaikan magang di Balmon.</p>
    </div>

    @if(empty($testimonials ?? null))
        <div class="bg-white rounded-xl border border-slate-200 p-10 text-center">
            <p class="font-semibold text-slate-900">Testimoni alumni akan tampil di sini</p>
            <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Jadilah angkatan pertama yang membagikan pengalaman magang di Balmon.</p>
            <a href="{{ route('register') }}" class="mt-4 inline-flex items-center justify-center px-5 py-2.5 min-h-[44px] bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">Daftar magang</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($testimonials->take(3) as $t)
                <figure class="bg-white rounded-xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-md transition duration-200">
                    <blockquote class="text-sm font-normal text-slate-600 leading-relaxed">{{ $t->quote }}</blockquote>
                    <figcaption class="mt-4">
                        <p class="font-bold text-slate-900 text-sm">{{ $t->name }}</p>
                        <p class="text-xs font-normal text-slate-600 leading-relaxed">{{ $t->meta }}</p>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    @endif
</section>
