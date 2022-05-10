import swal from 'sweetalert';

const deleteForms = document.querySelectorAll('.form__delete');
if (deleteForms.length > 0) {
    deleteForms.forEach(form => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            swal("Are you sure you want to do this?", {
                buttons: ["Cancel", "Yes"],
            }).then((value) => {
                if (value === true) {
                    form.submit();
                }
            });
        });
    });
}


