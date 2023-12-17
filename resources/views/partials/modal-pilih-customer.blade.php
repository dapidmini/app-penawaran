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
                <div class="table-responsive small table-content overflow-visible mb-3">
                    <table class="table table-striped table-bordered table-hover table-sm" id="tableDataCustomer" style="max-height: 40vh">
                        <thead>
                            <tr>
                                <th scope="col" class="fit text-start px-2">#</th>
                                <th scope="col" class="col-6">Nama Customer</th>
                                <th scope="col" class="col-2 fit">No. Telepon</th>
                                <th scope="col" class="col-2 fit">Email</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr data-slug="slug-customer-1">
                                <td data-id="index" data-value="1" class="fit">1.</td>
                                <td data-id="nama">Toko ABC</td>
                                <td data-id="telepon" class="fit">081222333444</td>
                                <td data-id="penawaran-terakhir" class="fit">abc.surabay@gmail.com</td>
                            </tr>
                            <tr data-slug="slug-customer-2">
                                <td data-id="index" data-value="1" class="fit">2.</td>
                                <td data-id="nama">Indomaret 123</td>
                                <td data-id="telepon" class="fit">081222333444</td>
                                <td data-id="penawaran-terakhir" class="fit">pdam.sidoarjo@gmail.com</td>
                            </tr>
                        </tbody>
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
