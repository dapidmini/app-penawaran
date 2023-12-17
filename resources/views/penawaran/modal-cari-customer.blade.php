{{--
    popup modal utk menampilkan data customer yg ada di database
    cth penggunaan utk membuat data penawaran baru
--}}
<div class="modal fade" id="modalCariCustomer" tabindex="-1" aria-labelledby="modalCariCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalCariCustomerLabel">Pilih Data Customer</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="fw-bold">Detail Customer</div>
                    <div id="detailCustomer">
                        <div>Nama : </div>
                        <div>Alamat : </div>
                        <div>Telepon : </div>
                    </div>
                </div>
                <div class="table-responsive small table-content overflow-visible">
                    <table class="table table-striped table-bordered table-hover table-sm" id="tableDataCustomer">
                        <thead>
                            <tr>
                                <th scope="col" class="fit text-start px-2">#</th>
                                <th scope="col" class="col-6">Nama Customer</th>
                                <th scope="col" class="fit">No. Telepon</th>
                                <th scope="col" class="fit">Email</th>
                                <th scope="col" class="fit">Penawaran Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr data-slug="slug-customer-1">
                                <td data-id="index" data-value="1" class="fit">1.</td>
                                <td data-id="nama" class="px-2">Toko ABC</td>
                                <td data-id="telepon" class="px-2 fit">081222333444</td>
                                <td data-id="penawaran-terakhir" class="px-2 fit">
                                    {{ date('d F Y H:i:s', strtotime($row->latestPenawaran->updated_at)); }}
                                </td>
                            </tr>
                            <tr data-slug="slug-customer-2">
                                <td data-id="index" data-value="1" class="fit">2.</td>
                                <td data-id="nama">Indomaret 123</td>
                                <td data-id="telepon" class="fit">081222333444</td>
                                <td data-id="penawaran-terakhir" class="fit">
                                    {{ date('d F Y H:i:s', strtotime($row->latestPenawaran->updated_at)); }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
