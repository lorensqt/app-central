<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300 overflow-x-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SAKO Central</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
        // Prevent FOUC (Flash of Unstyled Content)
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @php
        $faviconPath = resource_path('views/imgs/letter-s.png');
        $base64Favicon = '';
        if (file_exists($faviconPath)) {
            $base64Favicon = 'data:image/png;base64,' . base64_encode(file_get_contents($faviconPath));
        }
    @endphp
    @if($base64Favicon)
        <link rel="icon" type="image/png" href="{{ $base64Favicon }}">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom SweetAlert Input Overrides */
        .swal2-input.swal2-custom-input {
            box-shadow: none !important;
            height: auto !important;
            margin: 1rem auto 0 auto !important;
            width: 90% !important;
            max-width: 90% !important;
        }
        .swal2-input.swal2-custom-input:focus {
            box-shadow: 0 0 0 4px rgba(147, 51, 234, 0.1) !important;
            border-color: #9333ea !important;
        }
        /* Smooth Icon Rotation for Theme Toggle */
        .theme-icon-spin {
            animation: theme-spin 0.55s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes theme-spin {
            0% {
                transform: rotate(0deg) scale(0.7);
                opacity: 0.5;
            }
            100% {
                transform: rotate(360deg) scale(1);
                opacity: 1;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('styles')
</head>

<body class="min-h-screen flex flex-col justify-between relative bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300 overflow-x-hidden">
    <div>
        <!-- Top Navbar -->
        <nav class="bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800/80 sticky top-0 z-40 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Left: Brand -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                            @if($base64Favicon)
                                <img src="{{ $base64Favicon }}" alt="SAKO Central Logo" class="w-9 h-9 rounded-lg object-contain" />
                            @else
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-sm">
                                    SC
                                </span>
                            @endif
                            <span class="font-semibold text-slate-900 dark:text-white tracking-tight text-lg">SAKO Central</span>
                        </a>
                    </div>

                    <!-- Right: Premium Unified Profile Dropdown -->
                    <div class="flex items-center">
                        @auth
                            <!-- Elegant Theme Switcher Button -->
                            <button type="button" id="theme-toggle-navbar" class="mr-3 flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:border-slate-300 dark:hover:border-slate-700 text-slate-700 dark:text-slate-300 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95 focus:outline-none select-none" title="Switch Theme">
                                <i id="theme-icon-navbar" class="fa-solid text-lg transition-all duration-300"></i>
                            </button>

                            <div class="relative inline-block text-left" id="profile-dropdown-container">
                                <!-- Trigger Button -->
                                <button type="button" id="profile-dropdown-trigger" class="flex items-center gap-2.5 p-1.5 pl-3 pr-3.5 rounded-xl border border-slate-200/80 dark:border-slate-800/80 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:border-slate-300 dark:hover:border-slate-700 transition duration-150 focus:outline-none select-none">
                                    @if (Auth::user()->avatar)
                                        <img class="w-7 h-7 rounded-lg border border-slate-200/50 dark:border-slate-700/50 object-cover shrink-0"
                                            src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                                    @else
                                        <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center text-[10px] font-bold uppercase shrink-0">
                                            {{ substr(Auth::user()->name, 0, 2) }}
                                        </div>
                                    @endif
                                    
                                    <div class="text-left hidden sm:block">
                                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">
                                            {{ explode(' ', Auth::user()->name)[0] }} {{ explode(' ', Auth::user()->name)[1] ?? '' }}
                                        </p>
                                    </div>

                                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500 transition-transform duration-200 shrink-0" id="profile-dropdown-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown Menu Card -->
                                <div id="profile-dropdown-menu" class="hidden absolute right-0 mt-2.5 w-64 origin-top-right rounded-2xl border border-slate-200 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl shadow-xl z-50 transform scale-95 opacity-0 transition-all duration-200 ease-out focus:outline-none">
                                    <!-- User Summary -->
                                    <div class="p-4 border-b border-slate-100 dark:border-slate-800/60">
                                        <div class="flex items-start gap-3">
                                            @if (Auth::user()->avatar)
                                                <img class="w-10 h-10 rounded-xl border border-slate-200/50 dark:border-slate-700/50 object-cover shrink-0"
                                                    src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                                            @else
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center text-xs font-bold uppercase shrink-0">
                                                    {{ substr(Auth::user()->name, 0, 2) }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="text-sm font-extrabold text-slate-900 dark:text-white leading-tight truncate">
                                                    {{ Auth::user()->name }}
                                                </p>
                                                <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate mt-0.5 font-medium">
                                                    {{ Auth::user()->email }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Designations -->
                                        <div class="mt-4 space-y-1.5">
                                            <div class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Title & Roles</div>
                                            <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-semibold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/15 max-w-full truncate">
                                                    {{ Auth::user()->title ? (Auth::user()->title->title ?? Auth::user()->title->name) : (Auth::user()->isSuperAdmin() ? 'Platform Administrator' : 'Guest') }}
                                                </span>
                                                @if (Auth::user()->isSuperAdmin())
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/15 uppercase tracking-wide">
                                                        Super Admin
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions Group -->
                                    <div class="p-1.5 space-y-0.5">
                                        <!-- Integrated Theme Toggle Menu Item -->
                                        <button type="button" id="theme-toggle-dropdown" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 hover:text-slate-900 dark:hover:text-white transition duration-150">
                                            <span class="flex items-center gap-2.5">
                                                <i id="theme-icon-dropdown" class="fa-solid text-sm transition-all duration-300"></i>
                                                <span id="theme-toggle-text-dropdown">Switch Theme</span>
                                            </span>
                                            <span class="text-[9px] bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 py-0.5 px-1.5 rounded-md uppercase tracking-wider font-bold">Theme</span>
                                        </button>
                                    </div>

                                    <!-- Logout / Danger Section -->
                                    <div class="border-t border-slate-100 dark:border-slate-800/60 p-1.5">
                                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-bold text-red-600 dark:text-red-400 hover:bg-rose-50/50 dark:hover:bg-rose-950/20 transition duration-150 text-left">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                                <span>Logout Account</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content Wrapper -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200/80 dark:border-slate-800/80 py-6 text-center text-xs text-slate-400 dark:text-slate-500 mt-12 transition-colors duration-300">
        &copy; {{ date('Y') }} SAKO Central. All rights reserved.
    </footer>

    <!-- Toast & Confirm Engines (Powered by SweetAlert2) -->
    <script>
        // --- TOAST NOTIFICATIONS ENGINE (SWEETALERT2) ---
        window.showToast = function(message, type = 'success') {
            const isDark = document.documentElement.classList.contains('dark');
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xl'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            let swalType = 'info';
            if (type === 'success') swalType = 'success';
            if (type === 'error' || type === 'danger') swalType = 'error';
            if (type === 'warning') swalType = 'warning';

            Toast.fire({
                icon: swalType,
                title: message
            });
        };

        // --- CONFIRM MODAL ENGINE (SWEETALERT2) ---
        window.showConfirmModal = function(title, message, subtext, onConfirm) {
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: title,
                html: `
                    <div class="text-sm text-slate-650 dark:text-slate-350 leading-relaxed">${message}</div>
                    ${subtext ? `<div class="text-xs text-slate-400 dark:text-slate-500 mt-2 font-medium">${subtext}</div>` : ''}
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: isDark ? '#334155' : '#e2e8f0',
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xl',
                    confirmButton: 'rounded-xl text-xs font-semibold px-4 py-2.5 mx-1',
                    cancelButton: 'rounded-xl text-xs font-semibold px-4 py-2.5 mx-1 ' + (isDark ? 'text-slate-300' : 'text-slate-700')
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    onConfirm();
                }
            });
        };

        // --- PROMPT MODAL ENGINE (SWEETALERT2) ---
        window.showPromptModal = function(title, message, placeholder, onConfirm) {
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: title,
                html: `
                    <div class="text-sm text-slate-650 dark:text-slate-350 leading-relaxed">${message}</div>
                `,
                input: 'text',
                inputPlaceholder: placeholder,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // red/rose confirm for decline operations
                cancelButtonColor: isDark ? '#334155' : '#e2e8f0',
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
                background: isDark ? '#1e293b' : '#ffffff',
                color: isDark ? '#f1f5f9' : '#0f172a',
                customClass: {
                    popup: 'rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xl',
                    input: 'swal2-custom-input rounded-xl border border-slate-300 dark:border-slate-800 py-2.5 px-4 text-slate-700 dark:text-slate-200 text-sm focus:outline-none bg-white dark:bg-slate-950 transition-all duration-300 w-full',
                    confirmButton: 'rounded-xl text-xs font-semibold px-4 py-2.5 mx-1',
                    cancelButton: 'rounded-xl text-xs font-semibold px-4 py-2.5 mx-1 ' + (isDark ? 'text-slate-300' : 'text-slate-700')
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    onConfirm(result.value);
                }
            });
        };

        // Declarative submit listener for forms using data-confirm
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.hasAttribute('data-confirm')) {
                if (form.dataset.confirmed === 'true') {
                    return;
                }

                e.preventDefault();

                const title = form.getAttribute('data-confirm-title') || 'Confirm Deletion';
                const message = form.getAttribute('data-confirm');
                const subtext = form.getAttribute('data-confirm-sub') || '';

                window.showConfirmModal(title, message, subtext, () => {
                    form.dataset.confirmed = 'true';
                    form.submit();
                });
            }
        });

        // Automate flash toasts on load and initialize theme toggle buttons & dropdowns
        document.addEventListener('DOMContentLoaded', () => {
            @if (session('status') || session('success'))
                window.showToast("{{ session('status') ?? session('success') }}", 'success');
            @endif

            @if (session('error'))
                window.showToast("{{ session('error') }}", 'error');
            @endif

            // Unified Profile Dropdown Trigger & Click-Away Logic
            const dropdownTrigger = document.getElementById('profile-dropdown-trigger');
            const dropdownMenu = document.getElementById('profile-dropdown-menu');
            const dropdownChevron = document.getElementById('profile-dropdown-chevron');
            const dropdownContainer = document.getElementById('profile-dropdown-container');

            if (dropdownTrigger && dropdownMenu) {
                const toggleDropdown = () => {
                    const isClosed = dropdownMenu.classList.contains('hidden');
                    if (isClosed) {
                        // Open Dropdown
                        dropdownMenu.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            dropdownMenu.classList.remove('scale-95', 'opacity-0');
                            dropdownMenu.classList.add('scale-100', 'opacity-100');
                            if (dropdownChevron) dropdownChevron.classList.add('rotate-180');
                        });
                    } else {
                        // Close Dropdown
                        dropdownMenu.classList.remove('scale-100', 'opacity-100');
                        dropdownMenu.classList.add('scale-95', 'opacity-0');
                        if (dropdownChevron) dropdownChevron.classList.remove('rotate-180');
                        setTimeout(() => dropdownMenu.classList.add('hidden'), 200);
                    }
                };

                dropdownTrigger.addEventListener('click', (e) => {
                    e.stopPropagation();
                    toggleDropdown();
                });

                document.addEventListener('click', (e) => {
                    if (dropdownContainer && !dropdownContainer.contains(e.target)) {
                        if (!dropdownMenu.classList.contains('hidden')) {
                            dropdownMenu.classList.remove('scale-100', 'opacity-100');
                            dropdownMenu.classList.add('scale-95', 'opacity-0');
                            if (dropdownChevron) dropdownChevron.classList.remove('rotate-180');
                            setTimeout(() => dropdownMenu.classList.add('hidden'), 200);
                        }
                    }
                });
            }

            // Theme Toggle Logic for both Navbar and Dropdown
            const themeToggleBtnNavbar = document.getElementById('theme-toggle-navbar');
            const themeToggleBtnDropdown = document.getElementById('theme-toggle-dropdown');
            const themeToggleTextDropdown = document.getElementById('theme-toggle-text-dropdown');

            const syncThemeIcons = () => {
                const navbarIcon = document.getElementById('theme-icon-navbar');
                const dropdownIcon = document.getElementById('theme-icon-dropdown');
                const isDark = document.documentElement.classList.contains('dark');

                if (navbarIcon) {
                    navbarIcon.className = isDark 
                        ? 'fa-solid fa-sun text-lg text-amber-400 transition-all duration-300' 
                        : 'fa-solid fa-moon text-lg text-slate-700 transition-all duration-300';
                }

                if (dropdownIcon) {
                    dropdownIcon.className = isDark 
                        ? 'fa-solid fa-sun text-sm text-amber-400 transition-all duration-300' 
                        : 'fa-solid fa-moon text-sm text-slate-400 dark:text-slate-500 transition-all duration-300';
                }

                if (themeToggleTextDropdown) {
                    themeToggleTextDropdown.textContent = isDark ? 'Light Mode' : 'Dark Mode';
                }
            };

            const toggleTheme = () => {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                
                syncThemeIcons();

                // Trigger smooth 360-degree rotation animation
                const navbarIcon = document.getElementById('theme-icon-navbar');
                const dropdownIcon = document.getElementById('theme-icon-dropdown');
                [navbarIcon, dropdownIcon].forEach(icon => {
                    if (icon) {
                        icon.classList.remove('theme-icon-spin');
                        void icon.offsetWidth; // Force reflow to restart keyframe animation
                        icon.classList.add('theme-icon-spin');
                    }
                });
            };

            // Initialize theme icons and text labels
            syncThemeIcons();

            if (themeToggleBtnNavbar) {
                themeToggleBtnNavbar.addEventListener('click', toggleTheme);
            }

            if (themeToggleBtnDropdown) {
                themeToggleBtnDropdown.addEventListener('click', toggleTheme);
            }
        });
    </script>

    @yield('scripts')
</body>

</html>