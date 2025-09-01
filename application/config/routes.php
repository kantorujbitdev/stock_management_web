<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
$route['user/hak_akses'] = 'auth/User/hak_akses';
$route['user/simpan_hak_akses'] = 'auth/User/simpan_hak_akses';  // DITAMBAHKAN
$route['user/get_hak_akses'] = 'auth/User/get_hak_akses';  // DITAMBAHKAN

// Routes untuk Perusahaan (di subfolder perusahaan/)
$route['perusahaan'] = 'perusahaan/Perusahaan';
$route['perusahaan/add'] = 'perusahaan/Perusahaan/add';
$route['perusahaan/edit/(:num)'] = 'perusahaan/Perusahaan/edit/$1';
$route['perusahaan/delete/(:num)'] = 'perusahaan/Perusahaan/delete/$1';

// Routes untuk Gudang (di subfolder perusahaan/)
$route['gudang'] = 'perusahaan/Gudang';
$route['gudang/add'] = 'perusahaan/Gudang/add';
$route['gudang/edit/(:num)'] = 'perusahaan/Gudang/edit/$1';
$route['gudang/delete/(:num)'] = 'perusahaan/Gudang/delete/$1';
$route['gudang/get_gudang_by_perusahaan'] = 'perusahaan/Gudang/get_gudang_by_perusahaan';  // DITAMBAHKAN
$route['gudang/add_process'] = 'perusahaan/Gudang/add_process';
$route['gudang/edit_process'] = 'perusahaan/Gudang/edit_process';

// Routes untuk Master Data (di subfolder master/)
$route['kategori'] = 'master/Kategori';
$route['kategori/add'] = 'master/Kategori/add';
$route['kategori/edit/(:num)'] = 'master/Kategori/edit/$1';
$route['kategori/delete/(:num)'] = 'master/Kategori/delete/$1';
$route['kategori/add_process'] = 'master/Kategori/add_process';
$route['kategori/edit_process'] = 'master/Kategori/edit_process';

$route['barang'] = 'master/Barang';
$route['barang/add'] = 'master/Barang/add';
$route['barang/edit/(:num)'] = 'master/Barang/edit/$1';
$route['barang/delete/(:num)'] = 'master/Barang/delete/$1';
$route['barang/get_kategori_by_perusahaan'] = 'master/Barang/get_kategori_by_perusahaan';  // DITAMBAHKAN

$route['supplier'] = 'master/Supplier';
$route['supplier/add'] = 'master/Supplier/add';
$route['supplier/add_process'] = 'master/Supplier/add_process';
$route['supplier/edit_process'] = 'master/Supplier/edit_process';
$route['supplier/edit/(:num)'] = 'master/Supplier/edit/$1';
$route['supplier/delete/(:num)'] = 'master/Supplier/delete/$1';

$route['pelanggan'] = 'master/Pelanggan';
$route['pelanggan/add'] = 'master/Pelanggan/add';
$route['pelanggan/add_process'] = 'master/Pelanggan/add_process';
$route['pelanggan/edit_process'] = 'master/Pelanggan/edit_process';
$route['pelanggan/edit/(:num)'] = 'master/Pelanggan/edit/$1';
$route['pelanggan/delete/(:num)'] = 'master/Pelanggan/delete/$1';

// Routes untuk Stok (di subfolder stok/)
$route['stok_awal'] = 'stok/StokAwal';
$route['stok_awal/add'] = 'stok/StokAwal/add';
$route['stok_awal/edit/(:num)'] = 'stok/StokAwal/edit/$1';
$route['stok_awal/delete/(:num)'] = 'stok/StokAwal/delete/$1';
$route['stok_awal/get_gudang_by_perusahaan'] = 'stok/StokAwal/get_gudang_by_perusahaan';  // DITAMBAHKAN
$route['stok_awal/get_barang_by_perusahaan'] = 'stok/StokAwal/get_barang_by_perusahaan';  // DITAMBAHKAN

$route['penerimaan'] = 'stok/Penerimaan';
$route['penerimaan/add'] = 'stok/Penerimaan/add';
$route['penerimaan/detail/(:num)'] = 'stok/Penerimaan/detail/$1';  // DITAMBAHKAN
$route['penerimaan/proses/(:num)'] = 'stok/Penerimaan/proses/$1';  // DITAMBAHKAN
$route['penerimaan/batal/(:num)'] = 'stok/Penerimaan/batal/$1';  // DITAMBAHKAN
$route['penerimaan/add_barang'] = 'stok/Penerimaan/add_barang';  // DITAMBAHKAN
$route['penerimaan/delete_barang/(:num)'] = 'stok/Penerimaan/delete_barang/$1';  // DITAMBAHKAN
$route['penerimaan/get_gudang_by_perusahaan'] = 'stok/Penerimaan/get_gudang_by_perusahaan';  // DITAMBAHKAN

$route['transfer'] = 'stok/Transfer';
$route['transfer/add'] = 'stok/Transfer/add';
$route['transfer/approve/(:num)'] = 'stok/Transfer/approve/$1';  // DITAMBAHKAN
$route['transfer/reject/(:num)'] = 'stok/Transfer/reject/$1';  // DITAMBAHKAN
$route['transfer/get_gudang_by_perusahaan'] = 'stok/Transfer/get_gudang_by_perusahaan';  // DITAMBAHKAN
$route['transfer/get_barang_by_gudang'] = 'stok/Transfer/get_barang_by_gudang';  // DITAMBAHKAN
$route['transfer/get_stok_barang'] = 'stok/Transfer/get_stok_barang';  // DITAMBAHKAN

