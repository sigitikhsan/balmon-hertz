<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::where('role', User::ROLE_ADMIN)
            ->latest()
            ->paginate(10);

        $totalAdmins = User::where('role', User::ROLE_ADMIN)->count();
        $activeAdmins = User::where('role', User::ROLE_ADMIN)
            ->where('status', User::STATUS_AKTIF)
            ->count();
        $inactiveAdmins = User::where('role', User::ROLE_ADMIN)
            ->where('status', '!=', User::STATUS_AKTIF)
            ->count();

        return view('internal.admins.index', compact(
            'admins',
            'totalAdmins',
            'activeAdmins',
            'inactiveAdmins'
        ));
    }

    public function create()
    {
        return view('internal.admins.form', ['admin' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'status' => ['required', Rule::in([User::STATUS_AKTIF, User::STATUS_NONAKTIF])],
        ]);

        $admin = DB::transaction(function () use ($data, $request) {
            $admin = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => User::ROLE_ADMIN,
                'status' => $data['status'],
            ]);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'admin.created',
                'model_type' => User::class,
                'model_id' => $admin->id,
                'new_values' => $this->adminValues($admin),
                'ip_address' => $request->ip(),
            ]);

            return $admin;
        });

        return redirect()
            ->route('internal.admins.index')
            ->with('success', 'Akun admin berhasil dibuat.');
    }

    public function edit(User $admin)
    {
        $this->assertManageableAdmin($admin);

        return view('internal.admins.form', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        $this->assertManageableAdmin($admin);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'password' => ['nullable', 'string', 'min:8', 'max:255'],
            'status' => ['required', Rule::in([User::STATUS_AKTIF, User::STATUS_NONAKTIF])],
        ]);

        if ((int) $admin->id === (int) Auth::id() && $data['status'] !== User::STATUS_AKTIF) {
            throw ValidationException::withMessages([
                'status' => 'Anda tidak dapat menonaktifkan akun sendiri.',
            ]);
        }

        DB::transaction(function () use ($data, $admin, $request) {
            $lockedAdmin = User::whereKey($admin->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $oldValues = $this->adminValues($lockedAdmin);

            $payload = [
                'name' => $data['name'],
                'email' => $data['email'],
                'status' => $data['status'],
            ];

            if (! empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            $lockedAdmin->update($payload);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'admin.updated',
                'model_type' => User::class,
                'model_id' => $lockedAdmin->id,
                'old_values' => $oldValues,
                'new_values' => $this->adminValues($lockedAdmin),
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('internal.admins.index')
            ->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function updateStatus(Request $request, User $admin)
    {
        $this->assertManageableAdmin($admin);

        $data = $request->validate([
            'status' => ['required', Rule::in([User::STATUS_AKTIF, User::STATUS_NONAKTIF])],
        ]);

        if ((int) $admin->id === (int) Auth::id() && $data['status'] !== User::STATUS_AKTIF) {
            throw ValidationException::withMessages([
                'status' => 'Anda tidak dapat menonaktifkan akun sendiri.',
            ]);
        }

        $status = $data['status'];

        DB::transaction(function () use ($status, $admin, $request) {
            $lockedAdmin = User::whereKey($admin->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $oldValues = ['status' => $lockedAdmin->status];
            $lockedAdmin->status = $status;
            $lockedAdmin->save();

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'admin.status_updated',
                'model_type' => User::class,
                'model_id' => $lockedAdmin->id,
                'old_values' => $oldValues,
                'new_values' => ['status' => $lockedAdmin->status],
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('internal.admins.index')
            ->with('success', $status === User::STATUS_AKTIF
                ? 'Akun admin berhasil diaktifkan.'
                : 'Akun admin berhasil dinonaktifkan.');
    }

    private function assertManageableAdmin(User $admin): void
    {
        if (strtolower($admin->role) !== User::ROLE_ADMIN) {
            abort(404);
        }
    }

    private function adminValues(User $admin): array
    {
        return [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => $admin->role,
            'status' => $admin->status,
        ];
    }
}
