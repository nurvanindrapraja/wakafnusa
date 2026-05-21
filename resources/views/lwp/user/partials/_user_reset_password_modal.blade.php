<div id="resetPasswordModel" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/50" onclick="closeResetPasswordModal()"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <!-- Modal Header -->
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-slate-900">Reset Password Pengguna</h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase mt-0.5 tracking-wider font-semibold" id="resetPasswordTitle">Nama Pengguna</p>
                </div>
                <button type="button" onclick="closeResetPasswordModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Form -->
            <form id="resetPasswordForm" method="POST" onsubmit="submitResetPasswordForm(event)">
                @csrf
                <input type="hidden" name="id_user" id="resetPasswordUserId">
                <div class="px-8 py-6 space-y-6">
                    <!-- Error Alert -->
                    <div id="resetPasswordErrorAlert" class="hidden px-6 py-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl text-xs font-semibold">
                    </div>

                    <!-- New Password Input -->
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Password Baru <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text" id="resetPasswordInput" name="password" required minlength="6" placeholder="Masukkan password baru" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs font-semibold focus:outline-none focus:border-nu-green-500 focus:bg-white transition-all">
                            </div>
                            <button type="button" onclick="generateResetPasswordRecommendation()" class="px-4 py-3.5 bg-nu-green-50 hover:bg-nu-green-100 text-nu-green-700 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                                Rekomendasi
                            </button>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium mt-1.5">Password disimpan terenkripsi SHA1.</p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3 rounded-b-3xl">
                    <button type="button" onclick="closeResetPasswordModal()" class="px-6 py-3 bg-white hover:bg-slate-100 text-slate-700 border border-slate-100 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                        Batal
                    </button>
                    <button type="submit" id="resetPasswordSubmitBtn" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
