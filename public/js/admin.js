// resources/views/js/admin.js

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function () {
    // Initialize sidebar functionality
    initializeSidebar();
    
    // Initialize modal functionality
    initializeModals();
    
    // Initialize tooltips if needed
    initializeTooltips();
    
    // Initialize other UI components
    initializeUIComponents();
});

/**
 * Initialize sidebar functionality
 */
function initializeSidebar() {
    const hamburger = document.querySelector('.hamburger');
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');

    if (hamburger && sidebar) {
        // Toggle sidebar on hamburger click
        hamburger.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            
            // Add slide-in animation
            if (sidebar.classList.contains('active')) {
                sidebar.classList.add('slide-in');
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function (event) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('active');
            }
        });
    }
}

/**
 * Initialize modal functionality
 */
function initializeModals() {
    // Modal open buttons
    const modalTriggers = document.querySelectorAll('[data-modal]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function (e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            openModal(modalId);
        });
    });

    // Modal close buttons
    const closeButtons = document.querySelectorAll('.modal-close, [data-modal-close]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal);
            }
        });
    });

    // Close modal when clicking outside
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal(this);
            }
        });
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                closeModal(openModal);
            }
        }
    });
}

/**
 * Open modal by ID
 * @param {string} modalId 
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        // Focus trap for accessibility
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        if (focusableElements.length > 0) {
            focusableElements[0].focus();
        }
    }
}

/**
 * Close modal
 * @param {HTMLElement} modal 
 */
function closeModal(modal) {
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

/**
 * Show tooltip
 * @param {Event} e 
 */
function showTooltip(e) {
    const element = e.target;
    const tooltipText = element.getAttribute('data-tooltip');
    
    if (tooltipText) {
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = tooltipText;
        tooltip.style.cssText = `
            position: absolute;
            background: #374151;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            z-index: 1000;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s ease;
        `;
        
        document.body.appendChild(tooltip);
        
        // Position tooltip
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
        
        // Show tooltip
        setTimeout(() => {
            tooltip.style.opacity = '1';
        }, 10);
        
        // Store reference for cleanup
        element._tooltip = tooltip;
    }
}

/**
 * Hide tooltip
 * @param {Event} e 
 */
function hideTooltip(e) {
    const element = e.target;
    if (element._tooltip) {
        element._tooltip.remove();
        delete element._tooltip;
    }
}

/**
 * Initialize other UI components
 */
function initializeUIComponents() {
    // Initialize tab switching
    initializeTabs();
    
    // Initialize file inputs
    initializeFileInputs();
    
    // Initialize search functionality
    initializeSearch();
    
    // Initialize form validation
    initializeFormValidation();
}

/**
 * Initialize tab functionality
 */
function initializeTabs() {
    const tabLinks = document.querySelectorAll('.tab-link');
    
    tabLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all tabs and contents
            document.querySelectorAll('.tab-link').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Show target content
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.remove('hidden');
                targetContent.classList.add('active', 'fade-in');
            }
        });
    });
}

/**
 * Initialize file input styling
 */
function initializeFileInputs() {
    const fileInputs = document.querySelectorAll('.file-input-wrapper input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function () {
            const wrapper = this.closest('.file-input-wrapper');
            const label = wrapper.querySelector('.file-input-label');
            
            if (label && this.files.length > 0) {
                const fileName = this.files[0].name;
                label.textContent = fileName;
            }
        });
    });
}

/**
 * Initialize search functionality
 */
function initializeSearch() {
    const searchInputs = document.querySelectorAll('[data-search]');
    
    searchInputs.forEach(input => {
        let debounceTimer;
        
        input.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            const targetSelector = this.getAttribute('data-search');
            
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                performSearch(searchTerm, targetSelector);
            }, 300);
        });
    });
}

/**
 * Perform search filtering
 * @param {string} searchTerm 
 * @param {string} targetSelector 
 */
function performSearch(searchTerm, targetSelector) {
    const targets = document.querySelectorAll(targetSelector);
    
    targets.forEach(target => {
        const text = target.textContent.toLowerCase();
        const shouldShow = text.includes(searchTerm);
        
        target.style.display = shouldShow ? '' : 'none';
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Validate form
 * @param {HTMLFormElement} form 
 * @returns {boolean}
 */
function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'Field ini wajib diisi');
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });
    
    return isValid;
}

/**
 * Show field error
 * @param {HTMLElement} field 
 * @param {string} message 
 */
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('border-red-500');
    
    const error = document.createElement('div');
    error.className = 'field-error text-red-500 text-sm mt-1';
    error.textContent = message;
    
    field.parentNode.appendChild(error);
}

/**
 * Clear field error
 * @param {HTMLElement} field 
 */
function clearFieldError(field) {
    field.classList.remove('border-red-500');
    const error = field.parentNode.querySelector('.field-error');
    if (error) {
        error.remove();
    }
}

/**
 * Utility function to show notifications
 * @param {string} message 
 * @param {string} type 
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${getNotificationClasses(type)}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.add('translate-x-0');
    }, 10);
    
    // Auto remove
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

/**
 * Get notification classes based on type
 * @param {string} type 
 * @returns {string}
 */
function getNotificationClasses(type) {
    const classes = {
        'success': 'bg-green-500 text-white',
        'error': 'bg-red-500 text-white',
        'warning': 'bg-yellow-500 text-white',
        'info': 'bg-blue-500 text-white'
    };
    
    return classes[type] || classes.info;
}

// Export functions for external use if needed
if (typeof window !== 'undefined') {
    window.AdminJS = {
        openModal,
        closeModal,
        showNotification
    };
}