<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Default controller harus sesuai struktur folder
$route['default_controller'] = 'dashboard';  // Diubah dari 'welcome'
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Route untuk root URL
$route['^$'] = 'dashboard';  // Diubah dari 'auth/Auth/index'

// Routes untuk Auth (di subfolder auth/)
$route['auth'] = 'auth/Auth';
$route['auth/login'] = 'auth/Auth/login';
$route['auth/logout'] = 'auth/Auth/logout';

// Routes untuk Dashboard (di root controllers/)
$route['dashboard'] = 'dashboard';

// Routes untuk User Management (di subfolder auth/)
$route['user'] = 'auth/User';
$route['user/add'] = 'auth/User/add';
$route['user/edit/(:num)'] = 'auth/User/edit/$1';
$route['user/delete/(:num)'] = 'auth/User/delete/$1';
$route['user/aktif/(:num)'] = 'auth/User/aktif/$1';
$route['user/hak_akses'] = 'auth/User/hak_akses';
$route['user/simpan_hak_akses'] = 'auth/User/simpan_hak_akses';
$route['user/get_hak_akses'] = 'auth/User/get_hak_akses';

// Routes untuk Perusahaan (di subfolder perusahaan/)
$route['perusahaan'] = 'perusahaan/Perusahaan';
$route['perusahaan/add'] = 'perusahaan/Perusahaan/add';
$route['perusahaan/add_process'] = 'perusahaan/Perusahaan/add_process';
$route['perusahaan/edit_process'] = 'perusahaan/Perusahaan/edit_process';
$route['perusahaan/edit/(:num)'] = 'perusahaan/Perusahaan/edit/$1';
$route['perusahaan/aktif/(:num)'] = 'perusahaan/Perusahaan/aktif/$1';
$route['perusahaan/nonaktif/(:num)'] = 'perusahaan/Perusahaan/nonaktif/$1';
$route['perusahaan/delete/(:num)'] = 'perusahaan/Perusahaan/delete/$1';

// Routes untuk Gudang (di subfolder perusahaan/)
$route['gudang'] = 'perusahaan/Gudang';
$route['gudang/add'] = 'perusahaan/Gudang/add';
$route['gudang/add_process'] = 'perusahaan/Gudang/add_process';
$route['gudang/edit_process'] = 'perusahaan/Gudang/edit_process';
$route['gudang/edit/(:num)'] = 'perusahaan/Gudang/edit/$1';
$route['gudang/delete/(:num)'] = 'perusahaan/Gudang/delete/$1';
$route['gudang/aktif/(:num)'] = 'perusahaan/Gudang/aktif/$1';
$route['gudang/nonaktif/(:num)'] = 'perusahaan/Gudang/nonaktif/$1';
$route['gudang/get_gudang_by_perusahaan'] = 'perusahaan/Gudang/get_gudang_by_perusahaan';


// Routes untuk Master Data (di subfolder master/)
$route['kategori'] = 'master/Kategori';
$route['kategori/add'] = 'master/Kategori/add';
$route['kategori/edit/(:num)'] = 'master/Kategori/edit/$1';
$route['kategori/delete/(:num)'] = 'master/Kategori/delete/$1';
$route['kategori/aktif/(:num)'] = 'master/Kategori/aktif/$1';
$route['kategori/nonaktif/(:num)'] = 'master/Kategori/nonaktif/$1';
$route['kategori/add_process'] = 'master/Kategori/add_process';
$route['kategori/edit_process'] = 'master/Kategori/edit_process';

// route untuk barang master
$route['barang'] = 'master/Barang';
$route['barang/add'] = 'master/Barang/add';
$route['barang/add_process'] = 'master/Barang/add_process';
$route['barang/edit_process'] = 'master/Barang/edit_process';
$route['barang/edit/(:num)'] = 'master/Barang/edit/$1';
$route['barang/aktif/(:num)'] = 'master/Barang/aktif/$1';
$route['barang/nonaktif/(:num)'] = 'master/Barang/nonaktif/$1';
$route['barang/delete/(:num)'] = 'master/Barang/delete/$1';
$route['barang/get_kategori_by_perusahaan'] = 'master/Barang/get_kategori_by_perusahaan';
$route['barang/get_gudang_by_perusahaan'] = 'master/Barang/get_gudang_by_perusahaan';
$route['barang/get_gudang_by_perusahaan_filter'] = 'master/Barang/get_gudang_by_perusahaan_filter';
$route['barang/input_stok_awal_process'] = 'master/Barang/input_stok_awal_process';
$route['barang/load_more'] = 'master/Barang/load_more';

