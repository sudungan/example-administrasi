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

function swalNotificationWarning(message) {
     Swal.fire({
        icon: "warning",
        title: "Oops...",
        text: message,
    });
}

function swalInternalServerError(message) {

}

// function generateMessageError (text, errors, key) {
//     let word = text.split(" ");
//      errors[key] = word.slice(1, -2).join(" ");
//     console.log('error', errors[key])
// };

function tester() {
console.log('runing');
}

function resetFields(target) {
     Object.keys(target).forEach(key => {
        const value = target[key];

        if (Array.isArray(value)) {
            target[key] = [];
        } else if (typeof value === 'object' && value !== null) {
            target[key] = {};
        } else {
            target[key] = '';
        }
    });
}

function formatTime(time) {
    return time.slice(0,5)
}
// const formatTime = (time) => time.slice(0, 5);
