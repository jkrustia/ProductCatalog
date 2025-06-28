function showFilterPanel() {
    document.getElementById('filterPanel').classList.remove('d-none');
    document.getElementById('filterPanel').classList.add('d-lg-block');
    document.getElementById('filterIconShow').classList.add('d-none');
}
function hideFilterPanel() {
    document.getElementById('filterPanel').classList.remove('d-lg-block');
    document.getElementById('filterPanel').classList.add('d-none');
    document.getElementById('filterIconShow').classList.remove('d-none');
}

// document.addEventListener('DOMContentLoaded', function () {
//     const filterPanel = document.getElementById('filterPanel');
//     if (filterPanel) {
//         const form = filterPanel.querySelector('form');
//         if (form) {
//             form.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
//                 checkbox.addEventListener('change', function () {
//                     form.submit();
//                 });
//             });
//         }
//     }
// });