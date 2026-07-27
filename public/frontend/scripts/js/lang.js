(function() {
  var _t = {
    'auth.register_failed': 'Registrasi Gagal',
    'auth.login_failed': 'Login Gagal',
    'auth.login_error': 'Terjadi kesalahan. Silakan coba lagi.',
    'modal.ok': 'OK',
    'modal.cancel': 'Batal',

    'contact.form.sending': 'Mengirim...',
    'contact.form.submit': 'Kirim Pesan',

    'admin.no_users': 'Belum ada pengguna',
    'admin.topup_btn': 'Top Up Manual',
    'admin.loading': 'Memuat...',
    'admin.no_transactions': 'Belum ada transaksi',
    'admin.no_reports': 'Belum ada laporan',
    'admin.no_subjects': 'Belum ada mata pelajaran',
    'admin.add_subject': 'Tambah Mata Pelajaran',
    'admin.edit_subject': 'Edit Mata Pelajaran',
    'admin.subject_name_required': 'Nama mata pelajaran harus diisi',
    'admin.processing': 'Memproses...',
    'admin.save': 'Simpan',
    'admin.confirm_delete_subject': 'Yakin ingin menghapus mata pelajaran ini?',
    'admin.invalid_credits': 'Jumlah kredit tidak valid',
    'admin.topup_success': 'Top up berhasil!',
    'admin.topup_add': 'Tambah Kredit',
  };

  window.t = function(key) {
    return _t[key] || key;
  };
})();
