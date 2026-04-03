<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WalletTransactionController extends BaseController
{
    public function __construct(WalletTransaction $model)
    {
        parent::__construct($model);
    }

    public function index()
    {
        $rows = $this->model;
        $users = User::get();
        $rows = $this->filter($rows);
        $rows = $rows->paginate(10);
        $moduleName = $this->pluralModelName();
        $sModuleName = $this->getModelName();
        $routeName = $this->getClassNameFromModel();
        $pageTitle = "Control ".$moduleName;
        $pageDes = "Here you can add / edit / delete " .$moduleName;

        return view('admin.' . $this->getClassNameFromModel() . '.index', compact(
            'rows',
            'pageTitle',
            'moduleName',
            'pageDes',
            'sModuleName',
            'routeName',
            'users'
        ));
    }
    public function add_amount(Request $request)
    {
        $user = User::find($request->user_id);
        if($user != null) {
            $user->update([
                'wallet_amount' => $user->wallet_amount + $request->amount
            ]);
        }
        return redirect()->back()->with('success', __('global.create_success'));
    }
}
