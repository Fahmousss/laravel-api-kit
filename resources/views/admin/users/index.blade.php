@extends('layouts.app')

@section('sidebar')
    <x-layout.sidebar :links="[
        ['url' => route('admin.dashboard'), 'name' => 'Dashboard'],
        ['url' => route('admin.posyandus.index'), 'name' => 'Posyandus'],
        ['url' => route('admin.users.index'), 'name' => 'Users'],
        ['url' => route('admin.children.index'), 'name' => 'Children'],
        ['url' => route('admin.measurements.index'), 'name' => 'Measurements'],
    ]" />
@endsection

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-emerald-950 mb-2">Manage Users & Roles</h1>
        <p class="text-slate-500">View registered users, assign administrative or kader roles, and allocate Posyandus.</p>
    </div>
</div>

@if(session('success'))
    <div class="mb-6 px-4 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white border border-slate-100 rounded-2xl overflow-hidden">
    <x-data.table :headers="['Name', 'Email', 'Roles', 'Joined', 'Actions']" :paginator="$viewModel->paginator">
        @forelse($viewModel->users as $user)
            <tr class="hover:bg-slate-50 transition-colors group">
                <td class="px-6 py-4 font-medium text-emerald-950">{{ $user->name }}</td>
                <td class="px-6 py-4 text-slate-700">{{ $user->email }}</td>
                <td class="px-6 py-4 font-semibold text-emerald-700">{{ $user->rolesDisplay }}</td>
                <td class="px-6 py-4 text-slate-500">{{ $user->createdAtFormatted }}</td>
                <td class="px-6 py-4">
                    <div class="flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="flex gap-2">
                            {{-- Assign Role Form UI --}}
                            <form action="{{ route('admin.users.assignRole', $user->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                <select name="role" class="bg-slate-50 border border-slate-200 rounded px-2 py-1 text-xs text-emerald-950 outline-none focus:border-emerald-500" onchange="this.form.submit()">
                                    <option value="" class="bg-white">Assign Role...</option>
                                    <option value="admin" class="bg-white">Admin</option>
                                    <option value="kader" class="bg-white">Kader</option>
                                </select>
                            </form>
                        </div>
                        
                        {{-- Revoke Role Form UI --}}
                        @if(count($user->rawRoles) > 0)
                        <div class="flex gap-1 flex-wrap">
                            @foreach($user->rawRoles as $assignedRole)
                            <form action="{{ route('admin.users.revokeRole', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="role" value="{{ $assignedRole->value }}">
                                <button type="submit" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 rounded px-2 py-1 text-[10px] uppercase font-bold transition-colors" onclick="return confirm('Are you sure you want to revoke the {{ $assignedRole->value }} role?')">
                                    Revoke {{ $assignedRole->value }} &times;
                                </button>
                            </form>
                            @endforeach
                        </div>
                        @endif

                        {{-- Assign Posyandu Form UI --}}
                        @if(str_contains(strtolower($user->rolesDisplay), 'kader') || str_contains(strtolower($user->rolesDisplay), 'admin'))
                        <form action="{{ route('admin.users.assignPosyandu', $user->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <select name="posyandu_id" class="bg-slate-50 border border-slate-200 rounded px-2 py-1 text-xs text-emerald-950 outline-none focus:border-emerald-500" onchange="this.form.submit()">
                                <option value="" class="bg-white">Assign Posyandu...</option>
                                @foreach($posyanduOptions as $val => $label)
                                    <option value="{{ $val }}" class="bg-white">{{ $label }}</option>
                                @endforeach
                            </select>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                    No users found.
                </td>
            </tr>
        @endforelse

        <x-slot:footer>
            <x-data.pagination :paginator="$viewModel->paginator" />
        </x-slot:footer>
    </x-data.table>
</div>
@endsection
