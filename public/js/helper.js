function successNotification(message) {
    let updateNotification = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    updateNotification.fire({
        icon: 'success',
        title: message
    });
}

function cancelConfirmation(message, callback) {
    Swal.fire({
        title: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, batalkan!',
        cancelButtonText: 'Batal'
    }).then((result)=> {
        callback(result)
    })
}

function confirmDelete(message, callback) {
    Swal.fire({
        title: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        callback(result)
    });
}

function swalLoading(message, callback) {
    const loading = Swal.fire({
        title: 'Please wait...',
        text: message || 'Processing data',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            callback();
        }
    });
    return loading;
}

function swalNotificationConflict(message) {
    Swal.fire({
        icon: "error",
        title: "Oops...",
        text: message,
    });
}

function swalInternalServerError(message) {

}

function tester() {
console.log('runing');
}
