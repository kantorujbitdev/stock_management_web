<!-- JS Custom untuk halaman ini -->
<script>
    // Setup CSRF token for AJAX requests
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });

    // Check localStorage for filter visibility preference
    document.addEventListener('DOMContentLoaded', function () {
        const filterCardBody = document.getElementById('filterCardBody');
        const toggleFilterBtn = document.getElementById('toggleFilter');
        const toggleIcon = toggleFilterBtn.querySelector('i');

        // Get saved preference or default to hidden
        const isFilterVisible = localStorage.getItem('filterVisible') === 'true';

        // Set initial state based on saved preference
        if (isFilterVisible) {
            filterCardBody.style.display = 'block';
            toggleIcon.className = 'fas fa-chevron-up';
        } else {
            filterCardBody.style.display = 'none';
            toggleIcon.className = 'fas fa-chevron-down';
        }

        // Toggle Filter Visibility
        toggleFilterBtn.addEventListener('click', function () {
            if (filterCardBody.style.display === 'none') {
                filterCardBody.style.display = 'block';
                toggleIcon.className = 'fas fa-chevron-up';
                localStorage.setItem('filterVisible', 'true');
            } else {
                filterCardBody.style.display = 'none';
                toggleIcon.className = 'fas fa-chevron-down';
                localStorage.setItem('filterVisible', 'false');
            }
        });

        // Reset All Filters
        document.getElementById('resetFilter').addEventListener('click', function () {
            // Reset search input
            document.getElementById('searchBarang').value = '';

            // Reset all dropdowns to default/first option
            document.getElementById('filterKategori').selectedIndex = 0;
            document.getElementById('filterStatus').selectedIndex = 0;
            document.getElementById('filterStok').selectedIndex = 0;
            document.getElementById('sortBy').selectedIndex = 0;

            <?php if ($this->session->userdata('id_role') == 5): ?>
                document.getElementById('filterPerusahaan').selectedIndex = 0;
            <?php endif; ?>

            // Hide filter panel
            filterCardBody.style.display = 'none';
            toggleIcon.className = 'fas fa-chevron-down';
            localStorage.setItem('filterVisible', 'false');

            // Trigger filter after reset
            filterBarang();

            // Show notification that filter has been reset and hidden
            showNotification('Filter berhasil direset dan disembunyikan', 'info');
        });
    });

    // Function to show notification
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '250px';
        notification.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;

        // Add to body
        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 150);
        }, 3000);
    }

    // Filter functionality
    document.getElementById('searchBarang').addEventListener('keyup', filterBarang);
    <?php if ($this->session->userdata('id_role') == 5): ?>
        document.getElementById('filterPerusahaan').addEventListener('change', filterBarang);
    <?php endif; ?>
    document.getElementById('filterKategori').addEventListener('change', filterBarang);
    document.getElementById('filterStatus').addEventListener('change', filterBarang);
    document.getElementById('filterStok').addEventListener('change', filterBarang);
    document.getElementById('sortBy').addEventListener('change', sortBarang);

    function filterBarang() {
        const searchValue = document.getElementById('searchBarang').value.toLowerCase();
        const kategoriValue = document.getElementById('filterKategori').value;
        const statusValue = document.getElementById('filterStatus').value;
        const stokValue = document.getElementById('filterStok').value;
        const items = document.querySelectorAll('.barang-item');
        <?php if ($this->session->userdata('id_role') == 5): ?>
            const perusahaanValue = document.getElementById('filterPerusahaan').value;
        <?php endif; ?>

        items.forEach(item => {
            const nama = item.getAttribute('data-nama');
            const sku = item.getAttribute('data-sku');
            const kategori = item.getAttribute('data-kategori');
            const status = item.getAttribute('data-status');
            const hasStokAwal = item.getAttribute('data-hasstokawal');

            const matchSearch = nama.includes(searchValue) || sku.includes(searchValue);
            const matchKategori = kategoriValue === '' || kategori === kategoriValue;
            const matchStatus = statusValue === '' || status === statusValue;
            const matchStok = stokValue === '' ||
                (stokValue === 'empty' && hasStokAwal === '0') ||
                (stokValue === 'has_stock' && hasStokAwal === '1');

            <?php if ($this->session->userdata('id_role') == 5): ?>
                const perusahaan = item.getAttribute('data-idperusahaan');
                const matchPerusahaan = perusahaanValue === '' || perusahaan === perusahaanValue;
            <?php endif; ?>

            <?php if ($this->session->userdata('id_role') == 5): ?>
                if (matchSearch && matchKategori && matchStatus && matchStok && matchPerusahaan) {
                <?php else: ?>
                    if (matchSearch && matchKategori && matchStatus && matchStok) {
                    <?php endif; ?>
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
    }

    function sortBarang() {
        const sortBy = document.getElementById('sortBy').value;
        const grid = document.getElementById('barangGrid');
        const items = Array.from(grid.querySelectorAll('.barang-item'));

        items.sort((a, b) => {
            let aValue, bValue;
            switch (sortBy) {
                case 'nama_barang':
                    aValue = a.getAttribute('data-nama');
                    bValue = b.getAttribute('data-nama');
                    return aValue.localeCompare(bValue);
                case 'sku':
                    aValue = a.getAttribute('data-sku');
                    bValue = b.getAttribute('data-sku');
                    return aValue.localeCompare(bValue);
                case 'stok':
                    aValue = parseInt(a.getAttribute('data-stok'));
                    bValue = parseInt(b.getAttribute('data-stok'));
                    return bValue - aValue; // Descending for stock
                default:
                    return 0;
            }
        });

        // Re-append sorted items
        items.forEach(item => grid.appendChild(item));
    }

    // Detail modal functionality
    document.addEventListener('DOMContentLoaded', function () {
        // Add click event to all detail buttons
        const detailButtons = document.querySelectorAll('.detail-btn');
        detailButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation(); // Prevent card click event
                showDetailModal(this.closest('.barang-item'));
            });
        });

        // Add click event to all cards
        const cardItems = document.querySelectorAll('.card-clickable');
        cardItems.forEach(card => {
            card.addEventListener('click', function () {
                showDetailModal(this);
            });
        });

        // Add click event to input stok buttons
        const inputStokButtons = document.querySelectorAll('.input-stok-btn');
        inputStokButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation(); // Prevent card click event
                showInputStokModal(this);
            });
        });

        // Load gudang when modal is shown
        $('#inputStokModal').on('shown.bs.modal', function () {
            const idPerusahaan = $('#stokIdPerusahaan').val();
            if (idPerusahaan) {
                loadGudangOptions(idPerusahaan);
            }
        });
    });

    function showDetailModal(barangItem) {
        const id = barangItem.getAttribute('data-id');
        const gambar = barangItem.getAttribute('data-gambar');
        const namaBarang = barangItem.getAttribute('data-namabarang');
        const sku = barangItem.getAttribute('data-skuvalue');
        const kategori = barangItem.getAttribute('data-kategoriname');
        const perusahaan = barangItem.getAttribute('data-perusahaan');
        const deskripsi = barangItem.getAttribute('data-deskripsi');
        const stok = parseInt(barangItem.getAttribute('data-stok'));
        const status = barangItem.getAttribute('data-status');
        const hasStokAwal = barangItem.getAttribute('data-hasstokawal') === '1';

        // Set modal content
        if (gambar) {
            document.getElementById('modalGambarContainer').innerHTML =
                `<img src="${gambar}" class="img-fluid img-clickable" data-src="${gambar}">`;
        } else {
            document.getElementById('modalGambarContainer').innerHTML =
                `<div class="text-center text-muted p-4">
                    <i class="fas fa-image fa-5x"></i>
                    <p class="mt-2">No Image</p>
                </div>`;
        }

        document.getElementById('modalNamaBarang').textContent = namaBarang;
        document.getElementById('modalSKU').textContent = sku;
        document.getElementById('modalKategori').textContent = kategori;
        document.getElementById('modalPerusahaan').textContent = perusahaan;
        document.getElementById('modalDeskripsi').textContent = deskripsi;

        // Set stok with color
        const stokElement = document.getElementById('modalStok');
        stokElement.textContent = stok;
        stokElement.className = '';
        if (stok > 10) {
            stokElement.className = 'text-success';
            stokElement.style.fontSize = '1.5rem';
            stokElement.style.fontWeight = 'bold';
        } else if (stok > 0) {
            stokElement.className = 'text-warning';
            stokElement.style.fontSize = '1.5rem';
            stokElement.style.fontWeight = 'bold';
        } else {
            stokElement.className = 'text-danger';
            stokElement.style.fontSize = '1.5rem';
            stokElement.style.fontWeight = 'bold';
        }

        // Set status
        const statusElement = document.getElementById('modalStatus');
        if (status === '1') {
            statusElement.textContent = 'Aktif';
            statusElement.className = 'text-success font-weight-bold';
        } else {
            statusElement.textContent = 'Tidak Aktif';
            statusElement.className = 'text-danger font-weight-bold';
        }

        // Set stok awal status
        const stokAwalElement = document.getElementById('modalStokAwal');
        if (hasStokAwal) {
            stokAwalElement.textContent = 'Sudah Ada';
            stokAwalElement.className = 'text-success font-weight-bold';
        } else {
            stokAwalElement.textContent = 'Belum Ada';
            stokAwalElement.className = 'text-warning font-weight-bold';
        }

        // Show/hide perusahaan row based on user role
        const perusahaanRow = document.getElementById('modalPerusahaanRow');
        if (document.body.getAttribute('data-role') === '5') {
            perusahaanRow.style.display = '';
        } else {
            perusahaanRow.style.display = 'none';
        }

        // Set edit link
        document.getElementById('modalEditLink').href = `barang/edit/${id}`;
        if (document.body.getAttribute('data-role') === '5' || document.body.getAttribute('data-role') === '1') {
            document.getElementById('modalEditLink').style.display = '';
        } else {
            document.getElementById('modalEditLink').style.display = 'none';
        }

        // Set input stok button
        const inputStokBtn = document.getElementById('modalInputStokBtn');
        if (!hasStokAwal && (document.body.getAttribute('data-role') === '5' || document.body.getAttribute('data-role') === '1')) {
            inputStokBtn.style.display = '';
            inputStokBtn.setAttribute('data-id', id);
            inputStokBtn.setAttribute('data-nama', namaBarang);
            inputStokBtn.setAttribute('data-idperusahaan', barangItem.getAttribute('data-idperusahaan'));
        } else {
            inputStokBtn.style.display = 'none';
        }

        // Add event listener to input stok button in modal
        inputStokBtn.onclick = function () {
            $('#detailModal').modal('hide');
            showInputStokModal(this);
        };

        // Show modal
        $('#detailModal').modal('show');
    }

    function showInputStokModal(button) {
        const id = button.getAttribute('data-id');
        const nama = button.getAttribute('data-nama');
        const idPerusahaan = button.getAttribute('data-idperusahaan');

        // Set form values
        document.getElementById('stokIdBarang').value = id;
        document.getElementById('stokIdPerusahaan').value = idPerusahaan;
        document.getElementById('namaBarangDisplay').value = nama;
        document.getElementById('qty_awal').value = '';
        document.getElementById('keterangan').value = '';
        document.getElementById('id_gudang').innerHTML = '<option value="">-- Pilih Gudang --</option>';

        // Show modal
        $('#inputStokModal').modal('show');
    }

    function loadGudangOptions(idPerusahaan) {
        $.ajax({
            url: "<?php echo site_url('barang/get_gudang_by_perusahaan'); ?>",
            type: "GET",
            data: { id_perusahaan: idPerusahaan },
            dataType: "json",
            success: function (response) {
                let options = '<option value="">-- Pilih Gudang --</option>';
                response.forEach(function (gudang) {
                    options += `<option value="${gudang.id_gudang}">${gudang.nama_gudang}</option>`;
                });
                document.getElementById('id_gudang').innerHTML = options;
            },
            error: function (xhr, status, error) {
                console.error("Error loading gudang:", error);
                document.getElementById('id_gudang').innerHTML = '<option value="">-- Error --</option>';
            }
        });
    }

    // Fungsi untuk mengeksekusi script setelah jQuery dan Bootstrap siap
    function runImageFullscreenScript() {
        if (window.jQuery && typeof jQuery.fn.modal === 'function') {
            console.log("Initializing image fullscreen script");

            // Klik gambar untuk fullscreen
            jQuery(document).on('click', '.img-clickable', function (e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent card click event
                console.log('Gambar diklik');
                var src = jQuery(this).data('src');
                console.log('SRC: ' + src);
                jQuery('#gambarPreview').attr('src', src);
                jQuery('#gambarModal').modal('show');
            });

            // Zoom pakai scroll
            jQuery('#gambarPreview').on('wheel', function (e) {
                e.preventDefault();
                var scale = jQuery(this).data('scale') || 1;
                scale += (e.originalEvent.deltaY < 0 ? 0.1 : -0.1);
                if (scale < 0.5) scale = 0.5;
                if (scale > 3) scale = 3;
                jQuery(this).css('transform', 'translate(0,0) scale(' + scale + ')');
                jQuery(this).data('scale', scale);
            });

            // Drag gambar
            var isDragging = false, startX, startY, translateX = 0, translateY = 0;
            jQuery('#gambarPreview').on('mousedown', function (e) {
                isDragging = true;
                startX = e.pageX - translateX;
                startY = e.pageY - translateY;
                jQuery(this).css('cursor', 'grabbing');
            });

            jQuery(document).on('mouseup', function () {
                isDragging = false;
                jQuery('#gambarPreview').css('cursor', 'grab');
            });

            jQuery(document).on('mousemove', function (e) {
                if (!isDragging) return;
                translateX = e.pageX - startX;
                translateY = e.pageY - startY;
                jQuery('#gambarPreview').css('transform',
                    'translate(' + translateX + 'px,' + translateY + 'px) scale(' + (jQuery('#gambarPreview').data('scale') || 1) + ')');
            });

            // Reset saat modal ditutup
            jQuery('#gambarModal').on('hidden.bs.modal', function () {
                jQuery('#gambarPreview').css('transform', 'scale(1)').data('scale', 1);
                translateX = 0; translateY = 0;
            });

            console.log("Image fullscreen script initialized");
        } else {
            console.log("jQuery or Bootstrap not ready yet, retrying...");
            setTimeout(runImageFullscreenScript, 200);
        }
    }

    // Jalankan script setelah dependencies siap
    document.addEventListener('DOMContentLoaded', function () {
        runImageFullscreenScript();
    });
</script>