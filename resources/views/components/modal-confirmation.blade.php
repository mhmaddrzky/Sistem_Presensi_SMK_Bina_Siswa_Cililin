{{-- File: resources/views/components/modal-confirmation.blade.php --}}

<div id="confirmation-modal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    {{-- Backdrop (Latar Gelap Blur) --}}
    <div class="fixed inset-0 bg-slate-900/50 transition-opacity backdrop-blur-sm" id="modal-backdrop"></div>

    {{-- Wrapper agar modal di tengah --}}
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            {{-- Panel Modal --}}
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-100">
                
                {{-- Konten Modal --}}
                <div class="bg-white px-6 pt-6 pb-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-1 text-center sm:text-left w-full">
                            {{-- Judul --}}
                            <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title-text">
                                Konfirmasi
                            </h3>
                            {{-- Pesan --}}
                            <div class="mt-2">
                                <p class="text-sm text-slate-500" id="modal-message-text">
                                    Pesan konfirmasi...
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Tombol --}}
                <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                    
                    {{-- Tombol Utama (OK/Aksi) --}}
                    <button type="button" id="btn-confirm-yes" class="inline-flex w-full justify-center rounded-lg border border-transparent px-4 py-2 text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors">
                        OK
                    </button>

                    {{-- Tombol Batal (Bisa disembunyikan via JS) --}}
                    <button type="button" id="btn-confirm-cancel" class="inline-flex w-full justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-colors">
                        Batal
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Membuat fungsi global agar bisa dipanggil dari mana saja (termasuk script validasi di halaman Sesi)
    window.openModal = function(title, message, formId = null, btnText = 'OK', color = 'blue', type = 'confirm') {
        const modal = document.getElementById('confirmation-modal');
        const titleEl = document.getElementById('modal-title-text');
        const msgEl = document.getElementById('modal-message-text');
        const btnYes = document.getElementById('btn-confirm-yes');
        const btnCancel = document.getElementById('btn-confirm-cancel');
        
        // Set Konten
        titleEl.textContent = title;
        msgEl.textContent = message;
        btnYes.textContent = btnText;
        
        // Reset Event Listener Tombol Yes (agar tidak menumpuk)
        // Clone node untuk menghapus event listener lama
        const newBtnYes = btnYes.cloneNode(true);
        btnYes.parentNode.replaceChild(newBtnYes, btnYes);
        
        // Reset Warna & Tampilan Tombol
        newBtnYes.className = "inline-flex w-full justify-center rounded-lg border border-transparent px-4 py-2 text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors";
        
        // Logika Warna
        if (color === 'rose' || color === 'red') {
            newBtnYes.classList.add('bg-rose-600', 'hover:bg-rose-700', 'focus:ring-rose-500');
        } else if (color === 'blue') {
            newBtnYes.classList.add('bg-[#0D47C9]', 'hover:bg-blue-800', 'focus:ring-blue-500'); // Biru Konsisten
        } else {
            newBtnYes.classList.add('bg-emerald-600', 'hover:bg-emerald-700', 'focus:ring-emerald-500');
        }

        // Logika Tipe (Alert vs Confirm)
        if (type === 'alert') {
            btnCancel.classList.add('hidden'); // Sembunyikan tombol batal
        } else {
            btnCancel.classList.remove('hidden'); // Munculkan tombol batal
        }

        // Action Tombol YES
        newBtnYes.addEventListener('click', function () {
            if (formId) {
                const form = document.getElementById(formId);
                if (form) form.submit();
            }
            modal.classList.add('hidden');
        });

        // Tampilkan Modal
        modal.classList.remove('hidden');
    };

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('confirmation-modal');
        const modalBackdrop = document.getElementById('modal-backdrop');
        const btnCancel = document.getElementById('btn-confirm-cancel');

        // Close Action
        const closeAction = () => modal.classList.add('hidden');
        if(btnCancel) btnCancel.addEventListener('click', closeAction);
        if(modalBackdrop) modalBackdrop.addEventListener('click', closeAction);

        // Auto-bind untuk tombol dengan class .btn-confirm (seperti tombol hapus/tolak)
        document.querySelectorAll('.btn-confirm').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const formId = this.getAttribute('data-form-id');
                const title = this.getAttribute('data-title');
                const message = this.getAttribute('data-message');
                const btnText = this.getAttribute('data-btn-ok') || 'Ya, Lanjutkan';
                const color = this.getAttribute('data-btn-color'); 
                
                window.openModal(title, message, formId, btnText, color, 'confirm');
            });
        });
    });
</script>