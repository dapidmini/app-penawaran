document.addEventListener('DOMContentLoaded', function() {
    const modalPilihBarangSelector = '.modal.penawaran-data-barang'
    const modalPilihBarangContainer = document.querySelector(modalPilihBarangSelector);

    const accordDetailPenawaranBarangSelector = "#groupDetailPenawaranBarang";

    const formCreatePenawaran = document.querySelector('#formPenawaranCreate');

    // ketika modal ditampilkan, inisialisasi isi tabel data barangnya
    const btnTriggerModalPilihBarang = document.querySelector('[data-bs-target="#'+modalPilihBarangContainer.id+'"]');
    if (btnTriggerModalPilihBarang) {
        btnTriggerModalPilihBarang.addEventListener('click', function() {
            // kosongkan body tabel data barangnya
            const tblContentDataBarang = modalPilihBarangContainer.querySelector('table tbody');
            tblContentDataBarang.innerHTML = '';

            // fetch data barang dr database
            let url = '/barang?fetch=1';
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('data', data);

                    data.forEach((item, index) => {
                        let newRow = tblContentDataBarang.insertRow(-1);
                        let cell1 = newRow.insertCell(0);
                        let cell2 = newRow.insertCell(1);
                        let cell3 = newRow.insertCell(2);
                        let cell4 = newRow.insertCell(3);

                        cell1.innerHTML = (index+1)+'.';
                        cell1.classList.add('fit');
                        cell1.setAttribute('data-id', 'noUrut');
                        cell2.innerHTML = item.nama;
                        cell2.setAttribute('data-id', 'nama');
                        cell3.innerHTML = addThousandSeparator(item.stok);
                        cell3.classList.add('fit', 'text-center');
                        cell3.setAttribute('data-id', 'stok');
                        cell4.innerHTML = 'Rp ' + addThousandSeparator(item.harga);
                        cell4.classList.add('fit', 'text-end');
                        cell4.setAttribute('data-id', 'harga');

                        newRow.setAttribute('data-slug', item.slug);

                        // ketika klik salah satu cell/row di tabel data barang
                        // langsung pindah ke accordion item bagian input data penawaran barang
                        // buat function event listener nya di sini spy eventnya bs lsg dikenali
                        newRow.addEventListener('click', function() {
                            // console.log('clicked item', item.slug);
                            // masukkan datanya ke bagian detail penawaran barang
                            const containerDetailPenawaranBarang = modalPilihBarangContainer.querySelector('#groupDetailPenawaranBarang');
                            // reset semua nilai input
                            containerDetailPenawaranBarang.querySelectorAll('input').forEach(elem => {
                                elem.value = '0';
                            });
                            containerDetailPenawaranBarang.querySelector('#slugBarang').value = item.slug;
                            containerDetailPenawaranBarang.querySelector('#namaBarang').innerHTML = item.nama;
                            containerDetailPenawaranBarang.querySelector('#stokBarang').innerHTML = addThousandSeparator(item.stok);
                            containerDetailPenawaranBarang.querySelector('#hargaBarang').innerHTML = 'Rp ' + addThousandSeparator(item.harga);

                            // buka accordion bagian Detail Penawaran Barang sekaligus tutup accordion bagian Cari Barang
                            modalPilihBarangContainer.querySelector('button[data-bs-target="'+accordDetailPenawaranBarangSelector+'"]').click();
                        });
                    });
                })
                .catch(err => {
                    console.log('error', err);
                });
        });
        // end btnTriggerModalPilihBarang.addEventListener('click'
    }

    modalPilihBarangContainer.querySelector('button[data-bs-target="'+accordDetailPenawaranBarangSelector+'"]').addEventListener('click', function() {
        modalPilihBarangContainer.querySelector('#harga').focus();
    });

    const detailInputElems = modalPilihBarangContainer.querySelectorAll('.form-control');
    detailInputElems.forEach(elem => {
        elem.addEventListener('change', function() {
            adjustElemDetailPenawaran();
        });
    });

    function adjustElemDetailPenawaran()
    {
        const hasilHitung = prosesDataBarang();

        const elemSubtotal = modalPilihBarangContainer.querySelector('#subtotal');
        const elemSubtotalFinal = modalPilihBarangContainer.querySelector('#subtotalFinal');
        const elemProfit = modalPilihBarangContainer.querySelector('#profit');

        elemSubtotal.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.subtotalJual);
        elemSubtotalFinal.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.subtotalJualFinal);
        elemProfit.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.profit);
    }

    function prosesDataBarang()
    {
        let item = getDataBarang();

        // kalau input diskonSatuan diakhiri karakter %
        if (item['diskonSatuan'].slice(-1) == '%') {
            // maka hitung diskonSatuan pakai persentase
            item['diskonSatuan'] = parseInt(removeNonNumeric(item['diskonSatuan']));
            item['diskonSatuan'] = item['hargaJual'] * item['diskonSatuan'] / 100;
        } else {
            // kalau tidak diakhiri karakter %
            // artinya input diskonSatuan tsb adl dlm bentuk rupiah
            item['diskonSatuan'] = parseInt(removeNonNumeric(item['diskonSatuan']));
        }
        // kalau input biayaSatuan diakhiri karakter %
        if (item['biayaSatuan'].slice(-1) == '%') {
            // maka hitung biayaSatuan berdasarkan harga jual
            item['biayaSatuan'] = parseInt(removeNonNumeric(item['biayaSatuan']));
            item['biayaSatuan'] = item['hargaJual'] * item['biayaSatuan'] / 100;
        } else {
            // kalau tidak diakhiri karakter %
            // artinya input biayaSatuan tsb adl dlm bentuk rupiah
            item['biayaSatuan'] = parseInt(removeNonNumeric(item['biayaSatuan']));
        }

        item['subtotalJual'] = parseInt((item.hargaJual - item.diskonSatuan + item.biayaSatuan) * item.qty);

        // kalau input diskonSatuan diakhiri karakter %
        if (item['diskonKumulatif'].slice(-1) == '%') {
            // maka hitung diskonKumulatif pakai persentase
            item['diskonKumulatif'] = parseInt(removeNonNumeric(item['diskonKumulatif']));
            item['diskonKumulatif'] = item['hargaJual'] * item['diskonKumulatif'] / 100;
        } else {
            // kalau tidak diakhiri karakter %
            // artinya input diskonKumulatif tsb adl dlm bentuk rupiah
            item['diskonKumulatif'] = parseInt(removeNonNumeric(item['diskonKumulatif']));
        }
        // kalau input biayaKumulatif diakhiri karakter %
        if (item['biayaKumulatif'].slice(-1) == '%') {
            // maka hitung biayaKumulatif berdasarkan harga jual
            item['biayaKumulatif'] = parseInt(removeNonNumeric(item['biayaKumulatif']));
            item['biayaKumulatif'] = item['hargaJual'] * item['biayaKumulatif'] / 100;
        } else {
            // kalau tidak diakhiri karakter %
            // artinya input biayaKumulatif tsb adl dlm bentuk rupiah
            item['biayaKumulatif'] = parseInt(removeNonNumeric(item['biayaKumulatif']));
        }

        item['subtotalJualFinal'] = parseInt(item.subtotalJual - item.diskonKumulatif + item.biayaKumulatif);

        item['subtotalModal'] = item.hargaModal * item.qty;

        item['profit'] = item.subtotalJualFinal - item.subtotalModal;

        return item;
    }
    // end function prosesDataBarang

    function getDataBarang()
    {
        const namaBarangElem = modalPilihBarangContainer.querySelector('#namaBarang');
        const stokElem = modalPilihBarangContainer.querySelector('#stokBarang');
        const hargaModalElem = modalPilihBarangContainer.querySelector('#hargaBarang');
        const hargaJualElem = modalPilihBarangContainer.querySelector('#harga');
        const qtyElem = modalPilihBarangContainer.querySelector('#qty');
        const diskonSatuan = modalPilihBarangContainer.querySelector('#diskon');
        const biayaSatuan = modalPilihBarangContainer.querySelector('#biaya');

        let item = {
            nama: namaBarangElem.innerHTML.trim(),
            stok: parseInt(removeNonNumeric(stokElem.innerHTML.trim())),
            hargaModal: parseInt(removeNonNumeric(hargaModalElem.innerHTML.trim())),
            hargaJual: parseInt(removeNonNumeric(hargaJualElem.value.trim())),
            qty: parseInt(removeNonNumeric(qtyElem.value.trim())),
            diskonSatuan: diskonSatuan.value.trim(),
            biayaSatuan: biayaSatuan.value.trim(),
            diskonKumulatif: diskonKumulatif.value.trim(),
            biayaKumulatif: biayaKumulatif.value.trim(),
        };

        return item;
    }
    // end function getDataBarang

    const btnAddToPenawaran = modalPilihBarangContainer.querySelector('#btnAddToPenawaran');
    if (btnAddToPenawaran) {
        btnAddToPenawaran.addEventListener('click', function(e) {
            const cekInputBarang = validasiInputBarang();
            console.log('validasi', cekInputBarang);
            if (cekInputBarang.status != 'OK') {
                e.preventDefault();
                e.stopPropagation();
                const msgInputBarang = modalPilihBarangContainer.querySelector('#msgInputBarang');
                msgInputBarang.innerHTML = cekInputBarang.message;
                msgInputBarang.classList.add('is-invalid');
                return false;
            }

            let mytable = document.querySelector('#tableDataBarangPenawaran');
            let tblPenawaranItem = mytable.getElementsByTagName('tbody')[0];

            const hasilHitung = prosesDataBarang();
            console.log('hasil hitung', hasilHitung);
            hasilHitung['no'] = tblPenawaranItem.querySelectorAll('tr').length + 1;

            let newRow = tblPenawaranItem.insertRow(-1);
            let cell1 = newRow.insertCell(0);
            let cell2 = newRow.insertCell(1);
            let cell3 = newRow.insertCell(2);
            let cell4 = newRow.insertCell(3);
            let cell5 = newRow.insertCell(4);
            let cell6 = newRow.insertCell(5);

            cell1.innerHTML = hasilHitung.no + '.';
            cell1.classList.add('fit');
            cell2.innerHTML = '<a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modal-show-item-penawaran" data-slug="lampu-led-12-watt">'
                                + hasilHitung.nama
                            + '</a>';
            cell3.innerHTML = addThousandSeparator(hasilHitung.qty);
            cell3.classList.add('text-center', 'fit');
            cell4.innerHTML = addThousandSeparator(hasilHitung.hargaJual);
            cell4.classList.add('text-end', 'fit');
            cell5.innerHTML = addThousandSeparator(hasilHitung.subtotalJualFinal);
            cell5.classList.add('text-end', 'fit');
            cell6.innerHTML = addThousandSeparator(hasilHitung.profit);
            cell6.classList.add('text-end', 'fit');

            // // buat elemen baru utk menampung data barang yg akan ditawarkan
            // let newInputBarang;
            // newInputBarang = document.createElement('input');
            // newInputBarang.setAttribute('type', 'hidden');
            // newInputBarang.setAttribute('name', 'slugBarang[]');
            // newInputBarang.setAttribute('value', item.slug);
            // newInputBarang = document.createElement('input');
            // newInputBarang.setAttribute('type', 'hidden');
            // newInputBarang.setAttribute('name', 'qtyBarang[]');
            // newInputBarang.setAttribute('value', item.slug);
            // newInputBarang = document.createElement('input');
            // newInputBarang.setAttribute('type', 'hidden');
            // newInputBarang.setAttribute('name', 'hargaBarang[]');
            // newInputBarang.setAttribute('value', item.slug);
            // newInputBarang = document.createElement('input');
            // newInputBarang.setAttribute('type', 'hidden');
            // newInputBarang.setAttribute('name', 'subtotalBarang[]');
            // newInputBarang.setAttribute('value', item.slug);

            // formCreatePenawaran.appendChild(newInputBarang);

            // tutup modal
            const modalCloseBtn = modalPilihBarangContainer.querySelector('[data-bs-dismiss="modal"]');
            modalCloseBtn.click();
        });
    }

    function validasiInputBarang()
    {
        let response = {
            status: 'OK',
            message: '',
        }

        let dataBarang = getDataBarang();

        try {
            if (dataBarang.qty > dataBarang.stok) {
                response.status = 'err';
                throw 'Qty penawaran tidak boleh lebih besar dari Sisa Stok';
            }
            if (dataBarang.qty < 1) {
                response.status = 'err';
                throw 'Qty penawaran minimal 1';
            }
        } catch (error) {
            response.message = error;
        }

        return response;
    }
    // end function validasiInputBarang()

    ////////////////////
    // bagian pilih data customer
    ////////////////////
    // inisialisasi konten tabel data customer
    const modalPilihCustSelector = '.modal#modalPilihCustomer';
    const modalPilihCustContainer = document.querySelector(modalPilihCustSelector);

    // const accordDetailPenawaranBarangSelector = "#groupDetailPenawaranBarang";

    // ketika modal ditampilkan, inisialisasi isi tabel data customer yg ada di database
    const btnTriggerModalPilihCust = document.querySelector('[data-bs-target="#'+modalPilihCustContainer.id+'"]');
    if (btnTriggerModalPilihCust) {
        btnTriggerModalPilihCust.addEventListener('click', function() {
            loadDataCustomer();
        });
        // end btnTriggerModalPilihBarang.addEventListener('click'
    }
    // end inisialisasi tabel data customer saat tampilkan modal

    // ketika klik tombol search di dlm modal pilih data customer, lakukan loadDataCustomer
    const btnFilterCustomer = modalPilihCustContainer.querySelector('#btnFilterCustomer');
    if (btnFilterCustomer) {
        btnFilterCustomer.addEventListener('click', function() {
            let params = {
                search: 'abc',
            }
            loadDataCustomer(params);
        });
    }

    function loadDataCustomer(params={}) {
        // kosongkan body tabel data barangnya
        const tblContentDataCust = modalPilihCustContainer.querySelector('table tbody');
        tblContentDataCust.innerHTML = '';

        // fetch data barang dr database
        let url = '/customer';
        let qsObj = {
            fetch: 1,
        }
        if (typeof params.search != undefined && params.search) {
            qsObj['search'] = params.search.trim();
        }
        let qs = new URLSearchParams(qsObj).toString();
        url = url + '?' + qs;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('data', data);

                data.forEach((item, index) => {
                    let newRow = tblContentDataCust.insertRow(-1);
                    let cell1 = newRow.insertCell(0);
                    let cell2 = newRow.insertCell(1);
                    let cell3 = newRow.insertCell(2);
                    let cell4 = newRow.insertCell(3);
                    let cell5 = newRow.insertCell(4);

                    cell1.innerHTML = (index+1)+'.';
                    cell1.classList.add('fit');
                    cell1.setAttribute('data-id', 'noUrut');
                    cell2.innerHTML = item.nama;
                    cell2.setAttribute('data-id', 'nama');
                    cell2.classList.add('px-2')
                    cell3.innerHTML = item.telepon;
                    cell3.classList.add('px-2', 'fit', 'text-center');
                    cell3.setAttribute('data-id', 'telepon');
                    cell4.innerHTML = item.email;
                    cell4.classList.add('px-2', 'fit', 'text-center');
                    cell4.setAttribute('data-id', 'email');
                    // tampilkan tgl penawaran terakhir yg diajukan kpd customer ini
                    let dateStr = '-';
                    if (item.latest_penawaran_by_cust) {
                        console.log('asdasd');
                        let d = new Date(item.latest_penawaran_by_cust);
                        const options = {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric',
                        }
                        dateStr = d.toLocaleDateString('id-ID', options);
                    }
                    cell5.innerHTML = dateStr;
                    cell5.classList.add('px-2', 'fit', 'text-center');
                    cell5.setAttribute('data-id', 'tglPenawaranTerakhir');

                    newRow.setAttribute('data-slug', item.slug);

                    // ketika klik salah satu cell/row di tabel data customer
                    // masukkan datanya ke bagian detail (#pilihCustomer)
                    newRow.addEventListener('click', function() {
                        // masukkan datanya ke bagian detail penawaran barang
                        console.log('item', item);
                        const detailBarang = item;
                        const containerDetailPilihCust = document.querySelectorAll('.detail-pilih-customer');
                        containerDetailPilihCust.forEach((detailElem) => {
                            detailElem.querySelector('#nama').innerHTML = detailBarang.nama;
                            detailElem.querySelector('#alamat').innerHTML = detailBarang.alamat;
                            detailElem.querySelector('#telepon').innerHTML = detailBarang.telepon;
                            detailElem.querySelector('#email').innerHTML = detailBarang.email;
                            detailElem.querySelector('#slug').value = detailBarang.slug;
                            detailElem.querySelector('#tglPenawaranTerakhir').innerHTML = dateStr;
                        });
                    });
                });
            })
            .catch(err => {
                console.log('error', err);
            });
    }
    // end function loadDataCustomer(params={})

    // end bagian pilih data customer
    ////////////////////
});
