{{--
    popup modal utk menampilkan data customer yg ada di database
    cth penggunaan utk membuat data penawaran baru
--}}
<div class="modal fade" id="modalPilihCustomer" tabindex="-1" aria-labelledby="modalPilihCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalPilihCustomerLabel">Pilih Data Customer</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="fs-smaller">Cari Nama Customer</div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari Customer" id="inputFilterCustomer">
                    <button class="btn btn-outline-secondary" type="button" id="btnFilterCustomer">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                <div class="table-responsive small table-content overflow-visible mb-3">
                    <table class="table table-striped table-bordered table-hover table-sm" id="tableDataCustomer"
                        style="max-height: 40vh">
                        <thead>
                            <tr>
                                <th scope="col" class="fit text-start px-2">#</th>
                                <th scope="col" class="col-6">Nama Customer</th>
                                <th scope="col" class="col-2 fit">No. Telepon</th>
                                <th scope="col" class="col-2 fit">Email</th>
                                <th scope="col">Penawaran Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider"></tbody>
                    </table>
                </div>
                <div class="mb-3">
                    <div class="fw-bold">Detail Customer</div>
                    @include('partials.detail-pilih-customer')
                </div>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
