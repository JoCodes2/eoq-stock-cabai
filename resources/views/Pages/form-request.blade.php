@extends('Ui.master')
@section('content')
    @php
        $userId = auth()->user()->id;
        $userName = auth()->user()->nama;
    @endphp

    <div class="bg-red-50 min-h-screen">
        <div class="container mx-auto p-6">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-red-100">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-red-700">Profil Pengguna</h1>
                </div>

                <div class="border-b border-red-200 mb-6">
                    @include('Ui.navbar-profile')
                </div>

                <div class="bg-red-50 p-6 rounded-xl shadow-inner">
                    <h3 class="text-xl font-bold text-red-700 mb-2">🌶️ Form Permintaan Cabai</h3>
                    <p class="text-gray-600 mb-6">Tambahkan beberapa jenis cabai ke dalam daftar permintaan Anda.</p>

                    <form id="form-data">
                        @csrf
                        <input type="hidden" name="pengguna_id" value="{{ $userId }}">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-white p-4 rounded-xl border border-red-200">
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Jenis Cabai</label>
                                <select id="select_produk" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
                                    <option value="" selected disabled>-- Pilih Jenis --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-1">Jumlah (Kg)</label>
                                <input type="number" step="0.01" id="input_jumlah" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none" placeholder="0">
                            </div>
                            <div class="flex items-end">
                                <button type="button" id="add-item" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                        </div>

                        <div class="mb-6 overflow-x-auto">
                            <table class="w-full text-left border-collapse bg-white rounded-lg overflow-hidden shadow-sm">
                                <thead class="bg-red-600 text-white">
                                    <tr>
                                        <th class="p-3">Jenis Cabai</th>
                                        <th class="p-3">Jumlah</th>
                                        <th class="p-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="item-list">
                                    <tr id="empty-row">
                                        <td colspan="3" class="p-4 text-center text-gray-400 italic">Belum ada item yang ditambahkan</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-6">
                            <label for="catatan" class="block text-gray-700 font-medium mb-1">Catatan Tambahan</label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="w-full p-3 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition"
                                placeholder="Contoh: butuh cabai segar, kirim hari ini"></textarea>
                        </div>

                        <div class="text-right">
                            <button type="submit" id="submitBtn" class="bg-red-600 hover:bg-red-700 transition text-white px-8 py-3 rounded-xl shadow-md font-bold text-lg">
                                🚀 Kirim Permintaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // 1. Fungsi Alert Tailwind Custom
    function showAlert(message, type = 'success') {
        const alertId = "alert-" + new Date().getTime();
        let bgColor = "", iconClass = "";

        switch (type) {
            case 'success': bgColor = "bg-green-500"; iconClass = "fas fa-check-circle"; break;
            case 'warning': bgColor = "bg-yellow-500"; iconClass = "fas fa-exclamation-triangle"; break;
            case 'error': bgColor = "bg-red-500"; iconClass = "fas fa-times-circle"; break;
            default: bgColor = "bg-gray-500"; iconClass = "fas fa-info-circle";
        }

        const alertDiv = $(`
            <div id="${alertId}" class="fixed top-5 right-5 px-4 py-3 rounded shadow-md text-white text-sm ${bgColor} flex items-center space-x-2 z-[999]">
                <i class="${iconClass} text-white"></i>
                <span>${message}</span>
            </div>
        `);
        $("body").append(alertDiv);
        setTimeout(() => alertDiv.fadeOut(500, () => alertDiv.remove()), 3000);
    }

    $(document).ready(function() {
        let selectedItems = [];

        // 2. Load Data Master Cabai
        function loadMasterData() {
            $.get('/v1/master', function(response) {
                let options = '<option value="" disabled selected>-- Pilih Jenis --</option>';
                response.data.forEach(item => {
                    options += `<option value="${item.id}" data-nama="${item.nama}" data-harga="${item.harga_jual}">${item.nama} (Stok: ${item.jumlah})</option>`;
                });
                $('#select_produk').html(options);
            });
        }
        loadMasterData();

        // 3. Tambah Item ke List Sementara
        $('#add-item').on('click', function() {
            const productEl = $('#select_produk option:selected');
            const productId = productEl.val();
            const productName = productEl.data('nama');
            const productPrice = productEl.data('harga');
            const jumlah = $('#input_jumlah').val();

            if (!productId || !jumlah || jumlah <= 0) {
                showAlert('Lengkapi jenis cabai dan jumlah!', 'warning');
                return;
            }

            if (selectedItems.some(item => item.master_data_id === productId)) {
                showAlert('Item ini sudah masuk daftar', 'warning');
                return;
            }

            selectedItems.push({
                master_data_id: productId,
                nama_cabai: productName,
                jumlah: parseFloat(jumlah),
                harga_satuan: parseFloat(productPrice)
            });

            renderTable();
            $('#input_jumlah').val('');
            showAlert('Item ditambahkan', 'success');
        });

        function renderTable() {
            let rows = '';
            if (selectedItems.length === 0) {
                rows = '<tr id="empty-row"><td colspan="3" class="p-4 text-center text-gray-400 italic">Belum ada item yang ditambahkan</td></tr>';
            } else {
                selectedItems.forEach((item, index) => {
                    rows += `
                    <tr class="border-b border-gray-100">
                        <td class="p-3 text-gray-800 font-medium">${item.nama_cabai}</td>
                        <td class="p-3 text-gray-800">${item.jumlah} Kg</td>
                        <td class="p-3 text-center">
                            <button type="button" class="text-red-500 hover:text-red-700 remove-item" data-index="${index}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
            }
            $('#item-list').html(rows);
        }

        $(document).on('click', '.remove-item', function() {
            const index = $(this).data('index');
            selectedItems.splice(index, 1);
            renderTable();
            showAlert('Item dihapus', 'warning');
        });

        // 4. Submit Data ke Backend
        $('#form-data').on('submit', function(e) {
            e.preventDefault();

            if (selectedItems.length === 0) {
                showAlert('Daftar permintaan masih kosong!', 'error');
                return;
            }

            const payload = {
                pengguna_id: $('input[name="pengguna_id"]').val(),
                catatan: $('#catatan').val(),
                items: selectedItems
            };

            $.ajax({
                url: '/v1/permintaan/create',
                type: 'POST',
                data: JSON.stringify(payload),
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                success: function(response) {
                    showAlert('Permintaan Berhasil!', 'success');
                    showInvoice(response.data);
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON ? xhr.responseJSON.message : 'Gagal mengirim data';
                    showAlert(msg, 'error');
                }
            });
        });

        function showInvoice(data) {
            const namaPembeli = "{{ $userName }}";
            const nomorAdmin = "6287810216949";
            const totalBerat = selectedItems.reduce((acc, curr) => acc + curr.jumlah, 0);

            const invoiceHtml = `
                <div id="modal-invoice" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-[100] p-4 overflow-y-auto">
                    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full relative">

                        <div id="capture-area" class="p-8 bg-white rounded-xl">
                            <div class="text-center border-b-2 border-dashed pb-4 mb-4">
                                <h2 class="text-2xl font-black text-red-700 uppercase tracking-widest">Invoice Cabai</h2>
                                <p class="text-gray-500 text-xs font-mono">${data.nomor_permintaan}</p>
                            </div>

                            <div class="space-y-1 mb-6 text-sm">
                                <div class="flex justify-between text-gray-600"><span>Nama Pemesan:</span> <span class="font-bold text-gray-800">${namaPembeli}</span></div>
                                <div class="flex justify-between text-gray-600"><span>Tanggal:</span> <span class="font-medium text-gray-800">${new Date().toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})}</span></div>
                                <div class="flex justify-between text-gray-600"><span>Status:</span> <span class="text-yellow-600 font-bold uppercase">${data.status}</span></div>
                            </div>

                            <table class="w-full text-sm mb-4">
                                <thead class="border-b border-gray-100 text-gray-400 text-xs italic">
                                    <tr><th class="py-2 text-left uppercase">Produk</th><th class="py-2 text-right uppercase">Berat</th></tr>
                                </thead>
                                <tbody>
                                    ${selectedItems.map(item => `
                                        <tr class="border-b border-gray-50">
                                            <td class="py-2 text-gray-700">${item.nama_cabai}</td>
                                            <td class="py-2 text-right font-bold text-gray-800">${item.jumlah} Kg</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                                <tfoot>
                                    <tr class="text-gray-800 font-bold italic">
                                        <td class="py-3">Total Berat Estimasi:</td>
                                        <td class="py-3 text-right text-red-600">${totalBerat.toFixed(2)} Kg</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="bg-red-50 p-4 rounded-lg text-right mb-4">
                                <p class="text-[10px] text-red-600 font-bold uppercase tracking-tighter">Total Estimasi Harga</p>
                                <p class="text-2xl font-black text-red-700">Rp ${parseFloat(data.total_harga_nota).toLocaleString('id-ID')}</p>
                            </div>

                            <div class="text-center text-[9px] text-gray-400 italic mt-4">
                                *Simpan gambar ini untuk konfirmasi ke Admin.
                            </div>
                        </div>

                        <div class="p-6 bg-gray-50 rounded-b-xl border-t flex flex-col gap-2">
                            <button id="download-invoice" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 flex items-center justify-center gap-2">
                                <i class="fas fa-file-image"></i> Simpan Gambar
                            </button>
                            <a href="https://wa.me/${nomorAdmin}?text=Halo Admin, saya ingin konfirmasi pesanan *${data.nomor_permintaan}* atas nama *${namaPembeli}* dengan total berat *${totalBerat.toFixed(2)} Kg*."
                               target="_blank"
                               class="w-full bg-green-500 text-white py-3 rounded-lg font-bold hover:bg-green-600 flex items-center justify-center gap-2 text-center">
                                <i class="fab fa-whatsapp"></i> Chat Admin (Konfirmasi)
                            </a>
                            <button onclick="window.location.reload()" class="w-full text-gray-400 py-2 text-xs font-semibold hover:text-red-500 transition">Selesai & Tutup</button>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(invoiceHtml);

            $('#download-invoice').on('click', function() {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Mendownload...');

                html2canvas(document.querySelector("#capture-area"), { scale: 2 }).then(canvas => {
                    const link = document.createElement('a');
                    link.download = `Invoice-${data.nomor_permintaan}.png`;
                    link.href = canvas.toDataURL("image/png");
                    link.click();
                    btn.prop('disabled', false).html('<i class="fas fa-file-image"></i> Simpan Gambar');
                    showAlert('Bukti transaksi tersimpan!', 'success');
                });
            });
        }
    });
</script>
@endsection
