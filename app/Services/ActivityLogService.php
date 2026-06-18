<?php
namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogService
{
    public function log(
        string $action,
        string $modelType = null,
        int $modelId = null,
        array $oldValues = null,
        array $newValues = null
    ): void {
        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}