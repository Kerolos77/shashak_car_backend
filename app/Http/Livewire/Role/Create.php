<?php

namespace App\Http\Livewire\Role;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;

class Create extends Component
{
    public Role $role;

    public array $permissions = [];

    public array $listsForFields = [];

    public function mount()
    {
        $this->role = new Role();
        $this->initListsForFields();
    }

    public function boot()
    {
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.role.create');
    }

    public function submit()
    {
        // dd(request()->all());
        try {
            $this->validate();

            $this->role->save();
            
            // Only sync permissions if they are provided
            if (!empty($this->permissions)) {
                $this->role->permissions()->sync($this->permissions);
            }

            session()->flash('message', 'Role created successfully.');
            return redirect()->route('admin.roles.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors will be automatically displayed by Livewire
            // No need to handle them here as they're shown in the form
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating role: ' . $e->getMessage());
        }
    }

    protected function rules(): array
    {
        return [
            'role.title' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:roles,title',
            ],
            'permissions' => [
                'array',
            ],
            'permissions.*' => [
                'integer',
                'exists:permissions,id',
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'role.title.required' => 'The role title is required.',
            'role.title.string' => 'The role title must be a string.',
            'role.title.min' => 'The role title must be at least 2 characters.',
            'role.title.max' => 'The role title may not be greater than 255 characters.',
            'role.title.unique' => 'A role with this title already exists.',
            'permissions.array' => 'Permissions must be an array.',
            'permissions.*.integer' => 'Each permission must be an integer.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.',
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['permissions'] = Permission::pluck('title', 'id')->toArray();
    }
}
