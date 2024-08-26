
// Disable Ctrl+Shift+C
document.addEventListener("keydown", function (e) {
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'C') {
        Swal.fire({
            icon: 'warning',
            title: 'Action Disabled',
            text: "This function is disabled.",
            showConfirmButton: true
        });
        e.preventDefault();
    }
});

// Disable Ctrl+Shift+I
document.addEventListener("keydown", function (e) {
    if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'I') {
        Swal.fire({
            icon: 'warning',
            title: 'Action Disabled',
            text: "This function is disabled.",
            showConfirmButton: true
        });
        e.preventDefault();
    }
});

// Disable right-click
document.addEventListener("contextmenu", function (e) {
    Swal.fire({
        icon: 'warning',
        title: 'Action Disabled',
        text: "Right-click is disabled.",
        showConfirmButton: true
    });
    e.preventDefault();
});
