<?php

namespace App\Http\Controllers;

use App\DTOs\Pemeriksaan\CreatePemeriksaanDTO;
use App\Http\Requests\Pemeriksaan\StorePemeriksaanRequest;
use App\Http\Requests\Pemeriksaan\UpdatePemeriksaanRequest;
use App\Http\Resources\PemeriksaanResource;
use App\Models\Pemeriksaan;
use App\Services\PemeriksaanService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PemeriksaanController extends Controller
{
    public function __construct(
        private readonly PemeriksaanService $pemeriksaanService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Pemeriksaan::class);

        $query = \App\Models\Pemeriksaan::query()
            ->with(['peserta', 'jadwalPosyandu'])
            ->orderBy('tgl_pemeriksaan', 'desc');

        if ($request->search) {
            $query->whereHasMorph('peserta', ['*'], function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nik', 'like', "%{$request->search}%");
            });
        }

        if ($request->type && in_array($request->type, ['ibu_hamil', 'balita', 'lansia'])) {
            $query->where('peserta_type', $request->type);
        }

        $pemeriksaan = $query->paginate(10);

        return Inertia::render('Pemeriksaan/Index', [
            'pemeriksaan' => PemeriksaanResource::collection($pemeriksaan),
            'stats' => [
                'total_pemeriksaan' => \App\Models\Pemeriksaan::count(),
                'bumil_count' => \App\Models\Pemeriksaan::where('peserta_type', 'ibu_hamil')->count(),
                'balita_count' => \App\Models\Pemeriksaan::where('peserta_type', 'balita')->count(),
                'lansia_count' => \App\Models\Pemeriksaan::where('peserta_type', 'lansia')->count(),
            ],
            'filters' => $request->only(['search', 'type']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Pemeriksaan::class);

        return Inertia::render('Pemeriksaan/Create', [
            'jadwal' => \App\Models\JadwalPosyandu::with(['posyandu'])
                ->where('status', '!=', 'batal')
                ->get(),
            'participants' => [
                'ibu_hamil' => \App\Models\IbuHamil::where('is_active', true)->get(['id', 'nama', 'nik']),
                'balita' => \App\Models\Balita::where('is_active', true)->get(['id', 'nama', 'nik']),
                'lansia' => \App\Models\Lansia::where('is_active', true)->get(['id', 'nama', 'nik']),
            ],
            'kader' => \App\Models\Kader::all(['id', 'nama_kader']),
            'bidan' => \App\Models\Bidan::all(['id', 'nama_bidan']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePemeriksaanRequest $request)
    {
        $this->authorize('create', Pemeriksaan::class);

        $dto = CreatePemeriksaanDTO::fromRequest($request->validated());
        $this->pemeriksaanService->recordExamination($dto);

        return redirect()->back()->with('success', 'Data pemeriksaan berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $pemeriksaan = Pemeriksaan::with(['peserta', 'jadwalPosyandu.posyandu', 'kader', 'bidan'])->findOrFail($id);
        $this->authorize('view', $pemeriksaan);

        return Inertia::render('Pemeriksaan/Show', [
            'pemeriksaan' => new PemeriksaanResource($pemeriksaan),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $pemeriksaan = Pemeriksaan::with(['peserta', 'jadwalPosyandu.posyandu', 'kader', 'bidan'])->findOrFail($id);
        $this->authorize('update', $pemeriksaan);

        return Inertia::render('Pemeriksaan/Edit', [
            'pemeriksaan' => new PemeriksaanResource($pemeriksaan),
            'jadwal' => \App\Models\JadwalPosyandu::with(['posyandu'])
                ->where('status', '!=', 'batal')
                ->get(),
            'participants' => [
                'ibu_hamil' => \App\Models\IbuHamil::where('is_active', true)->get(['id', 'nama', 'nik']),
                'balita' => \App\Models\Balita::where('is_active', true)->get(['id', 'nama', 'nik']),
                'lansia' => \App\Models\Lansia::where('is_active', true)->get(['id', 'nama', 'nik']),
            ],
            'kader' => \App\Models\Kader::all(['id', 'nama_kader']),
            'bidan' => \App\Models\Bidan::all(['id', 'nama_bidan']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePemeriksaanRequest $request, string $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $this->authorize('update', $pemeriksaan);

        $this->pemeriksaanService->updateExamination($id, $request->validated());

        return redirect()->back()->with('success', 'Data pemeriksaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $this->authorize('delete', $pemeriksaan);

        $this->pemeriksaanService->deleteExamination($id);

        return redirect()->back()->with('success', 'Data pemeriksaan berhasil dihapus.');
    }
}
