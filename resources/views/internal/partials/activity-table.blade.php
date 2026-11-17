@php
    $dummyNames = ['Budi Santoso', 'Siti Rahma', 'Ahmad Yusuf', 'Dewi Lestari', 'Joko Susilo'];
    $dummyEmails = ['budi.santoso@email.com', 'siti.rahma@email.com', 'ahmad.yusuf@email.com', 'dewi.lestari@email.com', 'joko.susilo@email.com'];
    $dummyJobs = ['UI/UX Designer', 'Frontend Developer', 'Backend Engineer', 'Data Analyst', 'DevOps Engineer'];
@endphp
<div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Lowongan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @for ($i = 1; $i <= 5; $i++)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $i }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-slate-900">{{ $dummyNames[$i - 1] ?? 'Pengguna ' . $i }}</div>
                    <div class="text-sm text-slate-500">{{ $dummyEmails[$i - 1] ?? 'user' . $i . '@email.com' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $dummyJobs[$i - 1] ?? 'Lowongan ' . $i }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $statuses = ['menunggu', 'diterima', 'ditolak', 'perlu_perbaikan'];
                        $statusLabel = $statuses[array_rand($statuses)];
                        $statusColors = [
                            'menunggu' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                            'diterima' => 'bg-green-50 text-green-700 border border-green-200',
                            'ditolak' => 'bg-red-50 text-red-700 border border-red-200',
                            'perlu_perbaikan' => 'bg-blue-50 text-blue-700 border border-blue-200',
                        ];
                        $statusClass = $statusColors[$statusLabel] ?? 'bg-slate-100 text-slate-800';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                        {{ ucwords($statusLabel) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ rand(10, 28) }} {{ ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'][rand(0, 11)] }} 2026</td>
            </tr>
            @endfor
        </tbody>
    </table>
</div>