{{-- resources/views/jobs/apply.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="bg-slate-50 min-h-screen font-sans antialiased text-slate-800">

    @include('components.navbar')

    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Form Pendaftaran</h1>
        <p class="text-sm font-normal text-slate-600 leading-relaxed mt-1">Lengkapi data di bawah untuk melamar posisi ini.</p>

        <div class="mt-6 bg-white rounded-xl border border-slate-200 p-6">
            <p class="text-xs font-normal text-slate-600 leading-relaxed">Posisi dilamar</p>
            <p class="font-bold text-slate-900">{{ $job->title }} ({{ $job->type }}, {{ $job->duration }})</p>
        </div>

        @if($errors->any())
            <div class="mt-6 bg-white rounded-xl border border-slate-200 p-6" role="alert">
                <p class="font-bold text-slate-900 text-sm">Periksa kembali isian berikut:</p>
                <ul class="mt-2 list-disc list-inside text-sm font-normal text-slate-600 leading-relaxed">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('apply.store', $job->slug) }}" enctype="multipart/form-data" class="mt-6 bg-white rounded-xl border border-slate-200 p-6 space-y-5">
            @csrf

            <div>
                <label for="nama" class="block text-sm font-bold text-slate-900 mb-2">Nama lengkap</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required class="w-full min-h-[44px] px-4 rounded-lg bg-white border border-slate-200 text-sm font-normal text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-900 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full min-h-[44px] px-4 rounded-lg bg-white border border-slate-200 text-sm font-normal text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                </div>
                <div>
                    <label for="no_hp" class="block text-sm font-bold text-slate-900 mb-2">No HP</label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required class="w-full min-h-[44px] px-4 rounded-lg bg-white border border-slate-200 text-sm font-normal text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="ttl_tempat" class="block text-sm font-bold text-slate-900 mb-2">Tempat lahir</label>
                    <input type="text" id="ttl_tempat" name="ttl_tempat" value="{{ old('ttl_tempat') }}" required class="w-full min-h-[44px] px-4 rounded-lg bg-white border border-slate-200 text-sm font-normal text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                </div>
                <div>
                    <label for="ttl_tanggal" class="block text-sm font-bold text-slate-900 mb-2">Tanggal lahir</label>
                    <input type="date" id="ttl_tanggal" name="ttl_tanggal" value="{{ old('ttl_tanggal') }}" required class="w-full min-h-[44px] px-4 rounded-lg bg-white border border-slate-200 text-sm font-normal text-slate-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
                </div>
            </div>

            <div>
                <label for="cv" class="block text-sm font-bold text-slate-900 mb-2">Curriculum Vitae (PDF/DOCX, maks 5MB)</label>
                <input type="file" id="cv" name="cv" accept=".pdf,.docx" required class="w-full min-h-[44px] px-4 py-2.5 rounded-lg bg-white border border-dashed border-slate-300 text-sm font-normal text-slate-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
            </div>

            <div>
                <label for="surat" class="block text-sm font-bold text-slate-900 mb-2">Surat lamaran (PDF/DOCX, maks 5MB)</label>
                <input type="file" id="surat" name="surat" accept=".pdf,.docx" required class="w-full min-h-[44px] px-4 py-2.5 rounded-lg bg-white border border-dashed border-slate-300 text-sm font-normal text-slate-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600">
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center py-3 px-4 min-h-[44px] bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                Kirim Lamaran
            </button>
        </form>
    </section>

</main>
@endsection
