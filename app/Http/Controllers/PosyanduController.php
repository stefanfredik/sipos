<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posyandu\StorePosyanduRequest;
use App\Http\Requests\Posyandu\UpdatePosyanduRequest;
use App\Http\Resources\PosyanduResource;
use App\Models\Posyandu;
use App\Services\PosyanduService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PosyanduController extends Controller
{
    public function __construct(
        protected PosyanduService $service
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', Posyandu::class);

        $filters = $request->only(['search', 'is_active']);
        $perPage = $request->input('per_page', 15);

        $data = $this->service->paginate($filters, $perPage);

        return Inertia::render('Posyandu/Index', [
            'posyandu' => PosyanduResource::collection($data),
            'filters' => $filters,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Posyandu::class);

        return Inertia::render('Posyandu/Create');
    }

    public function store(StorePosyanduRequest $request)
    {
        $this->authorize('create', Posyandu::class);

        $this->service->create($request->validated());

        return redirect()->route('posyandu.index')
            ->with('success', 'Data Posyandu berhasil ditambahkan.');
    }

    public function show(string $id): Response
    {
        $posyandu = $this->service->findById($id);
        $this->authorize('view', $posyandu);

        return Inertia::render('Posyandu/Show', [
            'posyandu' => new PosyanduResource($posyandu),
        ]);
    }

    public function edit(string $id): Response
    {
        $posyandu = $this->service->findById($id);
        $this->authorize('update', $posyandu);

        return Inertia::render('Posyandu/Edit', [
            'posyandu' => new PosyanduResource($posyandu),
        ]);
    }

    public function update(UpdatePosyanduRequest $request, string $id)
    {
        $posyandu = $this->service->findById($id);
        $this->authorize('update', $posyandu);

        $this->service->update($id, $request->validated());

        return redirect()->route('posyandu.index')
            ->with('success', 'Data Posyandu berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $posyandu = $this->service->findById($id);
        $this->authorize('delete', $posyandu);

        $this->service->delete($id);

        return redirect()->route('posyandu.index')
            ->with('success', 'Data Posyandu berhasil dihapus.');
    }
}
