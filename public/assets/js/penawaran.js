document.addEventListener('DOMContentLoaded', function() {
    const modalPilihBarangSelector = '.modal.penawaran-data-barang'
    // const modalPilihBarangContainer = document.querySelector(modalPilihBarangSelector);
    const modalPilihBarangContainer = document.querySelector(modalPilihBarangSelector);

    const accordDetailPenawaranBarangID = 'groupDetailPenawaranBarang';

    const formPenawaran = document.querySelector('[id^="formPenawaran"]');

    // saat halaman create penawaran diload
    // reset isi tabel list data barangnya
    if (formPenawaran && formPenawaran.id == 'formPenawaranCreate') {
        const tblDataBarangPenawaranBody = document.querySelector('#tableDataBarangPenawaran tbody');
        tblDataBarangPenawaranBody.innerHTML = '';

        $('#'+modalPilihBarangContainer.id).on('shown.bs.modal', function() {
            // saat modal ini dibuka, selalu buat spy accordion bagian data barang yg dibuka
            const btnAccordionGroupCariBarang = modalPilihBarangContainer.querySelector('.accordion [data-bs-target="#groupCariBarang"]');
            if (btnAccordionGroupCariBarang) {
                if (btnAccordionGroupCariBarang.classList.contains('collapsed')) {
                    btnAccordionGroupCariBarang.click();
                }
            }
            loadDataBarang();
        });

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

        const btnAccordDetailBarang = modalPilihBarangContainer.querySelector(`button[data-bs-target="#${accordDetailPenawaranBarangID}"]`);
        if (btnAccordDetailBarang) {
            btnAccordDetailBarang.addEventListener('click', function() {
                // modalPilihBarangContainer.querySelector('#harga').focus();
                modalPilihBarangContainer.querySelector('#harga').select();
            });
        }
    
        const detailInputElems = modalPilihBarangContainer.querySelectorAll('.form-control');
        detailInputElems.forEach(elem => {
            elem.addEventListener('change', function() {
                console.log('change', elem);
                adjustElemDetailPenawaran(modalPilihBarangContainer);
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
                cell7.innerHTML = '<button class="btn btn-sm btn-info me-2 edit-row-data-barang"'
                    +   ' data-bs-toggle="modal" data-bs-target="#modalEditPenawaranDataBarang">'
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
                console.log('keys', keys);
                keys.forEach(itemKey => {
                    console.log(`${itemKey} is ${hasilHitung[itemKey]}`);
    
                    totalProfit += parseInt(`${hasilHitung['profit']}`);
                    
                    newInputBarang = document.createElement('input');
                    newInputBarang.setAttribute('type', 'hidden');
                    newInputBarang.setAttribute('name', `${itemKey}`+'[]');
                    newInputBarang.setAttribute('data-field', `${itemKey}`);
                    newInputBarang.setAttribute('value', `${hasilHitung[itemKey]}`);
    
                    newInputWrapper.appendChild(newInputBarang);
                });
    
                formPenawaran.appendChild(newInputWrapper);
                
                // assign event listener kepada tombol delete di row ini
                const btnDeleteRow = newRow.querySelector('.delete-row-data-barang');
                if (btnDeleteRow) {
                    btnDeleteRow.addEventListener('click', function() {
                        deleteRowPenawaranBarang(hasilHitung.slug);
                    });
                }
                
                // assign event listener kepada tombol delete di row ini
                const btnEditRow = newRow.querySelector('.edit-row-data-barang');
                if (btnEditRow) {
                    btnEditRow.addEventListener('click', function() {
                        console.log('edit data barang', hasilHitung.slug);
                        editBarangSlug = hasilHitung.slug;
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
    }

    // // ketika modal ditampilkan, inisialisasi isi tabel data barangnya
    // const btnTriggerModalPilihBarang = document.querySelector('[data-bs-target="#'+modalPilihBarangContainer.id+'"]');
    // if (btnTriggerModalPilihBarang) {
    //     btnTriggerModalPilihBarang.addEventListener('click', function() {
    //         let modalID = btnTriggerModalPilihBarang.getAttribute('data-bs-target');
    //         console.log('popup modal', modalPilihBarangContainer, modalID);

    //         // saat modal ini dibuka, selalu buat spy accordion bagian data barang yg dibuka
    //         const btnAccordionGroupCariBarang = modalPilihBarangContainer.querySelector('.accordion [data-bs-target="#groupCariBarang"]');
    //         if (btnAccordionGroupCariBarang) {
    //             if (btnAccordionGroupCariBarang.classList.contains('collapsed')) {
    //                 btnAccordionGroupCariBarang.click();
    //             }
    //         }
    //         loadDataBarang();
    //     });
    //     // end btnTriggerModalPilihBarang.addEventListener('click'
    // }
    // // end if (btnTriggerModalPilihBarang)


    function loadDataBarang(params={}) {
        // kosongkan body tabel data barangnya
        const tblContentDataBarang = modalPilihBarangContainer.querySelector('table tbody');
        tblContentDataBarang.innerHTML = '';

        // reset elemen

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
                    cell3.innerHTML = addThousandSeparator(item.stok).replace(/^0+/, '');
                    cell3.classList.add('fit', 'text-center');
                    cell3.setAttribute('data-id', 'stok');
                    cell4.innerHTML = 'Rp ' + addThousandSeparator(item.harga).replace(/^0+/, '');
                    cell4.classList.add('fit', 'text-end');
                    cell4.setAttribute('data-id', 'harga');

                    newRow.setAttribute('data-slug', item.slug);

                    // ketika klik salah satu cell/row di tabel data barang
                    // langsung pindah ke accordion item bagian input data penawaran barang
                    // buat function event listener nya di sini spy eventnya bs lsg dikenali
                    newRow.addEventListener('click', function() {
                        // buka accordion bagian Detail Penawaran Barang sekaligus tutup accordion bagian Cari Barang
                        const selector = `button[data-bs-target="#${accordDetailPenawaranBarangID}"]`;
                        const btnAccordDetailBarang = modalPilihBarangContainer.querySelector(selector);
                        if (btnAccordDetailBarang) {
                            btnAccordDetailBarang.click();
                            console.log('itemxx', item);
                            // masukkan datanya ke bagian detail penawaran barang
                            const containerDetailPenawaranBarang = modalPilihBarangContainer.querySelector('#groupDetailPenawaranBarang');
                            let obj = {
                                slug: item.slug,
                                nama: item.nama,
                                stok: item.stok,
                                harga: item.harga,
                                subtotalJualSatuan: 0,
                                subtotalJualFinal: 0,
                                profit: 0,
                            }
                            assignElemValues(containerDetailPenawaranBarang, obj);
                        }
                    });
                });
            })
            .catch(err => {
                console.log('error', err);
            });
    }
    // end function loadDataBarang

    function assignElemValues(myContainer, obj) {
        // reset semua nilai input
        myContainer.querySelectorAll('input').forEach(elem => {
            elem.value = '0';
        });
        myContainer.querySelector('#slugBarang').value = obj.slug;
        myContainer.querySelector('#namaBarang').innerHTML = obj.nama;
        myContainer.querySelector('#stokBarang').innerHTML = addThousandSeparator(obj.stok);
        myContainer.querySelector('#hargaBarang').innerHTML = 'Rp ' + addThousandSeparator(obj.harga);
        myContainer.querySelector('#subtotalJualSatuan').innerHTML = 'Rp ' + addThousandSeparator(obj.subtotalJualSatuan);
        myContainer.querySelector('#subtotalFinal').innerHTML = 'Rp ' + addThousandSeparator(obj.subtotalJualFinal);
        myContainer.querySelector('#profit').innerHTML = 'Rp ' + addThousandSeparator(obj.profit);

        // hightlight elemen input pertama di dalam container tsb
        myContainer.querySelector('input.form-control').select();
    }
    // end function assignElemValues(myContainer, obj)

    ////////////////////
    // end bagian add data penawaran barang
    ////////////////////

    ////////////////////
    // start bagian edit data penawaran barang
    ////////////////////

    // ketika menampilkan modal utk edit data penawaran barang, tdk usah jalankan loadDataBarang()
    // tapi langsung isi data barang di bagian detailnya saja
    const modalEditBarangID = 'modalEditPenawaranDataBarang';
    const modalEditBarangElem = document.querySelector('#'+modalEditBarangID);
    let editBarangSlug = '';
    $('#'+modalEditBarangID).on('shown.bs.modal', function() {
        let namaBarangLabel = modalEditBarangElem.querySelector('#namaBarang');
        let slugBarangLabel = modalEditBarangElem.querySelector('#slugBarang');
        let stokLabel = modalEditBarangElem.querySelector('#stokBarang');
        let hargaModalLabel = modalEditBarangElem.querySelector('#hargaBarang');
        let hargaJualElem = modalEditBarangElem.querySelector('#harga');
        let qtyElem = modalEditBarangElem.querySelector('#qty');
        let diskonSatuanElem = modalEditBarangElem.querySelector('#diskon');
        let biayaSatuanElem = modalEditBarangElem.querySelector('#biaya');
        let diskonSubtotalElem = modalEditBarangElem.querySelector('#diskonSubtotal');
        let biayaSubtotalElem = modalEditBarangElem.querySelector('#biayaSubtotal');
        let diskonSatuanValue = modalEditBarangElem.querySelector('#nilaiDiskonSatuan');
        let biayaSatuanValue = modalEditBarangElem.querySelector('#nilaiBiayaSatuan');
        let diskonSubtotalValue = modalEditBarangElem.querySelector('#nilaiDiskonSubtotal');
        let biayaSubtotalValue = modalEditBarangElem.querySelector('#nilaiBiayaSubtotal');

        const containerBarangToEdit = document.querySelector(`[data-slug-barang="${editBarangSlug}"]`);
        if (containerBarangToEdit) {
            let dataBarangToEdit = {
                nama: containerBarangToEdit.querySelector('[name="nama[]"]').value,
                slug: containerBarangToEdit.querySelector('[name="slug[]"]').value,
                stok: containerBarangToEdit.querySelector('[name="stok[]"]').value,
                hargaModal: containerBarangToEdit.querySelector('[name="hargaModal[]"]').value,
                
                qty: containerBarangToEdit.querySelector('[name="qty[]"]').value,
                hargaJualSatuanOri: containerBarangToEdit.querySelector('[name="hargaJual[]"]').value,
                diskonSatuanOri: containerBarangToEdit.querySelector('[name="diskonSatuanOri[]"]').value,
                biayaSatuanOri: containerBarangToEdit.querySelector('[name="biayaSatuanOri[]"]').value,
                diskonSubtotalOri: containerBarangToEdit.querySelector('[name="diskonSubtotalOri[]"]').value,
                biayaSubtotalOri: containerBarangToEdit.querySelector('[name="biayaSubtotalOri[]"]').value,
                diskonSatuan: containerBarangToEdit.querySelector('[name="diskonSatuan[]"]').value,
                biayaSatuan: containerBarangToEdit.querySelector('[name="biayaSatuan[]"]').value,
                diskonSubtotal: containerBarangToEdit.querySelector('[name="diskonSubtotal[]"]').value,
                biayaSubtotal: containerBarangToEdit.querySelector('[name="biayaSubtotal[]"]').value,
            }
            console.log('data for edit', dataBarangToEdit);

            namaBarangLabel.innerHTML = dataBarangToEdit.nama;
            slugBarangLabel.innerHTML = dataBarangToEdit.slug;
            stokLabel.innerHTML = addThousandSeparator(dataBarangToEdit.stok);
            hargaModalLabel.innerHTML = 'Rp ' + addThousandSeparator(dataBarangToEdit.hargaModal);
            qtyElem.value = addThousandSeparator(dataBarangToEdit.qty);
            diskonSatuanElem.value = dataBarangToEdit.diskonSatuanOri;
            biayaSatuanElem.value = dataBarangToEdit.biayaSatuanOri;
            diskonSubtotalElem.value = dataBarangToEdit.diskonSubtotalOri;
            biayaSubtotalElem.value = dataBarangToEdit.biayaSubtotalOri;
            diskonSatuanValue.value = 'Rp ' + addThousandSeparator(dataBarangToEdit.diskonSatuanValue);
            biayaSatuanValue.value = 'Rp ' + addThousandSeparator(dataBarangToEdit.biayaSatuanValue);
            diskonSubtotalValue.value = 'Rp ' + addThousandSeparator(dataBarangToEdit.diskonSubtotalValue);
            biayaSubtotalValue.value = 'Rp ' + addThousandSeparator(dataBarangToEdit.biayaSubtotalValue);
        }
    });

    ////////////////////
    // end bagian edit data penawaran barang
    ////////////////////


    const komponenFinalInputs = document.querySelectorAll('#containerKomponenFinal input');
    if (komponenFinalInputs) {
        komponenFinalInputs.forEach((elem) => {
            elem.addEventListener('blur', function() {
                calcDataPenawaran();
            });
        });
    }

    function adjustElemDetailPenawaran(elemContainer)
    {
        const hasilHitung = hitungDataBarang();

        const elemNilaiDiskonSatuan = elemContainer.querySelector('#nilaiDiskonSatuan');
        const elemNilaiBiayaSatuan = elemContainer.querySelector('#nilaiBiayaSatuan');
        const elemNilaiDiskonSubtotal = elemContainer.querySelector('#nilaiDiskonSubtotal');
        const elemNilaiBiayaSubtotal = elemContainer.querySelector('#nilaiBiayaSubtotal');

        elemNilaiDiskonSatuan.innerHTML = 'Rp ' + addThousandSeparator(removeLeadingZero(hasilHitung.diskonSatuan));
        elemNilaiBiayaSatuan.innerHTML = 'Rp ' + addThousandSeparator(removeLeadingZero(hasilHitung.biayaSatuan));
        elemNilaiDiskonSubtotal.innerHTML = 'Rp ' + addThousandSeparator(removeLeadingZero(hasilHitung.diskonSubtotal));
        elemNilaiBiayaSubtotal.innerHTML = 'Rp ' + addThousandSeparator(removeLeadingZero(hasilHitung.biayaSubtotal));

        const elemSubtotalJual = elemContainer.querySelector('#subtotalJualSatuan');
        const elemSubtotalModal = elemContainer.querySelector('#subtotalModal');
        const elemSubtotalFinal = elemContainer.querySelector('#subtotalFinal');
        const elemProfit = elemContainer.querySelector('#profit');

        elemSubtotalJual.innerHTML = 'Rp ' + addThousandSeparator(removeLeadingZero(hasilHitung.subtotalJualSatuan));
        elemSubtotalModal.innerHTML = 'Rp ' + addThousandSeparator(removeLeadingZero(hasilHitung.subtotalModal));
        elemSubtotalFinal.innerHTML = 'Rp ' + addThousandSeparator(removeLeadingZero(hasilHitung.subtotalJualBarang));
        elemProfit.innerHTML = 'Rp ' + addThousandSeparator(removeLeadingZero(hasilHitung.profit));
    }
    // end function adjustElemDetailPenawaran

    function hitungDataBarang() {
        let item = getDataBarang();

        item['diskonSatuan'] = cekPersentase(item.diskonSatuanOri, item.hargaJual);
        item['biayaSatuan'] = cekPersentase(item.biayaSatuanOri, item.hargaJual);

        item['subtotalModal'] = parseInt(item.hargaModal * item.qty);
        item['subtotalJualSatuan'] = parseInt((item.hargaJual - item.diskonSatuan + item.biayaSatuan) * item.qty);

        item['diskonSubtotal'] = cekPersentase(item.diskonSubtotalOri, item.hargaJual);
        item['biayaSubtotal'] = cekPersentase(item.biayaSubtotalOri, item.hargaJual);

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

    function getDataBarang(from='modal', slug='') {
        let namaBarangElem
        let slugBarangElem
        let stokElem
        let hargaModalElem
        let hargaJualElem
        let qtyElem
        let diskonSatuan
        let biayaSatuan
        let diskonSubtotal 
        let biayaSubtotal 

        let namaBarangLabel = modalPilihBarangContainer.querySelector('#namaBarang');
        let slugBarangLabel = modalPilihBarangContainer.querySelector('#slugBarang');
        let stokLabel = modalPilihBarangContainer.querySelector('#stokBarang');
        let hargaModalLabel = modalPilihBarangContainer.querySelector('#hargaBarang');
        let hargaJualElem = modalPilihBarangContainer.querySelector('#harga');
        let qtyElem = modalPilihBarangContainer.querySelector('#qty');
        let diskonSatuanElem = modalPilihBarangContainer.querySelector('#diskon');
        let biayaSatuanElem = modalPilihBarangContainer.querySelector('#biaya');
        let diskonSubtotalElem = modalPilihBarangContainer.querySelector('#diskonSubtotal');
        let biayaSubtotalElem = modalPilihBarangContainer.querySelector('#biayaSubtotal');
        let diskonSatuanValue
        let biayaSatuanValue
        let diskonSubtotalValue
        let biayaSubtotalValue

        if (from == 'edit-modal') {
            namaBarangLabel = modalEditBarangElem.querySelector('#namaBarang');
            slugBarangLabel = modalEditBarangElem.querySelector('#slugBarang');
            stokLabel = modalEditBarangElem.querySelector('#stokBarang');
            hargaModalLabel = modalEditBarangElem.querySelector('#hargaBarang');
            hargaJualElem = modalEditBarangElem.querySelector('#harga');
            qtyElem = modalEditBarangElem.querySelector('#qty');
            diskonSatuanElem = modalEditBarangElem.querySelector('#diskon');
            biayaSatuanElem = modalEditBarangElem.querySelector('#biaya');
            diskonSubtotalElem = modalEditBarangElem.querySelector('#diskonSubtotal');
            biayaSubtotalElem = modalEditBarangElem.querySelector('#biayaSubtotal');
            diskonSatuanValue = modalEditBarangElem.querySelector('#nilaiDiskonSatuan');
            biayaSatuanValue = modalEditBarangElem.querySelector('#nilaiBiayaSatuan');
            diskonSubtotalValue = modalEditBarangElem.querySelector('#nilaiDiskonSubtotal');
            biayaSubtotalValue = modalEditBarangElem.querySelector('#nilaiBiayaSubtotal');
        }

        // if (from == 'tableDataBarangPenawaran') {
        //     const mytable = document.querySelector('form #tableDataBarangPenawaran');
        //     const tblRow = mytable.querySelector('[data-field="slug"][data-fvalue="'+slug+'"]');
        //     namaBarangElem = tblRow.querySelector('[data-field="nama"]').getAttribute('data-fvalue');
        //     slugBarangElem = tblRow.getAttribute('data-fvalue');
        //     hargaModalElem = tblRow.querySelector('[data-field="nama"]').getAttribute('data-fvalue');
        // }

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
            diskonSatuanOri: diskonSatuan.value,
            biayaSatuanOri: biayaSatuan.value,
            diskonSubtotalOri: diskonSubtotal.value,
            biayaSubtotalOri: biayaSubtotal.value,
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

    function validasiInputBarang(from='modal') {
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

    function calcDataPenawaran() {
        let totalSubtotalModal = 0;
        let totalSubtotalJual = 0;
        let value = 0;
        // hitung total penjualan berdasarkan data barang yg sudah diinputkan (hidden inputs)
        const listDataPenawaran = formPenawaran.querySelectorAll('[data-slug-barang]');
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
        const totalPenjualanKotorElem = formPenawaran.querySelector('#totalPenjualanKotor');
        const totalPenjualanKotorValue = formPenawaran.querySelector('#totalPenjualanKotorValue');
        if (totalPenjualanKotorElem) {
            totalPenjualanKotorElem.innerHTML = 'Rp ' + addThousandSeparator(parseInt(totalSubtotalJual));
            totalPenjualanKotorValue.value = parseInt(totalSubtotalJual);
        }

        // kurangi dgn diskon final (kalau ada)
        const diskonJualFinalInput = formPenawaran.querySelector('#diskonFinalInput');
        const diskonJualFinalLabel = formPenawaran.querySelector('#diskonFinalValue');
        let diskonJualFinal = 0;
        if (diskonJualFinalInput) {
            diskonJualFinal = cekPersentase(diskonJualFinalInput.value, totalSubtotalJual);
            diskonJualFinalLabel.innerHTML = 'Rp ' + addThousandSeparator(parseInt(diskonJualFinal));
        }
        // tambahkan dgn biaya final (kalau ada)
        const biayaJualFinalInput = formPenawaran.querySelector('#biayaFinalInput');
        const biayaJualFinalLabel = formPenawaran.querySelector('#biayaFinalValue');
        let biayaJualFinal = 0;
        if (biayaJualFinalInput) {
            biayaJualFinal = cekPersentase(biayaJualFinalInput.value, totalSubtotalJual);
            biayaJualFinalLabel.innerHTML = 'Rp ' + addThousandSeparator(parseInt(biayaJualFinal));
        }

        const totalPenjualanElem = formPenawaran.querySelector('#totalPenjualanFinal');
        const totalPenjualanFinalValue = formPenawaran.querySelector('#totalPenjualanFinalValue');
        let totalJualFinal = totalSubtotalJual - diskonJualFinal + biayaJualFinal;
        if (totalPenjualanElem) {
            totalPenjualanElem.innerHTML = 'Rp ' + addThousandSeparator(parseInt(totalJualFinal));
            totalPenjualanFinalValue.value = parseInt(totalJualFinal);
        }
        const totalProfitElem = formPenawaran.querySelector('#totalProfit');
        const totalProfitValue = formPenawaran.querySelector('#totalProfitValue');
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
    if (formPenawaran) {
        // inisialisasi konten tabel data customer
        const modalPilihCustSelector = '.modal#modalPilihCustomer';
        const modalPilihCustContainer = document.querySelector(modalPilihCustSelector);

        // ketika modal ditampilkan, inisialisasi isi tabel data customer yg ada di database
        const btnTriggerModalPilihCust = document.querySelector('[data-bs-target="#'+modalPilihCustContainer.id+'"]');
        if (btnTriggerModalPilihCust) {
            btnTriggerModalPilihCust.addEventListener('click', function() {
                loadDataCustomer(modalPilihCustContainer);
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
                loadDataCustomer(modalPilihCustContainer, params);
            });
        }
    }

    function loadDataCustomer(containerElem, params={}) {
        // kosongkan body tabel data barangnya
        const tblContentDataCust = containerElem.querySelector('table tbody');
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
