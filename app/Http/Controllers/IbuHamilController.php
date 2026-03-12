<?php

namespace App\Http\Controllers;

use App\DTOs\IbuHamil\CreateIbuHamilDTO;
use App\Http\Requests\IbuHamil\StoreIbuHamilRequest;
use App\Http\Requests\IbuHamil\UpdateIbuHamilRequest;
use App\Http\Resources\IbuHamilResource;
use App\Models\IbuHamil;
use App\Services\IbuHamilService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class IbuHamilController extends Controller
{
    public function __construct(
        protected IbuHamilService $service
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', IbuHamil::class);

        $filters = $request->only(['search', 'is_active']);
        $perPage = $request->input('per_page', 15);

        $data = $this->service->paginate($filters, $perPage);

        return Inertia::render('IbuHamil/Index', [
            'ibu_hamil' => IbuHamilResource::collection($data),
            'filters' => $filters,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', IbuHamil::class);

        return Inertia::render('IbuHamil/Create');
    }

    public function store(StoreIbuHamilRequest $request)
    {
        $this->authorize('create', IbuHamil::class);

        $dto = CreateIbuHamilDTO::fromRequest(
            $request->validated(),
            $request->file('foto')
        );

        $this->service->create($dto);

        return redirect()->route('ibu-hamil.index')
            ->with('success', 'Data Ibu Hamil berhasil ditambahkan.');
    }

    public function show(string $id): Response
    {
        $ibuHamil = $this->service->findById($id);
        $this->authorize('view', $ibuHamil);

        $ibuHamil->load(['pemeriksaan' => function ($q) {
            $q->orderBy('tgl_pemeriksaan', 'asc');
        }]);

        return Inertia::render('IbuHamil/Show', [
            'ibu_hamil' => new IbuHamilResource($ibuHamil),
            'pemeriksaan' => \App\Http\Resources\PemeriksaanResource::collection($ibuHamil->pemeriksaan),
        ]);
    }

    public function edit(string $id): Response
    {
        $ibuHamil = $this->service->findById($id);
        $this->authorize('update', $ibuHamil);

        return Inertia::render('IbuHamil/Edit', [
            'ibu_hamil' => new IbuHamilResource($ibuHamil),
        ]);
    }

    public function update(UpdateIbuHamilRequest $request, string $id)
    {
        $ibuHamil = $this->service->findById($id);
        $this->authorize('update', $ibuHamil);

        $this->service->update(
            $id,
            $request->validated(),
            $request->file('foto')
        );

        return redirect()->route('ibu-hamil.index')
            ->with('success', 'Data Ibu Hamil berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $ibuHamil = $this->service->findById($id);
        $this->authorize('delete', $ibuHamil);

        $this->service->delete($id);

        return redirect()->route('ibu-hamil.index')
            ->with('success', 'Data Ibu Hamil berhasil dihapus.');
    }
}
