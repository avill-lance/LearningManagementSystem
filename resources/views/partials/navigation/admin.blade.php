{{-- Partial: Admin Top Navbar (notifications bell + profile shortcut to settings) --}}
@php
    $navUser = auth()->user();
    $navName = trim(($navUser->first_name ?? '') . ' ' . ($navUser->last_name ?? '')) ?: 'Administrator';
    $navInitials = mb_strtoupper(mb_substr($navUser->first_name ?? 'A', 0, 1) . mb_substr($navUser->last_name ?? '', 0, 1));

    $notifications = \App\Models\Notification::with('user')->latest('created_at')->get();
    $unreadCount = $notifications->where('is_read', false)->count();

    $iconBtn = 'relative flex h-10 w-10 items-center justify-center rounded-full border border-blue-100 bg-white text-slate-600 shadow-sm transition hover:text-blue-600 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:text-white';
@endphp

<nav class="sticky top-0 z-20 -mx-4 -mt-4 mb-4 border-b border-blue-100 bg-white/80 py-3 pl-16 pr-4 backdrop-blur dark:border-slate-700 dark:bg-slate-900/80"
     x-data="{
        open: false,
        unread: {{ $unreadCount }},
        items: @js($notifications->map(fn ($n) => [
            'id' => $n->notification_id,
            'type' => $n->type,
            'message' => $n->message,
            'is_read' => $n->is_read,
            'user' => $n->user ? trim($n->user->first_name . ' ' . $n->user->last_name) : 'Unknown user',
            'time' => $n->created_at?->diffForHumans(),
        ])->values()),
        markRead(item) {
            if (item.is_read) return;
            axios.post(`{{ url('/admin/notifications') }}/${item.id}/read`).then(() => {
                item.is_read = true;
                this.unread = Math.max(0, this.unread - 1);
            });
        },
        markAllRead() {
            axios.post('{{ route('admin.notifications.read-all') }}').then(() => {
                this.items.forEach(i => i.is_read = true);
                this.unread = 0;
            });
        },
     }"
     @keydown.escape.window="open = false">
    <div class="flex items-center justify-between gap-4">
        <h2 class="truncate text-lg font-semibold text-slate-900 dark:text-white">@yield('title', 'Admin')</h2>

        <div class="flex items-center gap-3">
            {{-- Notifications bell --}}
            <button type="button" @click="open = true" class="{{ $iconBtn }}" aria-label="Open notifications">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <span x-show="unread > 0" x-cloak x-text="unread > 99 ? '99+' : unread"
                      class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white ring-2 ring-white dark:ring-slate-900"></span>
            </button>

            {{-- Profile: goes to settings --}}
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-2 rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-400" title="Account settings">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-linear-to-br from-blue-500 to-indigo-600 text-sm font-semibold text-white shadow-sm">{{ $navInitials }}</span>
                <span class="hidden text-sm font-medium text-slate-700 md:block dark:text-slate-200">{{ $navName }}</span>
            </a>
        </div>
    </div>

    {{-- Notifications modal --}}
    <template x-teleport="body">
        <div x-show="open" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="notif-modal-title">
            <div x-show="open" x-transition.opacity @click="open = false" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>

            <div x-show="open" x-transition class="relative flex max-h-[80vh] w-full max-w-lg flex-col rounded-2xl border border-blue-100 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center justify-between border-b border-blue-100 px-5 py-4 dark:border-slate-700">
                    <div>
                        <h3 id="notif-modal-title" class="text-base font-semibold text-slate-900 dark:text-white">Notifications</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400"><span x-text="unread"></span> unread of <span x-text="items.length"></span></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="markAllRead()" x-show="unread > 0"
                                class="rounded-lg px-3 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-50 dark:text-blue-300 dark:hover:bg-blue-500/10">Mark all read</button>
                        <button type="button" @click="open = false" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-700" aria-label="Close">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <ul class="flex-1 divide-y divide-slate-100 overflow-y-auto dark:divide-slate-700">
                    <template x-for="item in items" :key="item.id">
                        <li @click="markRead(item)" class="flex cursor-pointer gap-3 px-5 py-3 transition hover:bg-slate-50 dark:hover:bg-slate-700/50"
                            :class="item.is_read ? '' : 'bg-blue-50/60 dark:bg-blue-500/10'">
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full" :class="item.is_read ? 'bg-transparent' : 'bg-blue-500'"></span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-medium uppercase tracking-wide text-slate-600 dark:bg-slate-700 dark:text-slate-300" x-text="item.type"></span>
                                    <span class="shrink-0 text-xs text-slate-400" x-text="item.time"></span>
                                </div>
                                <p class="mt-1 text-sm text-slate-800 dark:text-slate-100" x-text="item.message"></p>
                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400" x-text="'To: ' + item.user"></p>
                            </div>
                        </li>
                    </template>
                    <li x-show="items.length === 0" class="px-5 py-10 text-center text-sm text-slate-500 dark:text-slate-400">No notifications yet.</li>
                </ul>
            </div>
        </div>
    </template>
</nav>
