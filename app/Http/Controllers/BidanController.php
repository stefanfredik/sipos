<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bidan\StoreBidanRequest;
use App\Http\Requests\Bidan\UpdateBidanRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\BidanResource;
use App\Models\Bidan;
use App\Models\User;
use App\Services\BidanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class BidanController extends Controller
{
    private BidanService $bidanService;

    public function __construct(BidanService $bidanService)
    {
        $this->bidanService = $bidanService;
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Bidan::class);

        $filters = $request->only(['search']);
        $bidan = $this->bidanService->paginate($request->get('per_page', 15), $filters);

        return Inertia::render('Bidan/Index', [
            'bidan' => BidanResource::collection($bidan),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Bidan::class);

        return Inertia::render('Bidan/Create');
    }

    public function store(StoreBidanRequest $request)
    {
        $this->authorize('create', Bidan::class);

        $this->bidanService->create($request->validated());
        return redirect()->route('bidan.index')->with('success', 'Bidan berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $bidan = $this->bidanService->findById($id);
        $this->authorize('view', $bidan);

        return Inertia::render('Bidan/Show', [
            'bidan' => (new BidanResource($bidan))->resolve(),
        ]);
    }

    public function edit(string $id)
    {
        $bidan = $this->bidanService->findById($id);
        $this->authorize('update', $bidan);

        return Inertia::render('Bidan/Edit', [
            'bidan' => (new BidanResource($bidan))->resolve(),
        ]);
    }

    public function update(UpdateBidanRequest $request, string $id)
    {
        $bidan = $this->bidanService->findById($id);
        $this->authorize('update', $bidan);

        $this->bidanService->update($id, $request->validated());
        return redirect()->route('bidan.index')->with('success', 'Data bidan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $bidan = $this->bidanService->findById($id);
        $this->authorize('delete', $bidan);

        $this->bidanService->delete($id);
        return redirect()->route('bidan.index')->with('success', 'Bidan berhasil dihapus.');
    }

    public function changePassword(ChangePasswordRequest $request, string $id)
    {
        $bidan = $this->bidanService->findById($id);
        $this->authorize('update', $bidan);

        // Update password di user yang terkait
        $user = User::findOrFail($bidan->user_id);
        $user->update(['password' => Hash::make($request->validated('password'))]);

        return redirect()->route('bidan.index')->with('success', 'Password bidan berhasil diubah.');
    }
}
