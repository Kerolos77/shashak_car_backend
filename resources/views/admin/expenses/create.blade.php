@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item text-muted">إدارة الحسابات</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-muted">المصروفات</li>
    <span class="bullet bg-gray-300 w-5px h-2px"></span>
    <li class="breadcrumb-item text-dark">إضافة مصروف يدوي</li>
@endsection

@section('content')
@section('title', 'إضافة مصروف يدوي جديد')
@section('pageName', 'إضافة مصروف يدوي جديد')

<div class="row">
    <div class="col-lg-12">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center">
                <i class="ti ti-plus fs-3 text-primary me-2"></i>
                <h3 class="m-0 text-gray-800">بيانات المصروف اليدوي</h3>
            </div>
            
            <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- Category -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label required fw-bold" for="category">تصنيف المصروف</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" id="category" required>
                                <option value="" disabled selected>اختر التصنيف...</option>
                                <option value="digitalocean" {{ old('category') == 'digitalocean' ? 'selected' : '' }}>ديجيتال أوشن (سيرفر)</option>
                                <option value="google_cloud" {{ old('category') == 'google_cloud' ? 'selected' : '' }}>جوجل كلاود (Firebase/Gemini/APIs)</option>
                                <option value="domain" {{ old('category') == 'domain' ? 'selected' : '' }}>تجديد الدومين</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>مصاريف تشغيلية أخرى</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="col-lg-3 mb-3">
                            <label class="form-label required fw-bold" for="amount">القيمة المالية</label>
                            <input type="number" step="0.01" value="{{ old('amount') }}" name="amount" 
                                class="form-control @error('amount') is-invalid @enderror" id="amount" 
                                placeholder="مثال: 15.50" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Currency -->
                        <div class="col-lg-3 mb-3">
                            <label class="form-label required fw-bold" for="currency">العملة</label>
                            <select name="currency" class="form-select @error('currency') is-invalid @enderror" id="currency" required>
                                <option value="EGP" {{ old('currency') == 'EGP' ? 'selected' : '' }}>الجنيه المصري (EGP)</option>
                                <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>الدولار الأمريكي (USD)</option>
                            </select>
                            @error('currency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label required fw-bold" for="expense_date">تاريخ صرف المصروف</label>
                            <input type="date" value="{{ old('expense_date', date('Y-m-d')) }}" name="expense_date" 
                                class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" required>
                            @error('expense_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Invoice File -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label fw-bold" for="invoice_file">ملف الفاتورة (مستند أو صورة)</label>
                            <input type="file" name="invoice_file" class="form-control @error('invoice_file') is-invalid @enderror" 
                                id="invoice_file" accept=".pdf,.jpeg,.png,.jpg">
                            <div class="form-text text-muted">الصيغ المسموحة: PDF, JPEG, PNG (الحد الأقصى 5 ميجابايت)</div>
                            @error('invoice_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-lg-12 mb-3">
                            <label class="form-label required fw-bold" for="description">شرح المصروف (البيان)</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                id="description" rows="4" placeholder="مثال: فاتورة حجز الدومين ShakshakCar.net للعام 2026/2027" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end gap-2 bg-light">
                    <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ المصروف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
