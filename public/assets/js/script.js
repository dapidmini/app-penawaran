document.addEventListener("DOMContentLoaded", function(event) {
    let numericInputs = document.querySelectorAll("input[data-type=number]");
    
    numericInputs.forEach(elem => {
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
    });

    let forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', () => {
            numericInputs.forEach(elem => {
                elem.value = elem.value.replace(/\D/g,'');
            });
        });
    });

    
    // script utk generate slug berdasarkan module nya
    // slug barang berdasarkan field #nama (nama barang)
    // slug user berdasarkan field #username (username user)
    function generateSlug() {
        const form = document.querySelector('form');
        const slugField = form.querySelector('#slug');
        const moduleField = form.querySelector('#module');
        console.log('module', moduleField.value, moduleField);
        let uniqueField = null;
        if (moduleField.value == 'barang') {
            uniqueField = form.querySelector('#nama');
        } else if (moduleField.value == 'user') {
            uniqueField = form.querySelector('#username');
        }
        console.log('uniquefield', uniqueField);

        // ketika mengubah isi input box #nama (nama barang)
        // generate otomatis slug nya
        uniqueField.addEventListener('change', function() {
            let url = '/home/checkSlug';
            let qs = 'module=' + moduleField.value + '&value=' + uniqueField.value;
            url = url + '?' + qs;

            console.log('url', url);
            fetch(url)
                .then(response => {
                    console.log('response', response);
                    return response.json();
                })
                .then(data => {
                    console.log('data', data);
                    slugField.value = data.slug
                })
        });
    }
    // generateSlug();


    // script utk delete item
    const deleteLinks = document.querySelectorAll('form[id^="delete-form"] a');
    deleteLinks.forEach((elem, index) => {
        elem.addEventListener('click', function() {
            if (confirm('Data will be deleted. Continue?')) {
                elem.closest('form').submit();
            }
        });
    })
});


function addThousandSeparator(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function removeNonNumeric(x) {
    return x.toString().replace(/\D/g,'');
}
