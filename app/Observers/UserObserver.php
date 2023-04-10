<?php

namespace App\Observers;

use App\Models\Leader;
use App\Models\Manager;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $adminPermissions = Permission::whereNotIn('permission', [
            'campaign_create',
            'campaign_edit',
            'campaign_delete',
            'screening_create',
            'screening_edit',
            'screening_delete',
        ])->get();

        $managerPermissions = Permission::whereIn('permission', [
            'campaign_create',
            'campaign_access',
            'campaign_show',
            'campaign_edit',
            'campaign_delete',
            'stock_create',
            'stock_access',
            'stock_show',
            'stock_edit',
            'stock_delete',
            'screening_access',
        ])->get();

        $leaderPermissions = Permission::whereIn('permission', [
            'screening_access',
            'screening_create',
            'screening_show',
            'screening_edit',
            'screening_delete'
        ])->get();

        // After user is created in dashboard, Add respective permissions
        
        if ($user->role->role == Role::ADMIN_ROLE) {
            $user->permissions()->sync($adminPermissions);
        } else {
            $newManagerOrLeader = [
                'id' => Str::uuid()->toString(),
                'user_id' => $user->id
            ];
            if ($user->role->role == Role::DISTRICT_MANAGER_ROLE) {
                $user->permissions()->sync($managerPermissions);
                Manager::insert($newManagerOrLeader);
            } else {
                $user->permissions()->sync($leaderPermissions);
                Leader::insert($newManagerOrLeader);
            }
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
