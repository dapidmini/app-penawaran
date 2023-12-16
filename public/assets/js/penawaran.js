document.addEventListener('DOMContentLoaded', function() {
    const modalSelector = '.modal.penawaran-data-barang'
    const modalContainer = document.querySelector(modalSelector);
    const modalIDSelector = '#' + modalContainer.id;

    const accordDetailPenawaranBarangSelector = "#groupDetailPenawaranBarang";

    // ketika modal ditampilkan, inisialisasi isi tabel data barangnya
    const btnTriggerModal = document.querySelector('[data-bs-target="'+modalIDSelector+'"]');
    if (btnTriggerModal) {
        btnTriggerModal.addEventListener('click', function() {
            // kosongkan body tabel data barangnya
            const tblContentDataBarang = modalContainer.querySelector('table tbody');
            tblContentDataBarang.innerHTML = '';

            // fetch data barang dr database
            let url = '/barang/fetch';
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
                            const tblRow = this.closest('tr');
                            const detailBarang = {
                                nama: tblRow.querySelector('[data-id="nama"]').innerHTML,
                                stok: tblRow.querySelector('[data-id="stok"]').innerHTML,
                                harga: tblRow.querySelector('[data-id="harga"]').innerHTML,
                            }
                            const containerDetailPenawaranBarang = modalContainer.querySelector('#groupDetailPenawaranBarang');
                            containerDetailPenawaranBarang.querySelector('#namaBarang').innerHTML = detailBarang.nama;
                            containerDetailPenawaranBarang.querySelector('#stokBarang').innerHTML = addThousandSeparator(detailBarang.stok);
                            containerDetailPenawaranBarang.querySelector('#hargaBarang').innerHTML = 'Rp ' + addThousandSeparator(detailBarang.harga);
                            // reset semua nilai input
                            containerDetailPenawaranBarang.querySelectorAll('input').forEach(elem => {
                                elem.value = '0';
                            });

                            // buka accordion bagian Detail Penawaran Barang sekaligus tutup accordion bagian Cari Barang
                            modalContainer.querySelector('button[data-bs-target="'+accordDetailPenawaranBarangSelector+'"]').click();
                        });
                    });
                })
                .catch(err => {
                    console.log('error', err);
                });
        });
        // end btnTriggerModal.addEventListener('click'
    }

    modalContainer.querySelector('button[data-bs-target="'+accordDetailPenawaranBarangSelector+'"]').addEventListener('click', function() {
        modalContainer.querySelector('#harga').focus();
    });

    const detailInputElems = modalContainer.querySelectorAll('.form-control');
    detailInputElems.forEach(elem => {
        elem.addEventListener('change', function() {
            console.log('change');
            adjustElemDetailPenawaran();
        });
    });

    function adjustElemDetailPenawaran()
    {
        const hasilHitung = prosesDataBarang();

        const elemSubtotal = modalContainer.querySelector('#subtotal');
        const elemSubtotalFinal = modalContainer.querySelector('#subtotalFinal');
        const elemProfit = modalContainer.querySelector('#profit');

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
        const namaBarangElem = modalContainer.querySelector('#namaBarang');
        const stokElem = modalContainer.querySelector('#stokBarang');
        const hargaModalElem = modalContainer.querySelector('#hargaBarang');
        const hargaJualElem = modalContainer.querySelector('#harga');
        const qtyElem = modalContainer.querySelector('#qty');
        const diskonSatuan = modalContainer.querySelector('#diskon');
        const biayaSatuan = modalContainer.querySelector('#biaya');

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

    const btnAddToPenawaran = modalContainer.querySelector('#btnAddToPenawaran');
    if (btnAddToPenawaran) {
        btnAddToPenawaran.addEventListener('click', function(e) {
            const cekInputBarang = validasiInputBarang();
            console.log('validasi', cekInputBarang);
            if (cekInputBarang.status != 'OK') {
                e.preventDefault();
                e.stopPropagation();
                const msgInputBarang = modalContainer.querySelector('#msgInputBarang');
                msgInputBarang.innerHTML = cekInputBarang.message;
                msgInputBarang.classList.add('is-invalid');
                return false;
            }

            let mytable = document.querySelector('#tableDataBarangPenawaran');
            let tblPenawaranItem = mytable.getElementsByTagName('tbody')[0];

            const hasilHitung = prosesDataBarang();
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

            // tutup modal
            const modalCloseBtn = modalContainer.querySelector('[data-bs-dismiss="modal"]');
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
});
