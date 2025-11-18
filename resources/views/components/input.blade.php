@props([
  'label' => null,
  'name' => null,      // pour afficher l'erreur @error
  'type' => 'text',
  'required' => false,
])

<div class="w-full">
  @if($label)
    <label class="block text-sm font-medium mb-1">
      {{ $label }} @if($required)<span class="text-red-600">*</span>@endif
    </label>
  @endif

  <input
    type="{{ $type }}"
    name="{{ $name }}"
    {{ $attributes->merge(['class' => 'input input-bordered w-full']) }} />

  @if($name)
    @error($name)
      <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
  @endif
</div>
