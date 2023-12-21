document.addEventListener("DOMContentLoaded", function(event) {
    let numericInputs = document.querySelectorAll("input[data-type=number]");
    
    numericInputs.forEach(elem => {
        adjustElemContentText(elem);
    });

    let forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', () => {
            numericInputs.forEach(elem => {
                elem.value = removeNonNumeric(elem.value);
            });
        });
    });    

    // script utk delete item
    const deleteLinks = document.querySelectorAll('form[id^="delete-form"] a');
    deleteLinks.forEach((elem, index) => {
        elem.addEventListener('click', function() {
            if (confirm('Data will be deleted. Continue?')) {
                elem.closest('form').submit();
            }
        });
    });

    // tiap elemen yg mengandung properti #data-slugSelector
    // akan meng-auto generate kode slug 
    // dan mengisinya ke elemen yg ditunjuk oleh #data-slugSelector
    const elemsWithSlug = document.querySelectorAll('[data-slugSelector]');
    elemsWithSlug.forEach((elem) => {
        elem.addEventListener('blur', function() {
            const formContainer = this.closest('form');
            const slugElemSelector = this.getAttribute('data-slugSelector');
            const slugElem = formContainer.querySelector(slugElemSelector);
            const moduleName = formContainer.querySelector('#module').value;
            if (slugElem) {
                generateSlug(moduleName, elem.value).then((data) => {
                    slugElem.value = data.slug;
                });
            }
        });
    });
});


function addThousandSeparator(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function removeNonNumeric(x) {
    return x.toString().replace(/[^0-9&]/g,'');
}

// script utk generate slug berdasarkan module nya
// slug barang berdasarkan field #nama (nama barang)
// slug user berdasarkan field #username (username user)
async function generateSlug(moduleName, sourceValue) {
    let url = '/home/checkSlug';
    let qs = 'module=' + moduleName + '&value=' + sourceValue;
    url = url + '?' + qs;

    let slugValue = '';

    let response = await fetch(url);
    let data = await response.json();
    return data;
}
// end function generateSlug();

function adjustElemContentText(elem) {
    const value = elem.value;
    elem.type = "text";
    elem.value = addThousandSeparator(value); // beri pemisah ribuan

    // saat masuk/mengaktifkan input dgn atribut data-type=number
    // hilangkan semua karakter non-numerik dari value nya
    elem.addEventListener("focus", () => {
        elem.value = elem.value.replace(/\D/g, '');
        elem.select();
    });

    // saat keluar dari input dgn atribut data-type=number
    // berikan pemisah ribuan di value nya supaya mudah dibaca
    elem.addEventListener("blur", () => {
        const value = elem.value;
        elem.type = "text";
        elem.value = addThousandSeparator(value); // beri pemisah ribuan
    });
}

function removeLeadingZero(x) {
    let result = x.toString().replace(/^0+/, '');
    return result == '' ? '0' : result;
}