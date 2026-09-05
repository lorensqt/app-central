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
        if (user.email === 'castillojohnlaurence0@gmail.com') {
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
