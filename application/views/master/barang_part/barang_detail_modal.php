<!-- Modal Detail Barang Global -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detail Barang</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5 text-center">
                        <div id="modalGambarContainer">
                            <!-- Gambar akan dimuat di sini -->
                        </div>
                    </div>
                    <div class="col-md-7">
                        <table class="table table-borderless">
                            <tr>
                                <td width="30%"><strong>Nama Barang</strong></td>
                                <td width="5%">:</td>
                                <td id="modalNamaBarang"></td>
                            </tr>
                            <tr>
                                <td><strong>SKU</strong></td>
                                <td>:</td>
                                <td id="modalSKU"></td>
                            </tr>
                            <tr>
                                <td><strong>Kategori</strong></td>
                                <td>:</td>
                                <td id="modalKategori"></td>
                            </tr>
                            <tr id="modalPerusahaanRow" style="display: none;">
                                <td><strong>Perusahaan</strong></td>
                                <td>:</td>
                                <td id="modalPerusahaan"></td>
                            </tr>
                            <tr>
                                <td><strong>Stok Tersedia</strong></td>
                                <td>:</td>
                                <td>
                                    <span id="modalStok" style="font-size: 1.5rem; font-weight: bold;"></span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>:</td>
                                <td>
                                    <span id="modalStatus"></span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Deskripsi</strong></td>
                                <td>:</td>
                                <td id="modalDeskripsi"></td>
                            </tr>
                            <tr id="modalStokAwalRow">
                                <td><strong>Status Stok Awal</strong></td>
                                <td>:</td>
                                <td id="modalStokAwal"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a id="modalEditLink" href="#" class="btn btn-warning" style="display: none;">
                    <i class="fas fa-edit"></i> Edit Barang
                </a>
                <button id="modalInputStokBtn" class="btn btn-primary" style="display: none;">
                    <i class="fas fa-boxes"></i> Input Stok Awal
                </button>
            </div>
        </div>
    </div>
</div>