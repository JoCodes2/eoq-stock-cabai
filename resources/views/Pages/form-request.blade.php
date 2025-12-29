@extends('Ui.master')
@section('content')
    @php
        $user = auth()->user();
        $userId = $user->id;
        $userName = $user->nama;
    @endphp

    <div class="bg-red-50 min-h-screen">
        <div class="container mx-auto p-6">
            <div class="bg-white p-6 rounded-2xl shadow-lg border border-red-100">

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-red-700">Profil & Permintaan</h1>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="md:col-span-1 bg-white border border-red-100 p-6 rounded-2xl shadow-sm flex flex-col items-center justify-center">
                        <div class="w-20 h-20 bg-red-600 rounded-full flex items-center justify-center text-white mb-4 shadow-lg ring-4 ring-red-50">
                            <span class="text-3xl font-bold">{{ strtoupper(substr($userName, 0, 1)) }}</span>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800 text-center leading-tight">{{ $userName }}</h2>
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-[10px] font-black rounded-full uppercase mt-2 tracking-widest">
                            {{ $user->role }}
                        </span>
                    </div>

                    <div class="md:col-span-3 bg-white border border-red-100 p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-50 pb-2">
                            <h4 class="text-gray-400 uppercase text-xs font-bold tracking-wider">Identitas Akun</h4>
                            <i class="fas fa-shield-alt text-red-300"></i>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Email Terdaftar</p>
                                <p class="text-gray-800 font-semibold truncate">
                                    <i class="fas fa-envelope text-red-400 text-xs mr-1"></i> {{ $user->email }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Nomor Handphone</p>
                                <p class="text-gray-800 font-semibold">
                                    <i class="fas fa-phone text-green-500 text-xs mr-1"></i> {{ $user->no_hp ?? 'Belum diisi' }}
                                </p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Alamat Pengiriman</p>
                                @if($user->alamat)
                                    <p class="text-gray-700 font-medium text-sm leading-relaxed">
                                        <i class="fas fa-map-marker-alt text-red-500 text-xs mr-1"></i> {{ $user->alamat }}
                                    </p>
                                @else
                                    <p class="text-red-400 italic text-xs">
                                        <i class="fas fa-exclamation-circle mr-1"></i> Alamat belum diatur. Mohon lengkapi profil Anda.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-b border-red-200 mb-6">
                    @include('Ui.navbar-profile')
                </div>
                <div class="bg-red-50 p-6 rounded-xl shadow-inner border border-red-100">
                    <div class="mb-6 text-center md:text-left">
                        <h3 class="text-xl font-bold text-red-700 italic">🌶️ FORMULIR PEMESANAN CABAI</h3>
                        <p class="text-gray-500 text-sm">Pastikan berat (Kg) sudah sesuai dengan kebutuhan Anda.</p>
                    </div>

                    <form id="form-data">
                        @csrf
                        <input type="hidden" name="pengguna_id" value="{{ $userId }}">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 bg-white p-5 rounded-xl border border-red-200 shadow-sm">
                            <div>
                                <label class="block text-gray-600 text-[10px] font-black uppercase mb-2">Pilih Jenis Cabai</label>
                                <select id="select_produk" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none text-sm transition appearance-none">
                                    <option value="" selected disabled>-- Pilih Jenis --</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-600 text-[10px] font-black uppercase mb-2">Berat Pesanan (Kg)</label>
                                <input type="number" step="0.01" id="input_jumlah" class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none text-sm transition" placeholder="Contoh: 5.5">
                            </div>
                            <div class="flex items-end">
                                <button type="button" id="add-item" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg font-black transition flex items-center justify-center gap-2 shadow-md active:scale-95 uppercase text-xs">
                                    <i class="fas fa-plus"></i> Tambah ke List
                                </button>
                            </div>
                        </div>

                        <div class="mb-6 overflow-hidden rounded-xl border border-red-200 shadow-sm bg-white">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-red-700 text-white text-[10px] uppercase tracking-tighter">
                                    <tr>
                                        <th class="p-4">Jenis Cabai</th>
                                        <th class="p-4">Jumlah Berat</th>
                                        <th class="p-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="item-list" class="text-sm">
                                    <tr id="empty-row">
                                        <td colspan="3" class="p-10 text-center text-gray-400 italic">
                                            Belum ada item yang ditambahkan.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-6">
                            <label for="catatan" class="block text-gray-700 font-black text-[10px] uppercase mb-2">Catatan Pesanan</label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="w-full p-4 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 transition text-sm"
                                placeholder="Tulis catatan di sini..."></textarea>
                        </div>

                        <div class="flex flex-col md:flex-row justify-between items-center bg-white p-5 rounded-xl border border-red-100 shadow-sm gap-4">
                            <div class="text-gray-400 text-[10px] italic max-w-xs text-center md:text-left">
                                *Harga yang tertera pada nota nantinya adalah estimasi harga pasar saat ini.
                            </div>
                            <button type="submit" id="submitBtn" class="w-full md:w-auto bg-green-600 hover:bg-green-700 transition text-white px-12 py-4 rounded-2xl shadow-xl font-black text-xl flex items-center justify-center gap-3 active:scale-95">
                                🚀 KIRIM SEKARANG
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    // Alert System
    function showAlert(message, type = 'success') {
        const alertId = "alert-" + new Date().getTime();
        const colors = {
            success: "bg-green-600",
            warning: "bg-orange-500",
            error: "bg-red-600"
        };
        const alertDiv = $(`
            <div id="${alertId}" class="fixed top-5 right-5 px-6 py-4 rounded-xl shadow-2xl text-white text-xs ${colors[type]} flex items-center space-x-3 z-[9999] animate-pulse">
                <span><i class="fas fa-bell"></i></span>
                <span class="font-bold">${message}</span>
            </div>
        `);
        $("body").append(alertDiv);
        setTimeout(() => alertDiv.fadeOut(500, () => alertDiv.remove()), 3000);
    }

    $(document).ready(function() {
        let selectedItems = [];

        // Load Produk
        function loadMasterData() {
            $.get('/v1/master', function(response) {
                let options = '<option value="" disabled selected>-- Pilih Jenis --</option>';
                response.data.forEach(item => {
                    options += `<option value="${item.id}" data-nama="${item.nama}" data-harga="${item.harga_jual}">${item.nama} (Stok: ${item.jumlah} Kg)</option>`;
                });
                $('#select_produk').html(options);
            });
        }
        loadMasterData();

        // Add to Temp List
        $('#add-item').on('click', function() {
            const productEl = $('#select_produk option:selected');
            const productId = productEl.val();
            const productName = productEl.data('nama');
            const productPrice = productEl.data('harga');
            const jumlah = $('#input_jumlah').val();

            if (!productId || !jumlah || jumlah <= 0) {
                showAlert('Input tidak valid!', 'warning');
                return;
            }

            if (selectedItems.some(i => i.master_data_id === productId)) {
                showAlert('Sudah ada dalam daftar!', 'warning');
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
            showAlert('Ditambahkan!', 'success');
        });

        function renderTable() {
            let rows = '';
            if (selectedItems.length === 0) {
                rows = '<tr id="empty-row"><td colspan="3" class="p-10 text-center text-gray-400 italic">Daftar kosong.</td></tr>';
            } else {
                selectedItems.forEach((item, index) => {
                    rows += `
                    <tr class="border-b border-gray-50 hover:bg-red-50 transition">
                        <td class="p-4 font-bold text-gray-800">${item.nama_cabai}</td>
                        <td class="p-4 text-gray-700 font-bold">${item.jumlah} Kg</td>
                        <td class="p-4 text-center">
                            <button type="button" class="text-red-400 hover:text-red-600 remove-item" data-index="${index}">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </td>
                    </tr>`;
                });
            }
            $('#item-list').html(rows);
        }

        $(document).on('click', '.remove-item', function() {
            selectedItems.splice($(this).data('index'), 1);
            renderTable();
        });

        // Final Submit
        $('#form-data').on('submit', function(e) {
            e.preventDefault();
            if (selectedItems.length === 0) {
                showAlert('Daftar masih kosong!', 'error');
                return;
            }

            // Validasi kelengkapan profil sebelum order
            @if(!$user->alamat || !$user->no_hp)
                showAlert('Lengkapi Alamat & No HP di profil Anda!', 'error');
                return;
            @endif

            const payload = {
                pengguna_id: $('input[name="pengguna_id"]').val(),
                catatan: $('#catatan').val(),
                items: selectedItems
            };

            $('#submitBtn').prop('disabled', true).text('SEDANG MENGIRIM...');

            $.ajax({
                url: '/v1/permintaan/create',
                type: 'POST',
                data: JSON.stringify(payload),
                contentType: 'application/json',
                headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                success: function(response) {
                    showInvoice(response.data);
                },
                error: function(xhr) {
                    $('#submitBtn').prop('disabled', false).text('🚀 KIRIM SEKARANG');
                    showAlert('Gagal mengirim pesanan', 'error');
                }
            });
        });

        function showInvoice(data) {
            const totalBerat = selectedItems.reduce((acc, curr) => acc + curr.jumlah, 0);
            const invoiceHtml = `
                <div id="modal-invoice" class="fixed inset-0 bg-black/90 flex items-center justify-center z-[9999] p-4 backdrop-blur-md">
                    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
                        <div id="capture-area" class="p-8 bg-white">
                            <div class="text-center border-b-2 border-red-700 pb-4 mb-6">
                                <h2 class="text-2xl font-black text-red-700 italic">NOTA PERMINTAAN</h2>
                                <p class="text-[10px] font-mono text-gray-400 tracking-widest">${data.nomor_permintaan}</p>
                            </div>
                            <div class="text-[10px] font-bold uppercase space-y-1 mb-6 text-gray-500">
                                <div class="flex justify-between"><span>PEMBELI</span> <span class="text-gray-900">${data.user ? data.user.nama : '{{ $userName }}'}</span></div>
                                <div class="flex justify-between"><span>NO HP</span> <span class="text-gray-900">{{ $user->no_hp }}</span></div>
                                <div class="flex justify-between"><span>TANGGAL</span> <span class="text-gray-900">${new Date().toLocaleDateString('id-ID')}</span></div>
                            </div>
                            <table class="w-full text-xs mb-6">
                                <thead class="border-b border-gray-800 text-[9px] font-black italic uppercase">
                                    <tr><th class="py-2 text-left">PRODUK</th><th class="py-2 text-right">BERAT</th></tr>
                                </thead>
                                <tbody>
                                    ${selectedItems.map(item => `
                                        <tr class="border-b border-gray-50">
                                            <td class="py-3 font-bold text-gray-800">${item.nama_cabai}</td>
                                            <td class="py-3 text-right font-black">${item.jumlah} Kg</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                            <div class="bg-red-700 p-5 rounded-2xl text-white shadow-lg shadow-red-200">
                                <p class="text-[9px] font-bold uppercase opacity-70">Estimasi Total</p>
                                <p class="text-3xl font-black italic">Rp ${parseFloat(data.total_harga_nota).toLocaleString('id-ID')}</p>
                            </div>
                        </div>
                        <div class="p-6 bg-gray-50 flex flex-col gap-3">
                            <button id="download-invoice" class="w-full bg-blue-600 text-white py-4 rounded-xl font-black shadow-lg">SIMPAN NOTA</button>
                            <a href="https://wa.me/6287810216949?text=Halo Admin, saya {{ $userName }} konfirmasi pesanan ${data.nomor_permintaan}." target="_blank" class="w-full bg-green-600 text-white py-4 rounded-xl font-black text-center shadow-lg">HUBUNGI ADMIN</a>
                            <button onclick="window.location.reload()" class="text-gray-400 text-xs font-bold py-2">TUTUP</button>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(invoiceHtml);

            $('#download-invoice').on('click', function() {
                html2canvas(document.querySelector("#capture-area")).then(canvas => {
                    const link = document.createElement('a');
                    link.download = `Nota-${data.nomor_permintaan}.png`;
                    link.href = canvas.toDataURL();
                    link.click();
                    showAlert('Berhasil disimpan!', 'success');
                });
            });
        }
    });
</script>
@endsection
