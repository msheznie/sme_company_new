<li class="nav-item {{ Request::is('navigations*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('navigations.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Navigations</span>
    </a>
</li>
<li class="nav-item {{ Request::is('roleHasPermissions*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('roleHasPermissions.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Role Has Permissions</span>
    </a>
</li>
<li class="nav-item {{ Request::is('navigationRoles*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('navigationRoles.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Navigation Roles</span>
    </a>
</li>
<li class="nav-item {{ Request::is('permissionsModels*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('permissionsModels.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Permissions Models</span>
    </a>
</li>
<li class="nav-item {{ Request::is('priceLists*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('priceLists.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Price Lists</span>
    </a>
</li>
<li class="nav-item {{ Request::is('currencyMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('currencyMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Currency Masters</span>
    </a>
</li>
