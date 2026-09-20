{{-- Component: Form Error Message --}}
@if($errors->has($for))
    <span class="text-red-500 text-xs">{{ $errors->first($for) }}</span>
@endif
