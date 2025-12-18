 <!-- build:js assets/vendor/js/core.js -->
 <script src="{{ asset('assets/assets/vendor/libs/jquery/jquery.js') }}"></script>
 <script src="{{ asset('assets/assets/vendor/libs/popper/popper.js') }}"></script>
 <script src="{{ asset('assets/assets/vendor/js/bootstrap.js') }}"></script>
 <script src="{{ asset('assets/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

 <script src="{{ asset('assets/assets/vendor/js/menu.js') }}"></script>
 <!-- endbuild -->

 <!-- Vendors JS -->
 <script src="{{ asset('assets/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
 <!-- jQuery Validate -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.21.0/jquery.validate.min.js"
     integrity="sha512-KFHXdr2oObHKI9w4Hv1XPKc898mE4kgYx58oqsc/JqqdLMDI4YjOLzom+EMlW8HFUd0QfjfAvxSL6sEq/a42fQ=="
     crossorigin="anonymous" referrerpolicy="no-referrer"></script>

 <!-- Main JS -->
 <script src="{{ asset('assets/assets/js/main.js') }}"></script>

 <!-- Page JS -->
 <script src="{{ asset('assets/assets/js/dashboards-analytics.js') }}"></script>

 <!-- Place this tag in your head or just before your close body tag. -->
 <script async defer src="https://buttons.github.io/buttons.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script>
     const urlLogout = 'auth/logout'
     $(document).ready(function() {
         $('#btnLogout').click(function(e) {
             Swal.fire({
                 title: 'Yakin ingin Logout?',
                 icon: 'question',
                 showCancelButton: true,
                 confirmButtonText: 'Yes',
                 cancelButtonText: 'Cancel'
             }).then((result) => {
                 if (result.isConfirmed) {
                     e.preventDefault();
                     $.ajax({
                         url: `{{ url('v1/logout') }}`,
                         method: 'POST',
                         dataType: 'json',
                         headers: {
                             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                         },
                         success: function(response) {
                             console.log(response);
                             window.location.href = '/login';
                         },
                         error: function(xhr, status, error) {
                             alert('Error: Gagal logout. Silakan coba lagi.');
                         }
                     });
                 }
             });

         });
     });
 </script>
