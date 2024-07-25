
<ul>
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
        </a>
    </li>
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
    <li class="nav-item {{ Request::is('erpDocumentAttachments*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('erpDocumentAttachments.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Erp Document Attachments</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('contractDocuments*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('contractDocuments.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Contract Documents</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('documentReceivedFormats*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('documentReceivedFormats.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Document Received Formats</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('erpDocumentMasters*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('erpDocumentMasters.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Erp Document Masters</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('contractAdditionalDocuments*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('contractAdditionalDocuments.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Contract Additional Documents</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('contractMilestoneRetentions*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('contractMilestoneRetentions.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Contract Milestone Retentions</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('contractUserGroups*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('contractUserGroups.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Contract User Groups</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('contractUserAssigns*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('contractUserAssigns.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Contract User Assigns</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('milestoneStatusHistories*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('milestoneStatusHistories.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Milestone Status Histories</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('erpDocumentApproveds*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('erpDocumentApproveds.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Erp Document Approveds</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('companyDocumentAttachments*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('companyDocumentAttachments.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Company Document Attachments</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('erpApprovalLevels*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('erpApprovalLevels.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Erp Approval Levels</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('erpApprovalRoles*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('erpApprovalRoles.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Erp Approval Roles</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('contractHistories*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('contractHistories.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Contract Histories</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('erpEmployeesDepartments*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('erpEmployeesDepartments.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Erp Employees Departments</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('billingFrequencies*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('billingFrequencies.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Billing Frequencies</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('periodicBillings*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('periodicBillings.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Periodic Billings</span>
        </a>
    </li>
    <li class="nav-item {{ Request::is('milestonePaymentSchedules*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('milestonePaymentSchedules.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Milestone Payment Schedules</span>
        </a>
    </li>

<li class="nav-item {{ Request::is('contractStatusHistories*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractStatusHistories.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Status Histories</span>
    </a>
</li>

<li class="nav-item {{ Request::is('timeMaterialConsumptions*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('timeMaterialConsumptions.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Time Material Consumptions</span>

<li class="nav-item {{ Request::is('cMContractReminderScenarios*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractReminderScenarios.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>C M Contract Reminder Scenarios</span>
    </a>
</li>
    <li class="nav-item {{ Request::is('contractPaymentTerms*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('contractPaymentTerms.index') }}">
            <i class="nav-icon icon-cursor"></i>
            <span>Contract Payment Terms</span>
        </a>
    </li>
</ul>
<li class="nav-item {{ Request::is('cMContractMasterAmds*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractMasterAmds.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>C M Contract Master Amds</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMContractUserAssignAmds*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractUserAssignAmds.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>C M Contract User Assign Amds</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMContractBoqItemsAmds*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractBoqItemsAmds.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>C M Contract Boq Items Amds</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMContractMileStoneAmds*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractMileStoneAmds.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>C M Contract Mile Stone Amds</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMContractStatusHistoryAmds*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractStatusHistoryAmds.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>C M Contract Status History Amds</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMContractDeliverableAmds*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractDeliverableAmds.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>C M Contract Deliverable Amds</span>
    </a>
</li>

<li class="nav-item {{ Request::is('cMContractOverallRetentionAmds*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractOverallRetentionAmds.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>C M Contract Overall Retention Amds</span>
    </a>
</li>
<li class="nav-item {{ Request::is('cMContractDocumentAmds*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('cMContractDocumentAmds.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>C M Contract Document Amds</span>
    </a>
</li>
<li class="nav-item {{ Request::is('erpDocumentAttachmentsAmds*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('erpDocumentAttachmentsAmds.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Erp Document Attachments Amds</span>
    </a>
</li>
<li class="nav-item {{ Request::is('contractAmendmentAreas*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractAmendmentAreas.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Amendment Areas</span>
    </a>
</li>
<<<<<<< HEAD
<li class="nav-item {{ Request::is('appearanceSettings*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('appearanceSettings.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Appearance Settings</span>
    </a>
</li>
<li class="nav-item {{ Request::is('appearanceElements*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('appearanceElements.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Appearance Elements</span>
    </a>
</li>
<li class="nav-item {{ Request::is('systemConfigurationAttributes*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('systemConfigurationAttributes.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>System Configuration Attributes</span>
    </a>
</li>
<li class="nav-item {{ Request::is('systemConfigurationDetails*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('systemConfigurationDetails.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>System Configuration Details</span>
    </a>
</li>
<li class="nav-item {{ Request::is('contractAdditionalDocumentAmds*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('contractAdditionalDocumentAmds.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Contract Additional Document Amds</span>
    </a>
</li>
<li class="nav-item {{ Request::is('thirdPartySystems*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('thirdPartySystems.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Third Party Systems</span>
    </a>
</li>
<li class="nav-item {{ Request::is('thirdPartyIntegrationKeys*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('thirdPartyIntegrationKeys.index') }}">
        <i class="nav-icon icon-cursor"></i>
        <span>Third Party Integration Keys</span>
    </a>
</li>
