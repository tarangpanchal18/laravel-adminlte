@props([
    'name',
    'type' => 'text',
    'data' => [],
    'value' => '',
    'size' => 6,
    'label' => 'Input Field',
    'class' => 'form-control',
    'required' => false,
    'disabled' => false,
])

<div class="form-group col-md-{{ $size }}">
    <label>{{ $label }}</label>
    <select name="{{ $name }}" class="{{ $class }} select2" {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }}>
        <option value="">-- Select --</option>
        @foreach (json_decode( html_entity_decode($data), TRUE) as $name => $id)
        <option {{ $value == $id ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>
</div>
