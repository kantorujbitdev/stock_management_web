<?php $this->load->view('template/header') ?>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Top Menu -->
                <?php $this->load->view('template/top_menu') ?>
                <!-- End of Top Menu -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php $this->load->view($content) ?>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <?php $this->load->view('template/footer') ?>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Keluar</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">Yakin ingin keluar dari sistem? Tekan "Logout" untuk melanjutkan.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?php echo site_url('auth/logout') ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('template/js') ?>

    <script>
        $(document).ready(function () {
            // Handle dropdown menus in mobile view
            if ($(window).width() < 992) {
                $('.dropdown-menu a.dropdown-toggle').on('click', function (e) {
                    if (!$(this).next().hasClass('show')) {
                        $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
                    }
                    var $subMenu = $(this).next(".dropdown-menu");
                    $subMenu.toggleClass('show');

                    $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
                        $('.dropdown-submenu .show').removeClass("show");
                    });

                    return false;
                });
            }

            // Handle window resize
            $(window).resize(function () {
                if ($(window).width() >= 992) {
                    $('.dropdown-menu').removeClass('show');
                }
            });

            // Add animation to navbar collapse
            $('.navbar-collapse').on('show.bs.collapse', function () {
                $(this).addClass('showing');
            }).on('shown.bs.collapse', function () {
                $(this).removeClass('showing');
            }).on('hide.bs.collapse', function () {
                $(this).addClass('hiding');
            }).on('hidden.bs.collapse', function () {
                $(this).removeClass('hiding');
            });
        });
    </script>
</body>

</html>