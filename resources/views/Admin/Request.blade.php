@extends('Layouts.Base')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between border-bottom">
            <h3 class="m-0 font-weight-bold text-dark">
                <i class="fa-solid fa-pepper-hot pr-2 text-danger"></i> Data Permintaan Cabai
            </h3>
        </div>

        <div class="card-body bg-white py-2">
            <div class="py-2">
                <h6>Daftar Permintaan</h6>
               <div class="table-responsive">
                   <table id="tablePermintaan" class="table table-hover w-100">
                       <thead class="table-light">
                           <tr>
                               <th class="border-0">No</th>
                               <th class="border-0">Tanggal</th>
                               <th class="border-0">No. Nota</th>
                               <th class="border-0">Nama Pembeli</th>
                               <th class="border-0">Total Harga</th>
                               <th class="border-0">Status</th>
                               <th class="border-0 text-center">Aksi</th>
                           </tr>
                       </thead>
                       <tbody class="bg-white text-dark"></tbody>
                   </table>
               </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="upsertDataModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-white border-bottom py-3">
                    <h5 class="modal-title font-weight-bold text-dark" id="modalTitle">Rincian Permintaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body bg-white p-4" id="modalBodyPayload">
                    </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script>
    /* UTILS ALERT (Sesuai Permintaan) */
    function successAlert(message) {
        Swal.fire({
            title: 'Berhasil!',
            text: message,
            icon: 'success',
            showConfirmButton: false,
            timer: 1000,
        })
    }

    // Alert untuk Error/Peringatan
    function errorAlert(message = 'Terjadi kesalahan!', icon = 'error') {
        Swal.fire({
            title: icon === 'warning' ? 'Perhatian!' : 'Error',
            text: message,
            icon: icon,
            showConfirmButton: icon === 'warning',
            confirmButtonColor: '#48ABF7',
            timer: icon === 'warning' ? null : 1500,
        });
    }

    function reloadBrowsers() {
        setTimeout(function() {
            location.reload();
        }, 1500);
    }

    function confirmAlert(message, callback) {
        Swal.fire({
            title: '<span style="font-size: 22px"> Konfirmasi!</span>',
            html: message,
            showCancelButton: true,
            showConfirmButton: true,
            cancelButtonText: 'Tidak',
            confirmButtonText: 'Ya',
            reverseButtons: true,
            confirmButtonColor: '#48ABF7',
            cancelButtonColor: '#EFEFEF',
            customClass: {
                cancelButton: 'text-dark'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }

    function loadingAllert() {
        Swal.fire({
            title: 'Loading...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    $(document).ready(function() {
        // Inisialisasi DataTable
        const table = $('#tablePermintaan').DataTable({
            processing: true,
            serverSide: false, // Karena serverSide false, pastikan Controller mengembalikan semua data sekaligus
            ajax: {
                url: '/v1/permintaan',
                type: 'GET',
                // Perbaikan utama: Mengambil array dari properti "data" di JSON response
                dataSrc: function(json) {
                    if (json.data) {
                        return json.data;
                    } else {
                        return json; // Jika response API langsung berupa array
                    }
                }
            },
            columns: [
                {
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1
                },
                {
                    data: 'created_at',
                    render: (data) => data ? new Date(data).toLocaleDateString('id-ID') : '-'
                },
                {
                    data: 'nomor_permintaan',
                    className: 'fw-bold text-dark'
                },
                {
                    data: 'user.nama',
                    defaultContent: '<span class="text-muted">Umum</span>'
                },
                {
                    data: 'total_harga_nota',
                    render: (d) => `Rp ${parseFloat(d || 0).toLocaleString('id-ID')}`
                },
                {
                    data: 'status',
                    render: function(data) {
                        let cls = 'bg-light text-dark border';
                        if(data === 'menunggu') cls = 'bg-warning text-dark';
                        if(data === 'diproses') cls = 'bg-info text-white';
                        if(data === 'dikirim')  cls = 'bg-primary text-white';
                        if(data === 'selesai')  cls = 'bg-success text-white';
                        if(data === 'ditolak')  cls = 'bg-danger text-white';
                        return `<span class="badge ${cls} px-3 py-2 text-uppercase" style="font-size: 11px;">${data}</span>`;
                    }
                },
                {
                    data: 'id',
                    className: 'text-center',
                    render: function(id) {
                        return `<button class="btn btn-sm btn-outline-dark btn-detail" data-id="${id}">
                                    <i class="fa fa-file-invoice me-1"></i> Periksa
                                </button>`;
                    }
                }
            ]
        });

        // Handler Detail Modal
        $(document).on('click', '.btn-detail', function() {
            const id = $(this).data('id');
            $('#modalBodyPayload').html('<div class="text-center p-5"><div class="spinner-border text-secondary"></div></div>');
            $('#upsertDataModal').modal('show');

            $.get(`/v1/permintaan/show/${id}`, function(res) {
                const data = res.data;

                // --- 1. LOGIKA STEPPER CHECKPOINT ---
                const statuses = ['menunggu', 'diproses', 'dikirim', 'selesai'];
                let stepperHtml = '<div class="stepper-wrapper mb-4">';

                statuses.forEach((s, index) => {
                    let currentIdx = statuses.indexOf(data.status);
                    let stateClass = "";

                    // Jika status saat ini adalah ditolak, stepper tidak relevan (opsional bisa dihandle)
                    if (data.status === 'ditolak') {
                        stateClass = (s === 'menunggu') ? 'active' : '';
                    } else {
                        if (index < currentIdx || data.status === 'selesai') stateClass = "completed";
                        else if (index === currentIdx) stateClass = "active";
                    }

                    let iconContent = (stateClass === "completed") ? '<i class="fa fa-check"></i>' : index + 1;

                    stepperHtml += `
                        <div class="stepper-item ${stateClass}">
                            <div class="step-counter">${iconContent}</div>
                            <div class="step-name text-uppercase" style="font-size: 10px;">${s}</div>
                        </div>`;
                });
                stepperHtml += '</div>';

                // --- 2. LOGIKA TABEL ITEM ---
                let itemsHtml = '';
                data.items.forEach(item => {
                    itemsHtml += `
                        <tr class="border-bottom text-dark">
                            <td class="py-3">
                                <span class="fw-bold">${item.nama_cabai}</span>
                                <small class="text-muted d-block">ID: ${item.master_data_id}</small>
                            </td>
                            <td class="py-3 text-center">${item.jumlah} ${item.satuan}</td>
                            <td class="py-3 text-end">Rp ${parseFloat(item.harga_satuan).toLocaleString('id-ID')}</td>
                            <td class="py-3 text-end fw-bold">Rp ${parseFloat(item.total_harga).toLocaleString('id-ID')}</td>
                        </tr>`;
                });

                // --- 3. LOGIKA TOMBOL AKSI (ACTION AREA) ---
                let actionArea = '';
                if(data.status === 'menunggu') {
                    actionArea = `
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button onclick="procStatus('${id}', 'ditolak')" class="btn btn-light border px-4">Tolak Pesanan</button>
                            <button onclick="procStatus('${id}', 'diproses')" class="btn btn-dark px-4 fw-bold">Proses & Cek Stok</button>
                        </div>`;
                } else if (data.status === 'diproses') {
                    actionArea = `
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button onclick="procStatus('${id}', 'dikirim')" class="btn btn-primary px-4 fw-bold text-white">Kirim Sekarang</button>
                        </div>`;
                } else if (data.status === 'dikirim') {
                    actionArea = `
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button onclick="procStatus('${id}', 'selesai')" class="btn btn-success px-4 fw-bold text-white">Selesaikan</button>
                        </div>`;
                } else if (data.status === 'ditolak') {
                    actionArea = `<div class="alert alert-danger mt-4 mb-0 text-center fw-bold">PESANAN INI TELAH DITOLAK</div>`;
                } else {
                    actionArea = `<div class="alert alert-success mt-4 mb-0 text-center fw-bold">PESANAN TELAH SELESAI</div>`;
                }

                // --- 4. RENDER SEMUA KE MODAL ---
                $('#modalBodyPayload').html(`
                    ${stepperHtml}

                    <div class="row mb-4">
                        <div class="col-6">
                            <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="letter-spacing: 1px;">Nomor Nota</small>
                            <h5 class="fw-bold text-dark mb-0">${data.nomor_permintaan}</h5>
                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> ${new Date(data.created_at).toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric'})}</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="letter-spacing: 1px;">Pembeli</small>
                            <h5 class="fw-bold text-dark mb-0">${data.user.nama}</h5>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="border-bottom text-muted small text-uppercase">
                                <tr>
                                    <th class="py-2">Produk Cabai</th>
                                    <th class="py-2 text-center">Qty</th>
                                    <th class="py-2 text-end">Harga</th>
                                    <th class="py-2 text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>${itemsHtml}</tbody>
                        </table>
                    </div>

                    <div class="mt-3 p-3 bg-light border rounded d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block">TOTAL PEMBAYARAN</span>
                        </div>
                        <div class="text-end">
                            <span class="h4 mb-0 fw-bold text-primary">Rp ${parseFloat(data.total_harga_nota).toLocaleString('id-ID')}</span>
                        </div>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted d-block fw-bold small text-uppercase">Catatan Pembeli:</small>
                        <p class="mb-0 text-dark p-2 bg-white border rounded mt-1 italic" style="font-size: 13px; min-height: 40px;">
                            ${data.catatan ? data.catatan : '<span class="text-muted small">Tidak ada catatan.</span>'}
                        </p>
                    </div>

                    ${actionArea}
                `);
            });
        });
    });

    // FUNGSI UPDATE STATUS
    function procStatus(id, status) {
        let msg = "";
        if (status === 'diproses') msg = "Sistem akan mengecek ketersediaan stok di gudang. Lanjutkan?";
        if (status === 'dikirim')  msg = "Pastikan kurir sudah siap?";
        if (status === 'selesai')  msg = "Pesanan selesai. <b>Stok akan otomatis dipotong</b> dari gudang.";
        if (status === 'ditolak')  msg = "Batalkan pesanan ini?";

        confirmAlert(msg, () => {
            loadingAllert();
            $.ajax({
                url: `/v1/permintaan/update-status/${id}`,
                type: 'PATCH',
                data: { status: status, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    Swal.close();
                    successAlert(res.message);
                    $('#upsertDataModal').modal('hide');
                    $('#tablePermintaan').DataTable().ajax.reload();
                },
               error: function(err) {
                    Swal.close();
                    const res = err.responseJSON;
                    const message = res ? res.message : 'Terjadi kesalahan sistem';
                    const code = res ? res.code : 'UNKNOWN';

                    if (code === 'STOCK_INSUFFICIENT') {
                        errorAlert(message, 'warning');
                    } else {
                        errorAlert(message, 'error');
                    }
                }
            });
        });
    }
</script>
@endsection
