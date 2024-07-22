@props([
    'name',
    'type' => 'text',
    'id' => '',
    'value' => '',
    'size' => 6,
    'placeholder' => 'Enter '.ucfirst($name).' here',
    'label' => 'Input Field',
    'class' => 'form-control',
    'required' => false,
    'disabled' => false,
    'helpText' => '',
    'style' => '',
    'autocomplete' => 'off'
])

@if($type == "hidden")
<input
    type="{{ $type }}"
    @if (!empty($id)) id="{{ $id }}" @endif
    name="{{ $name }}"
    class="{{ $class }}"
    placeholder="{{ strreplace($placeholder) }}"
    value="{{ old($name, $value) }}"
    autocomplete="{{ $autocomplete }}"
    {{ $required ? 'required' : '' }}
    {{ $disabled ? 'disabled' : '' }}
    {{ $style ? 'style="'.$style.'"' : ''}}
/>
@else
<div class="form-group col-md-{{$size}}">
    <label>{{ $label }}</label>
    <input
        type="{{ $type }}"
        @if (!empty($id)) id="{{ $id }}" @endif
        name="{{ $name }}"
        class="{{ $class }}"
        placeholder="{{ strreplace($placeholder) }}"
        value="{{ old($name, $value) }}"
        autocomplete="{{ $autocomplete }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $style ? 'style="'.$style.'"' : ''}}
    />
    @if($helpText)<p class="text-sm text-muted">{{ $helpText }}</p>@endif
    @error($name)<p class="text-danger">{{ $message  }}</p>@enderror
</div>
@endif
