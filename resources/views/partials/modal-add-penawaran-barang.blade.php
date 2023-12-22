{{--
    popup modal utk menampilkan data barang yg ada di database
    cth penggunaan utk membuat data penawaran baru
--}}
<div class="modal fade penawaran-data-barang" id="modalPenawaranDataBarang" tabindex="-1" aria-labelledby="modalPenawaranDataBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalPenawaranDataBarangLabel">Pilih Data Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#groupCariBarang" aria-expanded="true" aria-controls="groupCariBarang">
                                Data Barang
                            </button>
                        </h2>
                        <div id="groupCariBarang" class="accordion-collapse collapse show"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control form-control-sm" placeholder="Cari Barang" id="inputFilterBarang">
                                    <button class="btn btn-outline-secondary" type="button" id="btnFilterBarang">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>

                                <div class="table-responsive small table-content overflow-visible overflow-y-auto"
                                    id="dataBarangWrapper" style="max-height:40vh">
                                    <table class="table table-striped table-bordered table-hover table-sm d-none">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="fit text-start px-2">#</th>
                                                <th scope="col">Nama Barang</th>
                                                <th scope="col" class="fit">Stok</th>
                                                <th scope="col" class="fit">Harga Modal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider"></tbody>
                                    </table>
                                    {{-- /.table --}}
                                    <div class="spinner-border icon-small" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div id="dataBarangMsg"></div>
                                </div>
                                {{-- /.table-responsive.table-content#dataBarangWrapper --}}
                            </div>
                            {{-- /.accordion-body --}}
                        </div>
                        {{-- /#groupCariBarang --}}
                    </div>
                    {{-- /.accordion-item --}}
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#groupDetailPenawaranBarang" aria-expanded="false"
                                aria-controls="groupDetailPenawaranBarang">
                                Detail Penawaran Barang
                            </button>
                        </h2>
                        <div id="groupDetailPenawaranBarang" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="mb-3">
                                    <small>
                                        <div>Nama Barang : <span id="namaBarang"></span></div>
                                        <div>Sisa Stok : <span id="stokBarang"></span></div>
                                        <div>Harga Modal : <span id="hargaBarang"></span></div>
                                        <input type="hidden" id="slugBarang">
                                        <input type="hidden" id="idBarang">
                                    </small>
                                </div>
                                <div class="row mb-2 fs-smaller">
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="hargaJualSatuan" class="col-form-label">Harga Jual : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="hargaJualSatuan"
                                                    class="form-control form-control-sm"
                                                    placeholder="harga penawaran barang">
                                            </div>
                                            <div class="col-auto">
                                                <span id="hargaTextInline" class="form-text">
                                                    / satuan
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="qty" class="col-form-label">Qty : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="qty"
                                                    class="form-control form-control-sm"
                                                    placeholder="jumlah barang penawaran">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-2 fs-smaller">
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto align-items-center">
                                                <label for="diskonSatuanOri" class="col-form-label">Diskon (/satuan) : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="diskonSatuanOri"
                                                    class="form-control form-control-sm"
                                                    placeholder="diskon harga penawaran barang">
                                                <span id="nilaiDiskonSatuan" class="form-text fs-label-sm">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="biayaSatuanOri" class="col-form-label">Biaya (/satuan) : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="biayaSatuanOri"
                                                    class="form-control form-control-sm"
                                                    placeholder="biaya harga penawaran barang">
                                                <span id="nilaiBiayaSatuan" class="form-text fs-label-sm">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="subtotalJualOri" class="col-form-label">Subtotal Jual : </label>
                                            </div>
                                            <div class="col-auto">
                                                <label class="col-form-label" id="subtotalJualOri">Rp 0</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="subtotalModal" class="col-form-label">Subtotal Modal : </label>
                                            </div>
                                            <div class="col-auto">
                                                <label class="col-form-label" id="subtotalModal">Rp 0</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 fs-smaller">
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="diskonSubtotalOri" class="col-form-label">Diskon Subtotal : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="diskonSubtotalOri"
                                                    class="form-control form-control-sm"
                                                    placeholder="diskon Subtotal harga penawaran barang">
                                                <span id="nilaiDiskonSubtotal" class="form-text fs-label-sm">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="biayaSubtotalOri" class="col-form-label">Biaya Subtotal : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="biayaSubtotalOri"
                                                    class="form-control form-control-sm"
                                                    placeholder="biaya Subtotal harga penawaran barang">
                                                <span id="nilaiBiayaSubtotal" class="form-text fs-label-sm">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 fw-bold">
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="subtotalBarang" class="col-form-label">
                                                    Subtotal Final :
                                                </label>
                                            </div>
                                            <div class="col-auto">
                                                <label class="col-form-label" id="subtotalBarang">Rp 0</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="profitBarang" class="col-form-label">
                                                    Profit : 
                                                </label>
                                            </div>
                                            <div class="col-auto">
                                                <label class="col-form-label" id="profitBarang">Rp 0</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="msgInputBarang" class="mb-3 err-label"></div>

                                <button type="button" class="btn btn-primary" id="btnAddToPenawaran" data-bs-dismiss="modal">
                                    Masukkan ke Penawaran
                                </button>
                            </div>
                            {{-- /.accordion-body --}}
                        </div>
                        {{-- /#groupDetailPenawaranBarang --}}
                    </div>
                    {{-- /.accordion-item --}}
                </div>
                {{-- /.accordion --}}
            </div>
            {{-- /.modal-body --}}
        </div>
        {{-- /.modal-content --}}
    </div>
</div>