/**
 * Main JavaScript File
 * 
 * Handles sidebar toggle, form validation, and other
 * interactive features for the Smart Billing System.
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ========================================
    // Sidebar Toggle Functionality
    // ========================================
    
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    }
    
    // ========================================
    // Auto-hide Alerts
    // ========================================
    
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000); // Auto-hide after 5 seconds
    });
    
    // ========================================
    // Confirm Delete Actions
    // ========================================
    
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // ========================================
    // Form Validation Helper
    // ========================================
    
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // ========================================
    // Number Formatting
    // ========================================
    
    function formatCurrency(amount) {
        return '₹' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }
    
    // Make formatCurrency available globally
    window.formatCurrency = formatCurrency;
    
    // ========================================
    // Print Functionality
    // ========================================
    
    const printButtons = document.querySelectorAll('.btn-print');
    printButtons.forEach(button => {
        button.addEventListener('click', function() {
            window.print();
        });
    });
    
    // ========================================
    // Tooltip Initialization
    // ========================================
    
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // ========================================
    // Loading Spinner Helper
    // ========================================
    
    function showLoading() {
        const spinner = document.createElement('div');
        spinner.id = 'loading-spinner';
        spinner.className = 'position-fixed top-50 start-50 translate-middle';
        spinner.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
        document.body.appendChild(spinner);
    }
    
    function hideLoading() {
        const spinner = document.getElementById('loading-spinner');
        if (spinner) {
            spinner.remove();
        }
    }
    
    // Make loading functions available globally
    window.showLoading = showLoading;
    window.hideLoading = hideLoading;
    
    // ========================================
    // Search Table Functionality
    // ========================================
    
    const searchInputs = document.querySelectorAll('.table-search');
    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = this.closest('.card').querySelector('table tbody');
            const rows = table.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
    });
    
    // ========================================
    // Console Log for Debugging
    // ========================================
    
    console.log('Smart Billing System - JavaScript Loaded Successfully');
});
