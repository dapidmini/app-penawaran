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
                {{-- <div class="accordion" id="accordionExample"> --}}
                    {{-- <div class="accordion-item"> --}}
                        <div id="groupDetailPenawaranBarang" class="">
                            {{-- <div class="accordion-body"> --}}
                                <div class="mb-3">
                                    <small>
                                        <input type="hidden" id="idBarang" class="plain-text">
                                        <input type="hidden" id="slugBarang" class="plain-text">
                                        <div>Nama Barang : <span id="namaBarang" class="plain-text"></span></div>
                                        <div>Sisa Stok : <span id="stokBarang" class="is-number"></span></div>
                                        <div>Harga Modal : <span id="hargaBarang" class="is-currency"></span></div>
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
                                                <span class="form-text">
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
                                                <span id="diskonSatuanValue" class="form-text is-currency fs-label-sm">Rp 0</span>
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
                                                <span id="biayaSatuanValue" class="form-text is-currency fs-label-sm">Rp 0</span>
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
                                                <label class="col-form-label is-currency" id="subtotalJualOri">Rp 0</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="subtotalModal" class="col-form-label">Subtotal Modal : </label>
                                            </div>
                                            <div class="col-auto">
                                                <label class="col-form-label is-currency" id="subtotalModal">Rp 0</label>
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
                                                <span id="diskonSubtotalValue" class="form-text is-currency fs-label-sm">Rp 0</span>
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
                                                <span id="biayaSubtotalValue" class="form-text is-currency fs-label-sm">Rp 0</span>
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
                                                <label class="col-form-label is-currency" id="subtotalBarang">Rp 0</label>
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
                                                <label class="col-form-label is-currency" id="profitBarang">Rp 0</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="msgInputBarang" class="mb-3 err-label"></div>

                                <button type="button" class="btn btn-primary" id="btnUpdatePenawaran" data-bs-dismiss="modal">
                                    Perbarui Penawaran
                                </button>
                            {{-- </div> --}}
                            {{-- /.accordion-body --}}
                        </div>
                        {{-- /#groupDetailPenawaranBarang --}}
                    {{-- </div> --}}
                    {{-- /.accordion-item --}}
                {{-- </div> --}}
                {{-- /.accordion --}}
            </div>
            {{-- /.modal-body --}}
        </div>
        {{-- /.modal-content --}}
    </div>
</div>