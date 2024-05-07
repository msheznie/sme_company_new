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
<li class="nav-item {{ Request::is('cMPartiesMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMPartiesMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Parties Masters</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMIntentsMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMIntentsMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Intents Masters</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMCounterPartiesMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMCounterPartiesMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Counter Parties Masters</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMContractsMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractsMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contracts Masters</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMContractTypes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractTypes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Types</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMContractTypeSections*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractTypeSections.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Type Sections</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMContractSectionsMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractSectionsMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>C M Contract Sections Masters</span>
    </a>
</li>
<li class="nav-item {{ Request::is('employeesDetails*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('employeesDetails.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Employees Details</span>
    </a>
</li>
<li class="nav-item {{ Request::is('contractUsers*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractUsers.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Users</span>
    </a>
</li>
<li class="nav-item {{ Request::is('supplierMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('supplierMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Supplier Masters</span>
    </a>
</li>
<li class="nav-item {{ Request::is('customerMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('customerMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Customer Masters</span>
    </a>
</li>
<li class="nav-item {{ Request::is('contractMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Masters</span>
    </a>
</li>
<li class="nav-item {{ Request::is('contractSectionDetails*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractSectionDetails.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Section Details</span>
    </a>
</li>
<li class="nav-item {{ Request::is('contractSettingMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractSettingMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Setting Masters</span>
    </a>
</li>
<li class="nav-item {{ Request::is('contractSettingDetails*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractSettingDetails.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Setting Details</span>
    </a>
</li>
<li class="nav-item {{ Request::is('contractMilestones*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractMilestones.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Milestones</span>
    </a>
</li>
<li class="nav-item {{ Request::is('contractDeliverables*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractDeliverables.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Deliverables</span>
    </a>
</li>

<li class="nav-item {{ Request::is('contractOverallRetentions*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractOverallRetentions.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Overall Retentions</span>
<li class="nav-item {{ Request::is('contractBoqItems*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractBoqItems.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Boq Items</span>
    </a>
</li>
<li class="nav-item {{ Request::is('documentMasters*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('documentMasters.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Document Masters</span>
    </a>
</li>
