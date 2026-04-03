<script>
    var hostUrl = "{{ asset('assets') }}/";
</script>

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->

<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<!--end::Vendors Javascript-->

<!--begin::Custom Javascript(used for this page only)-->
<script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
<script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
<script src="{{ asset('assets/js/custom/apps/chat/chat.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
<script src="{{ asset('assets/js/custom/utilities/modals/users-search.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize image input
        const imageInputElement = document.querySelector('[data-kt-image-input="true"]');
        if (imageInputElement) {
            const imageInput = KTImageInput.getInstance(imageInputElement);
        }

        // Form submission indicator
        const submitButton = document.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.addEventListener('click', function() {
                this.setAttribute('data-kt-indicator', 'on');
                this.disabled = true;
                this.closest('form').submit();
            });
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    //delegated event حتى لو كانت الأزرار داخل DataTable وتُعاد تهيئتها
    document.body.addEventListener('click', function (e) {
        if (!e.target.closest('.delete-btn')) return;

        const btn = e.target.closest('.delete-btn');
        const id  = btn.dataset.id;           // id الموديل
        const form = document.getElementById('delete-form-' + id);

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'لن تستطيع التراجع عن هذه العملية!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذفه',
            cancelButtonText: 'إلغاء',
            reverseButtons: true      // لأجل RTL
        }).then(result => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>

<script>
    @if (session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if (session('error'))
        toastr.error("{{ session('error') }}");
    @endif
</script>
<script>
    $(document).on('click', '.status-toggle', function () {
        let button = $(this);
        let id = button.data('id');
        let model = button.data('model');
        let currentStatus = button.hasClass('btn-success');
        let statusText = currentStatus ? '{{ __("تعطيل") }}' : '{{ __("تفعيل") }}';

        Swal.fire({
            title: '{{ __("هل أنت متأكد؟") }}',
            text: `{{ __("أنت على وشك") }} ${statusText} {{ __("هذا السجل") }}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __("نعم، المتابعة") }}',
            cancelButtonText: '{{ __("إلغاء") }}'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('status.toggle') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        model: model
                    },
                    success: function (response) {
                        if (response.status) {
                            button
                                .toggleClass('btn-success btn-danger')
                                .attr('title', response.is_active ? '{{ __("مفعل") }}' : '{{ __("غير مفعل") }}');

                            button.find('i')
                                .toggleClass('fa-check fa-times');

                            Swal.fire(
                                '{{ __("نجاح!") }}',
                                response.message,
                                'success'
                            );
                            
                            if ($.fn.DataTable.isDataTable('#organizations-table')) {
                                $('#organizations-table').DataTable().ajax.reload(null, false);
                            }
                        } else {
                            Swal.fire(
                                '{{ __("خطأ!") }}',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function (xhr) {
                        Swal.fire(
                            '{{ __("خطأ!") }}',
                            xhr.responseJSON?.message || '{{ __("حدث خطأ أثناء تغيير الحالة.") }}',
                            'error'
                        );
                    }
                });
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInputElement = document.querySelector('[data-kt-image-input="true"]');
        if (imageInputElement && typeof KTImageInput !== 'undefined') {
            KTImageInput.getInstance(imageInputElement);
        }
    });
</script>
@stack('scripts')