$route['supplier'] = 'master/Supplier';
$route['supplier/add'] = 'master/Supplier/add';
$route['supplier/add_process'] = 'master/Supplier/add_process';
$route['supplier/edit_process'] = 'master/Supplier/edit_process';
$route['supplier/edit/(:num)'] = 'master/Supplier/edit/$1';
$route['supplier/aktif/(:num)'] = 'master/Supplier/aktif/$1';
$route['supplier/nonaktif/(:num)'] = 'master/Supplier/nonaktif/$1';
$route['supplier/delete/(:num)'] = 'master/Supplier/delete/$1';

$route['pelanggan'] = 'master/Pelanggan';
$route['pelanggan/add'] = 'master/Pelanggan/add';
$route['pelanggan/add_process'] = 'master/Pelanggan/add_process';
$route['pelanggan/edit_process'] = 'master/Pelanggan/edit_process';
$route['pelanggan/edit/(:num)'] = 'master/Pelanggan/edit/$1';
$route['pelanggan/aktif/(:num)'] = 'master/Pelanggan/aktif/$1';
$route['pelanggan/nonaktif/(:num)'] = 'master/Pelanggan/nonaktif/$1';
$route['pelanggan/delete/(:num)'] = 'master/Pelanggan/delete/$1';

$route['transfer'] = 'stok/Transfer';
$route['transfer/add'] = 'stok/Transfer/add';
$route['transfer/approve/(:num)'] = 'stok/Transfer/approve/$1';
$route['transfer/reject/(:num)'] = 'stok/Transfer/reject/$1';
$route['transfer/get_gudang_by_perusahaan'] = 'stok/Transfer/get_gudang_by_perusahaan';
$route['transfer/get_barang_by_gudang'] = 'stok/Transfer/get_barang_by_gudang';
$route['transfer/get_stok_barang'] = 'stok/Transfer/get_stok_barang';

$route['penyesuaian'] = 'stok/Penyesuaian';
$route['penyesuaian/add'] = 'stok/Penyesuaian/add';
$route['penyesuaian/get_gudang_by_perusahaan'] = 'stok/Penyesuaian/get_gudang_by_perusahaan';
$route['penyesuaian/get_barang_by_gudang'] = 'stok/Penyesuaian/get_barang_by_gudang';
$route['penyesuaian/get_stok_barang'] = 'stok/Penyesuaian/get_stok_barang';

$route['riwayat'] = 'stok/Riwayat';
$route['riwayat/get_gudang_by_perusahaan'] = 'stok/Riwayat/get_gudang_by_perusahaan';
$route['riwayat/get_barang_by_gudang'] = 'stok/Riwayat/get_barang_by_gudang';

// Routes untuk Penjualan
$route['penjualan'] = 'penjualan/Penjualan';
$route['penjualan/add'] = 'penjualan/Penjualan/add';
$route['penjualan/add_process'] = 'penjualan/Penjualan/add_process';
$route['penjualan/detail/(:num)'] = 'penjualan/Penjualan/detail/$1';
$route['penjualan/proses/(:num)'] = 'penjualan/Penjualan/proses/$1';
$route['penjualan/kirim/(:num)'] = 'penjualan/Penjualan/kirim/$1';
$route['penjualan/selesai/(:num)'] = 'penjualan/Penjualan/selesai/$1';
$route['penjualan/batal/(:num)'] = 'penjualan/Penjualan/batal/$1';
$route['penjualan/add_barang'] = 'penjualan/Penjualan/add_barang';
$route['penjualan/delete_barang/(:num)'] = 'penjualan/Penjualan/delete_barang/$1';
$route['penjualan/get_gudang_by_perusahaan'] = 'penjualan/Penjualan/get_gudang_by_perusahaan';
$route['penjualan/get_barang_by_gudang'] = 'penjualan/Penjualan/get_barang_by_gudang';
$route['penjualan/get_stok_barang'] = 'penjualan/Penjualan/get_stok_barang';
$route['penjualan/view/(:num)'] = 'penjualan/Penjualan/view/$1';
// $route['penjualan/view/(:num)'] = 'laporan/LaporanPenjualan/detail/$1';
$route['penjualan/edit/(:num)'] = 'penjualan/Penjualan/edit/$1';
$route['penjualan/delete/(:num)'] = 'penjualan/Penjualan/delete/$1';
$route['penjualan/get_barang_by_perusahaan'] = 'penjualan/Penjualan/get_barang_by_perusahaan';
$route['penjualan/get_stock_by_barang'] = 'penjualan/Penjualan/get_stock_by_barang';
$route['penjualan/update_status/(:num)/(:any)'] = 'penjualan/Penjualan/update_status/$1/$2';

