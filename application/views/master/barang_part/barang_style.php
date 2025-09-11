<!-- CSS Custom -->
<style>
    /* Styling untuk card header dan tombol toggle */
    .card-header {
        background-color: #f8f9fc !important;
        border-bottom: 1px solid #e3e6f0;
        padding: 0.5rem 1rem;
    }

    .card-header h6 {
        color: #4e73df !important;
        margin: 0;
        font-size: 0.9rem;
    }

    /* Styling untuk tombol toggle filter */
    #toggleFilter {
        border-radius: 50% !important;
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border-color: #4e73df !important;
        color: #4e73df !important;
        background-color: transparent !important;
    }

    #toggleFilter:hover {
        background-color: #4e73df !important;
        color: white !important;
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(78, 115, 223, 0.3);
    }

    /* Styling untuk tombol reset filter */
    #resetFilter {
        border-radius: 6px !important;
        transition: all 0.3s ease;
        border-color: #e74a3b !important;
        color: #e74a3b !important;
        background-color: transparent !important;
        font-weight: 500;
    }

    #resetFilter:hover {
        background-color: #e74a3b !important;
        color: white !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(231, 74, 59, 0.3);
    }

    /* Styling untuk input group */
    .input-group {
        position: relative;
    }

    .input-group-text {
        background-color: #f8f9fc;
        border-right: none;
    }

    /* Styling untuk card body pertama (pencarian) */
    .card-body:first-child {
        padding-bottom: 0.5rem !important;
    }

    /* Styling untuk card body kedua (filter tambahan) */
    .card-body:last-child {
        padding-top: 0.5rem !important;
    }

    /* Animasi untuk toggle filter */
    #filterCardBody {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    /* Styling untuk notifikasi */
    .position-fixed {
        z-index: 1050 !important;
    }

    .alert {
        border-radius: 0.35rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    /* Styling untuk notifikasi info */
    .alert-info {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    /* Efek hover untuk card barang */
    .card {
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .barang-item {
        transition: all 0.3s ease;
    }

    .barang-item:hover {
        transform: translateY(-5px);
    }

    .card-clickable {
        cursor: pointer;
    }

    /* Perbaikan badge stok awal */
    .badge-warning {
        background-color: #f0ad4e;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 576px) {
        .card-img-top {
            height: 150px !important;
        }

        .card-title {
            font-size: 0.9rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.775rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Perbaikan untuk mobile */
        #toggleFilter {
            width: 28px;
            height: 28px;
        }

        .card-body .row>div {
            margin-bottom: 0.75rem;
        }
    }

    /* Responsif untuk layar sedang */
    @media (min-width: 577px) and (max-width: 768px) {
        .card-img-top {
            height: 160px !important;
        }
    }

    /* Responsif untuk layar besar */
    @media (min-width: 769px) {
        .card-img-top {
            height: 180px !important;
        }
    }
</style>