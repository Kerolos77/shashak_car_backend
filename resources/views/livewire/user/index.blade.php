<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_users_table">
    <thead>
        <tr class="text-gray-500 fw-bold fs-7 text-uppercase text-center">
            <th>#</th>
            <th>{{ trans('cruds.user.fields.name') }}</th>
            <th>{{ trans('cruds.user.fields.email') }}</th>
            <th>{{ trans('cruds.user.fields.roles') }}</th>
            <th>{{ trans('cruds.user.fields.locale') }}</th>
            <th>{{ trans('cruds.user.fields.phone_number') }}</th>
            <th>{{ trans('cruds.user.fields.wallet_amount') }}</th>
            <th>{{ trans('cruds.referral.fields.referral_code') }}</th>
            <th>{{ trans('cruds.referral.fields.referral_by') }}</th>
            <th>{{ trans('global.gender') }}</th>
            <th>{{ trans('cruds.driver.fields.status') }}</th> 
            <th>{{ trans('cruds.user.fields.created_at') }}</th>
            <th>{{ trans('global.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr class="text-center">
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                <td>
                    @foreach ($user->roles as $role)
                        <span class="badge badge-light-primary">{{ $role->title }}</span>
                    @endforeach
                </td>
                <td>{{ $user->locale }}</td>
                <td>{{ $user->phone_number }}</td>
                <td>{{ $user->wallet_amount }}</td>
                <td>{{ $user->referral_code }}</td>
                <td>{{ $user->referrer ? $user->referrer->name : '-' }}</td>
                <td>{{ $user->gender ? trans('global.' . strtolower($user->gender)) : '-' }}</td>
                <td>
                    <span class="badge badge-{{ $user->is_active ? 'success' : 'danger' }}">
                        {{ $user->is_active ? trans('global.active') : trans('global.inactive') }}
                    </span>
                </td>
                <td>{{ $user->created_at }}</td>
                <td>
                    <div class="d-flex justify-content-center">
                        @can('user_show')
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-light-info me-2"><i
                                    class="fa fa-eye"></i></a>
                        @endcan
                        @can('user_edit')
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-light-success me-2"><i
                                    class="fa fa-edit"></i></a>
                        @endcan
                        @can('user_delete')
                          <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" method="POST"
                                              id="delete-form-{{ $user->id }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-icon btn-light-danger"
                                                onclick="confirmDelete({{ $user->id }})" title="{{ trans('global.delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="d-flex justify-content-center mt-4">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
