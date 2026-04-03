<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends BaseController
{
    public function __construct(Page $model)
        {
            parent::__construct($model);
        }

        public function store(Request $request){
            $requestArray = $request->all();
            $row = $this->model->create($requestArray);
            $row->roles()->sync($request->roles);
            return redirect()->route('admin.pages.index');
        }
        public function create()
        {
            $moduleName = $this->getModelName();
            $pageTitle = "Create ". $moduleName;
            $pageDes = "Here you can create " .$moduleName;
            $folderName = $this->getClassNameFromModel();
            $routeName = $folderName;
            return view('admin.' . $folderName . '.create' , compact(
                'pageTitle',
                'moduleName',
                'pageDes',
                'folderName',
                'routeName',
            ));
        }
    
        public function update($id , Request $request){
            $row = $this->model->FindOrFail($id);
            $requestArray = $request->all();
            $row->update($requestArray);
            return redirect()->route('admin.pages.edit' , ['id' => $row->id]);
        }
        public function edit($id)
    {
        $row        = $this->model->FindOrFail($id);
        $moduleName = $this->getModelName();
        $pageTitle  = "Edit " . $moduleName;
        $pageDes    = "Here you can edit " .$moduleName;
        $folderName = $this->getClassNameFromModel();
        $routeName  = $folderName;
        $append     = $this->append();
        return view('admin.' . $folderName . '.edit', compact(
            'row',
            'pageTitle',
            'moduleName',
            'pageDes',
            'folderName',
            'routeName',
        ));
    }
}
