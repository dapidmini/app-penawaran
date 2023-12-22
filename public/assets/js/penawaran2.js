document.addEventListener('DOMContentLoaded', function() {
    
    const formPenawaran = document.querySelector('[id^="formPenawaran"]');

    /////////////////////////
    // start bagian modal pilih customer
    /////////////////////////

    // ketika buka modal utk pilih customer
    const modalPilihCustID = 'modalPilihCustomer';
    $('#'+modalPilihCustID).on('shown.bs.modal', function() {
        // inisialisasi elemen kontainer datanya
        loadDataCustomer();
    });
    // end $('#'+modalPilihCustID).on('shown.bs.modal', 

    $(`#${modalPilihCustID} #btnFilterCustomer`).on('click', function() {
        // perbarui data customer yg ditampilkan berdasarkan filter search
        const wrapper = this.parentNode;
        const filterCustInput = wrapper.querySelector('#inputFilterCustomer');
        let params = {
            search: filterCustInput.value,
        }
        loadDataCustomer(params);
    });

    function loadDataCustomer(params={}) {
        const dataCustWrapper = document.querySelector('#dataCustomerWrapper');
        const dataCustTbl = dataCustWrapper.querySelector('table');
        const dataCustContainer = dataCustWrapper.querySelector('tbody');
        dataCustContainer.innerHTML = '';
        const loadingIcon = dataCustWrapper.querySelector('.spinner-border');
        // tampilkan icon loading
        loadingIcon.classList.remove('d-none');
        // sembunyikan elemen tablenya
        dataCustTbl.classList.add('d-none');

        // jalankan fetch utk mendapatkan data customer
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
                // inisialisasi tampilan konten
                loadingIcon.classList.add('d-none');
                // hilangkan class d-none spy elemen table nya ditampilkan
                dataCustTbl.classList.remove('d-none');
                data.forEach((item, index) => {
                    let newRow = dataCustContainer.insertRow(-1);
                    let cell1 = newRow.insertCell(0);
                    let cell2 = newRow.insertCell(1);
                    let cell3 = newRow.insertCell(2);
                    let cell4 = newRow.insertCell(3);
                    // let cell5 = newRow.insertCell(4);

                    newRow.setAttribute('data-slug', item.slug);

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
                    // // tampilkan tgl penawaran terakhir yg diajukan kpd customer ini
                    // let dateStr = '-';
                    // if (item.latest_penawaran_by_cust) {
                    //     console.log('asdasd');
                    //     let d = new Date(item.latest_penawaran_by_cust);
                    //     const options = {
                    //         day: 'numeric',
                    //         month: 'short',
                    //         year: 'numeric',
                    //     }
                    //     dateStr = d.toLocaleDateString('id-ID', options);
                    // }
                    // cell5.innerHTML = dateStr;
                    // cell5.classList.add('px-2', 'fit', 'text-center');
                    // cell5.setAttribute('data-id', 'tglPenawaranTerakhir');

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
                            // detailElem.querySelector('#tglPenawaranTerakhir').innerHTML = dateStr;
                        });
                    });
                    // end newRow.addEventListener('click', function() 
                });
                // end data.forEach((item, index) 
            })
            .catch(err => {
                console.log('error', err);
            });
    }
    // end function loadDataCustomer(params={}) 

    /////////////////////////
    // end bagian modal pilih customer
    /////////////////////////

    /////////////////////////
    // start bagian modal cari barang
    /////////////////////////

    const modalPilihBarangID = 'modalPenawaranDataBarang';
    $('#'+modalPilihBarangID).on('shown.bs.modal', function() {
        console.log('#'+modalPilihBarangID);
        // reset elements di modal tsb dulu
        resetElements('#'+modalPilihBarangID);
        // inisialisasi elemen kontainer data barang
        loadDataBarang();
    });

    $(`#${modalPilihBarangID} #btnFilterBarang`).on('click', function() {
        // perbarui data customer yg ditampilkan berdasarkan filter search
        const wrapper = this.parentNode;
        const filterBarangInput = wrapper.querySelector('#inputFilterBarang');
        let params = {
            search: filterBarangInput.value,
        }
        loadDataBarang(params);
    });

    let slugToEdit = '';
    $('#modalEditPenawaranDataBarang').on('shown.bs.modal', function() {
        console.log('slug to edit', slugToEdit);
        const dataContainerDiv = formPenawaran.querySelector(`[data-slug-barang="${slugToEdit}"]`);
        if (dataContainerDiv) {
            const modalEditElem = document.querySelector('#modalEditPenawaranDataBarang');
            modalEditElem.querySelector('#idBarang').innerHTML = dataContainerDiv.querySelector(`[data-field="idBarang"]`).value;
            modalEditElem.querySelector('#namaBarang').innerHTML = dataContainerDiv.querySelector(`[data-field="namaBarang"]`).value;
            modalEditElem.querySelector('#slugBarang').innerHTML = dataContainerDiv.querySelector(`[data-field="slugBarang"]`).value;
            modalEditElem.querySelector('#hargaBarang').innerHTML = dataContainerDiv.querySelector(`[data-field="hargaBarang"]`).value;
            modalEditElem.querySelector('#hargaJualSatuan').innerHTML = dataContainerDiv.querySelector(`[data-field="hargaJualSatuan"]`).value;
        }
    });

    // // ketika klik tombol Hapus di row data barang penawaran
    // // pertama konfirmasi dulu apakah yakin mau hapus row ini?
    // const rowDeleteBtnElems = document.querySelectorAll('#tableDataBarangPenawaran .delete-row-data-barang');
    // rowDeleteBtnElems.forEach((btnDelete) => {
    //     btnDelete.addEventListener('click', function() {
    //         const container = document.querySelector(`[data-slug-barang="${this.getAttribute('data-slug')}"]`);
    //         if (container) {
    //             const namaBarang = container.querySelector(`[data-field="namaBarang"]`).value;
    //             if (namaBarang && ! confirm(`Data barang [${namaBarang}] akan dihapus dari daftar penawaran ini. Lanjutkan?`)) {
    //                 return false;
    //             }
    //             const myRow = btnDelete.closest('tr');
    //             myRow.remove();
    //         }
    //     });
    // });

    // ketika klik tombol Edit di row data barang penawaran
    // tampilkan modal utk edit penawaran (kurleb sama dgn modalPenawaranDataBarang, tp tdk ada pilih barangnya)
    // jadi tampilkan data

    const detailBarangWrapper = document.querySelector('#groupDetailPenawaranBarang');
    if (detailBarangWrapper) {
        // setiap kali pindah/keluar dari sebuah input element
        const inputElems = detailBarangWrapper.querySelectorAll('input');
        inputElems.forEach((inputElem) => {
            console.log('each', inputElem);
            inputElem.addEventListener('blur', function() {
                adjustElemenDetailBarang(detailBarangWrapper);
            });
            // end inputElem.on('blur', function() 
        });
        // end inputElems.forEach((inputElem) 
        // end hitung komponen2 setiap kali pindah/keluar dr input element
        
        // saat klik tombol Masukkan ke Penawaran
        const btnAddToPenawaran = document.querySelector('#btnAddToPenawaran');
        btnAddToPenawaran.addEventListener('click', function() {
            let mytable = document.querySelector('#tableDataBarangPenawaran');
            let tblPenawaranItem = mytable.getElementsByTagName('tbody')[0];

            const hasilHitung = hitungDetailBarang(detailBarangWrapper);
            console.log('hasil hitung', hasilHitung);
            hasilHitung['no'] = tblPenawaranItem.querySelectorAll('tr').length + 1;

            let newRow = tblPenawaranItem.insertRow(-1);
            newRow.setAttribute('data-field', 'slug');
            newRow.setAttribute('data-fvalue', hasilHitung.slugBarang);

            let cell1 = newRow.insertCell(0);
            cell1.innerHTML = hasilHitung.no + '.';
            cell1.classList.add('fit');
            let cell2 = newRow.insertCell(1);
            cell2.innerHTML = hasilHitung.namaBarang;
            let cell3 = newRow.insertCell(2);
            cell3.innerHTML = addThousandSeparator(hasilHitung.qty);
            cell3.classList.add('fit', 'px-2', 'text-center');
            let cell4 = newRow.insertCell(3);
            cell4.innerHTML = addThousandSeparator(hasilHitung.hargaJualSatuan);
            cell4.classList.add('fit', 'px-2', 'text-end');
            let cell5 = newRow.insertCell(4);
            cell5.innerHTML = addThousandSeparator(hasilHitung.subtotalBarang);
            cell5.classList.add('fit', 'px-2', 'text-end');
            let cell6 = newRow.insertCell(5);
            cell6.innerHTML = addThousandSeparator(hasilHitung.profitBarang);
            cell6.classList.add('fit', 'px-2', 'text-end');
            let cell7 = newRow.insertCell(6);
            cell7.innerHTML = '<button type="button" class="btn btn-sm btn-info me-2 edit-row-data-barang" data-slug="'+hasilHitung.slugBarang+'"'
                +   ' data-bs-toggle="modal" data-bs-target="#modalEditPenawaranDataBarang">'
                +   '<i class="bi bi-pencil-square"></i>'
                + '</button>';
            cell7.innerHTML += '<button type="button" class="btn btn-sm btn-danger delete-row-data-barang" data-slug="'+hasilHitung.slugBarang+'">'
                +   '<i class="bi bi-trash-fill"></i>'
                + '</button>';
            cell7.classList.add('fit', 'px-2', 'text-end');

            let newInputWrapper = document.createElement('div');
            newInputWrapper.setAttribute('data-slug-barang', `${hasilHitung.slugBarang}`);
            let newInputBarang;
            let keys = Object.keys(hasilHitung); // array of keys/properties in hasilHitung object
            console.log('keys', keys);
            keys.forEach(itemKey => {
                console.log(`${itemKey} is ${hasilHitung[itemKey]}`);

                newInputBarang = document.createElement('input');
                newInputBarang.setAttribute('type', 'hidden');
                newInputBarang.setAttribute('name', `${itemKey}`+'[]');
                newInputBarang.setAttribute('data-field', `${itemKey}`);
                newInputBarang.setAttribute('value', `${hasilHitung[itemKey]}`);

                newInputWrapper.appendChild(newInputBarang);
            });

            formPenawaran.appendChild(newInputWrapper);

            calcTotalDataPenawaran(formPenawaran);

            const deleteBarangBtnElems = tblPenawaranItem.querySelectorAll('.delete-row-data-barang');
            deleteBarangBtnElems.forEach((deleteBtn) => {
                deleteBtn.addEventListener('click', function() {
                    const mySlug = this.getAttribute('data-slug');
                    let selector = `[data-slug-barang="${mySlug}"]`;
                    const myInputWrapper = formPenawaran.querySelector(selector);
                    const myNamaBarang = myInputWrapper.querySelector(`[data-field="namaBarang"]`).value;
                    if ( ! confirm(`Data barang [${myNamaBarang}] akan dihapus dari daftar penawaran ini. Lanjutkan?`)) {
                        return false;
                    }
                    // hapus baris tsb dr tabel data penawaran barang
                    const myRow = tblPenawaranItem.querySelector(`tr[data-field="slug"][data-fvalue="${mySlug}"]`);
                    myRow.remove();
                    // hapus hidden input yg berhubungan dgn barang tsb
                    myInputWrapper.remove();

                    // hitung ulang total penjualannya
                    calcTotalDataPenawaran(formPenawaran);
                });
            });

            const editBarangBtnElems = tblPenawaranItem.querySelectorAll('.edit-row-data-barang');
            editBarangBtnElems.forEach((editBtn) => {
                slugToEdit = this.getAttribute('data-slug');
            });
        });
        // end btnAddToPenawaran.addEventListener('click', 
    }
    // end if (detailBarangWrapper) 

    function calcTotalDataPenawaran(formElem) {
        let totalModal = 0;
        let totalJual = 0;
        let value = 0;
        // hitung total penjualan berdasarkan data barang yg sudah diinputkan (hidden inputs)
        const listDataPenawaran = formElem.querySelectorAll('[data-slug-barang]');
        if (listDataPenawaran) {
            listDataPenawaran.forEach((rowData) => {
                value = rowData.querySelector('[data-field="subtotalModal"]').value;
                value = parseInt(removeNonNumeric(value));
                totalModal += value;
                value = rowData.querySelector('[data-field="subtotalBarang"]').value;
                value = parseInt(removeNonNumeric(value));
                totalJual += value;
            });
        }
        const totalPenjualanKotorElem = formElem.querySelector('#totalPenjualanKotor');
        const totalPenjualanKotorValue = formElem.querySelector('#totalPenjualanKotorValue');
        if (totalPenjualanKotorElem) {
            totalPenjualanKotorElem.innerHTML = 'Rp ' + addThousandSeparator(parseInt(totalJual));
            totalPenjualanKotorValue.value = parseInt(totalJual);
        }

        // kurangi dgn diskon final (kalau ada)
        const diskonJualFinalInput = formElem.querySelector('#diskonFinalInput');
        const diskonJualFinalLabel = formElem.querySelector('#diskonFinalValue');
        let diskonJualFinal = 0;
        if (diskonJualFinalInput) {
            diskonJualFinal = cekPersentase(diskonJualFinalInput.value, totalJual);
            diskonJualFinalLabel.innerHTML = 'Rp ' + addThousandSeparator(parseInt(diskonJualFinal));
        }
        // tambahkan dgn biaya final (kalau ada)
        const biayaJualFinalInput = formElem.querySelector('#biayaFinalInput');
        const biayaJualFinalLabel = formElem.querySelector('#biayaFinalValue');
        let biayaJualFinal = 0;
        if (biayaJualFinalInput) {
            biayaJualFinal = cekPersentase(biayaJualFinalInput.value, totalJual);
            biayaJualFinalLabel.innerHTML = 'Rp ' + addThousandSeparator(parseInt(biayaJualFinal));
        }

        const totalPenjualanElem = formElem.querySelector('#totalPenjualanFinal');
        const totalPenjualanFinalValue = formElem.querySelector('#totalPenjualanFinalValue');
        let totalJualFinal = totalJual - diskonJualFinal + biayaJualFinal;
        if (totalPenjualanElem) {
            totalPenjualanElem.innerHTML = 'Rp ' + addThousandSeparator(parseInt(totalJualFinal));
            totalPenjualanFinalValue.value = parseInt(totalJualFinal);
        }
        const totalProfitElem = formElem.querySelector('#totalProfit');
        const totalProfitValue = formElem.querySelector('#totalProfitValue');
        let totalProfit = totalJualFinal - totalModal;
        if (totalProfitElem) {
            totalProfitElem.innerHTML = 'Rp ' + addThousandSeparator(parseInt(totalProfit));
            totalProfitValue.value = parseInt(totalProfit);
        }
    }
    // end function calcTotalDataPenawaran()

    function adjustElemenDetailBarang(wrapperElem) {
        // lakukan perhitungan utk semua komponen input di #groupDetailPenawaranBarang
        let item = hitungDetailBarang(wrapperElem);

        // sesuaikan value setiap field dan label
        wrapperElem.querySelector('#nilaiDiskonSatuan').innerHTML = 'Rp ' + addThousandSeparator(item.diskonSatuanValue);
        wrapperElem.querySelector('#nilaiBiayaSatuan').innerHTML = 'Rp ' + addThousandSeparator(item.biayaSatuanValue);
        wrapperElem.querySelector('#nilaiDiskonSubtotal').innerHTML = 'Rp ' + addThousandSeparator(item.diskonSubtotalValue);
        wrapperElem.querySelector('#nilaiBiayaSubtotal').innerHTML = 'Rp ' + addThousandSeparator(item.biayaSubtotalValue);

        wrapperElem.querySelector('#subtotalJualOri').innerHTML = 'Rp ' + addThousandSeparator(item.subtotalJualOri);
        wrapperElem.querySelector('#subtotalModal').innerHTML = 'Rp ' + addThousandSeparator(item.subtotalModal);

        wrapperElem.querySelector('#subtotalBarang').innerHTML = 'Rp ' + addThousandSeparator(item.subtotalBarang);
        wrapperElem.querySelector('#profitBarang').innerHTML = 'Rp ' + addThousandSeparator(item.profitBarang);

        return item;
    }
    // end function adjustElemenDetailBarang

    function hitungDetailBarang(wrapperElem) {
        let value = 0;
        let item = {
            idBarang: wrapperElem.querySelector('#idBarang').value,
            slugBarang: wrapperElem.querySelector('#slugBarang').value,
            namaBarang: wrapperElem.querySelector('#namaBarang').innerHTML,
            hargaBarang: parseInt(removeNonNumeric(wrapperElem.querySelector('#hargaBarang').innerHTML)),
            hargaJualSatuan: parseInt(removeNonNumeric(wrapperElem.querySelector('#hargaJualSatuan').value)),
            qty: parseInt(removeNonNumeric(wrapperElem.querySelector('#qty').value)),
        };
        
        // dapatkan nilai rupiah dari masing2 input
        let diskonSatuanOri = wrapperElem.querySelector('#diskonSatuanOri').value.trim();
        item['diskonSatuanOri'] = diskonSatuanOri;
        item['diskonSatuanValue'] = cekPersentase(item.diskonSatuanOri, item.hargaJualSatuan);

        let biayaSatuanOri = wrapperElem.querySelector('#biayaSatuanOri').value.trim();
        item['biayaSatuanOri'] = biayaSatuanOri;
        item['biayaSatuanValue'] = cekPersentase(item.biayaSatuanOri, item.hargaJualSatuan);
        
        value = (item.hargaJualSatuan - item.diskonSatuanValue + item.biayaSatuanValue) * item.qty;
        item['subtotalJualOri'] = value;
        
        value = item.hargaBarang * item.qty;
        item['subtotalModal'] = value;
        
        let diskonSubtotalOri = wrapperElem.querySelector('#diskonSubtotalOri').value.trim();
        item['diskonSubtotalOri'] = diskonSubtotalOri;
        item['diskonSubtotalValue'] = cekPersentase(item.diskonSubtotalOri, item.subtotalJualOri);
        
        let biayaSubtotalOri = wrapperElem.querySelector('#biayaSubtotalOri').value.trim();
        item['biayaSubtotalOri'] = biayaSubtotalOri;
        item['biayaSubtotalValue'] = cekPersentase(item.biayaSubtotalOri, item.subtotalJualOri);

        value = item.subtotalJualOri - item.diskonSubtotalValue + item.biayaSubtotalValue;
        item['subtotalBarang'] = value;
        
        value = item.subtotalBarang - item.subtotalModal;
        item['profitBarang'] = value;

        return item;
    }
    // end function hitungDetailBarang(wrapperElem) 

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

    function resetElements(wrapperSelector) {
        const wrapperElem = document.querySelector(wrapperSelector);
        if (wrapperElem) {
            // reset semua input box
            const inputElems = wrapperElem.querySelectorAll('input');
            inputElems.forEach((input) => {
                if (input.getAttribute('data-type') == 'number') {
                    input.value = '0';
                } else {
                    input.value = '';
                }
            });

            // reset semua label yg punya ID
            const labelElems = wrapperElem.querySelectorAll('span[id]');
            labelElems.forEach((label) => {
                if (['idBarang', 'namaBarang', 'slugBarang'].includes(label.id)) {
                    label.innerHTML = '';
                } else if (label.id == 'stokBarang') {
                    label.innerHTML = '0';
                } else {
                    label.innerHTML = 'Rp 0';
                }
            });

            // get the first accordion element (if exist)
            const accordionBtnElems = wrapperElem.querySelectorAll('.accordion-button');
            if (accordionBtnElems) {
                const accordionBtn = accordionBtnElems[0];
                if (accordionBtn.classList.contains('collapsed')) {
                    accordionBtn.click();
                }
            }
        }
    }
    // end function resetElements

    function loadDataBarang(params={}) {
        const dataBarangWrapper = document.querySelector('#dataBarangWrapper');
        const dataBarangTbl = dataBarangWrapper.querySelector('table');
        const dataBarangContainer = dataBarangTbl.querySelector('tbody');
        dataBarangContainer.innerHTML = '';
        const loadingIcon = dataBarangWrapper.querySelector('.spinner-border');
        // tampilkan icon loading
        loadingIcon.classList.remove('d-none');
        // sembunyikan elemen table nya
        dataBarangTbl.classList.add('d-none');

        // jalankan fetch utk mendapatkan data barang
        let url = '/barang';
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
                // inisialisasi tampilan konten
                // sembunyikan icon loading
                loadingIcon.classList.add('d-none');
                // tampilkan table kontennya
                dataBarangTbl.classList.remove('d-none');
                dataBarangContainer.innerHTML = '';

                data.forEach((item, index) => {
                    let newRow = dataBarangContainer.insertRow(-1);
                    let cell1 = newRow.insertCell(0);
                    let cell2 = newRow.insertCell(1);
                    let cell3 = newRow.insertCell(2);
                    let cell4 = newRow.insertCell(3);

                    newRow.setAttribute('data-slug', item.slug);
                    newRow.setAttribute('data-id', item.id);

                    cell1.innerHTML = (index+1)+'.';
                    cell1.classList.add('fit');
                    cell1.setAttribute('data-id', 'noUrut');
                    cell2.innerHTML = item.nama;
                    cell2.setAttribute('data-id', 'nama');
                    cell3.innerHTML = addThousandSeparator(item.stok).replace(/^0+/, '');
                    cell3.classList.add('fit', 'text-center');
                    cell3.setAttribute('data-id', 'stok');
                    cell4.innerHTML = 'Rp ' + addThousandSeparator(item.harga).replace(/^0+/, '');
                    cell4.classList.add('fit', 'text-end');
                    cell4.setAttribute('data-id', 'harga');

                    // ketika klik salah satu cell/row di tabel data barang
                    // langsung pindah ke accordion item bagian input data penawaran barang
                    // buat function event listener nya di sini spy eventnya bs lsg dikenali
                    newRow.addEventListener('click', function() {
                        // buka accordion bagian Detail Penawaran Barang sekaligus tutup accordion bagian Cari Barang
                        const modalParentElem = dataBarangWrapper.closest('.modal');
                        const openAccordionBtn = modalParentElem.querySelector('.accordion-button[data-bs-target="#groupDetailPenawaranBarang"]');
                        const detailBarangContainer = modalParentElem.querySelector('#groupDetailPenawaranBarang');
                        // tampilkan data barang yg dipilih di accordion bag.detail barang
                        detailBarangContainer.querySelector('#idBarang').value = item.id;
                        detailBarangContainer.querySelector('#slugBarang').value = item.slug;
                        detailBarangContainer.querySelector('#namaBarang').innerHTML = item.nama;
                        detailBarangContainer.querySelector('#stokBarang').innerHTML = item.stok;
                        detailBarangContainer.querySelector('#hargaBarang').innerHTML = 'Rp ' + addThousandSeparator(removeNonNumeric(item.harga));
                        // inisialisasi elemen lainnya
                        detailBarangContainer.querySelector('#hargaJualSatuan').value = 0;
                        detailBarangContainer.querySelector('#qty').value = 0;
                        detailBarangContainer.querySelector('#diskonSatuanOri').value = 0;
                        detailBarangContainer.querySelector('#biayaSatuanOri').value = 0;
                        detailBarangContainer.querySelector('#diskonSubtotalOri').value = 0;
                        detailBarangContainer.querySelector('#biayaSubtotalOri').value = 0;
                        detailBarangContainer.querySelector('#nilaiDiskonSatuan').innerHTML = 'Rp 0';
                        detailBarangContainer.querySelector('#nilaiBiayaSatuan').innerHTML = 'Rp 0';
                        detailBarangContainer.querySelector('#subtotalJualOri').innerHTML = 'Rp 0';
                        detailBarangContainer.querySelector('#subtotalModal').innerHTML = 'Rp 0';
                        detailBarangContainer.querySelector('#nilaiDiskonSubtotal').innerHTML = 'Rp 0';
                        detailBarangContainer.querySelector('#nilaiBiayaSubtotal').innerHTML = 'Rp 0';
                        detailBarangContainer.querySelector('#subtotalBarang').innerHTML = 'Rp 0';
                        detailBarangContainer.querySelector('#profitBarang').innerHTML = 'Rp 0';

                        openAccordionBtn.click();

                        // set focus ke elemen harga jual
                        detailBarangContainer.querySelector('#hargaJualSatuan').focus();
                    });
                });
                // end data.forEach((item, index) 
            })
            .catch(err => {
                console.log('err fetch barang', err);
                const labelMessage = dataBarangWrapper.querySelector('#dataBarangMsg');
                labelMessage.innerHTML = err;
                labelMessage.classList.add('err-label');
            });
    }
    // end function loadDataBarang(params={}) 

    /////////////////////////
    // end bagian modal cari barang
    /////////////////////////

});