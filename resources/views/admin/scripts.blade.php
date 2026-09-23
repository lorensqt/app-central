<script>
    function switchAdminTab(tabId) {
        // Hide all sub-panels
        document.querySelectorAll('.admin-tab-panel').forEach(panel => {
            panel.classList.add('hidden');
        });

        // Reset tab buttons classes
        document.querySelectorAll('.admin-tab-btn').forEach(btn => {
            btn.classList.remove('border-purple-600', 'dark:border-purple-500', 'text-purple-600', 'dark:text-purple-400', 'font-semibold');
            btn.classList.add('border-transparent', 'text-slate-500', 'dark:text-slate-400', 'hover:border-slate-300', 'dark:hover:border-slate-700',
                'hover:text-slate-700', 'dark:hover:text-slate-200');
        });

        // Show active panel
        const activePanel = document.getElementById('panel-' + tabId);
        if (activePanel) {
            activePanel.classList.remove('hidden');
        }

        // Highlight active button
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-slate-500', 'dark:text-slate-400', 'hover:border-slate-300', 'dark:hover:border-slate-700',
                'hover:text-slate-700', 'dark:hover:text-slate-200');
            activeBtn.classList.add('border-purple-600', 'dark:border-purple-500', 'text-purple-600', 'dark:text-purple-400', 'font-semibold');
        }

        // Keep current sub-tab in URL query state
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.history.pushState({}, '', url);
    }

    // Modal Control: Whitelisted User Edit
    function openUserEditModal(user) {
        const modal = document.getElementById('user-edit-modal');
        const content = document.getElementById('user-edit-modal-content');
        const form = document.getElementById('user-edit-form');

        form.action = `/admin/users/${user.id}`;

        document.getElementById('edit_user_name').value = user.name;
        document.getElementById('edit_user_email').value = user.email;
        document.getElementById('edit_user_title').value = user.title_id || '';

        const emailInput = document.getElementById('edit_user_email');
        const superAdminEmail = "{{ config('app.super_admin_email') }}";
        if (superAdminEmail && user.email === superAdminEmail) {
            emailInput.setAttribute('readonly', 'true');
            emailInput.classList.add('bg-slate-50', 'dark:bg-slate-950', 'text-slate-400', 'dark:text-slate-500');
        } else {
            emailInput.removeAttribute('readonly');
            emailInput.classList.remove('bg-slate-50', 'dark:bg-slate-950', 'text-slate-400', 'dark:text-slate-500');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            if (content) {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
        }, 10);
    }

    function closeUserEditModal() {
        const modal = document.getElementById('user-edit-modal');
        const content = document.getElementById('user-edit-modal-content');

        modal.classList.add('opacity-0');
        if (content) {
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
        }

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    // Modal Control: Whitelist User Create
    function openWhitelistCreateModal() {
        const modal = document.getElementById('whitelist-create-modal');
        const content = document.getElementById('whitelist-create-modal-content');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            if (content) {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
        }, 10);
    }

    function closeWhitelistCreateModal() {
        const modal = document.getElementById('whitelist-create-modal');
        const content = document.getElementById('whitelist-create-modal-content');

        modal.classList.add('opacity-0');
        if (content) {
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
        }

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    // Modal Control: Register Administrator Create
    function openAdminCreateModal() {
        const modal = document.getElementById('admin-create-modal');
        const content = document.getElementById('admin-create-modal-content');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            if (content) {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
        }, 10);
    }

    function closeAdminCreateModal() {
        const modal = document.getElementById('admin-create-modal');
        const content = document.getElementById('admin-create-modal-content');

        modal.classList.add('opacity-0');
        if (content) {
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
        }

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    // Modal Control: Title Designation Edit
    function openTitleEditModal(title) {
        const modal = document.getElementById('title-edit-modal');
        const content = document.getElementById('title-edit-modal-content');
        const form = document.getElementById('title-edit-form');

        form.action = `/admin/titles/${title.id}`;

        document.getElementById('edit_title_group').value = title.group;
        document.getElementById('edit_title_name').value = title.title;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            if (content) {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
        }, 10);
    }

    function closeTitleEditModal() {
        const modal = document.getElementById('title-edit-modal');
        const content = document.getElementById('title-edit-modal-content');

        modal.classList.add('opacity-0');
        if (content) {
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
        }

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    // Modal Control: Custom Committee Edit
    function openCommitteeEditModal(committee) {
        const modal = document.getElementById('committee-edit-modal');
        const content = document.getElementById('committee-edit-modal-content');
        const form = document.getElementById('committee-edit-form');

        form.action = `/admin/committees/${committee.id}`;

        document.getElementById('edit_committee_name').value = committee.name;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            if (content) {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }
        }, 10);
    }

    // Modal Control: Close Custom Committee Edit
    function closeCommitteeEditModal() {
        const modal = document.getElementById('committee-edit-modal');
        const content = document.getElementById('committee-edit-modal-content');

        modal.classList.add('opacity-0');
        if (content) {
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
        }

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    // Whitelisted Access Directory Real-Time Filters
    function filterWhitelist() {
        const query = document.getElementById('whitelist-search-input').value.toLowerCase();
        const group = document.getElementById('filter-group').value;
        const status = document.getElementById('filter-status').value;
        const rows = document.querySelectorAll('.whitelist-user-row');

        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const rowGroup = row.getAttribute('data-group') || '';
            const rowStatus = row.getAttribute('data-status') || '';

            const matchesSearch = name.includes(query) || email.includes(query);
            const matchesGroup = group === '' ||
                (group === 'guest' && rowGroup === 'guest') ||
                (rowGroup === group);
            const matchesStatus = status === '' || rowStatus === status;

            if (matchesSearch && matchesGroup && matchesStatus) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        // Update authorized count badge dynamically
        const badge = document.getElementById('visible-whitelist-count');
        if (badge) {
            badge.innerText = visibleCount;
        }

        // Handle empty state within the table
        const emptyState = document.getElementById('whitelist-empty-state');
        const tableBody = document.getElementById('whitelist-table-body');
        const tableHeader = document.querySelector('#panel-users table');

        if (visibleCount === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            if (tableBody) tableBody.classList.add('hidden');
            if (tableHeader) tableHeader.classList.add('hidden');
        } else {
            if (emptyState) emptyState.classList.add('hidden');
            if (tableBody) tableBody.classList.remove('hidden');
            if (tableHeader) tableHeader.classList.remove('hidden');
        }
    }

    // Designations Directory Real-Time Filters
    function filterDesignations() {
        const query = document.getElementById('designation-search-input').value.toLowerCase();
        const rows = document.querySelectorAll('.designation-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const title = row.getAttribute('data-title') || '';
            const group = row.getAttribute('data-group') || '';

            if (title.includes(query) || group.includes(query)) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        const emptyState = document.getElementById('designation-empty-state');
        const tableBody = document.getElementById('designation-table-body');
        const tableHeader = document.getElementById('designation-table-header');

        if (visibleCount === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            if (tableBody) tableBody.classList.add('hidden');
            if (tableHeader) tableHeader.classList.add('hidden');
        } else {
            if (emptyState) emptyState.classList.add('hidden');
            if (tableBody) tableBody.classList.remove('hidden');
            if (tableHeader) tableHeader.classList.remove('hidden');
        }
    }

    // Close modals on backdrop click
    window.onclick = function(event) {
        const userModal = document.getElementById('user-edit-modal');
        const titleModal = document.getElementById('title-edit-modal');
        const createModal = document.getElementById('whitelist-create-modal');
        const committeeModal = document.getElementById('committee-edit-modal');
        const adminModal = document.getElementById('admin-create-modal');
        if (event.target == userModal) closeUserEditModal();
        if (event.target == titleModal) closeTitleEditModal();
        if (event.target == createModal) closeWhitelistCreateModal();
        if (event.target == committeeModal) closeCommitteeEditModal();
        if (event.target == adminModal) closeAdminCreateModal();
    }

    // Toggle registrations row collapse
    function toggleRegistrations(eventId) {
        const row = document.getElementById('event-regs-' + eventId);
        if (row) {
            row.classList.toggle('hidden');
        }
    }

    // Copy event shareable link to clipboard
    function copyEventLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            if (window.showToast) {
                window.showToast("Shareable event link copied to clipboard!", "success");
            } else {
                alert("Shareable link copied!");
            }
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    }

    // Close all admin active modals
    function closeActiveModals() {
        if (typeof closeUserEditModal === 'function') closeUserEditModal();
        if (typeof closeWhitelistCreateModal === 'function') closeWhitelistCreateModal();
        if (typeof closeAdminCreateModal === 'function') closeAdminCreateModal();
        if (typeof closeTitleEditModal === 'function') closeTitleEditModal();
        if (typeof closeCommitteeEditModal === 'function') closeCommitteeEditModal();
    }

    // Modern Button Loading Spinner Toggle
    function setFormLoading(form, isLoading) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (!submitBtn) return;

        if (isLoading) {
            submitBtn.disabled = true;
            submitBtn.dataset.originalHtml = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;
        } else {
            submitBtn.disabled = false;
            if (submitBtn.dataset.originalHtml) {
                submitBtn.innerHTML = submitBtn.dataset.originalHtml;
                delete submitBtn.dataset.originalHtml;
            }
        }
    }

    // Submit form using AJAX & surgically swap updated elements
    function submitFormAjax(form) {
        setFormLoading(form, true);

        const formData = new FormData(form);
        const action = form.getAttribute('action') || window.location.href;

        fetch(action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            // Because Laravel automatically handles redirect, fetch follows it to /admin?...
            // We read the final followed response as text (HTML)
            return response.text();
        })
        .then(htmlText => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlText, 'text/html');

            // 1. Check for validation errors in the returned HTML
            const newErrorContainer = doc.getElementById('admin-error-container');
            const localErrorContainer = document.getElementById('admin-error-container');

            if (newErrorContainer && newErrorContainer.querySelector('.p-4')) {
                // Validation error occurred!
                if (localErrorContainer) {
                    localErrorContainer.innerHTML = newErrorContainer.innerHTML;
                    localErrorContainer.classList.remove('hidden');
                    // Scroll to error container with smooth behavior
                    localErrorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                setFormLoading(form, false);
                return;
            }

            // Success! Clear any existing validation errors
            if (localErrorContainer) {
                localErrorContainer.innerHTML = '';
                localErrorContainer.classList.add('hidden');
            }

            // 2. Extract and fire success/error toast messages
            const statusEl = doc.getElementById('admin-status-message');
            const errorEl = doc.getElementById('admin-error-message');

            if (statusEl && window.showToast) {
                window.showToast(statusEl.dataset.message, 'success');
            } else if (errorEl && window.showToast) {
                window.showToast(errorEl.dataset.message, 'error');
            }

            // 3. Dismiss any active modals
            closeActiveModals();

            // 4. Surgically update specific elements on the page without full refresh
            const elementsToUpdate = [
                'admin-stats-row',
                'whitelist-directory-card',
                'committees-dashboard-card',
                'titles-directory-card',
                'title_group',
                'create_user_title',
                'edit_user_title',
                'filter-group'
            ];

            elementsToUpdate.forEach(id => {
                const newEl = doc.getElementById(id);
                const localEl = document.getElementById(id);
                if (newEl && localEl) {
                    localEl.innerHTML = newEl.innerHTML;
                }
            });

            // 5. Reset inputs if it is a "create" form
            if (form.id !== 'user-edit-form' && form.id !== 'title-edit-form' && form.id !== 'committee-edit-form') {
                form.reset();
            }

            // 6. Re-apply any active real-time search queries & dropdown filters
            if (typeof filterWhitelist === 'function') filterWhitelist();
            if (typeof filterDesignations === 'function') filterDesignations();

            setFormLoading(form, false);
        })
        .catch(err => {
            console.error('AJAX form submission failed:', err);
            if (window.showToast) {
                window.showToast('An error occurred. Please try again.', 'error');
            }
            setFormLoading(form, false);
        });
    }

    // Form submission interceptor using event capturing (runs before other listeners)
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const action = form.getAttribute('action') || '';

        // Only intercept admin-specific actions (to allow other forms like Logout to submit normally)
        if (!action.includes('/admin/')) {
            return;
        }

        e.preventDefault();
        e.stopImmediatePropagation();

        // Check if confirmation modal is required for this form
        if (form.hasAttribute('data-confirm')) {
            const title = form.getAttribute('data-confirm-title') || 'Confirm Action';
            const message = form.getAttribute('data-confirm');
            const subtext = form.getAttribute('data-confirm-sub') || '';

            if (window.showConfirmModal) {
                window.showConfirmModal(title, message, subtext, () => {
                    submitFormAjax(form);
                });
            } else if (confirm(`${title}\n\n${message}\n${subtext}`)) {
                submitFormAjax(form);
            }
        } else {
            submitFormAjax(form);
        }
    }, true); // Use capturing phase to execute BEFORE the global bubble-phase listener in layout

    // Initialize from URL search parameter or default to 'users'
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        let tab = urlParams.get('tab');

        if (!tab || !document.getElementById('panel-' + tab)) {
            tab = 'users';
        }
        switchAdminTab(tab);
    });
</script>
