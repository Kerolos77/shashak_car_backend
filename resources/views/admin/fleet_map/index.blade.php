@extends('layouts.admin')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxxl">

        <div class="card mb-6 shadow-sm border-0">
            <div class="card-body p-6 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-light-primary p-4 rounded-circle">
                        <i class="ki-outline ki-geolocation fs-2x text-primary"></i>
                    </div>
                    <div>
                        <h2 class="fw-bolder mb-1">خريطة تتبع أسطول السائقين المباشرة (Live Fleet Driver Map)</h2>
                        <div class="text-muted fs-6">عرض أماكن وحالات السائقين المتصلين والمتاحين في الوقت الفعلي على الخريطة.</div>
                    </div>
                </div>
                <button id="refreshMapBtn" class="btn btn-light-primary fw-bold">
                    <i class="ki-outline ki-arrows-loop fs-4 me-1"></i> تحديث الخريطة الآن
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-6">
            <div class="card-body p-4">
                <div class="row g-4 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">تصفية بحسب الخدمة</label>
                        <select id="filter_service" class="form-select form-select-solid">
                            <option value="">جميع الخدمات</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->title }} ({{ $service->service_type }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">تصفية بحسب حالة السائق</label>
                        <select id="filter_status" class="form-select form-select-solid">
                            <option value="">جميع الحالات</option>
                            <option value="active">نشط ومتاح (Active)</option>
                            <option value="pending">بانتظار المراجعة (Pending)</option>
                            <option value="blocked">محظور (Blocked)</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-center gap-4 mt-8">
                        <div><span class="badge bg-success p-2 me-1"></span> متاح أونلاين</div>
                        <div><span class="badge bg-secondary p-2 me-1"></span> أوفلاين</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div id="fleetMap" style="height: 650px; width: 100%; border-radius: 8px;"></div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet OpenStreetMap (Free Light & Fast Live Map) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Default Cairo Center
    var map = L.map('fleetMap').setView([30.0444, 31.2357], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; Shakshak Live Fleet Map',
        maxZoom: 19
    }).addTo(map);

    var markersGroup = L.layerGroup().addTo(map);

    function loadDrivers() {
        var serviceId = $('#filter_service').val();
        var status = $('#filter_status').val();

        $.ajax({
            url: '{{ route("admin.fleet-map.data") }}',
            type: 'GET',
            data: {
                service_id: serviceId,
                status: status
            },
            success: function (res) {
                if (res.success) {
                    markersGroup.clearLayers();
                    var bounds = [];

                    res.drivers.forEach(function (driver) {
                        if (driver.lat && driver.lng) {
                            var color = driver.is_online ? 'green' : 'gray';
                            var marker = L.circleMarker([driver.lat, driver.lng], {
                                radius: 9,
                                fillColor: color,
                                color: '#ffffff',
                                weight: 2,
                                opacity: 1,
                                fillOpacity: 0.9
                            });

                            var popupContent = `
                                <div class="p-2 text-right">
                                    <h6 class="fw-bolder mb-1">${driver.name}</h6>
                                    <div class="text-muted fs-7 mb-1">📱 ${driver.phone}</div>
                                    <div class="badge bg-light-primary text-primary mb-1">${driver.service_title}</div>
                                    <div><span class="badge ${driver.is_online ? 'bg-success' : 'bg-secondary'}">${driver.is_online ? 'متصل أونلاين' : 'غير متصل'}</span></div>
                                </div>
                            `;

                            marker.bindPopup(popupContent);
                            markersGroup.addLayer(marker);
                            bounds.push([driver.lat, driver.lng]);
                        }
                    });

                    if (bounds.length > 0) {
                        map.fitBounds(bounds, { padding: [50, 50] });
                    }
                }
            }
        });
    }

    loadDrivers();

    $('#filter_service, #filter_status').on('change', loadDrivers);
    $('#refreshMapBtn').on('click', loadDrivers);

    // Auto Refresh map every 30 seconds
    setInterval(loadDrivers, 30000);
});
</script>
@endpush
