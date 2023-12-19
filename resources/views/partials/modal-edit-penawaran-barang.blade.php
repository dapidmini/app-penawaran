{{--
    popup modal utk menampilkan data barang yg ada di database
    cth penggunaan utk membuat data penawaran baru
--}}
<div class="modal fade" id="modalEditPenawaranDataBarang" tabindex="-1" aria-labelledby="modalEditPenawaranDataBarangLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalEditPenawaranDataBarangLabel">Edit Data Penawaran Barang</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="accordionExample">
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
                                        <input type="hidden" id="slugBarang" value="xxx">
                                    </small>
                                </div>
                                <div class="row mb-2 fs-smaller">
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="harga" class="col-form-label">Harga Jual : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="harga"
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
                                                <label for="diskon" class="col-form-label">Diskon (/satuan) : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="diskon"
                                                    class="form-control form-control-sm"
                                                    placeholder="diskon harga penawaran barang">
                                                <span id="nilaiDiskonSatuan" class="form-text fs-label-sm">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="biaya" class="col-form-label">Biaya (/satuan) : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="biaya"
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
                                                <label for="subtotalJualSatuan" class="col-form-label">Subtotal Jual : </label>
                                            </div>
                                            <div class="col-auto">
                                                <label class="col-form-label" id="subtotalJualSatuan">Rp 0</label>
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
                                                <label for="diskonSubtotal" class="col-form-label">Diskon Subtotal : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="diskonSubtotal"
                                                    class="form-control form-control-sm"
                                                    placeholder="diskon Subtotal harga penawaran barang">
                                                <span id="nilaiDiskonSubtotal" class="form-text fs-label-sm">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="biayaSubtotal" class="col-form-label">Biaya Subtotal : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="biayaSubtotal"
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
                                                <label for="subtotalFinal" class="col-form-label">
                                                    Subtotal Final :
                                                </label>
                                            </div>
                                            <div class="col-auto">
                                                <label class="col-form-label" id="subtotalFinal">Rp 0</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="profit" class="col-form-label">
                                                    Profit : 
                                                </label>
                                            </div>
                                            <div class="col-auto">
                                                <label class="col-form-label" id="profit">Rp 0</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="msgInputBarang" class="mb-3 err-label"></div>

                                <button type="button" class="btn btn-primary" id="btnAddToPenawaran">
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