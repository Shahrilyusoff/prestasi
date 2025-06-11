import './bootstrap';
import 'bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    // === Sidebar toggle ===
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });
    }

    // === Highlight active menu item ===
    const currentPath = window.location.pathname;
    const menuItems = document.querySelectorAll('.sidebar-menu a');

    menuItems.forEach(item => {
        if (item.getAttribute('href') === currentPath) {
            item.parentElement.classList.add('active');
        }
    });

    // === Dynamic row addition and removal ===
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-row') || e.target.closest('.add-row')) {
            const button = e.target.classList.contains('add-row') ? e.target : e.target.closest('.add-row');
            const tableId = button.getAttribute('data-table');
            const table = document.getElementById(tableId);
            const tbody = table.querySelector('tbody');
            const lastRow = tbody.lastElementChild;
            const newRow = lastRow.cloneNode(true);

            // Clear inputs in the new row
            newRow.querySelectorAll('input, textarea, select').forEach(input => {
                if (input.type !== 'button' && !input.classList.contains('add-row') && !input.classList.contains('remove-row')) {
                    input.value = '';
                    input.removeAttribute('disabled');
                }
            });

            // Convert "Add" button to "Remove" button
            const addButton = newRow.querySelector('.add-row');
            if (addButton) {
                addButton.classList.remove('btn-success', 'add-row');
                addButton.classList.add('btn-danger', 'remove-row');
                addButton.innerHTML = '<i class="fas fa-minus"></i>';
            }

            tbody.appendChild(newRow);
        }

        // Remove row
        if (e.target.classList.contains('remove-row') || e.target.closest('.remove-row')) {
            const button = e.target.classList.contains('remove-row') ? e.target : e.target.closest('.remove-row');
            const row = button.closest('tr');
            if (row && row.parentElement.querySelectorAll('tr').length > 1) {
                row.remove();
            }
        }
    });

    // === Live mark calculation ===
    document.addEventListener('input', function (e) {
        if (e.target.matches('select[name^="markah_ppp"], select[name^="markah_ppk"]')) {
            updateMarksDisplay();
        }
    });

    function updateMarksDisplay() {
        // You can implement your live scoring logic here
        // Example: update a total field or display live average
    }
});
