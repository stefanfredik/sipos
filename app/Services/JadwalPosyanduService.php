<?php

namespace App\Services;

use App\DTOs\JadwalPosyandu\CreateJadwalDTO;
use App\DTOs\JadwalPosyandu\UpdateJadwalDTO;
use App\Enums\JadwalStatus;
use App\Models\JadwalPosyandu;
use App\Models\User;
use App\Enums\UserRole;
use App\Notifications\JadwalBaruNotification;
use App\Notifications\JadwalUpdateNotification;
use App\Repositories\Interfaces\JadwalPosyanduRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Notification;

class JadwalPosyanduService extends BaseService
{
    public function __construct(
        JadwalPosyanduRepositoryInterface $repository
    ) {
        parent::__construct($repository);
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }

    public function findById(string $id): JadwalPosyandu
    {
        return $this->repository->findById($id);
    }

    public function create(CreateJadwalDTO $dto): JadwalPosyandu
    {
        $jadwal = $this->repository->create([
            'posyandu_id' => $dto->posyandu_id,
            'kader_id' => $dto->kader_id,
            'tanggal' => $dto->tanggal,
            'waktu_mulai' => $dto->waktu_mulai,
            'waktu_selesai' => $dto->waktu_selesai,
            'status' => $dto->status,
            'catatan_bidan' => $dto->catatan_bidan,
        ]);

        // Notify all bidans about new jadwal
        $bidans = User::where('role', UserRole::Bidan)->get();
        Notification::send($bidans, new JadwalBaruNotification($jadwal));

        return $jadwal;
    }

    public function update(string $id, UpdateJadwalDTO $dto): JadwalPosyandu
    {
        $jadwal = $this->repository->findById($id);
        $oldStatus = $jadwal->status;

        $this->repository->update($id, [
            'posyandu_id' => $dto->posyandu_id,
            'kader_id' => $dto->kader_id,
            'bidan_id' => $dto->bidan_id,
            'tanggal' => $dto->tanggal,
            'waktu_mulai' => $dto->waktu_mulai,
            'waktu_selesai' => $dto->waktu_selesai,
            'status' => $dto->status,
            'catatan_bidan' => $dto->catatan_bidan,
        ]);

        $jadwal->refresh();

        if ($oldStatus === JadwalStatus::Draft && $jadwal->status !== JadwalStatus::Draft) {
            $this->notifyKader($jadwal);
        }

        return $jadwal;
    }

    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function validate(JadwalPosyandu $jadwal, string $bidanId, ?string $catatanBidan = null): JadwalPosyandu
    {
        $jadwal->update([
            'status' => JadwalStatus::Validated,
            'bidan_id' => $bidanId,
            'catatan_bidan' => $catatanBidan,
        ]);

        $this->notifyKader($jadwal);

        return $jadwal->refresh();
    }

    public function reject(JadwalPosyandu $jadwal, string $catatanBidan): JadwalPosyandu
    {
        $jadwal->update([
            'status' => JadwalStatus::Rejected,
            'catatan_bidan' => $catatanBidan,
        ]);

        $this->notifyKader($jadwal);

        return $jadwal->refresh();
    }

    public function complete(JadwalPosyandu $jadwal): JadwalPosyandu
    {
        $jadwal->update([
            'status' => JadwalStatus::Completed,
        ]);

        return $jadwal->refresh();
    }

    public function getUpcoming(int $days = 7): Collection
    {
        return $this->repository->getUpcoming($days);
    }

    public function getByMonth(int $year, int $month): Collection
    {
        return $this->repository->getByMonth($year, $month);
    }

    public function getByPosyandu(string $posyanduId): Collection
    {
        return $this->repository->getByPosyandu($posyanduId);
    }

    private function notifyKader(JadwalPosyandu $jadwal): void
    {
        $kaderUser = $jadwal->kader?->user;
        if ($kaderUser) {
            $kaderUser->notify(new JadwalUpdateNotification($jadwal));
        }
    }
}
