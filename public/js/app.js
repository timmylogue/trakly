// Trakly App JavaScript

// Mobile Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggle = document.getElementById('navbarToggle');
    const navbarMenu = document.getElementById('navbarMenu');
    
    if (navbarToggle && navbarMenu) {
        navbarToggle.addEventListener('click', function() {
            navbarToggle.classList.toggle('active');
            navbarMenu.classList.toggle('active');
            document.body.style.overflow = navbarMenu.classList.contains('active') ? 'hidden' : '';
        });
        
        // Close menu when clicking a link
        const menuLinks = navbarMenu.querySelectorAll('a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                navbarToggle.classList.remove('active');
                navbarMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (navbarMenu.classList.contains('active') && 
                !navbarMenu.contains(event.target) && 
                !navbarToggle.contains(event.target)) {
                navbarToggle.classList.remove('active');
                navbarMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
});

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Confirmation dialogs
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Number formatting
function formatCurrency(amount, symbol = '$') {
    return symbol + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

// Date formatting
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.style.borderColor = 'var(--danger-color)';
        } else {
            input.style.borderColor = '';
        }
    });
    
    return isValid;
}

// Mobile menu toggle (if needed)
function toggleMobileMenu() {
    const menu = document.querySelector('.navbar-menu');
    if (menu) {
        menu.classList.toggle('active');
    }
}

// Local storage helpers
const Storage = {
    set: function(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch (e) {
            console.error('Storage error:', e);
            return false;
        }
    },
    
    get: function(key) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : null;
        } catch (e) {
            console.error('Storage error:', e);
            return null;
        }
    },
    
    remove: function(key) {
        try {
            localStorage.removeItem(key);
            return true;
        } catch (e) {
            console.error('Storage error:', e);
            return false;
        }
    }
};

// Dark mode toggle (future feature)
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    Storage.set('darkMode', isDark);
}

// Initialize dark mode from storage
document.addEventListener('DOMContentLoaded', function() {
    const isDarkMode = Storage.get('darkMode');
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
    }
});

// Transaction form helpers
document.addEventListener('DOMContentLoaded', function() {
    // Auto-categorize based on keywords (Premium feature)
    const noteInput = document.querySelector('input[name="note"], textarea[name="note"]');
    const categorySelect = document.querySelector('select[name="category_id"]');
    
    if (noteInput && categorySelect) {
        noteInput.addEventListener('blur', function() {
            // This would be enhanced with AJAX to check auto-categorization rules
            const note = this.value.toLowerCase();
            
            // Simple client-side categorization examples
            const categories = {
                'grocery': 'Groceries',
                'walmart': 'Groceries',
                'amazon': 'Shopping',
                'netflix': 'Entertainment',
                'spotify': 'Entertainment',
                'gas': 'Transportation',
                'uber': 'Transportation'
            };
            
            for (const [keyword, categoryName] of Object.entries(categories)) {
                if (note.includes(keyword)) {
                    // Find and select the category
                    const options = categorySelect.options;
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].text.includes(categoryName)) {
                            categorySelect.selectedIndex = i;
                            break;
                        }
                    }
                    break;
                }
            }
        });
    }
    
    // Set default date to today
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        if (!input.value) {
            input.value = new Date().toISOString().split('T')[0];
        }
    });
});

// Chart helpers
function createChart(canvasId, type, data, options = {}) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return null;
    
    const ctx = canvas.getContext('2d');
    return new Chart(ctx, {
        type: type,
        data: data,
        options: options
    });
}

// Export functions
window.Trakly = {
    formatCurrency,
    formatDate,
    validateForm,
    confirmDelete,
    toggleDarkMode,
    Storage,
    createChart
};
