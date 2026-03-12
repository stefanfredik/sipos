<?php

namespace App\Http\Controllers;

use App\Http\Requests\Balita\StoreBalitaRequest;
use App\Http\Requests\Balita\UpdateBalitaRequest;
use App\Http\Resources\BalitaResource;
use App\Models\Balita;
use App\Services\BalitaService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BalitaController extends Controller
{
    public function __construct(
        protected BalitaService $service
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Balita::class);

        $filters = $request->only(['search', 'is_active']);
        $perPage = $request->input('per_page', 15);

        $data = $this->service->paginate($filters, $perPage);

        return Inertia::render('Balita/Index', [
            'balita' => BalitaResource::collection($data),
            'filters' => $filters,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Balita::class);

        return Inertia::render('Balita/Create');
    }

    public function store(StoreBalitaRequest $request)
    {
        $this->authorize('create', Balita::class);

        $this->service->create($request->validated(), $request->file('foto'));

        return redirect()->route('balita.index')
            ->with('success', 'Data Balita berhasil ditambahkan.');
    }

    public function show(string $id): Response
    {
        $balita = $this->service->findById($id);
        $this->authorize('view', $balita);

        $pemeriksaan = $balita->pemeriksaan()
            ->orderBy('tgl_pemeriksaan', 'desc')
            ->get();

        return Inertia::render('Balita/Show', [
            'balita' => new BalitaResource($balita),
            'pemeriksaan' => ['data' => $pemeriksaan],
        ]);
    }

    public function edit(string $id): Response
    {
        $balita = $this->service->findById($id);
        $this->authorize('update', $balita);

        return Inertia::render('Balita/Edit', [
            'balita' => new BalitaResource($balita),
        ]);
    }

    public function update(UpdateBalitaRequest $request, string $id)
    {
        $balita = $this->service->findById($id);
        $this->authorize('update', $balita);

        $this->service->update($id, $request->validated(), $request->file('foto'));

        return redirect()->route('balita.index')
            ->with('success', 'Data Balita berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $balita = $this->service->findById($id);
        $this->authorize('delete', $balita);

        $this->service->delete($id);

        return redirect()->route('balita.index')
            ->with('success', 'Data Balita berhasil dihapus.');
    }
}
