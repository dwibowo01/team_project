@props(['for' => null, 'messages' => []])

@if($for)
    @error($for)
        <p {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400']) }}>{{ $message }}</p>
    @enderror
@elseif($messages)
    @foreach((array) $messages as $message)
        <p {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400']) }}>{{ $message }}</p>
    @endforeach
@endif
