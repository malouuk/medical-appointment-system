@switch($status)
    @case('pending')
        <span class="badge badge-status bg-secondary">
            <i class="bi bi-hourglass-split me-1"></i>{{ __('messages.pending') }}
        </span>
        @break
    @case('confirmed')
        <span class="badge badge-status bg-dark">
            <i class="bi bi-check-circle me-1"></i>{{ __('messages.confirmed') }}
        </span>
        @break
    @case('cancelled')
        <span class="badge badge-status border border-dark text-dark" style="background:transparent;">
            <i class="bi bi-x-circle me-1"></i>{{ __('messages.cancelled') }}
        </span>
        @break
    @case('completed')
        <span class="badge badge-status bg-light text-dark border border-secondary">
            <i class="bi bi-check-all me-1"></i>{{ __('messages.completed') }}
        </span>
        @break
@endswitch
