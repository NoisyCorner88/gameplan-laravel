<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>📋 GamePlan — Todo List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Space Grotesk', sans-serif; }

        /* ======================== */
        /*   NEOBRUTALISM  SYSTEM   */
        /* ======================== */

        .neo-card {
            border: 3px solid #222;
            border-radius: 16px;
            box-shadow: 6px 6px 0px #222;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .neo-card:hover {
            box-shadow: 8px 8px 0px #222;
            transform: translate(-2px, -2px);
        }

        .neo-input {
            border: 3px solid #222;
            border-radius: 12px;
            box-shadow: 4px 4px 0px #222;
            transition: transform 0.12s ease, box-shadow 0.12s ease;
            background: #fff;
            padding: 10px 16px;
            font-size: 15px;
            font-weight: 500;
            color: #222;
        }
        .neo-input:focus {
            box-shadow: 6px 6px 0px #222;
            transform: translate(-2px, -2px);
            outline: none;
        }
        .neo-input::placeholder { color: #999; font-weight: 400; }

        .neo-btn {
            border: 3px solid #222;
            border-radius: 12px;
            box-shadow: 4px 4px 0px #222;
            transition: transform 0.1s ease, box-shadow 0.1s ease;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            font-size: 14px;
            padding: 10px 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .neo-btn:hover {
            box-shadow: 6px 6px 0px #222;
            transform: translate(-2px, -2px);
        }
        .neo-btn:active {
            box-shadow: 1px 1px 0px #222;
            transform: translate(3px, 3px);
        }

        .neo-badge {
            border: 2px solid #222;
            border-radius: 8px;
            box-shadow: 2px 2px 0px #222;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 2px 10px;
            display: inline-block;
        }

        .neo-checkbox {
            border: 3px solid #222;
            border-radius: 8px;
            box-shadow: 3px 3px 0px #222;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.1s ease;
            flex-shrink: 0;
            font-size: 16px;
            font-weight: 800;
        }
        .neo-checkbox:active {
            box-shadow: 1px 1px 0px #222;
            transform: translate(2px, 2px);
        }

        /* Stat cards */
        .neo-stat {
            border: 3px solid #222;
            border-radius: 14px;
            box-shadow: 5px 5px 0px #222;
            padding: 16px;
            text-align: center;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .neo-stat:hover {
            box-shadow: 7px 7px 0px #222;
            transform: translate(-2px, -2px);
        }

        /* Completed task styling */
        .task-done {
            opacity: 0.5;
        }
        .task-done .task-title {
            text-decoration: line-through;
        }

        /* Overdue pulse */
        .overdue-pulse {
            animation: pulse-neo 1.5s ease-in-out infinite;
        }
        @keyframes pulse-neo {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.05); }
        }

        /* Modal */
        .modal-overlay {
            background: rgba(0,0,0,0.35);
            backdrop-filter: blur(4px);
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f0e6d3; }
        ::-webkit-scrollbar-thumb { background: #222; border-radius: 4px; }

        /* Empty state illustration */
        .empty-illustration {
            font-size: 80px;
            filter: grayscale(0.2);
        }
    </style>
</head>
<body class="bg-[#f0e6d3] min-h-screen flex flex-col">

    {{-- ═══════════════════════════════ --}}
    {{--           HEADER / NAV          --}}
    {{-- ═══════════════════════════════ --}}
    <header class="bg-[#FF6B6B] border-b-4 border-[#222]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-5 sm:py-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#222] tracking-tight leading-tight">
                    📋 GamePlan
                </h1>
                <p class="text-[#222] font-semibold text-sm sm:text-base mt-0.5 opacity-80">
                    Plan your tasks. Crush your goals.
                </p>
            </div>
            <div class="hidden sm:block">
                <div class="neo-badge bg-[#FFE066] text-[#222] text-xs sm:text-sm px-3 py-1">
                    {{ now()->translatedFormat('l, d M Y') }}
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-5xl mx-auto w-full px-4 sm:px-6 py-6 sm:py-8 space-y-6 sm:space-y-8">

        {{-- ═══════════════ SUCCESS TOAST ═══════════════ --}}
        @if(session('success'))
            <div id="success-toast" class="neo-badge bg-[#6BCB77] text-[#222] text-sm px-5 py-3 flex items-center gap-2 w-fit">
                <span class="text-lg">🎉</span> {{ session('success') }}
            </div>
        @endif

        {{-- ═══════════════ STATS COUNTERS ═══════════════ --}}
        @php
            $totalCount     = $tasks->count();
            $completedCount = $tasks->where('completed', true)->count();
            $activeCount    = $totalCount - $completedCount;
            $overdueCount   = $tasks->where('completed', false)
                                    ->filter(fn($t) => $t->deadline && $t->deadline->lt(today()))
                                    ->count();
        @endphp
        <section class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
            <div class="neo-stat bg-white">
                <div class="text-3xl sm:text-4xl font-extrabold text-[#222]">{{ $totalCount }}</div>
                <div class="text-xs sm:text-sm font-bold text-[#666] mt-1 uppercase tracking-wide">Total</div>
            </div>
            <div class="neo-stat bg-[#74B9FF]">
                <div class="text-3xl sm:text-4xl font-extrabold text-[#222]">{{ $activeCount }}</div>
                <div class="text-xs sm:text-sm font-bold text-[#222] mt-1 uppercase tracking-wide">Aktif</div>
            </div>
            <div class="neo-stat bg-[#6BCB77]">
                <div class="text-3xl sm:text-4xl font-extrabold text-[#222]">{{ $completedCount }}</div>
                <div class="text-xs sm:text-sm font-bold text-[#222] mt-1 uppercase tracking-wide">Selesai</div>
            </div>
            <div class="neo-stat {{ $overdueCount > 0 ? 'bg-[#FF4757]' : 'bg-[#DFE6E9]' }}">
                <div class="text-3xl sm:text-4xl font-extrabold {{ $overdueCount > 0 ? 'text-white' : 'text-[#222]' }}">{{ $overdueCount }}</div>
                <div class="text-xs sm:text-sm font-bold {{ $overdueCount > 0 ? 'text-white' : 'text-[#666]' }} mt-1 uppercase tracking-wide">Terlambat</div>
            </div>
        </section>

        {{-- ═══════════════ ADD TASK FORM ═══════════════ --}}
        <section class="neo-card bg-white p-5 sm:p-6">
            <div class="flex items-center gap-3 mb-5">
                <span class="neo-badge bg-[#FFE066] text-[#222]">Baru</span>
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#222]">Tambah Tugas</h2>
            </div>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-[#222] mb-1.5 text-sm">Judul *</label>
                        <input type="text" name="title" required placeholder="Apa yang harus dikerjakan?"
                               class="neo-input w-full" value="{{ old('title') }}" />
                        @error('title')
                            <p class="text-[#FF4757] font-bold text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block font-bold text-[#222] mb-1.5 text-sm">Deadline</label>
                        <input type="date" name="deadline" class="neo-input w-full" value="{{ old('deadline') }}" />
                    </div>
                    <div>
                        <label class="block font-bold text-[#222] mb-1.5 text-sm">Prioritas</label>
                        <select name="priority" class="neo-input w-full">
                            <option value="low"    {{ old('priority') == 'low'    ? 'selected' : '' }}>🟢 Rendah</option>
                            <option value="medium" {{ old('priority','medium') == 'medium' ? 'selected' : '' }}>🟡 Sedang</option>
                            <option value="high"   {{ old('priority') == 'high'   ? 'selected' : '' }}>🔴 Tinggi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-bold text-[#222] mb-1.5 text-sm">Deskripsi</label>
                        <textarea name="description" rows="1" placeholder="Detail opsional..."
                                  class="neo-input w-full resize-none">{{ old('description') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="neo-btn bg-[#FF6B6B] text-[#222] mt-5 w-full sm:w-auto text-base">
                    ➕ Tambah Tugas
                </button>
            </form>
        </section>

        {{-- ═══════════════ FILTER & SEARCH ═══════════════ --}}
        <section class="neo-card bg-white p-5 sm:p-6">
            <form method="GET" action="{{ route('tasks.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block font-bold text-[#222] mb-1.5 text-sm">🔍 Cari</label>
                    <input type="text" name="search" placeholder="Cari judul tugas..."
                           value="{{ request('search') }}" class="neo-input w-full" />
                </div>
                <div>
                    <label class="block font-bold text-[#222] mb-1.5 text-sm">📋 Filter</label>
                    <select name="filter" class="neo-input w-full">
                        <option value="all"       {{ request('filter') == 'all'       ? 'selected' : '' }}>Semua</option>
                        <option value="active"    {{ request('filter') == 'active'    ? 'selected' : '' }}>Aktif</option>
                        <option value="completed" {{ request('filter') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-[#222] mb-1.5 text-sm">↕️ Urutkan</label>
                    <select name="sort" class="neo-input w-full">
                        <option value="deadline" {{ request('sort','deadline') == 'deadline' ? 'selected' : '' }}>Deadline</option>
                        <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Prioritas</option>
                    </select>
                </div>
                <button type="submit" class="neo-btn bg-[#A29BFE] text-[#222] w-full">
                    🔎 Terapkan
                </button>
            </form>
        </section>

        {{-- ═══════════════ TASK LIST ═══════════════ --}}
        @if($tasks->isEmpty())
            <section class="neo-card bg-white p-8 sm:p-12 text-center">
                <div class="empty-illustration mb-4">📝</div>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-[#222] mb-2">Belum ada tugas!</h3>
                <p class="text-[#888] font-medium text-base">Tambahkan tugas pertamamu di form atas 👆</p>
            </section>
        @else
            <section class="space-y-4">
                @foreach($tasks as $task)
                    @php
                        $isOverdue = !$task->completed && $task->deadline && $task->deadline->lt(today());
                        $bgColor = match($task->priority) {
                            'high'   => 'bg-[#FFE8E8]',
                            'medium' => 'bg-[#FFF8E1]',
                            default  => 'bg-[#E8F5E9]',
                        };
                        $priorityBg = match($task->priority) {
                            'high'   => 'bg-[#FF6B6B]',
                            'medium' => 'bg-[#FFD93D]',
                            default  => 'bg-[#6BCB77]',
                        };
                        $priorityLabel = match($task->priority) {
                            'high'   => 'Tinggi',
                            'medium' => 'Sedang',
                            default  => 'Rendah',
                        };
                    @endphp

                    <div class="neo-card {{ $task->completed ? 'task-done bg-[#F1F1F1]' : $bgColor }} p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                            {{-- Checkbox --}}
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="shrink-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="neo-checkbox {{ $task->completed ? 'bg-[#6BCB77] text-white' : 'bg-white text-transparent' }}">
                                    ✓
                                </button>
                            </form>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="task-title text-lg font-bold text-[#222] break-words">{{ $task->title }}</h3>
                                @if($task->description)
                                    <p class="text-[#555] text-sm mt-1 break-words">{{ $task->description }}</p>
                                @endif
                                <div class="flex flex-wrap items-center gap-2 mt-2.5">
                                    <span class="neo-badge {{ $priorityBg }} text-[#222]">{{ $priorityLabel }}</span>
                                    @if($task->deadline)
                                        <span class="neo-badge bg-white text-[#222]">
                                            📅 {{ $task->deadline->format('d M Y') }}
                                        </span>
                                    @endif
                                    @if($isOverdue)
                                        <span class="neo-badge bg-[#FF4757] text-white overdue-pulse">
                                            ⚠️ Terlambat!
                                        </span>
                                    @endif
                                    @if($task->completed)
                                        <span class="neo-badge bg-[#6BCB77] text-[#222]">✅ Selesai</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-2 shrink-0 self-start sm:self-center">
                                <button onclick="openEdit({{ $task->id }}, `{{ addslashes($task->title) }}`, `{{ addslashes($task->description ?? '') }}`, '{{ $task->deadline ? $task->deadline->format('Y-m-d') : '' }}', '{{ $task->priority }}')"
                                        class="neo-btn bg-[#FFE066] text-[#222] text-xs px-3 py-2">
                                    ✏️ Edit
                                </button>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                      onsubmit="return confirm('Hapus tugas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="neo-btn bg-[#FF4757] text-white text-xs px-3 py-2">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>
        @endif

    </main>

    {{-- ═══════════════ EDIT MODAL ═══════════════ --}}
    <div id="edit-modal" class="fixed inset-0 modal-overlay z-50 hidden items-center justify-center p-4">
        <div class="neo-card bg-white p-6 sm:p-8 w-full max-w-lg mx-auto" onclick="event.stopPropagation()">
            <div class="flex items-center gap-3 mb-5">
                <span class="neo-badge bg-[#FFE066] text-[#222]">Edit</span>
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#222]">Edit Tugas</h2>
            </div>
            <form id="edit-form" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block font-bold text-[#222] mb-1.5 text-sm">Judul *</label>
                        <input type="text" name="title" id="edit-title" required class="neo-input w-full" />
                    </div>
                    <div>
                        <label class="block font-bold text-[#222] mb-1.5 text-sm">Deskripsi</label>
                        <textarea name="description" id="edit-description" rows="3"
                                  class="neo-input w-full resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-[#222] mb-1.5 text-sm">Deadline</label>
                            <input type="date" name="deadline" id="edit-deadline" class="neo-input w-full" />
                        </div>
                        <div>
                            <label class="block font-bold text-[#222] mb-1.5 text-sm">Prioritas</label>
                            <select name="priority" id="edit-priority" class="neo-input w-full">
                                <option value="low">🟢 Rendah</option>
                                <option value="medium">🟡 Sedang</option>
                                <option value="high">🔴 Tinggi</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button type="submit" class="neo-btn bg-[#6BCB77] text-[#222] flex-1 text-base">
                        💾 Simpan
                    </button>
                    <button type="button" onclick="closeEdit()"
                            class="neo-btn bg-[#DFE6E9] text-[#222] text-base">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════ FOOTER ═══════════════ --}}
    <footer class="bg-[#222] border-t-4 border-[#FF6B6B] py-4 mt-auto">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center">
            <p class="text-white font-bold text-sm">
                GamePlan &copy; {{ date('Y') }} — Dibangun dengan 💪 Neobrutalism
            </p>
        </div>
    </footer>

    {{-- ═══════════════ SCRIPTS ═══════════════ --}}
    <script>
        // Edit Modal
        function openEdit(id, title, description, deadline, priority) {
            const modal = document.getElementById('edit-modal');
            const form  = document.getElementById('edit-form');

            form.action = '/tasks/' + id;
            document.getElementById('edit-title').value       = title;
            document.getElementById('edit-description').value = description;
            document.getElementById('edit-deadline').value    = deadline;
            document.getElementById('edit-priority').value    = priority;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.getElementById('edit-title').focus();
        }

        function closeEdit() {
            const modal = document.getElementById('edit-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close on Escape
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeEdit();
        });

        // Close on overlay click
        document.getElementById('edit-modal').addEventListener('click', function(e) {
            if (e.target === this) closeEdit();
        });

        // Auto-hide success toast
        const toast = document.getElementById('success-toast');
        if (toast) {
            setTimeout(() => {
                toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>
</body>
</html>
