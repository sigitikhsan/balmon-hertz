<div class="fixed top-4 right-4 z-50 space-y-2">
    <!-- Success Toast -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg transform translate-x-0 transition-transform duration-300">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                <i class="fa-solid fa-check text-green-600"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-green-800">Sukses</h4>
                <p class="text-sm text-green-700">Operasi berhasil diselesaikan.</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-green-400 hover:text-green-600">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
    
    <!-- Error Toast -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 shadow-lg transform translate-x-0 transition-transform duration-300">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                <i class="fa-solid fa-exclamation-triangle text-red-600"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-red-800">Error</h4>
                <p class="text-sm text-red-700">Terjadi kesalahan saat memproses permintaan.</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-red-400 hover:text-red-600">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    </div>
</div>