<?php

namespace App\Workflow;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Workflow generik permission-based: draft → submitted → review → approved → executed → closed
 * (+ rejected / revision). Setiap transisi diaudit via activity log.
 */
class WorkflowService
{
    public const FLOWS = [
        'default' => [
            'draft' => ['submitted'],
            'submitted' => ['review', 'rejected'],
            'review' => ['approved', 'rejected', 'revision'],
            'revision' => ['submitted'],
            'approved' => ['executed', 'rejected'],
            'executed' => ['closed'],
            'rejected' => ['draft'],
            'closed' => [],
        ],
    ];

    public const TRANSITION_PERMISSION = [
        'submitted' => 'create', 'review' => 'update', 'approved' => 'approve',
        'rejected' => 'approve', 'revision' => 'update', 'executed' => 'update',
        'closed' => 'update', 'draft' => 'update',
    ];

    public static function allowed(string $from, string $flow = 'default'): array
    {
        return self::FLOWS[$flow][$from] ?? [];
    }

    /**
     * @param  Model  $model  harus punya kolom `status`
     * @param  string  $module  permission module, mis. 'procurement'
     */
    public static function transition(Model $model, string $to, string $module, ?string $catatan = null, ?int $userId = null): Model
    {
        $from = $model->status;
        if (! in_array($to, self::allowed($from), true)) {
            throw new \InvalidArgumentException("Transisi {$from} → {$to} tidak diizinkan.");
        }

        $user = $userId ? User::findOrFail($userId) : auth()->user();
        $need = $module.'.'.(self::TRANSITION_PERMISSION[$to] ?? 'update');
        if ($user && ! ($user->can($need) || $user->hasRole('super-admin'))) {
            throw new \InvalidArgumentException("Butuh izin {$need} untuk transisi ke {$to}.");
        }

        $model->update(['status' => $to]);

        activity('workflow')->causedBy($user)->performedOn($model)->withProperties([
            'module' => $module, 'from' => $from, 'to' => $to, 'catatan' => $catatan,
        ])->log('workflow_transition');

        return $model->refresh();
    }
}
