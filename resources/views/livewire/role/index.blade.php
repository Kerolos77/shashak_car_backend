  <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_roles_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('cruds.role.fields.title') }}</th>
                            <th class="text-center">{{ __('cruds.role.fields.permissions') }}</th>
                            <th class="text-center">{{ __('global.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($roles as $role)
                        <tr>
                            <td class="text-center">{{ $role->id }}</td>
                            <td class="text-center">{{ $role->title }}</td>
                            <td class="text-center">
                                @foreach($role->permissions as $permission)
                                    <span class="badge bg-primary">{{ $permission->title }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    @can('role_edit')
                                    <a href="{{ route('admin.roles.edit', $role->id) }}"
                                       class="btn btn-sm btn-icon btn-light-primary me-2" title="{{ __('global.edit') }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    @endcan

                                    @can('role_show')
                                    <a href="{{ route('admin.roles.show', $role->id) }}"
                                       class="btn btn-sm btn-icon btn-light-success me-2" title="{{ __('global.view') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @endcan

                                    @can('role_delete')
                            <button class="btn btn-sm btn-danger mr-2" type="button" wire:click="confirm('delete', {{ $role->id }})" wire:loading.attr="disabled">
                              <i class="fa fa-trash"></i>
                            </button>
                        @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if ($roles->isEmpty())
                        <tr>
                            <td class="text-center" colspan="4">{{ __('global.no_entries_found') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!--end::Card body-->

        </div>
 
