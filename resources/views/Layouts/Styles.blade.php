<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Icons. Uncomment required icon fonts -->
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/fonts/boxicons.css') }}" />

<!-- Core CSS -->
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/css/theme-default.css') }}"
    class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('assets/assets/css/demo.css') }}" />

<style>
    .swal2-container {
        z-index: 9999;
    }
</style>
<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

<!-- Page CSS -->

<!-- Helpers -->
<script src="{{ asset('assets/assets/vendor/js/helpers.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- Helpers -->
<script src="{{ asset('assets/assets/vendor/js/helpers.js') }}"></script>

<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
<script src="{{ asset('assets/assets/js/config.js') }}"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
    }
    .stepper-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }
    .stepper-item::before {
        position: absolute;
        content: "";
        border-bottom: 2px solid #ccc;
        width: 100%;
        top: 20px;
        left: -50%;
        z-index: 0;
    }
    .stepper-item:first-child::before { content: none; }
    .step-counter {
        position: relative;
        z-index: 5;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ccc;
        color: white;
        font-weight: bold;
        margin-bottom: 6px;
    }
    .stepper-item.active .step-counter { background-color: #48ABF7; }
    .stepper-item.completed .step-counter { background-color: #28a745; }
    .stepper-item.completed::before { border-color: #28a745; }
    .step-name { font-size: 12px; color: #666; font-weight: 500; }
    .stepper-item.active .step-name { color: #48ABF7; font-weight: bold; }
</style>
<style>
    /* Styling agar tabel terlihat lebih bersih */
    #tableStokKeluar thead th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 15px 10px;
    }
    #tableStokKeluar tbody td {
        vertical-align: middle;
        padding: 12px 10px;
        border-color: #f8f9fa;
    }
</style>
