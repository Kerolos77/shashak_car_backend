<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Models\Permission;

class TestRoleCreation extends Command
{
    protected $signature = 'test:role-creation';
    protected $description = 'Test role creation functionality';

    public function handle()
    {
        $this->info('Testing role creation...');
        
        // Test creating a role
        try {
            $role = new Role();
            $role->title = 'Test Role';
            $role->save();
            
            $this->info('Role created successfully with ID: ' . $role->id);
            
            // Test getting permissions
            $permissions = Permission::pluck('title', 'id')->toArray();
            $this->info('Found ' . count($permissions) . ' permissions');
            
            // Test syncing permissions
            $role->permissions()->sync([1, 2, 3]);
            $this->info('Permissions synced successfully');
            
            // Clean up
            $role->delete();
            $this->info('Test role deleted');
            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}