$route['penyesuaian'] = 'stok/Penyesuaian';
$route['penyesuaian/add'] = 'stok/Penyesuaian/add';
$route['penyesuaian/get_gudang_by_perusahaan'] = 'stok/Penyesuaian/get_gudang_by_perusahaan';  // DITAMBAHKAN
$route['penyesuaian/get_barang_by_gudang'] = 'stok/Penyesuaian/get_barang_by_gudang';  // DITAMBAHKAN
$route['penyesuaian/get_stok_barang'] = 'stok/Penyesuaian/get_stok_barang';  // DITAMBAHKAN

$route['riwayat'] = 'stok/Riwayat';
$route['riwayat/get_gudang_by_perusahaan'] = 'stok/Riwayat/get_gudang_by_perusahaan';  // DITAMBAHKAN
$route['riwayat/get_barang_by_gudang'] = 'stok/Riwayat/get_barang_by_gudang';  // DITAMBAHKAN

// Routes untuk Penjualan
$route['penjualan'] = 'penjualan/Penjualan';
$route['penjualan/add'] = 'penjualan/Penjualan/add';
$route['penjualan/detail/(:num)'] = 'penjualan/Penjualan/detail/$1';
$route['penjualan/proses/(:num)'] = 'penjualan/Penjualan/proses/$1';
$route['penjualan/kirim/(:num)'] = 'penjualan/Penjualan/kirim/$1';
$route['penjualan/selesai/(:num)'] = 'penjualan/Penjualan/selesai/$1';
$route['penjualan/batal/(:num)'] = 'penjualan/Penjualan/batal/$1';
$route['penjualan/add_barang'] = 'penjualan/Penjualan/add_barang';
$route['penjualan/delete_barang/(:num)'] = 'penjualan/Penjualan/delete_barang/$1';
$route['penjualan/get_gudang_by_perusahaan'] = 'penjualan/Penjualan/get_gudang_by_perusahaan';  // DITAMBAHKAN
$route['penjualan/get_barang_by_gudang'] = 'penjualan/Penjualan/get_barang_by_gudang';  // DITAMBAHKAN
$route['penjualan/get_stok_barang'] = 'penjualan/Penjualan/get_stok_barang';  // DITAMBAHKAN

$route['retur'] = 'penjualan/Retur';
$route['retur/add'] = 'penjualan/Retur/add';
$route['retur/detail/(:num)'] = 'penjualan/Retur/detail/$1';
$route['retur/proses/(:num)'] = 'penjualan/Retur/proses/$1';
$route['retur/tolak/(:num)'] = 'penjualan/Retur/tolak/$1';
$route['retur/add_barang'] = 'penjualan/Retur/add_barang';
$route['retur/delete_barang/(:num)'] = 'penjualan/Retur/delete_barang/$1';
$route['retur/get_penjualan_by_perusahaan'] = 'penjualan/Retur/get_penjualan_by_perusahaan';  // DITAMBAHKAN
$route['retur/get_barang_by_penjualan'] = 'penjualan/Retur/get_barang_by_penjualan';  // DITAMBAHKAN

// Routes untuk Laporan (di subfolder laporan/)
$route['laporan_stok'] = 'laporan/LaporanStok';
$route['laporan_stok/export_pdf'] = 'laporan/LaporanStok/export_pdf';  // DITAMBAHKAN
$route['laporan_stok/export_excel'] = 'laporan/LaporanStok/export_excel';  // DITAMBAHKAN
$route['laporan_stok/get_gudang_by_perusahaan'] = 'laporan/LaporanStok/get_gudang_by_perusahaan';  // DITAMBAHKAN
$route['laporan_stok/get_kategori_by_perusahaan'] = 'laporan/LaporanStok/get_kategori_by_perusahaan';  // DITAMBAHKAN

$route['laporan_penjualan'] = 'laporan/LaporanPenjualan';
$route['laporan_penjualan/export_pdf'] = 'laporan/LaporanPenjualan/export_pdf';  // DITAMBAHKAN
$route['laporan_penjualan/export_excel'] = 'laporan/LaporanPenjualan/export_excel';  // DITAMBAHKAN

$route['laporan_retur'] = 'laporan/LaporanRetur';
$route['laporan_retur/export_pdf'] = 'laporan/LaporanRetur/export_pdf';  // DITAMBAHKAN
$route['laporan_retur/export_excel'] = 'laporan/LaporanRetur/export_excel';  // DITAMBAHKAN

$route['laporan_transfer'] = 'laporan/LaporanTransfer';
$route['laporan_transfer/export_pdf'] = 'laporan/LaporanTransfer/export_pdf';  // DITAMBAHKAN
$route['laporan_transfer/export_excel'] = 'laporan/LaporanTransfer/export_excel';  // DITAMBAHKAN

// Routes untuk Pengaturan (di subfolder pengaturan/)
$route['pengaturan'] = 'pengaturan/Sistem';
$route['pengaturan/update'] = 'pengaturan/Sistem/update';  // DITAMBAHKAN

$route['backup'] = 'pengaturan/Backup';
$route['backup/create'] = 'pengaturan/Backup/create';  // DITAMBAHKAN
$route['backup/download/(:any)'] = 'pengaturan/Backup/download/$1';  // DITAMBAHKAN
$route['backup/delete/(:any)'] = 'pengaturan/Backup/delete/$1';  // DITAMBAHKAN