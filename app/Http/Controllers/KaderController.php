<?php

namespace App\Http\Controllers;

use App\Http\Requests\Kader\StoreKaderRequest;
use App\Http\Requests\Kader\UpdateKaderRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\KaderResource;
use App\Models\Kader;
use App\Models\Posyandu;
use App\Models\User;
use App\Services\KaderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class KaderController extends Controller
{
    private KaderService $kaderService;

    public function __construct(KaderService $kaderService)
    {
        $this->kaderService = $kaderService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Kader::class);

        $filters = $request->only(['search']);
        $kader = $this->kaderService->paginate($request->get('per_page', 15), $filters);

        return Inertia::render('Kader/Index', [
            'kader' => KaderResource::collection($kader),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Kader::class);

        return Inertia::render('Kader/Create', [
            'posyandu' => Posyandu::where('is_active', true)->get(['id', 'nama_posyandu']),
        ]);
    }

    public function store(StoreKaderRequest $request)
    {
        $this->authorize('create', Kader::class);

        $this->kaderService->create($request->validated());
        return redirect()->route('kader.index')->with('success', 'Kader berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $kader = $this->kaderService->findById($id);
        $this->authorize('view', $kader);

        return Inertia::render('Kader/Show', [
            'kader' => (new KaderResource($kader))->resolve(),
        ]);
    }

    public function edit(string $id)
    {
        $kader = $this->kaderService->findById($id);
        $this->authorize('update', $kader);

        return Inertia::render('Kader/Edit', [
            'kader' => (new KaderResource($kader))->resolve(),
            'posyandu' => Posyandu::where('is_active', true)->get(['id', 'nama_posyandu']),
        ]);
    }

    public function update(UpdateKaderRequest $request, string $id)
    {
        $kader = $this->kaderService->findById($id);
        $this->authorize('update', $kader);

        $this->kaderService->update($id, $request->validated());
        return redirect()->route('kader.index')->with('success', 'Data kader berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $kader = $this->kaderService->findById($id);
        $this->authorize('delete', $kader);

        $this->kaderService->delete($id);
        return redirect()->route('kader.index')->with('success', 'Kader berhasil dihapus.');
    }

    public function changePassword(ChangePasswordRequest $request, string $id)
    {
        $kader = $this->kaderService->findById($id);
        $this->authorize('update', $kader);

        // Update password di user yang terkait
        $user = User::findOrFail($kader->user_id);
        $user->update(['password' => Hash::make($request->validated('password'))]);

        return redirect()->route('kader.index')->with('success', 'Password kader berhasil diubah.');
    }
}
