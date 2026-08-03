<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::with('permissions')->get();

        return view('admin.role.index', compact('roles'));
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all();
        $permissionGroups = $this->formatPermissionsForView();

        return view('admin.role.create', compact('permissions', 'permissionGroups'));
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all();
        $role->load('permissions');
        $permissionGroups = $this->formatPermissionsForView();

        return view('admin.role.edit', compact('role', 'permissions', 'permissionGroups'));
    }

    public function show(Role $role)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        return view('admin.role.show', compact('role'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'title' => 'required|string|min:2|max:255|unique:roles,title',
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = Role::create([
            'title' => $request->title,
        ]);

        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'title' => 'required|string|min:2|max:255|unique:roles,title,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role->update([
            'title' => $request->title,
        ]);

        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        abort_if(Gate::denies('role_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    private function formatPermissionsForView()
    {
        $allPermissions = Permission::all();
        
        $groups = [
            'coupons' => [
                'name_ar' => '🎫 إدارة الكوبونات والخصومات (Coupons)',
                'icon' => 'ki-discount',
                'permissions' => []
            ],
            'users' => [
                'name_ar' => '👥 إدارة العملاء والمستخدمين (Users)',
                'icon' => 'ki-user',
                'permissions' => []
            ],
            'drivers' => [
                'name_ar' => '🚗 إدارة الكباتن والسائقين (Drivers)',
                'icon' => 'ki-car',
                'permissions' => []
            ],
            'orders' => [
                'name_ar' => '🚚 إدارة الرحلات وطلبات الشحن (Orders)',
                'icon' => 'ki-truck',
                'permissions' => []
            ],
            'financials' => [
                'name_ar' => '💳 الحسابات والمدفوعات والمحفظة (Financials)',
                'icon' => 'ki-wallet',
                'permissions' => []
            ],
            'services' => [
                'name_ar' => '⚙️ إدارة الخدمات والأسعار (Services & Settings)',
                'icon' => 'ki-setting-2',
                'permissions' => []
            ],
            'support' => [
                'name_ar' => '💬 الدعم الفني والتذاكر والإشعارات (Support & Notifications)',
                'icon' => 'ki-message-text',
                'permissions' => []
            ],
            'packages' => [
                'name_ar' => '📦 الباقات والمكافآت (Packages & Rewards)',
                'icon' => 'ki-shop',
                'permissions' => []
            ],
            'admins' => [
                'name_ar' => '👑 إدارة المشرفين والأدوار والمديرين (Admins & Roles)',
                'icon' => 'ki-shield-tick',
                'permissions' => []
            ],
            'other' => [
                'name_ar' => '📁 صلاحيات أخرى (General System Permissions)',
                'icon' => 'ki-element-plus',
                'permissions' => []
            ]
        ];

        foreach ($allPermissions as $perm) {
            $t = $perm->title;
            $desc = $this->getPermissionDescription($t);

            $permData = [
                'id' => $perm->id,
                'title' => $perm->title,
                'label_ar' => $desc['ar_label'],
                'label_en' => $desc['en_label'],
                'desc_ar' => $desc['ar_desc'],
                'desc_en' => $desc['en_desc'],
            ];

            if (str_contains($t, 'coupon')) {
                $groups['coupons']['permissions'][] = $permData;
            } elseif (str_contains($t, 'user') && !str_contains($t, 'driver_user')) {
                $groups['users']['permissions'][] = $permData;
            } elseif (str_contains($t, 'driver')) {
                $groups['drivers']['permissions'][] = $permData;
            } elseif (str_contains($t, 'order') || str_contains($t, 'so_')) {
                $groups['orders']['permissions'][] = $permData;
            } elseif (str_contains($t, 'payment') || str_contains($t, 'wallet') || str_contains($t, 'tax') || str_contains($t, 'currency')) {
                $groups['financials']['permissions'][] = $permData;
            } elseif (str_contains($t, 'service') || str_contains($t, 'setting') || str_contains($t, 'airport')) {
                $groups['services']['permissions'][] = $permData;
            } elseif (str_contains($t, 'faq') || str_contains($t, 'cms') || str_contains($t, 'chat') || str_contains($t, 'caption') || str_contains($t, 'document')) {
                $groups['support']['permissions'][] = $permData;
            } elseif (str_contains($t, 'package') || str_contains($t, 'referral') || str_contains($t, 'on_boarding')) {
                $groups['packages']['permissions'][] = $permData;
            } elseif (str_contains($t, 'role') || str_contains($t, 'permission') || str_contains($t, 'admin')) {
                $groups['admins']['permissions'][] = $permData;
            } else {
                $groups['other']['permissions'][] = $permData;
            }
        }

        return array_filter($groups, fn($g) => count($g['permissions']) > 0);
    }

    private function getPermissionDescription($title)
    {
        $parts = explode('_', $title);
        $action = end($parts);

        $actionMapAr = [
            'access' => 'مشاهدة ودخول',
            'create' => 'إضافة وإنشاء',
            'edit' => 'تعديل وتحديث',
            'show' => 'عرض تفاصيل',
            'delete' => 'حذف وإلغاء',
        ];

        $actionMapEn = [
            'access' => 'View & Access',
            'create' => 'Create & Add',
            'edit' => 'Edit & Update',
            'show' => 'Show Details',
            'delete' => 'Delete & Remove',
        ];

        $actionDescAr = [
            'access' => 'تسمح للمشرف بدخول ومشاهدة قائمة العناصر والبيانات الخاصة بهذا القسم.',
            'create' => 'تسمح للمشرف بإضافة وإنشاء بيانات وسجلات جديدة في هذا القسم.',
            'edit' => 'تسمح للمشرف بتعديل بيانات وسجلات هذا القسم والتأثير على حالتها.',
            'show' => 'تسمح للمشرف بعرض الصفحة التفصيلية الكاملة لكل عنصر في هذا القسم.',
            'delete' => 'تسمح للمشرف بحذف وإلغاء البيانات والسجلات من النظام نهائياً.',
        ];

        $actionDescEn = [
            'access' => 'Allows admin to access and view the listings page for this module.',
            'create' => 'Allows admin to create and add new entries in this module.',
            'edit' => 'Allows admin to edit and modify existing entries in this module.',
            'show' => 'Allows admin to open and view the detailed view page for items in this module.',
            'delete' => 'Allows admin to delete and permanently remove entries in this module.',
        ];

        $actAr = $actionMapAr[$action] ?? 'إدارة';
        $actEn = $actionMapEn[$action] ?? 'Manage';
        $descAr = $actionDescAr[$action] ?? 'صلاحية خاصة لإدارة هذا العنصر في لوحة التحكم.';
        $descEn = $actionDescEn[$action] ?? 'Special permission to manage this item in admin panel.';

        $titlePretty = ucwords(str_replace('_', ' ', $title));

        return [
            'ar_label' => "{$actAr} ({$titlePretty})",
            'en_label' => "{$actEn} ({$titlePretty})",
            'ar_desc' => "[$title]: {$descAr}",
            'en_desc' => "[$title]: {$descEn}",
        ];
    }
}
