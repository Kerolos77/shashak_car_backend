<?php

namespace App\Http\Livewire\Role;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;

class Edit extends Component
{
    public Role $role;

    public array $permissions = [];

    public array $listsForFields = [];

    public function mount(Role $role)
    {
        $this->role        = $role;
        $this->permissions = $this->role->permissions()->pluck('id')->toArray();
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.role.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->role->save();
        $this->role->permissions()->sync($this->permissions);

        return redirect()->route('admin.roles.index');
    }

    protected function rules(): array
    {
        return [
            'role.title' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'unique:roles,title,' . $this->role->id,
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
