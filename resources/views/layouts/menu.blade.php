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
<li class="nav-item {{ Request::is('tenants*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('tenants.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Tenants</span>
    </a>
</li>
<li class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('users.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Users</span>
    </a>
</li>
<li class="nav-item {{ Request::is('employees*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('employees.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Employees</span>
    </a>
</li>
<li class="nav-item {{ Request::is('webEmployeeProfiles*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('webEmployeeProfiles.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Web Employee Profiles</span>
    </a>
</li>
<li class="nav-item {{ Request::is('companies*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('companies.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Companies</span>
    </a>
</li>
<li class="nav-item {{ Request::is('erpEmployeeNavigations*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('erpEmployeeNavigations.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Erp Employee Navigations</span>
    </a>
</li>
<li class="nav-item {{ Request::is('navigationUserGroupSetups*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('navigationUserGroupSetups.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Navigation User Group Setups</span>
    </a>
</li>
