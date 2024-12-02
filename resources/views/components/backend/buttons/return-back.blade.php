@props(["small" => "true"])
<button
    onclick="window.location.href='{{ route('backend.movies.add') }}'"
    class="btn btn-success {{ $small == 'true' ? 'btn-sm' : '' }} m-1"
    data-toggle="tooltip"
    title="{{ __('Add Movie') }}"
>
    <i class="fas fa-add fa-fw"></i>
    &nbsp;{!! $slot != '' ? '&nbsp;' . $slot : '' !!}
</button>

<button
    onclick="window.history.back();"
    class="btn btn-warning {{ $small == "true" ? "btn-sm" : "" }} m-1"
    data-toggle="tooltip"
    title="{{ __("Return Back") }}"
>
    <i class="fas fa-reply fa-fw"></i>
    &nbsp;{!! $slot != "" ? "&nbsp;" . $slot : "" !!}
</button>

