<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Field;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FieldManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $fieldsQuery = Field::withCount('jobs')
            ->latest();

        if ($search) {
            $fieldsQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($status !== null && $status !== '') {
            $fieldsQuery->where('is_active', $status);
        }

        $fields = $fieldsQuery->paginate(10)
            ->appends($request->query());

        $totalFields = Field::count();
        $activeFields = Field::where('is_active', true)->count();
        $inactiveFields = Field::where('is_active', false)->count();
        $totalJobs = Field::withCount('jobs')->get()->sum('jobs_count');

        return view('internal.fields.index', compact(
            'fields',
            'totalFields',
            'activeFields',
            'inactiveFields',
            'totalJobs',
            'search',
            'status'
        ));
    }

    public function create()
    {
        return view('internal.fields.form', ['field' => null]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedField($request);

        $field = DB::transaction(function () use ($data, $request) {
            $data['slug'] = $this->uniqueFieldSlug($data['name']);
            $field = Field::create($data);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'field.created',
                'model_type' => Field::class,
                'model_id' => $field->id,
                'new_values' => $this->fieldValues($field),
                'ip_address' => $request->ip(),
            ]);

            return $field;
        });

        return redirect()
            ->route('internal.fields.index')
            ->with('success', 'Bidang magang berhasil dibuat.');
    }

    public function edit(Field $field)
    {
        return view('internal.fields.form', compact('field'));
    }

    public function update(Request $request, Field $field)
    {
        $data = $this->validatedField($request);

        DB::transaction(function () use ($data, $field, $request) {
            $lockedField = Field::whereKey($field->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $oldValues = $this->fieldValues($lockedField);
            $lockedField->update($data);

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'field.updated',
                'model_type' => Field::class,
                'model_id' => $lockedField->id,
                'old_values' => $oldValues,
                'new_values' => $this->fieldValues($lockedField),
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('internal.fields.index')
            ->with('success', 'Bidang magang berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Field $field)
    {
        $data = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);
        $isActive = (bool) $data['is_active'];

        DB::transaction(function () use ($isActive, $field, $request) {
            $lockedField = Field::whereKey($field->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $oldValues = ['is_active' => $lockedField->is_active];
            $lockedField->is_active = $isActive;
            $lockedField->save();

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => 'field.status_updated',
                'model_type' => Field::class,
                'model_id' => $lockedField->id,
                'old_values' => $oldValues,
                'new_values' => ['is_active' => $lockedField->is_active],
                'ip_address' => $request->ip(),
            ]);
        });

        return redirect()
            ->route('internal.fields.index')
            ->with('success', $isActive ? 'Bidang magang berhasil diaktifkan.' : 'Bidang magang berhasil dinonaktifkan.');
    }

    private function validatedField(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    private function uniqueFieldSlug(string $name): string
    {
        $baseSlug = Str::slug($name) ?: 'bidang';
        $suffix = 1;

        do {
            $suffixText = $suffix === 1 ? '' : '-' . $suffix;
            $slug = rtrim(substr($baseSlug, 0, 255 - strlen($suffixText)), '-') . $suffixText;
            $suffix++;
        } while (Field::where('slug', $slug)->exists());

        return $slug;
    }

    private function fieldValues(Field $field): array
    {
        return [
            'name' => $field->name,
            'slug' => $field->slug,
            'description' => $field->description,
            'is_active' => $field->is_active,
        ];
    }
}
