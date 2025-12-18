@extends('Layouts.Base')

@section('content')
    <div class="container mt-4">
        <div class="row" id="dashboard-cards">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3 shadow">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-user-shield fa-2x me-2"></i>
                        <span>Total Admin</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white fw-bold" id="admin-count">0</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3 shadow">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-truck fa-2x me-2"></i>
                        <span>Total Supplier</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white fw-bold" id="supplier-count">0</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3 shadow">
                    <div class="card-header d-flex align-items-center">
                        <i class="fas fa-store fa-2x me-2"></i>
                        <span>Total Market</span>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title text-white fw-bold" id="market-count">0</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Ambil data dashboard dari API
            $.ajax({
                url: '/v1/dashboard',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#admin-count').text(response.data.admin);
                        $('#supplier-count').text(response.data.supplier);
                        $('#market-count').text(response.data.market);
                    } else {
                        console.error('Gagal mengambil data dashboard');
                    }
                },
                error: function(xhr) {
                    console.error('Terjadi kesalahan saat mengambil data:', xhr.responseText);
                }
            });
        });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-title {
            color: #fff;
            font-weight: bold;
            font-size: 2rem;
        }
    </style>
@endsection