// Routes untuk Retur Penjualan
$route['retur'] = 'penjualan/Retur';
$route['retur/add'] = 'penjualan/Retur/add';
$route['retur/add_process'] = 'penjualan/Retur/add_process';
$route['retur/view/(:num)'] = 'penjualan/Retur/view/$1';
$route['retur/add/(:num)'] = 'penjualan/Retur/add/$1';

// Routes untuk update status retur
$route['retur/update_status/(:num)/(:any)'] = 'penjualan/Retur/update_status/$1/$2';
$route['retur/diterima/(:num)'] = 'penjualan/Retur/update_status/$1/diterima';
$route['retur/ditolak/(:num)'] = 'penjualan/Retur/update_status/$1/ditolak';
$route['retur/batal/(:num)'] = 'penjualan/Retur/update_status/$1/batal';

// Routes untuk API/AJAX (jika diperlukan)
$route['retur/get_penjualan_by_perusahaan'] = 'penjualan/Retur/get_penjualan_by_perusahaan';
$route['retur/get_barang_by_penjualan'] = 'penjualan/Retur/get_barang_by_penjualan';

// Routes legacy (jika masih digunakan di tempat lain)
$route['retur/detail/(:num)'] = 'penjualan/Retur/view/$1'; // Redirect ke view
$route['retur/proses/(:num)'] = 'penjualan/Retur/update_status/$1/diproses'; // Jika masih digunakan
$route['retur/tolak/(:num)'] = 'penjualan/Retur/update_status/$1/ditolak'; // Sudah ada di atas

// Routes untuk Laporan (di subfolder laporan/)
$route['laporan_stok'] = 'laporan/LaporanStok';
$route['laporan_stok/export_pdf'] = 'laporan/LaporanStok/export_pdf';
$route['laporan_stok/export_excel'] = 'laporan/LaporanStok/export_excel';
$route['laporan_stok/get_gudang_by_perusahaan'] = 'laporan/LaporanStok/get_gudang_by_perusahaan';
$route['laporan_stok/get_kategori_by_perusahaan'] = 'laporan/LaporanStok/get_kategori_by_perusahaan';

$route['laporan_penjualan'] = 'laporan/LaporanPenjualan';
$route['laporan_penjualan/detail/(:num)'] = 'laporan/LaporanPenjualan/detail/$1';
$route['laporan_penjualan/export_pdf'] = 'laporan/LaporanPenjualan/export_pdf';
$route['laporan_penjualan/export_excel'] = 'laporan/LaporanPenjualan/export_excel';

$route['laporan_retur'] = 'laporan/LaporanRetur';
$route['laporan_retur/export_pdf'] = 'laporan/LaporanRetur/export_pdf';
$route['laporan_retur/export_excel'] = 'laporan/LaporanRetur/export_excel';

$route['laporan_transfer'] = 'laporan/LaporanTransfer';
$route['laporan_transfer/export_pdf'] = 'laporan/LaporanTransfer/export_pdf';
$route['laporan_transfer/export_excel'] = 'laporan/LaporanTransfer/export_excel';

// Routes untuk Pengaturan (di subfolder pengaturan/)
$route['pengaturan'] = 'pengaturan/Sistem';
$route['pengaturan/update'] = 'pengaturan/Sistem/update';

$route['backup'] = 'pengaturan/Backup';
$route['backup/create'] = 'pengaturan/Backup/create';
$route['backup/download/(:any)'] = 'pengaturan/Backup/download/$1';
$route['backup/delete/(:any)'] = 'pengaturan/Backup/delete/$1';