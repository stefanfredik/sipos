<?php

namespace App\Http\Controllers;

use App\DTOs\JadwalPosyandu\CreateJadwalDTO;
use App\DTOs\JadwalPosyandu\UpdateJadwalDTO;
use App\Http\Requests\JadwalPosyandu\StoreJadwalRequest;
use App\Http\Requests\JadwalPosyandu\UpdateJadwalRequest;
use App\Http\Resources\JadwalPosyanduResource;
use App\Models\Bidan;
use App\Models\JadwalPosyandu;
use App\Models\Kader;
use App\Models\Posyandu;
use App\Services\JadwalPosyanduService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class JadwalPosyanduController extends Controller
{
    public function __construct(
        private readonly JadwalPosyanduService $jadwalService
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', JadwalPosyandu::class);

        $jadwal = $this->jadwalService->paginate(
            filters: $request->only(['search', 'status', 'posyandu_id']),
            perPage: 10
        );

        return Inertia::render('JadwalPosyandu/Index', [
            'jadwal' => JadwalPosyanduResource::collection($jadwal),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', JadwalPosyandu::class);

        return Inertia::render('JadwalPosyandu/Create', [
            'posyandu' => Posyandu::all(),
            'kader' => Kader::all(),
            'bidan' => Bidan::all(),
        ]);
    }

    public function store(StoreJadwalRequest $request): RedirectResponse
    {
        $dto = CreateJadwalDTO::fromRequest($request);
        $this->jadwalService->create($dto);

        return redirect()->route('jadwal-posyandu.index')
            ->with('success', 'Jadwal Posyandu berhasil dibuat.');
    }

    public function show(JadwalPosyandu $jadwalPosyandu): Response
    {
        $this->authorize('view', $jadwalPosyandu);

        return Inertia::render('JadwalPosyandu/Show', [
            'jadwal' => new JadwalPosyanduResource($jadwalPosyandu->load(['posyandu', 'kader', 'bidan'])),
        ]);
    }

    public function edit(JadwalPosyandu $jadwalPosyandu): Response
    {
        $this->authorize('update', $jadwalPosyandu);

        return Inertia::render('JadwalPosyandu/Edit', [
            'jadwal' => new JadwalPosyanduResource($jadwalPosyandu->load(['posyandu', 'kader', 'bidan'])),
            'posyandu' => Posyandu::all(),
            'kader' => Kader::all(),
            'bidan' => Bidan::all(),
        ]);
    }

    public function update(UpdateJadwalRequest $request, JadwalPosyandu $jadwalPosyandu): RedirectResponse
    {
        $dto = UpdateJadwalDTO::fromRequest($request);
        $this->jadwalService->update($jadwalPosyandu->id, $dto);

        return redirect()->route('jadwal-posyandu.index')
            ->with('success', 'Jadwal Posyandu berhasil diperbarui.');
    }

    public function destroy(JadwalPosyandu $jadwalPosyandu): RedirectResponse
    {
        $this->authorize('delete', $jadwalPosyandu);

        $this->jadwalService->delete($jadwalPosyandu->id);

        return redirect()->route('jadwal-posyandu.index')
            ->with('success', 'Jadwal Posyandu berhasil dihapus.');
    }

    public function validateJadwal(Request $request, JadwalPosyandu $jadwalPosyandu): RedirectResponse
    {
        $this->authorize('validate', $jadwalPosyandu);

        $validated = $request->validate([
            'bidan_id' => ['required', 'exists:bidan,id'],
            'catatan_bidan' => ['nullable', 'string', 'max:500'],
        ]);

        $this->jadwalService->validate(
            $jadwalPosyandu,
            $validated['bidan_id'],
            $validated['catatan_bidan'] ?? null
        );

        return redirect()->back()->with('success', 'Jadwal berhasil divalidasi.');
    }

    public function rejectJadwal(Request $request, JadwalPosyandu $jadwalPosyandu): RedirectResponse
    {
        $this->authorize('reject', $jadwalPosyandu);

        $validated = $request->validate([
            'catatan_bidan' => ['required', 'string', 'max:500'],
        ]);

        $this->jadwalService->reject($jadwalPosyandu, $validated['catatan_bidan']);

        return redirect()->back()->with('success', 'Jadwal berhasil ditolak.');
    }

    public function completeJadwal(JadwalPosyandu $jadwalPosyandu): RedirectResponse
    {
        $this->authorize('complete', $jadwalPosyandu);

        $this->jadwalService->complete($jadwalPosyandu);

        return redirect()->back()->with('success', 'Jadwal ditandai selesai.');
    }
}
