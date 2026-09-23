<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Flowbite') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Eye blink when toggling password visibility */
        .blink .eye-shape { animation: eye-blink .3s ease; }
        @keyframes eye-blink {
            0%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(.1); }
        }

        /* Owl mascot */
        .owl-float { animation: owl-float 3.2s ease-in-out infinite; }
        .owl-pupil { transition: transform .12s ease-out; }
        .owl-lid { transform: scaleY(0); }
        .owl.blinking .owl-lid { animation: owl-blink .25s ease; }
        .owl-wing { transition: transform .45s cubic-bezier(.34, 1.56, .64, 1); }
        .owl-wing-l { transform: rotate(12deg); }
        .owl-wing-r { transform: rotate(-12deg); }
        .owl.cover .owl-wing-l, .owl.peek .owl-wing-l { transform: translate(24px, -26px) rotate(-8deg) scale(1.5, .85); }
        .owl.cover .owl-wing-r { transform: translate(-24px, -26px) rotate(8deg) scale(1.5, .85); }
        .owl.peek .owl-wing-r { transform: translate(-18px, -6px) rotate(35deg) scale(1.3, .85); }
        .owl.hop .owl-hop { animation: owl-hop .5s ease; }
        .owl.hop .owl-tassel { animation: owl-sway .6s ease; }
        .owl.shake .owl-hop { animation: owl-shake .5s ease; }
        @keyframes owl-float { 50% { transform: translateY(-4px); } }
        @keyframes owl-blink { 50% { transform: scaleY(1); } }
        @keyframes owl-hop { 30% { transform: translateY(-10px); } 60% { transform: translateY(0); } 80% { transform: translateY(-3px); } }
        @keyframes owl-sway { 25% { transform: rotate(-14deg); } 60% { transform: rotate(10deg); } }
        @keyframes owl-shake { 20%, 60% { transform: translateX(-5px); } 40%, 80% { transform: translateX(5px); } }
        @media (prefers-reduced-motion: reduce) {
            .owl * { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body class="min-h-screen antialiased bg-gradient-to-br from-sky-200 via-blue-100 to-cyan-100">
    <section class="min-h-screen flex">
        <div class="relative flex flex-col w-full">

            {{-- Brand --}}
            <a href="{{ url('/') }}" class="absolute top-6 left-6 sm:top-8 sm:left-10 text-2xl font-semibold tracking-tight text-gray-900">
                LMS ADMIN
            </a>

            {{-- Owl mascot: eyes follow the cursor, covers eyes on password --}}
            <svg id="owl" class="owl absolute top-4 right-4 sm:top-6 sm:right-10 w-20 sm:w-28 lg:w-32 h-auto select-none" viewBox="0 0 160 145" aria-hidden="true">
                <g class="owl-float">
                    <g class="owl-hop">
                        <ellipse cx="64" cy="137" rx="9" ry="5" fill="#f59e0b"/>
                        <ellipse cx="96" cy="137" rx="9" ry="5" fill="#f59e0b"/>
                        <path d="M34 60 30 32l24 16Z" fill="#0369a1"/>
                        <path d="M126 60l4-28-24 16Z" fill="#0369a1"/>
                        <ellipse cx="80" cy="90" rx="52" ry="48" fill="#0284c7"/>
                        <ellipse cx="80" cy="106" rx="32" ry="28" fill="#e0f2fe"/>
                        <path d="M68 104q4 4 8 0m8 0q4 4 8 0m-16 12q4 4 8 0" stroke="#7dd3fc" stroke-width="2" fill="none" stroke-linecap="round"/>

                        <circle cx="58" cy="74" r="20" fill="#fff"/>
                        <circle cx="102" cy="74" r="20" fill="#fff"/>
                        <g class="owl-pupil" data-cx="58" data-cy="74">
                            <circle cx="58" cy="74" r="9" fill="#0f172a"/>
                            <circle cx="61" cy="70" r="3" fill="#fff"/>
                        </g>
                        <g class="owl-pupil" data-cx="102" data-cy="74">
                            <circle cx="102" cy="74" r="9" fill="#0f172a"/>
                            <circle cx="105" cy="70" r="3" fill="#fff"/>
                        </g>
                        <circle class="owl-lid" cx="58" cy="74" r="21" fill="#0284c7" style="transform-origin: 58px 53px"/>
                        <circle class="owl-lid" cx="102" cy="74" r="21" fill="#0284c7" style="transform-origin: 102px 53px"/>

                        <path d="M74 88h12l-6 11Z" fill="#f59e0b"/>

                        <rect x="62" y="30" width="36" height="12" rx="3" fill="#1e293b"/>
                        <polygon points="80,14 124,26 80,38 36,26" fill="#0f172a"/>
                        <g class="owl-tassel" style="transform-origin: 80px 26px">
                            <path d="M80 26l34 4v16" stroke="#fbbf24" stroke-width="2" fill="none" stroke-linecap="round"/>
                            <circle cx="114" cy="48" r="3.5" fill="#fbbf24"/>
                        </g>
                        <circle cx="80" cy="26" r="2.5" fill="#fbbf24"/>

                        <ellipse class="owl-wing owl-wing-l" cx="34" cy="100" rx="14" ry="26" fill="#0369a1" style="transform-origin: 34px 100px"/>
                        <ellipse class="owl-wing owl-wing-r" cx="126" cy="100" rx="14" ry="26" fill="#0369a1" style="transform-origin: 126px 100px"/>
                    </g>
                </g>
            </svg>

            <div class="flex flex-1 items-center justify-center px-6 py-24">
                <div class="w-full max-w-sm">
                    <h1 class="text-center text-xl font-semibold text-gray-900">
                        Login To Your Account
                    </h1>
                    <p class="mt-3 text-center text-sm text-gray-600">
                        You dont have an account
                        <a href="{{ route('signup') }}" class="font-semibold text-sky-700 underline underline-offset-2 hover:text-sky-900">Sign up</a>
                    </p>

                    {{-- Session Status --}}
                    @if (session('status'))
                        <div class="relative mt-6 overflow-hidden rounded-xl border border-white/80 bg-white/70 py-2.5 pl-3.5 pr-3 text-xs text-emerald-800 shadow-md shadow-emerald-500/10 ring-1 ring-emerald-200/60 backdrop-blur-md" role="alert">
                            <span class="absolute inset-y-0 left-0 w-0.5 bg-gradient-to-b from-emerald-400 to-teal-500" aria-hidden="true"></span>
                            <div class="flex items-start gap-2.5">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-sm shadow-emerald-500/30">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="m5 12.5 4.5 4.5L19 7.5"/>
                                    </svg>
                                </span>
                                <div class="min-w-0 pt-px">
                                    <p class="font-semibold text-gray-900">All set</p>
                                    <p class="leading-snug text-emerald-700/90">{{ session('status') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="relative mt-6 overflow-hidden rounded-xl border border-white/80 bg-white/70 py-2.5 pl-3.5 pr-3 text-xs text-red-800 shadow-md shadow-rose-500/10 ring-1 ring-rose-200/70 backdrop-blur-md" role="alert">
                            <span class="absolute inset-y-0 left-0 w-0.5 bg-gradient-to-b from-rose-400 to-red-500" aria-hidden="true"></span>
                            <div class="flex items-start gap-2.5">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-gradient-to-br from-rose-400 to-red-500 text-white shadow-sm shadow-rose-500/30">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M12 8v5"/>
                                        <circle cx="12" cy="16.5" r=".6" fill="currentColor"/>
                                        <path d="M10.3 3.9 2.6 17.2A2 2 0 0 0 4.3 20h15.4a2 2 0 0 0 1.7-2.8L13.7 3.9a2 2 0 0 0-3.4 0Z"/>
                                    </svg>
                                </span>
                                <div class="min-w-0 pt-px">
                                    <p class="font-semibold text-gray-900">Couldn't sign you in</p>
                                    @if ($errors->count() === 1)
                                        <p class="leading-snug text-red-700/90">{{ $errors->first() }}</p>
                                    @else
                                        <ul class="mt-0.5 space-y-0.5">
                                            @foreach ($errors->all() as $error)
                                                <li class="flex items-start gap-1.5 leading-snug text-red-700/90">
                                                    <span class="mt-[6px] h-1 w-1 shrink-0 rounded-full bg-rose-400" aria-hidden="true"></span>
                                                    <span>{{ $error }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <form class="mt-8" method="POST" action="{{ route('login.authenticate') }}">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label for="email" class="sr-only">Email</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sky-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                                            <path d="m3 7 9 6 9-6" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           value="{{ old('email') ?? old('identifier') }}"
                                           class="block w-full rounded-xl border border-sky-200 bg-white py-3 pl-11 pr-4 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-sky-400 focus:outline-none focus:ring-4 focus:ring-sky-200/60"
                                           placeholder="Email"
                                           required
                                           autofocus
                                           autocomplete="username">
                                </div>
                            </div>

                            <div>
                                <label for="password" class="sr-only">Password</label>
                                <div class="relative">
                                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-sky-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                            <rect x="5" y="11" width="14" height="10" rx="2"/>
                                            <path d="M8 11V7a4 4 0 0 1 8 0v4" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <input type="password"
                                           name="password"
                                           id="password"
                                           placeholder="Password"
                                           class="block w-full rounded-xl border border-sky-200 bg-white py-3 pl-11 pr-12 text-sm text-gray-900 placeholder-gray-400 shadow-sm transition focus:border-sky-400 focus:outline-none focus:ring-4 focus:ring-sky-200/60"
                                           required
                                           autocomplete="current-password">
                                    <button type="button"
                                            id="toggle-password"
                                            class="group absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-sky-600 focus:outline-none focus-visible:text-sky-600"
                                            aria-label="Show password"
                                            aria-pressed="false">
                                        <svg class="w-5 h-5 transition-transform duration-200 group-active:scale-90" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                                            <g class="eye-shape origin-center [transform-box:fill-box]">
                                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                                                <circle class="origin-center [transform-box:fill-box] scale-60 opacity-60 transition duration-300 group-aria-pressed:scale-100 group-aria-pressed:opacity-100" cx="12" cy="12" r="3"/>
                                            </g>
                                            <path class="[stroke-dasharray:26] [stroke-dashoffset:0] transition-[stroke-dashoffset] duration-300 group-aria-pressed:[stroke-dashoffset:26]" d="M3 3l18 18"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center">
                            <input id="remember"
                                   name="remember"
                                   aria-describedby="remember"
                                   type="checkbox"
                                   class="w-4 h-4 rounded border-gray-300 text-sky-600 focus:ring-sky-300"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                        </div>

                        <div class="mt-10 flex justify-center">
                            <button type="submit" class="w-40 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-500/30 transition hover:from-sky-600 hover:to-blue-700 hover:shadow-sky-500/40 focus:outline-none focus:ring-4 focus:ring-sky-300 active:scale-[.98]">
                                Login
                            </button>
                        </div>

                        <p class="mt-8 text-center">
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-gray-800 underline underline-offset-2 hover:text-sky-700">Forgot Password??</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        (function () {
            var toggle = document.getElementById('toggle-password');
            var input = document.getElementById('password');
            if (!toggle || !input) return;

            toggle.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                toggle.setAttribute('aria-pressed', show ? 'true' : 'false');
                toggle.setAttribute('aria-label', show ? 'Hide password' : 'Show password');

                toggle.classList.remove('blink');
                void toggle.offsetWidth; // restart animation
                toggle.classList.add('blink');
            });
        })();
    </script>

    <script>
        // Owl mascot animation (visual only)
        (function () {
            var owl = document.getElementById('owl');
            var email = document.getElementById('email');
            var pw = document.getElementById('password');
            var toggle = document.getElementById('toggle-password');
            if (!owl || !email || !pw) return;

            var pupils = owl.querySelectorAll('.owl-pupil');
            var locked = false;
            var timers = {};

            function lookAt(x, y) {
                var r = owl.getBoundingClientRect();
                var s = r.width / 160;
                pupils.forEach(function (p) {
                    var dx = x - (r.left + p.dataset.cx * s);
                    var dy = y - (r.top + p.dataset.cy * s);
                    var d = Math.hypot(dx, dy) || 1;
                    var m = Math.min(7, d / 20);
                    p.style.transform = 'translate(' + (dx / d * m) + 'px,' + (dy / d * m) + 'px)';
                });
            }

            function play(cls, ms) {
                owl.classList.remove(cls);
                owl.getBoundingClientRect(); // restart animation
                owl.classList.add(cls);
                clearTimeout(timers[cls]);
                timers[cls] = setTimeout(function () { owl.classList.remove(cls); }, ms);
            }

            function lookAtInput(input, followText) {
                var r = input.getBoundingClientRect();
                var x = followText ? r.left + 44 + Math.min(input.value.length * 7, r.width - 60) : r.left + r.width / 2;
                lookAt(x, r.top + r.height / 2);
            }

            function update() {
                var el = document.activeElement;
                var onPw = el === pw || el === toggle;
                var shown = pw.type === 'text';
                owl.classList.toggle('cover', onPw && !shown);
                owl.classList.toggle('peek', onPw && shown);
                locked = el === email || onPw;
                if (el === email) lookAtInput(email, true);
                else if (onPw) lookAtInput(pw, false);
            }

            document.addEventListener('pointermove', function (e) {
                if (!locked) lookAt(e.clientX, e.clientY);
            });
            document.addEventListener('pointerdown', function (e) {
                lookAt(e.clientX, e.clientY);
                play('blinking', 250);
                play('hop', 600);
            });
            document.addEventListener('focusin', update);
            document.addEventListener('focusout', function () { setTimeout(update, 0); });
            email.addEventListener('input', function () { lookAtInput(email, true); });
            if (toggle) toggle.addEventListener('click', function () { setTimeout(update, 0); });

            (function idleBlink() {
                setTimeout(function () { play('blinking', 250); idleBlink(); }, 2500 + Math.random() * 3000);
            })();

            if (document.querySelector('[role="alert"].text-red-800')) play('shake', 600);
            update();
        })();
    </script>
</body>
</html>
