<select id="select-nav" name="select-nav" onchange="setLocation(this.value);">
    <option selected="selected" value="{{ route('customer.profile') }}">Customer info</option>
    <option value="{{ route('customer.address') }}">Addresses</option>
    <option value="{{ route('customer.order') }}">Orders</option>
    <option value="{{ route('customer.change.password') }}">Change your Password</option>
</select>
