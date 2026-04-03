<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends BaseController
{
        public function __construct(Admin $model)
        {
            parent::__construct($model);
        }
        protected function append(){
            return [];
            
        }
        public function store(StoreAdminRequest $request){
            $requestArray = $request->all();
            $requestArray['password'] =  Hash::make($requestArray['password']);
            unset($requestArray['confirm_password']);
            unset($requestArray['roles']);
            $row = $this->model->create($requestArray);
            $row->roles()->sync($request->roles);
            return redirect()->route('admin.admins.index');
        }
        public function create()
        {
            $moduleName = $this->getModelName();
            $pageTitle = "Create ". $moduleName;
            $pageDes = "Here you can create " .$moduleName;
            $folderName = $this->getClassNameFromModel();
            $routeName = $folderName;
            $roles = Role::get();
            return view('admin.' . $folderName . '.create' , compact(
                'pageTitle',
                'moduleName',
                'pageDes',
                'folderName',
                'routeName',
                'roles'
            ));
        }
    
        public function update($id , UpdateAdminRequest $request){
            $row = $this->model->FindOrFail($id);
            $requestArray = $request->all();
            if(isset($requestArray['password']) && $requestArray['password'] != ""){
                $requestArray['password'] =  Hash::make($requestArray['password']);
            }else{
                unset($requestArray['password']);
            }
            unset($requestArray['confirm_password']);
            unset($requestArray['roles']);

            $row->update($requestArray);
            $row->roles()->sync($request->roles);

            return redirect()->route('admin.admins.index');
        }
        public function edit($id)
    {
        $row        = $this->model->FindOrFail($id);
        $roles      = Role::get();
        $moduleName = $this->getModelName();
        $pageTitle  = "Edit " . $moduleName;
        $pageDes    = "Here you can edit " .$moduleName;
        $folderName = $this->getClassNameFromModel();
        $routeName  = $folderName;
        $append     = $this->append();
        $selected   = $row->roles->pluck('id')->toArray();
        return view('admin.' . $folderName . '.edit', compact(
            'row',
            'pageTitle',
            'moduleName',
            'pageDes',
            'folderName',
            'routeName',
            'roles',
            'selected'
        ));
    }
}
