<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditObserver
{
    /**
     * Handle the created event for all models.
     */
    public function created(Model $model): void
    {
        $this->log('created', $model, null, $model->getAttributes());
    }

    /**
     * Handle the updated event for all models.
     */
    public function updated(Model $model): void
    {
        if ($model->wasRecentlyCreated) {
            return;
        }

        $changes = $model->getChanges();
        $original = $model->getOriginal();
        
        if (!empty($changes)) {
            $this->log('updated', $model, $original, $changes);
        }
    }

    /**
     * Handle the deleted event for all models.
     */
    public function deleted(Model $model): void
    {
        $this->log('deleted', $model, $model->getOriginal(), null);
    }

    /**
     * Handle the restored event for all models.
     */
    public function restored(Model $model): void
    {
        $this->log('restored', $model, null, $model->getAttributes());
    }

    /**
     * Log an audit event.
     */
    protected function log(string $event, Model $model, ?array $oldValues, ?array $newValues): void
    {
        // Skip if no authenticated user
        $user = Auth::user();
        
        AuditLog::create([
            'user_id' => $user?->id,
            'event' => $event,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'old_values' => $this->filterSensitiveData($oldValues),
            'new_values' => $this->filterSensitiveData($newValues),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Filter sensitive data from values.
     */
    protected function filterSensitiveData(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        $sensitive = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];
        
        return collect($values)
            ->except($sensitive)
            ->toArray();
    }
}
