{{--
    popup modal utk menampilkan data barang yg ada di database
    cth penggunaan utk membuat data penawaran baru
--}}
<div class="modal fade" id="modalPenawaranDataBarang" tabindex="-1" aria-labelledby="modalPenawaranDataBarangLabel" aria-hidden="true">
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
                                Cari Barang
                            </button>
                        </h2>
                        <div id="groupCariBarang" class="accordion-collapse collapse show"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="table-responsive small table-content overflow-visible"
                                    id="containerPilihBarang">
                                    <table class="table table-striped table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="fit text-start px-2">#</th>
                                                <th scope="col">Nama Barang</th>
                                                <th scope="col" class="fit">Stok</th>
                                                <th scope="col" class="fit">Harga Modal</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider">
                                            {{-- <tr data-slug="slug-barang-1">
                                                <td data-id="index" data-value="1" class="fit">1.</td>
                                                <td data-id="nama">Lampu LED 12 watt</td>
                                                <td data-id="stok" class="fit">25</td>
                                                <td data-id="harga" class="fit">Rp 20.000</td>
                                            </tr>
                                            <tr data-slug="slug-barang-2">
                                                <td data-id="index" class="fit">2.</td>
                                                <td data-id="nama" class="fit">CCTV Panasonic</td>
                                                <td data-id="stok" class="fit">50</td>
                                                <td data-id="harga" class="fit">Rp 150.000</td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                    {{-- /.table --}}
                                </div>
                                {{-- /.table-responsive.table-content#containerPilihBarang --}}
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
                                            <div class="col-auto">
                                                <label for="diskon" class="col-form-label">Diskon : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="diskon"
                                                    class="form-control form-control-sm"
                                                    placeholder="diskon harga penawaran barang">
                                            </div>
                                            <div class="col-auto">
                                                <span id="diskonTextInline" class="form-text">
                                                    / satuan
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="biaya" class="col-form-label">Biaya : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="biaya"
                                                    class="form-control form-control-sm"
                                                    placeholder="biaya harga penawaran barang">
                                            </div>
                                            <div class="col-auto">
                                                <span id="biayaTextInline" class="form-text">
                                                    / satuan
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="subtotal" class="col-form-label">Subtotal : </label>
                                            </div>
                                            <div class="col-auto">
                                                <label class="col-form-label" id="subtotal">Rp 0</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 fs-smaller">
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="diskonKumulatif" class="col-form-label">Diskon Kumulatif : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="diskonKumulatif"
                                                    class="form-control form-control-sm"
                                                    placeholder="diskon Kumulatif harga penawaran barang">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                <label for="biayaKumulatif" class="col-form-label">Biaya Kumulatif : </label>
                                            </div>
                                            <div class="col-auto">
                                                <input type="text" data-type="number" id="biayaKumulatif"
                                                    class="form-control form-control-sm"
                                                    placeholder="biaya Kumulatif harga penawaran barang">
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
            </div>
        </div>
    </div>
