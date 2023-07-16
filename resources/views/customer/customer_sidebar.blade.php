<ul class="list">
    <li class="customer-info">
        <a href="{{ route('customer.profile') }}" class="{{ Request::is('customer/profile') ? 'active' : '' }}" data-hover="Customer info">Customer info</a>
    </li>
{{--    <li class="customer-addresses">--}}
{{--        <a href="{{ route('customer.address') }}" class="{{ Request::is('customer/address') ? 'active' : '' }}" data-hover="Addresses">Addresses</a>--}}
{{--    </li>--}}
    <li class="customer-orders">
        <a href="{{ route('customer.order') }}" class="{{ Request::is('customer/order') ? 'active' : '' }}" data-hover="Orders">Orders</a>
    </li>
    <li class="change-password">
        <a href="{{ route('customer.change.password') }}" class="{{ Request::is('customer/change-password') ? 'active' : '' }}" data-hover="Change your Password">Change your Password</a>
    </li>
</ul>
