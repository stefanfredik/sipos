<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lansia\StoreLansiaRequest;
use App\Http\Requests\Lansia\UpdateLansiaRequest;
use App\Http\Resources\LansiaResource;
use App\Models\Lansia;
use App\Services\LansiaService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LansiaController extends Controller
{
    public function __construct(
        protected LansiaService $service
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Lansia::class);

        $filters = $request->only(['search', 'is_active']);
        $perPage = $request->input('per_page', 15);

        $data = $this->service->paginate($filters, $perPage);

        return Inertia::render('Lansia/Index', [
            'lansia' => LansiaResource::collection($data),
            'filters' => $filters,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Lansia::class);

        return Inertia::render('Lansia/Create');
    }

    public function store(StoreLansiaRequest $request)
    {
        $this->authorize('create', Lansia::class);

        $this->service->create($request->validated(), $request->file('foto'));

        return redirect()->route('lansia.index')
            ->with('success', 'Data Lansia berhasil ditambahkan.');
    }

    public function show(string $id): Response
    {
        $lansia = $this->service->findById($id);
        $this->authorize('view', $lansia);

        $lansia->load(['pemeriksaan' => function ($q) {
            $q->orderBy('tgl_pemeriksaan', 'asc');
        }]);

        return Inertia::render('Lansia/Show', [
            'lansia' => new LansiaResource($lansia),
            'pemeriksaan' => \App\Http\Resources\PemeriksaanResource::collection($lansia->pemeriksaan),
        ]);
    }

    public function edit(string $id): Response
    {
        $lansia = $this->service->findById($id);
        
        if (!$lansia) {
            abort(404, 'Data Lansia tidak ditemukan.');
        }
        
        $this->authorize('update', $lansia);

        $resource = new LansiaResource($lansia);
        \Log::info('Lansia edit data:', ['id' => $id, 'resource' => $resource->resolve(request())]);

        return Inertia::render('Lansia/Edit', [
            'lansia' => $resource,
        ]);
    }

    public function update(UpdateLansiaRequest $request, string $id)
    {
        $lansia = $this->service->findById($id);
        $this->authorize('update', $lansia);

        $this->service->update($id, $request->validated(), $request->file('foto'));

        return redirect()->route('lansia.index')
            ->with('success', 'Data Lansia berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $lansia = $this->service->findById($id);
        $this->authorize('delete', $lansia);

        $this->service->delete($id);

        return redirect()->route('lansia.index')
            ->with('success', 'Data Lansia berhasil dihapus.');
    }
}
