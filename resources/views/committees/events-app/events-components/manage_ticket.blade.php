<!DOCTYPE html>
<html lang="en" class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Assembly Entry Pass - SAKO Central</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="h-full flex flex-col justify-between relative bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden min-h-screen transition-colors duration-300">
    
    <!-- Ambient backgrounds -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-purple-200/30 dark:bg-purple-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-20 right-1/4 w-[600px] h-[600px] bg-indigo-200/20 dark:bg-indigo-900/10 rounded-full blur-[120px]"></div>
    </div>

    <!-- Header Branding -->
    <header class="w-full max-w-4xl mx-auto px-4 pt-6 flex items-center justify-between z-10 relative">
        <div class="flex items-center gap-2.5">
            @php
                $logoPath = resource_path('views/imgs/letter-s.png');
                $base64Logo = '';
                if (file_exists($logoPath)) {
                    $base64Logo = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
                }
            @endphp
            @if($base64Logo)
                <img src="{{ $base64Logo }}" alt="SAKO Central Logo" class="w-9 h-9 rounded-xl object-contain shadow-md shadow-purple-500/20" />
            @else
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-purple-600 to-indigo-600 text-white font-extrabold text-sm shadow-md shadow-purple-500/20">
                    SC
                </span>
            @endif
            <span class="font-bold text-slate-800 dark:text-slate-200 tracking-tight">SAKO Central Pass</span>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-150 dark:border-emerald-900/30 px-2.5 py-1 rounded-md">
                Active Pass
            </div>
        </div>
    </header>

    <!-- Main Ticket Pass Portal -->
    <main class="flex-grow flex items-center justify-center p-4 z-10 relative">
        <div class="max-w-md w-full space-y-6">
            
            <!-- Validation Errors and Alerts (SweetAlert2 Enabled) -->
            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const isDark = document.documentElement.classList.contains('dark');
                        Swal.fire({
                            icon: 'error',
                            title: 'Notification',
                            text: "{{ session('error') }}",
                            background: isDark ? '#1e293b' : '#ffffff',
                            color: isDark ? '#f1f5f9' : '#0f172a',
                            confirmButtonColor: '#ef4444',
                            customClass: {
                                popup: 'rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl',
                                confirmButton: 'rounded-xl text-xs font-semibold px-4 py-2.5'
                            }
                        });
                    });
                </script>
            @endif

            <!-- Ticket Card (Luma Style Premium aesthetics) -->
            <div class="bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300">
                
                <!-- Ticket Header Cover -->
                <div class="p-6 bg-gradient-to-br from-slate-900 via-indigo-950 to-purple-950 text-white relative">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    
                    <div class="space-y-4">
                        <span class="inline-flex px-2.5 py-0.5 bg-white/10 backdrop-blur-md rounded-md text-[10px] font-extrabold text-purple-300 uppercase tracking-widest border border-white/5">
                            {{ $event->committee ? $event->committee->name : 'Division Assembly' }}
                        </span>
                        <h1 class="text-xl font-extrabold leading-tight tracking-tight text-white">{{ $event->title }}</h1>
                        
                        <div class="grid grid-cols-2 gap-4 pt-2 border-t border-white/10 text-xs text-slate-350">
                            <div>
                                <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Date & Time</p>
                                <p class="font-semibold text-slate-100 mt-0.5">{{ $event->event_date->format('M j, Y • g:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-purple-400 uppercase tracking-wider">Location Venue</p>
                                <p class="font-semibold text-slate-100 mt-0.5 truncate" title="{{ $event->location }}">{{ $event->location }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Check-In Body -->
                <div class="p-8 text-center space-y-6 flex flex-col items-center">
                    
                    <!-- QR Code Target Container -->
                    <div class="p-3 bg-white border border-slate-100 dark:border-slate-800 rounded-2xl shadow-sm inline-block">
                        <div id="qrcode" class="w-40 h-40 flex items-center justify-center">
                            <!-- JS will inject QR code image dynamically -->
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500">Secure Entry Ticket Code</p>
                        <p class="text-3xl font-extrabold text-slate-950 dark:text-white tracking-widest font-mono">
                            {{ $registration->ticket_code ?? 'AC-PASS' }}
                        </p>
                        <span class="inline-flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                            Attendee: <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ $registration->name }}</strong>
                        </span>
                    </div>

                    <!-- Scan notice / Instructions & Self Check-In Trigger -->
                    @if($registration->attended)
                        <div class="px-5 py-4 bg-emerald-50 dark:bg-emerald-950/20 rounded-2xl border border-emerald-200 dark:border-emerald-900/30 text-xs text-emerald-800 dark:text-emerald-400 font-semibold flex items-center justify-center gap-2 w-full shadow-sm">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Verified Guest: Checked-In on {{ $registration->attended_at ? \Carbon\Carbon::parse($registration->attended_at)->format('M j • g:i A') : now()->format('M j • g:i A') }}</span>
                        </div>
                    @else
                        @if(true)
                            @if($event->isEnded())
                                <div class="px-5 py-4 bg-rose-50 dark:bg-rose-950/20 rounded-2xl border border-rose-200 dark:border-rose-900/30 text-xs text-rose-800 dark:text-rose-400 font-semibold flex flex-col items-center gap-2 w-full shadow-sm text-center">
                                    <svg class="w-6 h-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Assembly Ended: This event has already ended. Self-check-in is closed.</span>
                                </div>
                            @elseif(!$event->canCheckIn())
                                @php
                                    $daysLeft = $event->daysUntilStart();
                                @endphp
                                <div class="px-5 py-4 bg-amber-50/60 dark:bg-amber-950/15 rounded-2xl border border-amber-200/60 dark:border-amber-900/30 text-xs text-amber-800 dark:text-amber-400 font-semibold flex flex-col items-center gap-2 w-full shadow-sm text-center">
                                    <svg class="w-6 h-6 text-amber-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @if($event->startsTomorrow())
                                        <span>Check-In Opens Tomorrow: This assembly starts tomorrow. Self-check-in will open on the day of the event.</span>
                                    @elseif($daysLeft == 2)
                                        <span>Check-In Opens in 2 Days: This assembly starts in 2 days. Please check back on the day of the event to check in.</span>
                                    @else
                                        <span>Check-In Opens in {{ $daysLeft }} Days: This assembly starts in {{ $daysLeft }} days. Please check back on the day of the event to check in.</span>
                                    @endif
                                </div>
                            @else
                                <div class="px-5 py-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800/80 text-xs text-slate-500 dark:text-slate-400 leading-relaxed w-full flex items-start gap-2.5">
                                    <img src="https://img.icons8.com/ios-filled/50/8b5cf6/info.png" alt="Info" class="w-4 h-4 shrink-0 mt-0.5">
                                    <div>
                                        <strong class="text-slate-700 dark:text-slate-300">Self-Check-In Support:</strong> Arrive at the venue and click the direct check-in button below to instantly verify your arrival.
                                    </div>
                                </div>

                                @if($event->isUpcoming())
                                    <div class="px-5 py-3.5 bg-indigo-50/60 dark:bg-indigo-950/15 rounded-2xl border border-indigo-200/50 dark:border-indigo-900/30 text-xs text-indigo-800 dark:text-indigo-400 font-semibold flex items-center justify-center gap-2 w-full text-center">
                                        <svg class="w-3 h-3 text-indigo-500 animate-pulse shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Starts In {{ $event->event_date->diffForHumans(['parts' => 1]) }} (Today at {{ $event->event_date->format('g:i A') }}). You can check in early now!</span>
                                    </div>
                                @endif

                                <!-- Direct 1-Tap Self Check-In Button -->
                                <div class="w-full">
                                    <a href="{{ URL::signedRoute('events.direct_check_in', ['registration' => $registration->id]) }}"
                                       class="inline-flex w-full items-center justify-center gap-2 py-3.5 bg-gradient-to-r from-emerald-500 via-teal-500 to-indigo-600 hover:from-emerald-600 hover:to-indigo-700 text-white font-extrabold rounded-2xl text-xs tracking-wider transition-all duration-300 shadow-md shadow-emerald-500/10 hover:shadow-indigo-500/25 active:scale-[0.99] border border-white/5">
                                        <img src="https://img.icons8.com/ios-filled/50/ffffff/marker.png" alt="Pin" class="w-4 h-4 animate-pulse shrink-0">
                                        <span>1-TAP SELF-CHECK-IN NOW</span>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="px-5 py-3.5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800/80 text-xs text-slate-550 dark:text-slate-400 leading-relaxed w-full flex items-start gap-2.5">
                                <img src="https://img.icons8.com/ios-filled/50/8b5cf6/info.png" alt="Info" class="w-4 h-4 shrink-0 mt-0.5">
                                <span>Show this entry pass on your phone to any division host at the entrance gate to verify your seat reservation.</span>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Ticket Footer Action Bar (With Luma Cutoff Check) -->
                @php
                    $cutoff = $event->registration_deadline ?? $event->event_date;
                    $canCancel = now()->isBefore($cutoff);
                @endphp
                
                <div class="p-6 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-150 dark:border-slate-800/80 flex flex-col gap-3">
                    @if($canCancel)
                        <div class="text-center text-[11px] text-slate-400 dark:text-slate-500">
                            Need to adjust plans? You can cancel your RSVP pass until <br>
                            <span class="font-bold text-slate-600 dark:text-slate-300">{{ $cutoff->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                        
                        <form id="cancel-rsvp-form" action="{{ URL::signedRoute('events.cancel_registration', ['registration' => $registration->id]) }}" method="POST" 
                              class="w-full">
                            @csrf
                            <button type="submit" class="w-full py-3.5 px-4 rounded-xl border border-rose-200 dark:border-rose-900/40 text-rose-600 dark:text-rose-400 hover:text-white dark:hover:text-slate-900 hover:bg-rose-600 dark:hover:bg-rose-500 text-xs font-extrabold tracking-wide transition duration-150 shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Cancel RSVP Entry Pass</span>
                            </button>
                        </form>
                    @else
                        <div class="flex items-center justify-center gap-2 text-slate-400 dark:text-slate-500 text-xs py-2 bg-slate-100/50 dark:bg-slate-950/40 rounded-xl border border-slate-200/20">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>RSVP Cancellation Closed</span>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Return back and theme toggle info -->
            <div class="flex items-center justify-between text-xs text-slate-400 px-2">
                <a href="{{ $event->public_url }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Event Page
                </a>
                <span>Secure Ticket Gateway</span>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-4xl mx-auto px-4 py-6 text-center text-xs text-slate-400 dark:text-slate-500 z-10 relative">
        &copy; 2026 SAKO Central Event Management. All rights secured.
    </footer>

    <!-- QRCode script load -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate QR code targeting the direct, secure signed check-in URL
        window.addEventListener('DOMContentLoaded', () => {
            const qrText = "{{ URL::signedRoute('events.direct_check_in', ['registration' => $registration->id]) }}";
            new QRCode(document.getElementById("qrcode"), {
                text: qrText,
                width: 160,
                height: 160,
                colorDark : "#0f172a", // slate-900
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.M
            });

            // SweetAlert2 RSVP Cancellation Confirmation
            const cancelForm = document.getElementById('cancel-rsvp-form');
            if (cancelForm) {
                cancelForm.addEventListener('submit', (e) => {
                    if (cancelForm.dataset.confirmed === 'true') {
                        return;
                    }
                    e.preventDefault();
                    const isDark = document.documentElement.classList.contains('dark');
                    Swal.fire({
                        title: 'Cancel RSVP Entry Pass?',
                        text: 'Are you sure you want to cancel your RSVP? This will immediately release your seat to other potential participants.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: isDark ? '#334155' : '#e2e8f0',
                        confirmButtonText: 'Yes, Cancel RSVP',
                        cancelButtonText: 'Keep My Spot',
                        background: isDark ? '#1e293b' : '#ffffff',
                        color: isDark ? '#f1f5f9' : '#0f172a',
                        customClass: {
                            popup: 'rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl',
                            confirmButton: 'rounded-xl text-xs font-semibold px-4 py-2.5 mx-1',
                            cancelButton: 'rounded-xl text-xs font-semibold px-4 py-2.5 mx-1 ' + (isDark ? 'text-slate-300' : 'text-slate-700')
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            cancelForm.dataset.confirmed = 'true';
                            cancelForm.submit();
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
