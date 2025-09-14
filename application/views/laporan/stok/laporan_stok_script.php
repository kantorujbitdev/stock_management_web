<script>
    $(document).ready(function () {
        // Initialize filter toggle
        initializeFilterToggle();

        // Initialize filter listeners
        initializeFilterListeners();
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
    }

    // Initialize filter listeners
    function initializeFilterListeners() {
        // Event listener untuk perusahaan change
        $('#id_perusahaan').change(function () {
            var id_perusahaan = $(this).val();

            // Reset gudang and kategori
            $('#id_gudang').html('<option value="">-- Semua Gudang --</option>');
            $('#id_kategori').html('<option value="">-- Semua Kategori --</option>');

            if (id_perusahaan != '') {
                // Load gudang
                $.ajax({
                    url: "<?php echo site_url('laporan_stok/get_gudang_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    dataType: "html",
                    success: function (data) {
                        if (data) {
                            $('#id_gudang').append(data);
                        }
                    }
                });

                // Load kategori
                $.ajax({
                    url: "<?php echo site_url('laporan_stok/get_kategori_by_perusahaan') ?>",
                    method: "POST",
                    data: { id_perusahaan: id_perusahaan },
                    dataType: "html",
                    success: function (data) {
                        if (data) {
                            $('#id_kategori').append(data);
                        }
                    }
                });
            }

            // Submit form automatically
            $('#filterForm').submit();
        });

        // Event listener untuk gudang change
        $('#id_gudang').change(function () {
            $('#filterForm').submit();
        });

        // Event listener untuk kategori change
        $('#id_kategori').change(function () {
            $('#filterForm').submit();
        });

        // Event listener untuk status stok change
        $('#stock_status').change(function () {
            $('#filterForm').submit();
        });

        // Event listener untuk reset filter
        $('#resetFilter').click(function () {
            // Reset semua dropdown
            $('#id_perusahaan').val('');
            $('#id_gudang').html('<option value="">-- Semua Gudang --</option>');
            $('#id_kategori').html('<option value="">-- Semua Kategori --</option>');
            $('#stock_status').val('');

            // Submit form
            $('#filterForm').submit();
        });
    }
</script>