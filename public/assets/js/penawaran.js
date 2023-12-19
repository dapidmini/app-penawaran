document.addEventListener('DOMContentLoaded', function() {
    const modalPilihBarangSelector = '.modal.penawaran-data-barang'
    const modalPilihBarangContainer = document.querySelector(modalPilihBarangSelector);

    const accordDetailPenawaranBarangSelector = "#groupDetailPenawaranBarang";

    const formCreatePenawaran = document.querySelector('#formPenawaranCreate');

    // saat halaman create penawaran diload
    // reset isi tabel list data barangnya
    const tblDataBarangPenawaranBody = document.querySelector('#tableDataBarangPenawaran tbody');
    tblDataBarangPenawaranBody.innerHTML = '';

    // ketika modal ditampilkan, inisialisasi isi tabel data barangnya
    const btnTriggerModalPilihBarang = document.querySelector('[data-bs-target="#'+modalPilihBarangContainer.id+'"]');
    if (btnTriggerModalPilihBarang) {
        btnTriggerModalPilihBarang.addEventListener('click', function() {
            // saat modal ini dibuka, selalu buat spy accordion bagian data barang yg dibuka
            const btnAccordionGroupCariBarang = modalPilihBarangContainer.querySelector('.accordion [data-bs-target="#groupCariBarang"]');
            if (btnAccordionGroupCariBarang) {
                if (btnAccordionGroupCariBarang.classList.contains('collapsed')) {
                    btnAccordionGroupCariBarang.click();
                }
            }
            loadDataBarang();
        });
        // end btnTriggerModalPilihBarang.addEventListener('click'
    }
    // end if (btnTriggerModalPilihBarang)

    // ketika klik tombol #btnFilterBarang, reload isi tabel data barangnya
    // berdasarkan keyword filter pencarian
    const btnFilterBarang = modalPilihBarangContainer.querySelector('#btnFilterBarang');
    if (btnFilterBarang) {
        btnFilterBarang.addEventListener('click', function() {
            let params = {
                search: modalPilihBarangContainer.querySelector('#inputFilterBarang').value,
            }
            loadDataBarang(params);
        });
    }

    function loadDataBarang(params={})
    {
        // kosongkan body tabel data barangnya
        const tblContentDataBarang = modalPilihBarangContainer.querySelector('table tbody');
        tblContentDataBarang.innerHTML = '';

        // fetch data barang dr database
        let url = '/barang';
        let qsObj = {
            fetch: 1
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
                        containerDetailPenawaranBarang.querySelector('#subtotalJualSatuan').innerHTML = 'Rp 0';
                        containerDetailPenawaranBarang.querySelector('#subtotalFinal').innerHTML = 'Rp 0';
                        containerDetailPenawaranBarang.querySelector('#profit').innerHTML = 'Rp 0';

                        // buka accordion bagian Detail Penawaran Barang sekaligus tutup accordion bagian Cari Barang
                        modalPilihBarangContainer.querySelector('button[data-bs-target="'+accordDetailPenawaranBarangSelector+'"]').click();
                    });
                });
            })
            .catch(err => {
                console.log('error', err);
            });
    }
    // end function loadDataBarang

    modalPilihBarangContainer.querySelector('button[data-bs-target="'+accordDetailPenawaranBarangSelector+'"]').addEventListener('click', function() {
        modalPilihBarangContainer.querySelector('#harga').focus();
    });

    const detailInputElems = modalPilihBarangContainer.querySelectorAll('.form-control');
    detailInputElems.forEach(elem => {
        elem.addEventListener('change', function() {
            adjustElemDetailPenawaran();
        });
    });

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

            const hasilHitung = hitungDataBarang();
            console.log('hasil hitung', hasilHitung);
            hasilHitung['no'] = tblPenawaranItem.querySelectorAll('tr').length + 1;

            let newRow = tblPenawaranItem.insertRow(-1);
            newRow.setAttribute('data-field', 'slug');
            newRow.setAttribute('data-fvalue', hasilHitung.slug);

            let cell1 = newRow.insertCell(0);
            cell1.innerHTML = hasilHitung.no + '.';
            cell1.classList.add('fit');
            let cell2 = newRow.insertCell(1);
            cell2.innerHTML = '<a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modal-show-item-penawaran" data-slug="lampu-led-12-watt">'
                                + hasilHitung.nama
                            + '</a>';
            let cell3 = newRow.insertCell(2);
            cell3.innerHTML = addThousandSeparator(hasilHitung.qty);
            cell3.classList.add('text-center', 'fit');
            let cell4 = newRow.insertCell(3);
            cell4.innerHTML = addThousandSeparator(hasilHitung.hargaJual);
            cell4.classList.add('text-end', 'fit');
            let cell5 = newRow.insertCell(4);
            cell5.innerHTML = addThousandSeparator(hasilHitung.subtotalJualBarang);
            cell5.classList.add('text-end', 'fit');
            let cell6 = newRow.insertCell(5);
            cell6.innerHTML = addThousandSeparator(hasilHitung.profit);
            cell6.classList.add('text-end', 'fit');
            let cell7 = newRow.insertCell(6);
            cell7.innerHTML = '<button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#modalEditPenawaranDataBarang">'
                +   '<i class="bi bi-pencil-square"></i>'
                + '</button>';
            cell7.innerHTML += '<button class="btn btn-sm btn-danger delete-row-data-barang">'
                +   '<i class="bi bi-trash-fill"></i>'
                + '</button>';
            cell7.classList.add('text-end', 'fit');

            // buat elemen baru berupa input type hidden utk menampung data barang yg akan ditawarkan
            // utk dikirimkan dgn method POST ke controller
            let totalProfit = 0;
            let newInputWrapper = document.createElement('div');
            newInputWrapper.setAttribute('data-slug-barang', `${hasilHitung['slug']}`);
            let newInputBarang;
            let keys = Object.keys(hasilHitung); // array of keys/properties in hasilHitung object
            let c = 0;
            keys.forEach(itemKey => {
                console.log(`${itemKey} is ${hasilHitung[itemKey]}`);
                c++;

                totalProfit += parseInt(`${hasilHitung['profit']}`);
                
                newInputBarang = document.createElement('input');
                newInputBarang.setAttribute('type', 'hidden');
                newInputBarang.setAttribute('name', `${itemKey}`+'[]');
                newInputBarang.setAttribute('data-field', `${itemKey}`);
                newInputBarang.setAttribute('value', `${hasilHitung[itemKey]}`);

                newInputWrapper.appendChild(newInputBarang);
            });

            formCreatePenawaran.appendChild(newInputWrapper);
            
            const btnDeleteRow = tblDataBarangPenawaranBody.querySelector('.delete-row-data-barang');
            if (btnDeleteRow) {
                btnDeleteRow.addEventListener('click', function() {
                    deleteRowPenawaranBarang(hasilHitung.slug);
                });
            }

            calcDataPenawaran();

            // tutup modal
            const modalCloseBtn = modalPilihBarangContainer.querySelector('[data-bs-dismiss="modal"]');
            modalCloseBtn.click();
        });
        // end btnAddToPenawaran.addEventListener('click'
    }
    // end if (btnAddToPenawaran) 

    const komponenFinalInputs = document.querySelectorAll('#containerKomponenFinal input');
    if (komponenFinalInputs) {
        komponenFinalInputs.forEach((elem) => {
            elem.addEventListener('blur', function() {
                calcDataPenawaran();
            });
        });
    }

    function adjustElemDetailPenawaran()
    {
        const hasilHitung = hitungDataBarang();

        const elemNilaiDiskonSatuan = modalPilihBarangContainer.querySelector('#nilaiDiskonSatuan');
        const elemNilaiBiayaSatuan = modalPilihBarangContainer.querySelector('#nilaiBiayaSatuan');
        const elemNilaiDiskonSubtotal = modalPilihBarangContainer.querySelector('#nilaiDiskonSubtotal');
        const elemNilaiBiayaSubtotal = modalPilihBarangContainer.querySelector('#nilaiBiayaSubtotal');

        elemNilaiDiskonSatuan.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.diskonSatuan);
        elemNilaiBiayaSatuan.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.biayaSatuan);
        elemNilaiDiskonSubtotal.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.diskonSubtotal);
        elemNilaiBiayaSubtotal.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.biayaSubtotal);

        const elemSubtotalJual = modalPilihBarangContainer.querySelector('#subtotalJualSatuan');
        const elemSubtotalModal = modalPilihBarangContainer.querySelector('#subtotalModal');
        const elemSubtotalFinal = modalPilihBarangContainer.querySelector('#subtotalFinal');
        const elemProfit = modalPilihBarangContainer.querySelector('#profit');

        elemSubtotalJual.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.subtotalJualSatuan);
        elemSubtotalModal.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.subtotalModal);
        elemSubtotalFinal.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.subtotalJualBarang);
        elemProfit.innerHTML = 'Rp ' + addThousandSeparator(hasilHitung.profit);
    }
    // end function adjustElemDetailPenawaran

    function hitungDataBarang()
    {
        let item = getDataBarang();

        item['diskonSatuan'] = cekPersentase(item.diskonSatuan, item.hargaJual);
        item['biayaSatuan'] = cekPersentase(item.biayaSatuan, item.hargaJual);

        item['subtotalModal'] = parseInt(item.hargaModal * item.qty);
        item['subtotalJualSatuan'] = parseInt((item.hargaJual - item.diskonSatuan + item.biayaSatuan) * item.qty);

        item['diskonSubtotal'] = cekPersentase(item.diskonSubtotal, item.hargaJual);
        item['biayaSubtotal'] = cekPersentase(item.biayaSubtotal, item.hargaJual);

        item['subtotalJualBarang'] = parseInt(item.subtotalJualSatuan - item.diskonSubtotal + item.biayaSubtotal);

        item['profit'] = item.subtotalJualBarang - item.subtotalModal;

        return item;
    }
    // end function hitungDataBarang

    function cekPersentase(inputValue, targetValue) {
        // kalau input diskonSatuan diakhiri karakter %
        if (inputValue.slice(-1) == '%') {
            // maka hitung diskonSubtotal pakai persentase
            inputValue = inputValue.toString().slice(0, -1).trim();
            inputValue = targetValue * inputValue / 100;
        } else {
            // kalau tidak diakhiri karakter %
            // artinya input diskonSubtotal tsb adl dlm bentuk rupiah
            inputValue = parseInt(removeNonNumeric(inputValue));
        }

        return inputValue;
    }
    // end function cekPersentase(inputValue, targetValue)

    function getDataBarang(from='modal', slug='')
    {
        let namaBarangElem = modalPilihBarangContainer.querySelector('#namaBarang');
        let slugBarangElem = modalPilihBarangContainer.querySelector('#slugBarang');
        let stokElem = modalPilihBarangContainer.querySelector('#stokBarang');
        let hargaModalElem = modalPilihBarangContainer.querySelector('#hargaBarang');
        let hargaJualElem = modalPilihBarangContainer.querySelector('#harga');
        let qtyElem = modalPilihBarangContainer.querySelector('#qty');
        let diskonSatuan = modalPilihBarangContainer.querySelector('#diskon');
        let biayaSatuan = modalPilihBarangContainer.querySelector('#biaya');
        let diskonSubtotal = modalPilihBarangContainer.querySelector('#diskonSubtotal');
        let biayaSubtotal = modalPilihBarangContainer.querySelector('#biayaSubtotal');

        if (from == 'tableDataBarangPenawaran') {
            const mytable = document.querySelector('form #tableDataBarangPenawaran');
            const tblRow = mytable.querySelector('[data-field="slug"][data-fvalue="'+slug+'"]');
            namaBarangElem = tblRow.querySelector('[data-field="nama"]').getAttribute('data-fvalue');
            slugBarangElem = tblRow.getAttribute('data-fvalue');
            hargaModalElem = tblRow.querySelector('[data-field="nama"]').getAttribute('data-fvalue');
        }

        let item = {
            nama: namaBarangElem.innerHTML.trim(),
            slug: slugBarangElem.value,
            stok: stokElem.innerHTML,
            hargaModal: hargaModalElem.innerHTML,
            hargaJual: hargaJualElem.value,
            qty: qtyElem.value,
            diskonSatuan: diskonSatuan.value,
            biayaSatuan: biayaSatuan.value,
            diskonSubtotal: diskonSubtotal.value,
            biayaSubtotal: biayaSubtotal.value,
        };

        item['stok'] = parseInt(removeNonNumeric(item.stok.trim()));
        item['hargaModal'] = parseInt(removeNonNumeric(item.hargaModal.trim()));
        item['hargaJual'] = parseInt(removeNonNumeric(item.hargaJual.trim()));
        item['qty'] = parseInt(removeNonNumeric(item.qty.trim()));
        item['diskonSatuan'] = item.diskonSatuan.trim();
        item['biayaSatuan'] = item.biayaSatuan.trim();
        item['diskonSubtotal'] = item.diskonSubtotal.trim();
        item['biayaSubtotal'] = item.biayaSubtotal.trim();

        return item;
    }
    // end function getDataBarang(from='modal')

    function validasiInputBarang(from='modal')
    {
        let response = {
            status: 'OK',
            message: '',
        }

        let dataBarang = getDataBarang(from);

        try {
            if (dataBarang.qty > dataBarang.stok) {
                response.status = 'err';
                throw '['+dataBarang.nama+'] : Qty penawaran tidak boleh lebih besar dari Sisa Stok';
            }
            if (dataBarang.qty < 1) {
                response.status = 'err';
                throw '['+dataBarang.nama+'] : Qty penawaran minimal 1';
            }
        } catch (error) {
            response.message = error;
        }

        return response;
    }
    // end function validasiInputBarang()

    function calcDataPenawaran()
    {
        let totalSubtotalModal = 0;
        let totalSubtotalJual = 0;
        let value = 0;
        // hitung total penjualan berdasarkan data barang yg sudah diinputkan (hidden inputs)
        const listDataPenawaran = formCreatePenawaran.querySelectorAll('[data-slug-barang]');
        if (listDataPenawaran) {
            listDataPenawaran.forEach((rowData) => {
                value = rowData.querySelector('[data-field="subtotalModal"]').value;
                value = parseInt(removeNonNumeric(value));
                totalSubtotalModal += value;
                value = rowData.querySelector('[data-field="subtotalJualBarang"]').value;
                value = parseInt(removeNonNumeric(value));
                totalSubtotalJual += value;
            });
        }
        const totalPenjualanKotorElem = formCreatePenawaran.querySelector('#totalPenjualanKotor');
        const totalPenjualanKotorValue = formCreatePenawaran.querySelector('#totalPenjualanKotorValue');
        if (totalPenjualanKotorElem) {
            totalPenjualanKotorElem.innerHTML = 'Rp ' + addThousandSeparator(parseInt(totalSubtotalJual));
            totalPenjualanKotorValue.value = parseInt(totalSubtotalJual);
        }

        // kurangi dgn diskon final (kalau ada)
        const diskonJualFinalInput = formCreatePenawaran.querySelector('#diskonFinalInput');
        const diskonJualFinalLabel = formCreatePenawaran.querySelector('#diskonFinalValue');
        let diskonJualFinal = 0;
        if (diskonJualFinalInput) {
            diskonJualFinal = cekPersentase(diskonJualFinalInput.value, totalSubtotalJual);
            diskonJualFinalLabel.innerHTML = 'Rp ' + addThousandSeparator(parseInt(diskonJualFinal));
        }
        // tambahkan dgn biaya final (kalau ada)
        const biayaJualFinalInput = formCreatePenawaran.querySelector('#biayaFinalInput');
        const biayaJualFinalLabel = formCreatePenawaran.querySelector('#biayaFinalValue');
        let biayaJualFinal = 0;
        if (biayaJualFinalInput) {
            biayaJualFinal = cekPersentase(biayaJualFinalInput.value, totalSubtotalJual);
            biayaJualFinalLabel.innerHTML = 'Rp ' + addThousandSeparator(parseInt(biayaJualFinal));
        }

        const totalPenjualanElem = formCreatePenawaran.querySelector('#totalPenjualanFinal');
        const totalPenjualanFinalValue = formCreatePenawaran.querySelector('#totalPenjualanFinalValue');
        let totalJualFinal = totalSubtotalJual - diskonJualFinal + biayaJualFinal;
        if (totalPenjualanElem) {
            totalPenjualanElem.innerHTML = 'Rp ' + addThousandSeparator(parseInt(totalJualFinal));
            totalPenjualanFinalValue.value = parseInt(totalJualFinal);
        }
        const totalProfitElem = formCreatePenawaran.querySelector('#totalProfit');
        const totalProfitValue = formCreatePenawaran.querySelector('#totalProfitValue');
        let totalProfit = totalJualFinal - totalSubtotalModal;
        if (totalProfitElem) {
            totalProfitElem.innerHTML = 'Rp ' + addThousandSeparator(parseInt(totalProfit));
            totalProfitValue.value = parseInt(totalProfit);
        }
    }
    // end function calcDataPenawaran()

    function deleteRowPenawaranBarang(slug='') {
        let inputContainer = document.querySelector('form [data-slug-barang="'+slug+'"]');
        let str = 'Data barang [' + inputContainer.querySelector('[name="nama[]"]').value + ']'
            + ' Rp ' + inputContainer.querySelector('[name="hargaJual[]"]').value
            + ' Qty ' + inputContainer.querySelector('[name="qty[]"]').value
            + ' akan dihapus dari daftar penawaran. Lanjutkan?';
        if ( ! confirm(str)) {
            return false;
        }
        
        let rowElem = document.querySelector('[data-field="slug"][data-fvalue="'+slug+'"]');
        rowElem.remove();
        inputContainer.remove();
    }
    // end function deleteRowPenawaranBarang

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
                search: modalPilihCustContainer.querySelector('#inputFilterCustomer').value,
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
