// Multi-Step Form Wizard - Improved Version
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 5;

    // Step Navigation
    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                goToStep(currentStep + 1);
            }
        });
    });

    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            goToStep(currentStep - 1);
        });
    });

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;

        // Hide current step
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        document.querySelector(`.step-indicator[data-step="${currentStep}"]`).classList.remove('active');
        
        // Mark previous steps as completed
        if (step > currentStep) {
            document.querySelector(`.step-indicator[data-step="${currentStep}"]`).classList.add('completed');
        } else {
            // Remove completed status if going back
            for (let i = step; i < totalSteps; i++) {
                document.querySelector(`.step-indicator[data-step="${i + 1}"]`)?.classList.remove('completed');
            }
        }

        // Show new step
        currentStep = step;
        document.getElementById(`step-${currentStep}`).classList.add('active');
        document.querySelector(`.step-indicator[data-step="${currentStep}"]`).classList.add('active');

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
        let isValid = true;
        const stepElement = document.getElementById(`step-${step}`);

        // Step 1: Validate required fields
        if (step === 1) {
            const name = stepElement.querySelector('[name="name"]');
            const email = stepElement.querySelector('[name="email"]');

            if (!name.value.trim()) {
                showError(name, 'Name is required');
                isValid = false;
            } else {
                clearError(name);
            }

            if (!email.value.trim() || !isValidEmail(email.value)) {
                showError(email, 'Valid email is required');
                isValid = false;
            } else {
                clearError(email);
            }
        }

        // Step 2: Validate travel details
        if (step === 2) {
            const adultsCount = stepElement.querySelector('[name="adults_count"]');
            
            if (!adultsCount.value || adultsCount.value < 1) {
                showError(adultsCount, 'At least 1 adult is required');
                isValid = false;
            } else {
                clearError(adultsCount);
            }
        }

        return isValid;
    }

    function showError(element, message) {
        element.classList.add('border-red-500');
        
        // Remove existing error message
        const existingError = element.parentElement.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Add error message
        const errorDiv = document.createElement('p');
        errorDiv.className = 'error-message text-red-500 text-sm mt-1';
        errorDiv.textContent = message;
        element.parentElement.appendChild(errorDiv);

        // Shake animation
        element.classList.add('animate-shake');
        setTimeout(() => element.classList.remove('animate-shake'), 500);
    }

    function clearError(element) {
        element.classList.remove('border-red-500');
        const errorMessage = element.parentElement.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // ========== DESTINATION SELECTION ==========
    const destinationCheckboxes = document.querySelectorAll('.destination-checkbox');
    const selectedDestinationsSummary = document.getElementById('selected-destinations-summary');
    const selectedDestinationsList = document.getElementById('selected-destinations-list');
    const selectedCount = document.getElementById('selected-count');

    destinationCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedDestinations);
    });

    function updateSelectedDestinations() {
        const selected = Array.from(destinationCheckboxes).filter(cb => cb.checked);
        selectedCount.textContent = selected.length;

        if (selected.length > 0) {
            selectedDestinationsSummary.classList.remove('hidden');
            selectedDestinationsList.innerHTML = '';

            selected.forEach(checkbox => {
                const label = checkbox.closest('.destination-item');
                const name = label.querySelector('h4').textContent.trim();
                const tag = document.createElement('span');
                tag.className = 'bg-green-100 text-green-800 px-3 py-1.5 rounded-full text-sm font-medium inline-flex items-center';
                tag.innerHTML = `
                    <i class="fas fa-map-marker-alt mr-1.5 text-xs"></i>
                    ${name}
                `;
                selectedDestinationsList.appendChild(tag);
            });
        } else {
            selectedDestinationsSummary.classList.add('hidden');
        }
    }

    // ========== ACTIVITY SELECTION ==========
    const activityCheckboxes = document.querySelectorAll('.activity-checkbox');
    const selectedActivitiesSummary = document.getElementById('selected-activities-summary');
    const selectedActivitiesList = document.getElementById('selected-activities-list');
    const selectedActivitiesCount = document.getElementById('selected-activities-count');

    activityCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedActivities);
    });

    function updateSelectedActivities() {
        const selected = Array.from(activityCheckboxes).filter(cb => cb.checked);
        selectedActivitiesCount.textContent = selected.length;

        if (selected.length > 0) {
            selectedActivitiesSummary.classList.remove('hidden');
            selectedActivitiesList.innerHTML = '';

            selected.forEach(checkbox => {
                const label = checkbox.closest('.activity-item');
                const name = label.querySelector('h4').textContent.trim();
                const tag = document.createElement('span');
                tag.className = 'bg-orange-100 text-orange-800 px-3 py-1.5 rounded-full text-sm font-medium inline-flex items-center';
                tag.innerHTML = `
                    <i class="fas fa-hiking mr-1.5 text-xs"></i>
                    ${name}
                `;
                selectedActivitiesList.appendChild(tag);
            });
        } else {
            selectedActivitiesSummary.classList.add('hidden');
        }
    }

    // ========== DESTINATION SEARCH & FILTER ==========
    const destinationSearch = document.getElementById('destination-search');
    const countryFilter = document.getElementById('country-filter');
    const destinationItems = document.querySelectorAll('.destination-item');
    const countryGroups = document.querySelectorAll('.country-group');
    const noDestinationsMessage = document.getElementById('no-destinations-message');

    destinationSearch?.addEventListener('input', debounce(filterDestinations, 300));
    countryFilter?.addEventListener('change', filterDestinations);

    function filterDestinations() {
        const searchTerm = destinationSearch.value.toLowerCase().trim();
        const selectedCountry = countryFilter.value;
        let visibleCount = 0;

        // Hide all country groups first
        countryGroups.forEach(group => group.style.display = 'none');

        destinationItems.forEach(item => {
            const name = item.dataset.name;
            const country = item.dataset.country;
            const parentGroup = item.closest('.country-group');

            const matchesSearch = !searchTerm || name.includes(searchTerm);
            const matchesCountry = !selectedCountry || country === selectedCountry;

            if (matchesSearch && matchesCountry) {
                item.style.display = 'block';
                if (parentGroup) {
                    parentGroup.style.display = 'block';
                }
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            noDestinationsMessage?.classList.remove('hidden');
        } else {
            noDestinationsMessage?.classList.add('hidden');
        }
    }

    // ========== ACTIVITY SEARCH & FILTER ==========
    const activitySearch = document.getElementById('activity-search');
    const categoryFilter = document.getElementById('category-filter');
    const activityItems = document.querySelectorAll('.activity-item');
    const categoryGroups = document.querySelectorAll('.category-group');
    const noActivitiesMessage = document.getElementById('no-activities-message');

    activitySearch?.addEventListener('input', debounce(filterActivities, 300));
    categoryFilter?.addEventListener('change', filterActivities);

    function filterActivities() {
        const searchTerm = activitySearch.value.toLowerCase().trim();
        const selectedCategory = categoryFilter.value;
        let visibleCount = 0;

        // Hide all category groups first
        categoryGroups.forEach(group => group.style.display = 'none');

        activityItems.forEach(item => {
            const name = item.dataset.name;
            const category = item.dataset.category;
            const parentGroup = item.closest('.category-group');

            const matchesSearch = !searchTerm || name.includes(searchTerm);
            const matchesCategory = !selectedCategory || category === selectedCategory;

            if (matchesSearch && matchesCategory) {
                item.style.display = 'block';
                if (parentGroup) {
                    parentGroup.style.display = 'block';
                }
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/hide no results message
        if (visibleCount === 0) {
            noActivitiesMessage?.classList.remove('hidden');
        } else {
            noActivitiesMessage?.classList.add('hidden');
        }
    }

    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // ========== BUDGET OPTION SELECTION ==========
    const budgetOptions = document.querySelectorAll('.budget-option');
    budgetOptions.forEach(option => {
        option.addEventListener('click', function() {
            budgetOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    // ========== FORM SUBMISSION ==========
    const form = document.getElementById('customTourForm');
    form.addEventListener('submit', function(e) {
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Submitting...';

        // Allow form to submit naturally
        // If there's an error, restore the button after timeout
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }, 5000);
    });

    // ========== DATE VALIDATION ==========
    const dateFrom = document.querySelector('[name="travel_date_from"]');
    const dateTo = document.querySelector('[name="travel_date_to"]');

    dateFrom?.addEventListener('change', function() {
        if (this.value) {
            dateTo.min = this.value;
            // Clear end date error if dates are now valid
            if (dateTo.value && dateTo.value >= this.value) {
                clearError(dateTo);
            }
        }
    });

    dateTo?.addEventListener('change', function() {
        if (dateFrom.value && this.value < dateFrom.value) {
            showError(this, 'End date must be after start date');
        } else {
            clearError(this);
        }
    });

    // ========== INITIALIZE ON PAGE LOAD ==========
    // Initialize selected items (for validation errors - old() data)
    updateSelectedDestinations();
    updateSelectedActivities();

    // ========== KEYBOARD NAVIGATION ==========
    document.addEventListener('keydown', function(e) {
        // Allow Enter key to move to next step (except in textareas)
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
            const nextBtn = document.querySelector(`#step-${currentStep} .next-btn`);
            if (nextBtn) {
                e.preventDefault();
                nextBtn.click();
            }
        }
    });

    // ========== SELECT ALL / DESELECT ALL SHORTCUTS ==========
    // Add keyboard shortcuts for power users
    const destinationsContainer = document.getElementById('destinations-container');
    const activitiesContainer = document.getElementById('activities-container');

    // Add "Select All" buttons (optional enhancement)
    if (destinationsContainer) {
        addSelectAllButton('destinations', destinationsContainer, destinationCheckboxes, updateSelectedDestinations);
    }

    if (activitiesContainer) {
        addSelectAllButton('activities', activitiesContainer, activityCheckboxes, updateSelectedActivities);
    }

    function addSelectAllButton(type, container, checkboxes, updateFunction) {
        const buttonContainer = document.createElement('div');
        buttonContainer.className = 'flex justify-end mb-3';
        
        const selectAllBtn = document.createElement('button');
        selectAllBtn.type = 'button';
        selectAllBtn.className = 'text-sm text-green-600 hover:text-green-700 font-medium';
        selectAllBtn.innerHTML = `<i class="fas fa-check-double mr-1"></i> Select All Visible`;
        
        const deselectAllBtn = document.createElement('button');
        deselectAllBtn.type = 'button';
        deselectAllBtn.className = 'text-sm text-gray-600 hover:text-gray-700 font-medium ml-4';
        deselectAllBtn.innerHTML = `<i class="fas fa-times mr-1"></i> Deselect All`;
        
        selectAllBtn.addEventListener('click', function() {
            Array.from(checkboxes).forEach(cb => {
                const item = cb.closest(type === 'destinations' ? '.destination-item' : '.activity-item');
                if (item && item.style.display !== 'none') {
                    cb.checked = true;
                }
            });
            updateFunction();
        });
        
        deselectAllBtn.addEventListener('click', function() {
            Array.from(checkboxes).forEach(cb => cb.checked = false);
            updateFunction();
        });
        
        buttonContainer.appendChild(selectAllBtn);
        buttonContainer.appendChild(deselectAllBtn);
        container.parentElement.insertBefore(buttonContainer, container);
    }

    // ========== MOBILE OPTIMIZATION ==========
    // Adjust behavior for mobile devices
    function isMobile() {
        return window.innerWidth < 768;
    }

    // Make sure mobile users can easily tap checkboxes
    if (isMobile()) {
        document.querySelectorAll('.destination-item, .activity-item').forEach(item => {
            item.style.minHeight = '60px'; // Ensure tappable area
        });
    }

    // ========== PROGRESS PERSISTENCE ==========
    // Save form progress to sessionStorage
    const formInputs = form.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        // Load saved value
        const savedValue = sessionStorage.getItem(`customTour_${input.name}`);
        if (savedValue && !input.value) {
            if (input.type === 'checkbox') {
                input.checked = savedValue === 'true';
            } else {
                input.value = savedValue;
            }
        }

        // Save on change
        input.addEventListener('change', function() {
            if (input.type === 'checkbox') {
                sessionStorage.setItem(`customTour_${input.name}`, input.checked);
            } else {
                sessionStorage.setItem(`customTour_${input.name}`, input.value);
            }
        });
    });

    // Clear session storage on successful submission
    form.addEventListener('submit', function() {
        // Clear after a short delay to allow form to submit
        setTimeout(() => {
            formInputs.forEach(input => {
                sessionStorage.removeItem(`customTour_${input.name}`);
            });
        }, 1000);
    });
});