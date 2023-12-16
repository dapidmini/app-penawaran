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
                    <table class="table table-striped table-hover table-sm" id="tableDataCustomer">
                        <thead>
                            <tr>
                                <th scope="col" class="fit text-start px-2">#</th>
                                <th scope="col" class="col-3">Nama Customer</th>
                                <th scope="col" class="col-4">Alamat</th>
                                <th scope="col" class="col-2">No. Telepon</th>
                                <th scope="col" class="col-2">Penawaran Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <tr data-slug="slug-customer-1">
                                <td data-id="index" data-value="1" class="fit">1.</td>
                                <td data-id="nama">Toko ABC</td>
                                <td data-id="alamat">Jalan Ngagel Jaya Selatan no.100, Surabaya</td>
                                <td data-id="telepon">081222333444</td>
                                <td data-id="penawaran-terakhir">22 November 2023</td>
                            </tr>
                            <tr data-slug="slug-customer-2">
                                <td data-id="index" class="fit">2.</td>
                                <td data-id="nama">Indomaret Dinoyo 122</td>
                                <td data-id="alamat">Jalan Raya Dinoyo no.10, Surabaya</td>
                                <td data-id="telepon">081222333444</td>
                                <td data-id="penawaran-terakhir">22 November 2023</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     const modalContainer = document.querySelector('#modalCariCustomer');
    //     const tblCells = modalContainer.querySelectorAll('td');
    //     if (tblCells) {
    //         tblCells.forEach(elem => {
    //             elem.addEventListener('click', function() {
    //                 const tblRow = elem.closest('tr');
    //                 let str = '<div>Nama : ' + tblRow.querySelector('[data-id="nama"]').innerHTML + '</div>'
    //                         + '<div>Alamat : ' + tblRow.querySelector('[data-id="alamat"]').innerHTML + '</div>'
    //                         + '<div>Telepon : ' + tblRow.querySelector('[data-id="telepon"]').innerHTML + '</div>'
    //                         ;
    //                 const containerDetailCustomer = modalContainer.querySelector('#detailCustomer');
    //                 containerDetailCustomer.innerHTML = str;

    //                 str += '<input type="hidden" name="' + tblRow.getAttribute('data-slug') + '">';
    //                 const dataCustomer = document.querySelector('form #dataCustomer');
    //                 dataCustomer.innerHTML = str;
    //             });
    //         });
    //     }
    // });
</script>
