@props([
    'for' => null,
    'label' => null,
    'error' => null
])

<div class="form-group {{ $error ? 'invalid' : '' }}">
    @if($label)
        <label for="{{ $for }}" class="form-label">{{ $label }}</label>
    @endif
    
    {{ $slot }}
    
    @if($error)
        <span class="text-danger text-sm">{{ $error }}</span>
    @endif
</div>
