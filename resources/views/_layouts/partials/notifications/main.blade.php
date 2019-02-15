{{-- Info --}}
@if (session()->get('notification-info'))
    <div class="notification-container" data-color="bg-black" data-align="center">
        {{ session()->get('notification-info') }}
    </div>
{{-- Success --}}
@elseif (session()->get('notification-success'))
    <div class="notification-container" data-color="bg-green" data-align="center">
        {{ session()->get('notification-success') }}
    </div>
{{-- Warning --}}
@elseif (session()->get('notification-warning'))
    <div class="notification-container" data-color="bg-orange" data-align="center">
        {{ session()->get('notification-warning') }}
    </div>
{{-- Error --}}
@elseif (session()->get('notification-error'))
    <div class="notification-container" data-color="bg-red" data-align="center">
        {{ session()->get('notification-error') }}
    </div>
@endif