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
                    <h1 class="text-2xl font-bold text-red-700">Riwayat Permintaan</h1>
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

                <div class="border-b border-red-200 mb-8">
                    @include('Ui.navbar-profile')
                </div>

                <div class="space-y-4" id="history-container">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-black text-red-700 uppercase italic tracking-tighter">Daftar Transaksi</h3>
                        <span id="total-badge" class="bg-red-100 text-red-600 text-[10px] px-3 py-1 rounded-full font-bold">Memuat...</span>
                    </div>

                    <div id="history-list" class="grid grid-cols-1 gap-4">
                        <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                            <i class="fas fa-spinner fa-spin text-red-600 text-3xl mb-3"></i>
                            <p class="text-gray-400 font-medium italic">Mengambil riwayat pesanan...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalDetail" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-[9999] p-4 backdrop-blur-md">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                <h3 class="font-black text-red-700 uppercase tracking-widest text-xs">Rincian Nota</h3>
                <button onclick="closeModal()" class="w-8 h-8 flex items-center justify-center rounded-full bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition">&times;</button>
            </div>
            <div id="detailContent" class="p-6 overflow-y-auto flex-1">
                </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        loadHistory();
    });

    function loadHistory() {
        $.ajax({
            url: '/v1/permintaan',
            type: 'GET',
            success: function(res) {
                const data = res.data;
                $('#total-badge').text(data.length + ' Pesanan');

                if (data.length === 0) {
                    $('#history-list').html(`
                        <div class="text-center py-20 bg-gray-50 rounded-2xl">
                            <i class="fas fa-receipt text-gray-200 text-5xl mb-4"></i>
                            <p class="text-gray-400 italic">Belum ada riwayat permintaan.</p>
                        </div>
                    `);
                    return;
                }

                let html = '';
                data.forEach(item => {
                    // Penentuan warna status
                    let statusColor = 'bg-gray-100 text-gray-600';
                    if(item.status === 'menunggu') statusColor = 'bg-orange-100 text-orange-600';
                    if(item.status === 'diproses') statusColor = 'bg-blue-100 text-blue-600';
                    if(item.status === 'selesai')  statusColor = 'bg-green-100 text-green-600';
                    if(item.status === 'ditolak')  statusColor = 'bg-red-100 text-red-600';

                    html += `
                        <div class="bg-white border border-gray-100 p-5 rounded-2xl shadow-sm hover:shadow-md hover:border-red-200 transition group">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600 group-hover:bg-red-600 group-hover:text-white transition">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">${new Date(item.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'})}</p>
                                        <h4 class="font-black text-gray-800">${item.nomor_permintaan}</h4>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between md:justify-end gap-6 border-t md:border-0 pt-3 md:pt-0">
                                    <div class="text-right">
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Estimasi</p>
                                        <p class="font-black text-red-700">Rp ${parseFloat(item.total_harga_nota).toLocaleString('id-ID')}</p>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <span class="${statusColor} px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter">${item.status}</span>
                                        <button onclick="viewDetail('${item.id}')" class="text-[10px] font-bold text-blue-600 hover:underline">Lihat Detail <i class="fas fa-chevron-right ml-1"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#history-list').html(html);
            }
        });
    }

    function viewDetail(id) {
        $('#detailContent').html('<div class="text-center py-10"><i class="fas fa-circle-notch fa-spin text-2xl text-red-600"></i></div>');
        $('#modalDetail').removeClass('hidden').addClass('flex');

        $.get(`/v1/permintaan/show/${id}`, function(res) {
            const data = res.data;
            let itemsHtml = data.items.map(item => `
                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl">
                    <div>
                        <p class="font-black text-gray-800 text-xs uppercase">${item.nama_cabai}</p>
                        <p class="text-[10px] text-gray-400">Harga: Rp ${parseFloat(item.harga_satuan).toLocaleString('id-ID')}/Kg</p>
                    </div>
                    <div class="text-right">
                        <p class="font-black text-gray-900 text-sm">${item.jumlah} Kg</p>
                        <p class="text-[10px] font-bold text-red-600 italic">Rp ${parseFloat(item.total_harga).toLocaleString('id-ID')}</p>
                    </div>
                </div>
            `).join('');

            $('#detailContent').html(`
                <div class="space-y-6">
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Status Permintaan</p>
                        <span class="bg-red-700 text-white px-6 py-1 rounded-full text-[10px] font-black uppercase italic">${data.status}</span>
                    </div>

                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b pb-1">Daftar Item</p>
                        <div class="space-y-2">
                            ${itemsHtml}
                        </div>
                    </div>

                    <div class="bg-red-50 p-5 rounded-2xl border border-red-100 shadow-inner">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black text-red-400 uppercase">Total Pembayaran</span>
                            <span class="text-2xl font-black italic text-red-700">Rp ${parseFloat(data.total_harga_nota).toLocaleString('id-ID')}</span>
                        </div>
                    </div>

                    <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                        <p class="text-[9px] font-black text-yellow-600 uppercase mb-1">Catatan Anda:</p>
                        <p class="text-xs text-yellow-700 italic">"${data.catatan || 'Tidak ada catatan.'}"</p>
                    </div>

                    <div class="pt-4 flex flex-col gap-2">
                        <a href="https://wa.me/6285656735557?text=Halo Admin, saya {{ $userName }} konfirmasi pesanan ${data.nomor_permintaan}." target="_blank" class="w-full bg-green-600 text-white py-4 rounded-xl font-black text-center shadow-lg">HUBUNGI ADMIN</a>
                        <button onclick="closeModal()" class="w-full text-gray-400 text-[10px] font-bold py-2">KEMBALI</button>
                    </div>
                </div>
            `);
        });
    }

    function closeModal() {
        $('#modalDetail').addClass('hidden').removeClass('flex');
    }
</script>
@endsection
