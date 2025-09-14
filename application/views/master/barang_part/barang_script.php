<!-- JS Custom untuk halaman ini -->
<script>
    // Setup CSRF token for AJAX requests
    $.ajaxSetup({
        data: {
            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
        }
    });
    // Global variables - tambahkan variable baru
    let isLoading = false;
    let hasMoreData = <?php echo $has_more ? 'true' : 'false'; ?>;
    let currentPage = <?php echo $current_page; ?>;
    const itemsPerPage = <?php echo $items_per_page; ?>;
    let totalItemsLoaded = 0; // Tambahkan ini untuk tracking total item

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize filter toggle
        initializeFilterToggle();
        // Initialize filter listeners
        initializeFilterListeners();
        // Initialize infinite scroll
        initializeInfiniteScroll();
        // Attach event listeners to existing items
        attachEventListenersToItems();
        // Handle modal close to fix focus issue
        $('#detailModal').on('hidden.bs.modal', function () {
            // Remove focus from any element inside the modal
            $(this).find('button, a, input, select, textarea').blur();
            $('body').focus();
        });
        $('#inputStokModal').on('hidden.bs.modal', function () {
            // Remove focus from any element inside the modal
            $(this).find('button, a, input, select, textarea').blur();
            $('body').focus();
        });
    });
    // Initialize filter toggle
    function initializeFilterToggle() {
        const filterCardBody = document.getElementById('filterCardBody');
        const toggleFilterBtn = document.getElementById('toggleFilter');
        const toggleHeaderFrame = document.getElementById('filterHeader');
        const toggleIcon = toggleFilterBtn.querySelector('i');
        // Get saved preference
        const isFilterVisible = localStorage.getItem('filterVisible') === 'true';
        // Set initial state
        if (isFilterVisible) {
            filterCardBody.style.display = 'block';
            toggleIcon.className = 'fas fa-chevron-up';
        } else {
            filterCardBody.style.display = 'none';
            toggleIcon.className = 'fas fa-chevron-down';
        }
        // Toggle filter
        toggleHeaderFrame.addEventListener('click', function () {
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
        // Reset filter
        document.getElementById('resetFilter').addEventListener('click', function () {
            // Reset all inputs
            document.getElementById('searchBarang').value = '';
            document.getElementById('filterKategori').selectedIndex = 0;
            document.getElementById('filterStatus').selectedIndex = 0;
            document.getElementById('filterStok').selectedIndex = 0;
            document.getElementById('filterGudang').selectedIndex = 0; // Reset filter gudang
            document.getElementById('sortBy').selectedIndex = 0;
            <?php if ($this->session->userdata('id_role') == 5): ?>
                document.getElementById('filterPerusahaan').selectedIndex = 0;
                // Reset kategori options when perusahaan is reset
                loadKategoriOptions('');
            <?php endif; ?>
            // Hide filter panel
            filterCardBody.style.display = 'none';
            toggleIcon.className = 'fas fa-chevron-down';
            localStorage.setItem('filterVisible', 'false');
            // Trigger filter with server-side reload
            reloadWithFilters();
            // showNotification('Filter berhasil direset dan disembunyikan', 'info');
        });
    }
    // Initialize filter listeners
    function initializeFilterListeners() {
        // Event listener untuk pencarian - tambahkan debounce untuk mengurangi request
        let searchTimeout;
        document.getElementById('searchBarang').addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () {
                reloadWithFilters();
            }, 500); // Delay 500ms setelah user berhenti mengetik
        });

        // Event listener untuk filter kategori
        document.getElementById('filterKategori').addEventListener('change', function () {
            reloadWithFilters();
        });

        // Event listener untuk filter gudang
        document.getElementById('filterGudang').addEventListener('change', function () {
            reloadWithFilters();
        });

        // Event listener untuk filter status
        document.getElementById('filterStatus').addEventListener('change', function () {
            reloadWithFilters();
        });

        // Event listener untuk filter stok
        document.getElementById('filterStok').addEventListener('change', function () {
            reloadWithFilters();
        });

        // Event listener untuk sort
        document.getElementById('sortBy').addEventListener('change', function () {
            reloadWithFilters();
        });


        <?php if ($this->session->userdata('id_role') == 5): ?>
            // Event listener untuk filter perusahaan (hanya untuk Super Admin)
            document.getElementById('filterPerusahaan').addEventListener('change', function () {
                const idPerusahaan = this.value;
                // Update kategori dropdown based on selected perusahaan
                loadKategoriOptions(idPerusahaan);
                // Reload data with new filters
                reloadWithFilters();
            });
        <?php endif; ?>
    }


    // Load gudang options based on perusahaan
    function loadGudangOptions(idPerusahaan) {
        $.ajax({
            url: "<?php echo site_url('barang/get_gudang_by_perusahaan'); ?>",
            type: "GET",
            data: { id_perusahaan: idPerusahaan },
            dataType: "json",
            success: function (response) {
                let options = '<option value="">Semua Gudang</option>';
                if (response && Array.isArray(response)) {
                    response.forEach(function (gudang) {
                        options += `<option value="${gudang.id_gudang}">${gudang.nama_gudang}</option>`;
                    });
                }
                document.getElementById('filterGudang').innerHTML = options;
            },
            error: function (xhr, status, error) {
                console.error("Error loading gudang:", error);
                document.getElementById('filterGudang').innerHTML = '<option value="">-- Error --</option>';
            }
        });
    }

    // Load kategori options based on perusahaan
    function loadKategoriOptions(idPerusahaan) {
        $.ajax({
            url: "<?php echo site_url('barang/get_kategori_by_perusahaan'); ?>",
            type: "GET",
            data: { id_perusahaan: idPerusahaan },
            dataType: "json",
            success: function (response) {
                let options = '<option value="">Semua Kategori</option>';
                if (response && Array.isArray(response)) {
                    response.forEach(function (kategori) {
                        options += `<option value="${kategori.id_kategori}">${kategori.nama_kategori}</option>`;
                    });
                }
                document.getElementById('filterKategori').innerHTML = options;
            },
            error: function (xhr, status, error) {
                console.error("Error loading kategori:", error);
                document.getElementById('filterKategori').innerHTML = '<option value="">-- Error --</option>';
            }
        });
    }
    // Reload data with current filters
    function reloadWithFilters() {
        // Reset pagination
        currentPage = 1;
        hasMoreData = true;
        totalItemsLoaded = 0; // Reset counter item

        // Clear existing items
        const barangGrid = document.getElementById('barangGrid');
        barangGrid.innerHTML = '';


        // Show loading indicator
        const loadingIndicator = document.getElementById('loadingIndicator');
        loadingIndicator.style.display = 'block';

        // Remove any existing "no data" message
        const existingNoDataMsg = document.getElementById('noDataMessage');
        if (existingNoDataMsg) {
            existingNoDataMsg.remove();
        }

        // Get filter values
        const searchValue = document.getElementById('searchBarang').value;
        const kategoriValue = document.getElementById('filterKategori').value;
        const statusValue = document.getElementById('filterStatus').value;
        const stokValue = document.getElementById('filterStok').value;
        const gudangValue = document.getElementById('filterGudang').value;
        const sortByValue = document.getElementById('sortBy').value;
        <?php if ($this->session->userdata('id_role') == 5): ?>
            const perusahaanValue = document.getElementById('filterPerusahaan').value;
        <?php endif; ?>

        // Prepare form data
        const formData = new FormData();
        formData.append('page', currentPage);
        formData.append('search', searchValue);
        formData.append('id_kategori', kategoriValue);
        formData.append('status', statusValue);
        formData.append('stock_status', stokValue);
        formData.append('id_gudang', gudangValue);
        formData.append('sort_by', sortByValue);
        <?php if ($this->session->userdata('id_role') == 5): ?>
            formData.append('id_perusahaan', perusahaanValue);
        <?php endif; ?>

        // Send AJAX request
        $.ajax({
            url: '<?php echo site_url("barang/load_more"); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data) {
                console.log('Server response:', data); // Debug log
                if (data.success) {
                    // Check if there are items
                    if (data.html && data.html.length > 0) {
                        // Append new items
                        data.html.forEach(itemHtml => {
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = itemHtml;
                            barangGrid.appendChild(tempDiv.firstElementChild);
                        });
                        // Update variables
                        currentPage = data.current_page;
                        hasMoreData = data.has_more;
                        // Attach event listeners
                        attachEventListenersToItems();
                    } else {
                        // No data found, show message
                        showNoDataMessage();
                    }
                } else {
                    showNotification('Gagal memuat data: ' + (data.message || 'Unknown error'), 'danger');
                }
                isLoading = false;
                loadingIndicator.style.display = 'none';
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.log('Response Text:', xhr.responseText);

                // Try to parse response as JSON to see if there's a specific error message
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response && response.message) {
                        showNotification('Error: ' + response.message, 'danger');
                    } else {
                        showNotification('Terjadi kesalahan saat memuat data', 'danger');
                    }
                } catch (e) {
                    // If response is not JSON, show no data message
                    showNoDataMessage();
                }

                isLoading = false;
                loadingIndicator.style.display = 'none';
            }
        });
    }

    // Function to show "no data" message
    function showNoDataMessage() {
        const barangGrid = document.getElementById('barangGrid');
        const noDataMsg = document.createElement('div');
        noDataMsg.id = 'noDataMessage';
        noDataMsg.className = 'col-12 text-center py-5';
        noDataMsg.innerHTML = `
            <div class="text-muted">
                <i class="fas fa-search fa-3x mb-3"></i>
                <h4>Data tidak tersedia</h4>
                <p>Tidak ada barang yang sesuai dengan filter yang dipilih</p>
            </div>
        `;
        barangGrid.appendChild(noDataMsg);
    }

    // Initialize infinite scroll
    function initializeInfiniteScroll() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const barangGrid = document.getElementById('barangGrid');

        // Load more items function
        function loadMoreItems() {

            if (isLoading || !hasMoreData) return;
            console.log('Loading more items...');
            isLoading = true;
            loadingIndicator.style.display = 'block';

            // Get filter values
            const searchValue = document.getElementById('searchBarang').value;
            const kategoriValue = document.getElementById('filterKategori').value;
            const statusValue = document.getElementById('filterStatus').value;
            const stokValue = document.getElementById('filterStok').value;
            const gudangValue = document.getElementById('filterGudang').value;
            const sortByValue = document.getElementById('sortBy').value;
            <?php if ($this->session->userdata('id_role') == 5): ?>
                const perusahaanValue = document.getElementById('filterPerusahaan').value;
            <?php endif; ?>

            // Prepare form data
            const formData = new FormData();
            formData.append('page', currentPage + 1);
            formData.append('search', searchValue);
            formData.append('id_kategori', kategoriValue);
            formData.append('status', statusValue);
            formData.append('stock_status', stokValue);
            formData.append('id_gudang', gudangValue);
            formData.append('sort_by', sortByValue);
            <?php if ($this->session->userdata('id_role') == 5): ?>
                formData.append('id_perusahaan', perusahaanValue);
            <?php endif; ?>

            // Send AJAX request
            $.ajax({
                url: '<?php echo site_url("barang/load_more"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (data) {
                    console.log('Server response:', data);
                    if (data.success) {
                        if (data.html && data.html.length > 0) {
                            // Append new items
                            data.html.forEach((itemHtml, index) => {
                                const tempDiv = document.createElement('div');
                                tempDiv.innerHTML = itemHtml;

                                // Update nomor urut untuk item baru
                                const newItem = tempDiv.firstElementChild;
                                const nomorUrutElement = newItem.querySelector('.nomor-urut');
                                if (nomorUrutElement) {
                                    nomorUrutElement.textContent = totalItemsLoaded + index + 1;
                                }

                                barangGrid.appendChild(newItem);
                            });

                            // Update variables
                            currentPage = data.current_page;
                            hasMoreData = data.has_more;
                            totalItemsLoaded += data.html.length; // Update counter

                            // Attach event listeners
                            attachEventListenersToNewItems();
                        } else {
                            hasMoreData = false;
                        }
                    } else {
                        showNotification('Gagal memuat data: ' + (data.message || 'Unknown error'), 'danger');
                    }
                    isLoading = false;
                    loadingIndicator.style.display = 'none';
                },
                error: function (xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    hasMoreData = false;
                    isLoading = false;
                    loadingIndicator.style.display = 'none';
                }
            });
        }

        // Scroll event listener
        function handleScroll() {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 1000) {
                loadMoreItems();
            }
        }

        // Add scroll listener with throttle
        let scrollTimeout;
        window.addEventListener('scroll', function () {
            if (!scrollTimeout) {
                scrollTimeout = setTimeout(function () {
                    scrollTimeout = null;
                    handleScroll();
                }, 200);
            }
        });
    }

    function resetPagination() {
        currentPage = 1;
        hasMoreData = true;
        // Remove items beyond first page
        const items = document.querySelectorAll('#barangGrid .barang-item');
        for (let i = itemsPerPage; i < items.length; i++) {
            items[i].remove();
        }
        // Reset counters
        const totalItems = parseInt(document.getElementById('total-count').textContent);
        const initialShowing = Math.min(itemsPerPage, totalItems);
        document.getElementById('showing-count').textContent = initialShowing;
        document.getElementById('current-page').textContent = 1;
    }

    // Filter functionality - kept for compatibility but not used with new reload approach
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

    // Sort functionality
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
                    return bValue - aValue;
                default:
                    return 0;
            }
        });
        // Re-append sorted items
        items.forEach(item => grid.appendChild(item));
    }

    // Attach event listeners to existing items
    function attachEventListenersToItems() {
        // Detail buttons
        document.querySelectorAll('.detail-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation();
                showDetailModal(this.closest('.barang-item'));
            });
        });

        // Card clicks
        document.querySelectorAll('.card-clickable').forEach(card => {
            card.addEventListener('click', function () {
                showDetailModal(this);
            });
        });

        // Input stok buttons
        document.querySelectorAll('.input-stok-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation();
                showInputStokModal(this);
            });
        });
    }

    // Attach event listeners to new items
    function attachEventListenersToNewItems() {
        // Detail buttons
        document.querySelectorAll('.barang-item:not(.attached) .detail-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation();
                showDetailModal(this.closest('.barang-item'));
            });
            button.closest('.barang-item').classList.add('attached');
        });

        // Card clicks
        document.querySelectorAll('.barang-item:not(.attached)').forEach(card => {
            card.addEventListener('click', function () {
                showDetailModal(this);
            });
            card.classList.add('attached');
        });

        // Input stok buttons
        document.querySelectorAll('.barang-item:not(.attached) .input-stok-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                e.stopPropagation();
                showInputStokModal(this);
            });
            button.closest('.barang-item').classList.add('attached');
        });
    }


    // Modal functions (keep existing modal functions)
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

        // Reset gudang options
        document.getElementById('id_gudang').innerHTML = '<option value="">-- Pilih Gudang --</option>';

        // Load gudang options
        if (idPerusahaan) {
            loadGudangOptions(idPerusahaan);
        }

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
                if (response && Array.isArray(response)) {
                    response.forEach(function (gudang) {
                        options += `<option value="${gudang.id_gudang}">${gudang.nama_gudang}</option>`;
                    });
                }
                document.getElementById('id_gudang').innerHTML = options;
            },
            error: function (xhr, status, error) {
                console.error("Error loading gudang:", error);
                document.getElementById('id_gudang').innerHTML = '<option value="">-- Error --</option>';
            }
        });
    }

    // Image fullscreen
    function runImageFullscreenScript() {
        if (window.jQuery && typeof jQuery.fn.modal === 'function') {
            console.log("Initializing image fullscreen script");

            // Klik gambar untuk fullscreen
            jQuery(document).on('click', '.img-clickable', function (e) {
                e.preventDefault();
                e.stopPropagation();
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

    // Initialize image fullscreen
    document.addEventListener('DOMContentLoaded', function () {
        runImageFullscreenScript();
    });
</script>