@extends('layouts.app')

@section('title', $tableInfo['title'])
@section('page_title', $tableInfo['title'])

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{
        selectedRecord: null,
        modalOpen: false,
        createOpen: {{ (isset($errors) && $errors->any() && old('_token')) ? 'true' : 'false' }},
        editOpen: false,
        editRecord: {},
        deleteOpen: false,
        deleteRecord: null,
        searchQuery: '{{ $search }}',
        searchLoading: false,
        totalRecords: {{ $records->total() }},
        debounceTimer: null,
        abortController: null,
        openDetail(record) {
            this.selectedRecord = record;
            this.modalOpen = true;
        },
        openEdit(record) {
            this.editRecord = Object.assign({}, record);
            this.editOpen = true;
        },
        openDelete(id, label) {
            this.deleteRecord = { id: id, label: label };
            this.deleteOpen = true;
        },
        handleSearchInput() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.performLiveSearch();
            }, 300);
        },
        async performLiveSearch() {
            if (this.abortController) {
                this.abortController.abort();
            }
            this.abortController = new AbortController();
            this.searchLoading = true;
            try {
                const url = '{{ route('table.show', $table) }}?search=' + encodeURIComponent(this.searchQuery);
                const res = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    signal: this.abortController.signal
                });
                if (!res.ok) throw new Error('Search failed');
                const data = await res.json();
                if (this.$refs.tableBody) {
                    this.$refs.tableBody.innerHTML = data.html;
                }
                if (this.$refs.paginationContainer) {
                    this.$refs.paginationContainer.innerHTML = data.pagination || '';
                }
                this.totalRecords = data.total;
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('Live search error:', err);
                }
            } finally {
                this.searchLoading = false;
            }
        },
        clearSearch() {
            this.searchQuery = '';
            this.performLiveSearch();
        }
    }">

    <!-- HEADER SECTION (Matches Screenshot 2 & 3) -->
    <div class="bg-white dark:bg-[#0e1626] rounded-2xl p-5 sm:p-6 border border-slate-200/80 dark:border-slate-800/80 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4 transition-colors">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-100 dark:border-indigo-800/60 text-[#4f46e5] dark:text-indigo-400 flex items-center justify-center text-xl shadow-xs flex-shrink-0">
                <i class="fa-solid {{ $tableInfo['icon'] }}"></i>
            </div>
            <div>
                <div class="flex items-center gap-2.5 flex-wrap">
                    <h2 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ $tableInfo['title'] }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800/60 text-indigo-700 dark:text-indigo-300 text-xs font-bold" x-text="totalRecords + ' Data'">
                        {{ $records->total() }} Data
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $tableInfo['desc'] }}</p>
            </div>
        </div>

        <!-- SEARCH BAR + TAMBAH DATA BUTTON (Live Search On Right) -->
        <div class="flex items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 md:w-64">
                <!-- Search Icon / Loading Spinner -->
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none" x-show="!searchLoading"></i>
                <i class="fa-solid fa-circle-notch fa-spin absolute left-3.5 top-1/2 -translate-y-1/2 text-indigo-500 text-xs pointer-events-none" x-cloak x-show="searchLoading"></i>

                <input type="text" 
                       x-model="searchQuery" 
                       @input="handleSearchInput()"
                       placeholder="Cari dalam tabel ini..." 
                       class="w-full pl-9 pr-8 py-2 bg-slate-50 dark:bg-[#131d31] border border-slate-200 dark:border-slate-700/80 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-[#18233a] transition-all">
                
                <!-- Clear button (X) -->
                <button type="button" 
                        x-cloak 
                        x-show="searchQuery.length > 0" 
                        @click="clearSearch()" 
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <button type="button" 
                    @click="createOpen = true"
                    class="px-4 py-2 bg-[#4f46e5] hover:bg-indigo-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-500/20 inline-flex items-center gap-1.5 whitespace-nowrap cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Tambah {{ $tableInfo['title'] }}</span>
            </button>
        </div>
    </div>

    <!-- MAIN TABLE CARD -->
    <div class="bg-white dark:bg-[#0e1626] rounded-2xl border border-slate-200/80 dark:border-slate-800/80 shadow-2xs overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-[#121c2e] border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">
                        <th class="py-3.5 px-4 sm:px-6 w-16">#ID</th>
                        
                        @if($table === 'alumni')
                            <th class="py-3.5 px-4">FOTO & NAMA ALUMNI</th>
                            <th class="py-3.5 px-4">ANGKATAN & KELAS</th>
                            <th class="py-3.5 px-4">TELEPON / KONTAK</th>
                            <th class="py-3.5 px-4">PEKERJAAN SAAT INI</th>
                        @elseif($table === 'angkatan')
                            <th class="py-3.5 px-4">TAHUN ANGKATAN</th>
                            <th class="py-3.5 px-4">NAMA / JULUKAN ANGKATAN</th>
                            <th class="py-3.5 px-4">TOTAL KELAS</th>
                            <th class="py-3.5 px-4">TOTAL ALUMNI</th>
                        @elseif($table === 'kelas')
                            <th class="py-3.5 px-4">NAMA KELAS</th>
                            <th class="py-3.5 px-4">TAHUN ANGKATAN</th>
                            <th class="py-3.5 px-4">TOTAL SISWA TERDATA</th>
                        @elseif($table === 'users')
                            <th class="py-3.5 px-4">USERNAME</th>
                            <th class="py-3.5 px-4">EMAIL</th>
                            <th class="py-3.5 px-4">ROLE AKSES</th>
                            <th class="py-3.5 px-4">STATUS ALUMNI</th>
                        @elseif($table === 'periode_kepengurusan')
                            <th class="py-3.5 px-4">NAMA PERIODE</th>
                            <th class="py-3.5 px-4">TANGGAL MULAI</th>
                            <th class="py-3.5 px-4">TANGGAL SELESAI</th>
                            <th class="py-3.5 px-4">TOTAL PENGURUS</th>
                        @elseif($table === 'pengurus_alumni')
                            <th class="py-3.5 px-4">NAMA ALUMNI</th>
                            <th class="py-3.5 px-4">JABATAN ORGANISASI</th>
                            <th class="py-3.5 px-4">PERIODE KEPENGURUSAN</th>
                        @elseif($table === 'prestasi_alumni')
                            <th class="py-3.5 px-4">NAMA PRESTASI & PENGHARGAAN</th>
                            <th class="py-3.5 px-4">PEMENANG (ALUMNI)</th>
                            <th class="py-3.5 px-4">TINGKAT & TAHUN</th>
                        @elseif($table === 'artikels')
                            <th class="py-3.5 px-4">COVER</th>
                            <th class="py-3.5 px-4">JUDUL ARTIKEL</th>
                            <th class="py-3.5 px-4">STATUS</th>
                            <th class="py-3.5 px-4">AUTHOR</th>
                        @elseif($table === 'acara')
                            <th class="py-3.5 px-4">BANNER</th>
                            <th class="py-3.5 px-4">NAMA ACARA</th>
                            <th class="py-3.5 px-4">JADWAL PELAKSANAAN</th>
                            <th class="py-3.5 px-4">LOKASI</th>
                        @elseif($table === 'albums')
                            <th class="py-3.5 px-4">COVER</th>
                            <th class="py-3.5 px-4">NAMA ALBUM</th>
                            <th class="py-3.5 px-4">DESKRIPSI</th>
                            <th class="py-3.5 px-4">JUMLAH FOTO</th>
                        @elseif($table === 'galleries')
                            <th class="py-3.5 px-4">PREVIEW FOTO</th>
                            <th class="py-3.5 px-4">ALBUM</th>
                            <th class="py-3.5 px-4">CAPTION FOTO</th>
                        @elseif($table === 'testimonies')
                            <th class="py-3.5 px-4">ALUMNI</th>
                            <th class="py-3.5 px-4">PESAN TESTIMONI</th>
                            <th class="py-3.5 px-4">STATUS</th>
                        @elseif($table === 'contents')
                            <th class="py-3.5 px-4">KEY IDENTIFIER</th>
                            <th class="py-3.5 px-4">JUDUL KONTEN</th>
                            <th class="py-3.5 px-4">ISI KONTEN RINGKAS</th>
                        @endif

                        <th class="py-3.5 px-4 text-center w-28">AKSI</th>
                    </tr>
                </thead>
                <tbody x-ref="tableBody" class="divide-y divide-slate-100 dark:divide-slate-800/70 text-xs sm:text-sm text-slate-700 dark:text-slate-200">
                    @include('tables._rows')
                </tbody>
            </table>
        </div>

        <!-- PAGINATION CONTAINER -->
        <div x-ref="paginationContainer" class="p-4 sm:p-5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-[#0e1626]">
            {{ $records->links() }}
        </div>
    </div>

    <!-- DETAIL RECORD MODAL -->
    <template x-teleport="body">
        <div x-cloak x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
            <div x-show="modalOpen"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modalOpen = false"
                 class="fixed inset-0 bg-slate-950/80 transition-opacity"></div>

            <div x-show="modalOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
                 class="relative w-full max-w-2xl bg-white dark:bg-[#0e1626] rounded-2xl shadow-2xl shadow-slate-950/60 border border-slate-200/90 dark:border-slate-800 overflow-hidden z-10 max-h-[90vh] flex flex-col">
                    
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shadow-xs">
                                <i class="fa-solid fa-database"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-sm">Detail Data</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Tabel: <code class="font-mono text-indigo-600 dark:text-indigo-400 font-semibold">{{ $table }}</code></p>
                            </div>
                        </div>
                        <button @click="modalOpen = false" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    <div class="p-6 max-h-[70vh] overflow-y-auto space-y-4 custom-scrollbar">
                        <template x-if="selectedRecord">
                            <div class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <template x-for="(value, key) in selectedRecord" :key="key">
                                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#131d31] border border-slate-200/70 dark:border-slate-800" x-show="typeof value !== 'object'">
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500" x-text="key"></p>
                                            <p class="text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1 break-words" x-text="value ?? '-'"></p>
                                        </div>
                                    </template>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                                        <i class="fa-solid fa-code text-indigo-500"></i> Raw JSON Output:
                                    </p>
                                    <pre class="p-3.5 bg-slate-900 text-emerald-400 rounded-xl text-[11px] font-mono overflow-x-auto leading-relaxed border border-slate-800" x-text="JSON.stringify(selectedRecord, null, 2)"></pre>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="px-6 py-3.5 border-t border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex justify-end">
                        <button @click="modalOpen = false" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-colors cursor-pointer">
                            Tutup
                        </button>
                    </div>
            </div>
        </div>
    </template>

    <!-- CREATE MODAL -->
    <template x-teleport="body">
        <div x-cloak x-show="createOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
            <div x-show="createOpen"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="createOpen = false"
                 class="fixed inset-0 bg-slate-950/80 transition-opacity"></div>

            <div x-show="createOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
                 class="relative w-full max-w-xl bg-white dark:bg-[#0e1626] rounded-2xl shadow-2xl shadow-slate-950/60 border border-slate-200/90 dark:border-slate-800 overflow-hidden z-10">

                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200/80 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-sm shadow-xs">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-sm">Tambah {{ $tableInfo['title'] }}</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Tabel: <code class="font-mono text-indigo-600 dark:text-indigo-400 font-semibold">{{ $table }}</code></p>
                            </div>
                        </div>
                        <button @click="createOpen = false" type="button" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                            <i class="fa-solid fa-xmark text-base"></i>
                        </button>
                    </div>

                    <form method="POST" action="{{ route('table.store', $table) }}">
                        @csrf
                        <div class="p-6 max-h-[65vh] overflow-y-auto space-y-4 custom-scrollbar">
                            @foreach($formFields as $field)
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                        {{ $field['label'] }} @if($field['required'])<span class="text-rose-500 font-bold">*</span>@endif
                                    </label>

                                    @if($field['type'] === 'select')
                                        <select name="{{ $field['name'] }}" {{ $field['required'] ? 'required' : '' }}
                                                class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">
                                            <option value="">-- Pilih {{ $field['label'] }} --</option>
                                            @foreach($field['options'] ?? [] as $optValue => $optLabel)
                                                <option value="{{ $optValue }}" {{ old($field['name']) == $optValue ? 'selected' : '' }}>{{ $optLabel }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($field['type'] === 'textarea')
                                        <textarea name="{{ $field['name'] }}" rows="3" {{ $field['required'] ? 'required' : '' }}
                                                  class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">{{ old($field['name']) }}</textarea>
                                    @else
                                        <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" value="{{ old($field['name']) }}" {{ $field['required'] ? 'required' : '' }}
                                               class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">
                                    @endif

                                    @error($field['name'])
                                        <p class="text-[11px] text-rose-500 mt-1 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex justify-end gap-2.5">
                            <button type="button" @click="createOpen = false" class="px-4 py-2.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-[#4f46e5] hover:bg-indigo-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-indigo-600/20 inline-flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-check"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </template>

    <!-- EDIT MODAL -->
    <template x-teleport="body">
        <div x-cloak x-show="editOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
            <div x-show="editOpen"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="editOpen = false"
                 class="fixed inset-0 bg-slate-950/80 transition-opacity"></div>

            <div x-show="editOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
                 class="relative w-full max-w-xl bg-white dark:bg-[#0e1626] rounded-2xl shadow-2xl shadow-slate-950/60 border border-slate-200/90 dark:border-slate-800 overflow-hidden z-10">

                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200/80 dark:border-amber-800 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-sm shadow-xs">
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 dark:text-white text-sm">Ubah Data {{ $tableInfo['title'] }}</h3>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Tabel: <code class="font-mono text-amber-600 dark:text-amber-400 font-semibold">{{ $table }}</code> &bull; ID: <span class="font-mono font-bold" x-text="editRecord.id"></span></p>
                        </div>
                    </div>
                    <button @click="editOpen = false" type="button" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center cursor-pointer">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <form method="POST" :action="'{{ url('table/'.$table) }}/' + (editRecord.id || '')">
                    @csrf
                    @method('PUT')
                    <div class="p-6 max-h-[65vh] overflow-y-auto space-y-4 custom-scrollbar">
                        @foreach($formFields as $field)
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                    {{ $field['label'] }} @if($field['required'] && $field['type'] !== 'password')<span class="text-rose-500 font-bold">*</span>@endif
                                </label>

                                @if($field['type'] === 'select')
                                    <select name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}" {{ $field['required'] ? 'required' : '' }}
                                            class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">
                                        <option value="">-- Pilih {{ $field['label'] }} --</option>
                                        @foreach($field['options'] ?? [] as $optValue => $optLabel)
                                            <option value="{{ $optValue }}">{{ $optLabel }}</option>
                                        @endforeach
                                    </select>
                                @elseif($field['type'] === 'textarea')
                                    <textarea name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}" rows="3" {{ $field['required'] ? 'required' : '' }}
                                              class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs"></textarea>
                                @elseif($field['type'] === 'password')
                                    <input type="password" name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}"
                                           placeholder="Kosongkan jika tidak ingin mengganti password"
                                           class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">
                                @else
                                    <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" x-model="editRecord.{{ $field['name'] }}" {{ $field['required'] ? 'required' : '' }}
                                           class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-[#131d31] border border-slate-300 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 focus:bg-white dark:focus:bg-[#18233a] transition-all shadow-xs">
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/80 dark:bg-[#121c2e] flex justify-end gap-2.5">
                        <button type="button" @click="editOpen = false" class="px-4 py-2.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-amber-500/20 inline-flex items-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-check"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- DELETE CONFIRM MODAL -->
    <template x-teleport="body">
        <div x-cloak x-show="deleteOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto" role="dialog" aria-modal="true">
            <div x-show="deleteOpen"
                 x-transition:enter="transition-opacity ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="deleteOpen = false"
                 class="fixed inset-0 bg-slate-950/80 transition-opacity"></div>

            <div x-show="deleteOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-3 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-3 sm:scale-95"
                 class="relative w-full max-w-sm bg-white dark:bg-[#0e1626] rounded-2xl shadow-2xl shadow-slate-950/60 border border-slate-200/90 dark:border-slate-800 overflow-hidden z-10">

                <div class="p-6 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200/80 dark:border-rose-800 text-rose-500 dark:text-rose-400 flex items-center justify-center text-2xl mx-auto mb-4 shadow-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 dark:text-white text-base mb-1.5">Hapus data ini?</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Anda akan menghapus <span class="font-bold text-slate-800 dark:text-white" x-text="deleteRecord?.label"></span> secara permanen dari tabel <code class="font-mono text-indigo-600 dark:text-indigo-400 font-semibold">{{ $table }}</code>. Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>

                <form method="POST" :action="'{{ url('table/'.$table) }}/' + (deleteRecord ? deleteRecord.id : '')" class="px-6 pb-6 flex gap-2.5">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="deleteOpen = false" class="flex-1 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition-all cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-rose-600/20 inline-flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-trash text-xs"></i>
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
