<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TaniCabai</title>
    <script>
        let appUrl = '{{ env('APP_URL') }}';
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" />

</head>

<body class="font-roboto">
    <!-- Header -->
    @include('Ui.navbar')
    <!-- Hero Section -->
    <main>
        @yield('content')
        <!-- How it works -->
    </main>

    <!-- Footer -->

    @include('Ui.footer')
    <!-- Script: Toggle Mobile Menu -->
    <script>
        document.getElementById('menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"
        integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script>
            function showAlert(message, type = 'success') {
                const alertId = "alert-" + new Date().getTime();
                let bgColor = "",
                    iconClass = "";

                switch (type) {
                    case 'success':
                        bgColor = "bg-green-500";
                        iconClass = "fas fa-check-circle";
                        break;
                    case 'warning':
                        bgColor = "bg-yellow-500";
                        iconClass = "fas fa-exclamation-triangle";
                        break;
                    case 'error':
                        bgColor = "bg-red-500";
                        iconClass = "fas fa-times-circle";
                        break;
                    default:
                        bgColor = "bg-gray-500";
                        iconClass = "fas fa-info-circle";
                }

                const alertDiv = $(`
                <div id="${alertId}" class="fixed top-5 right-5 px-4 py-3 rounded shadow-md text-white text-sm ${bgColor} flex items-center space-x-2 z-50">
                    <i class="${iconClass} text-white"></i>
                    <span>${message}</span>
                </div>
            `);
                $("body").append(alertDiv);
                setTimeout(() => alertDiv.fadeOut(500, () => alertDiv.remove()), 3000);
            }
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '#btnLogout', function() {
                $('#confirmModalLogout').removeClass('hidden');
            });

            $('#cancelBtnLogout').on('click', function() {
                $('#confirmModalLogout').addClass('hidden');
            });
            $('#confirmBtnLogout').click(function(e) {
                $.ajax({
                    url: `/v1/logout`,
                    method: 'POST',
                    dataType: "json",
                    success: function(response) {
                        console.log('Response diterima:', response);

                        if (response.code === 200) {
                            showAlert('Anda berhasil logout!', 'success');
                            location.reload();
                        } else {
                            showAlert('Terjadi kesalahan!', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        showAlert('Terjadi kesalahan!', 'error');
                    },
                    complete: function() {
                        $('#confirmModal').addClass('hidden');
                        selectId = null;
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
