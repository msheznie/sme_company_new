<?php

namespace Database\Seeders;

use App\Models\FormData;
use App\Models\FormField;
use App\Models\FormFieldData;
use App\Models\FormFieldValidator;
use App\Models\FormGroup;
use App\Models\FormGroupDetail;
use App\Models\FormSection;
use App\Models\FormValidator;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultKYCFormDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // validations
        define('REQUIRED', 'REQUIRED'); // 1
        define('EMAIL', 'EMAIL'); // 2
        define('MIN_LENGTH', 'MIN_LENGTH'); // 3
        define('MAX_LENGTH', 'MAX_LENGTH'); // 4
        define('PATTERN', 'PATTERN'); // 5
        define('NO_SPECIAL_CHARACTER', 'NO_SPECIAL_CHARACTER'); // 6
        define('YEAR', 'YEAR'); // 7
        define('URL', 'URL'); // 8
        define('NUMBER', 'NUMBER'); // 9
        define('LETTER_NUMBER_DASH', 'LETTER_NUMBER_DASH'); // 10
        define('UNSIGNED_INTEGER', 'UNSIGNED_INTEGER'); // 11
        define('LETTERS_ONLY', 'LETTERS_ONLY'); // 12
        define('SPECIAL_CHARACTER_COMMAS', 'SPECIAL_CHARACTER_COMMAS'); // 13
        define('PHONE_NUMBER', 'PHONE_NUMBER'); // 14

        DB::transaction(function (){
            /**
             * cleaning tables before seeding
             */
            FormSection::truncate();
            FormGroup::truncate();
            FormField::truncate();
            FormFieldData::truncate();
            FormFieldValidator::truncate();
            FormGroupDetail::truncate();
            FormData::truncate();
            FormValidator::truncate();

            /**
             * reusable variable
             */
            $now = Carbon::now();

            /**
             * DB inserts
             */

            // insert form sections
            FormSection::insert([
                [
                    'tenant_id'     => 0,
                    'name'          => 'general_details',
                    'display_name'  => 'General Details',
                    'icon'          => 'fa-user',
                    'status'        => 1,
                    'sort'          => 1,
                    'created_at'    => $now
                ],
                [
                    'tenant_id'     => 0,
                    'name'          => 'cooperate_details',
                    'display_name'  => 'Cooperate Details',
                    'icon'          => 'fa-building',
                    'status'        => 1,
                    'sort'          => 2,
                    'created_at'    => $now
                ],
                [
                    'tenant_id'     => 0,
                    'name'          => 'finance_details',
                    'display_name'  => 'Finance Details',
                    'icon'          => 'fa-money',
                    'status'        => 1,
                    'sort'          => 3,
                    'created_at'    => $now
                ],
                [
                    'tenant_id'     => 0,
                    'name'          => 'products_&_services',
                    'display_name'  => 'Products & Services',
                    'icon'          => 'fa-list-alt',
                    'status'        => 1,
                    'sort'          => 4,
                    'created_at'    => $now
                ],
                [
                    'tenant_id'     => 0,
                    'name'          => 'supplier_address',
                    'display_name'  => 'Supplier Address',
                    'icon'          => 'fa-address-book',
                    'status'        => 1,
                    'sort'          => 5,
                    'created_at'    => $now
                ],
                [
                    'tenant_id'     => 0,
                    'name'          => 'supplier_contacts',
                    'display_name'  => 'Supplier Contacts',
                    'icon'          => 'fa-address-card',
                    'status'        => 1,
                    'sort'          => 6,
                    'created_at'    => $now
                ]
            ]);

            // insert sections form groups
            FormGroup::insert([
                // section 1
                [
                    'form_section_id'   => 1,
                    'name'              => 'basic_details',
                    'display_name'      => 'Basic Details',
                    'created_at'        => $now,
                    'type'              => 'single',
                    'at_least'          => 0,
                    'sort'              => 1
                ],
                [
                    'form_section_id'   => 1,
                    'name'              => 'parent_supplier_details',
                    'display_name'      => 'Parent Supplier Details',
                    'type'              => 'single',
                    'created_at'        => $now,
                    'at_least'          => 0,
                    'sort'              => 2
                ],  
               
                [
                    'form_section_id'   => 1,
                    'name'              => 'attachments',
                    'display_name'      => 'Attachments',
                    'type'              => 'multiple',
                    'created_at'        => $now,
                    'at_least'          => 1,
                    'sort'              => 3
                ],
                [
                    'form_section_id'   => 1,
                    'name'              => 'partners',
                    'display_name'      => 'Partners',
                    'type'              => 'multiple',
                    'created_at'        => $now,
                    'at_least'          => 0,
                    'sort'              => 4
                ],
                [
                    'form_section_id'   => 1,
                    'name'              => 'affiliations',
                    'display_name'      => 'Affiliations',
                    'type'              => 'multiple',
                    'created_at'        => $now,
                    'at_least'          => 0,
                    'sort'              => 5
                ],
                [
                    'form_section_id'   => 1,
                    'name'              => 'supplier_certification',
                    'display_name'      => 'Supplier Certification',
                    'type'              => 'multiple',
                    'created_at'        => $now,
                    'at_least'          => 0,
                    'sort'              => 6
                ],

                // section 2
                [
                    'form_section_id'   => 2,
                    'name'              => 'basic_cooperate_details',
                    'display_name'      => 'Basic Cooperate Details',
                    'created_at'        => $now,
                    'type'              => 'single',
                    'at_least'          => 0,
                    'sort'              => 7
                ],
                [
                    'form_section_id'   => 2,
                    'name'              => 'shareholders',
                    'display_name'      => 'Shareholders',
                    'type'              => 'multiple',
                    'created_at'        => $now,
                    'at_least'          => 0,
                    'sort'              => 8
                ],
                [
                    'form_section_id'   => 2,
                    'name'              => 'customer_details',
                    'display_name'      => 'Customer Details',
                    'type'              => 'multiple',
                    'created_at'        => $now,
                    'at_least'          => 1,
                    'sort'              => 9
                ],

                // section 3
                [
                    'form_section_id'   => 3,
                    'name'              => 'basic_finance_details',
                    'display_name'      => 'Basic Finance Details',
                    'created_at'        => $now,
                    'type'              => 'single',
                    'at_least'          => 0,
                    'sort'              => 10
                ],
                [
                    'form_section_id'   => 3,
                    'name'              => 'tax_details',
                    'display_name'      => 'Tax Details',
                    'type'              => 'single',
                    'created_at'        => $now,
                    'at_least'          => 0,
                    'sort'              => 11
                ],
                [
                    'form_section_id'   => 3,
                    'name'              => 'bank_details',
                    'display_name'      => 'Bank Details',
                    'type'              => 'multiple',
                    'created_at'        => $now,
                    'at_least'          => 1,
                    'sort'              => 12
                ],

                // section 4
                [
                    'form_section_id'   => 4,
                    'name'              => 'basic_product_and_services_details',
                    'display_name'      => 'Basic Product and Services Details',
                    'created_at'        => $now,
                    'type'              => 'single',
                    'at_least'          => 0,
                    'sort'              => 13
                ],

                // section 5
                [
                    'form_section_id'   => 5,
                    'name'              => 'supplier_address_details',
                    'display_name'      => 'Supplier Address',
                    'type'              => 'multiple',
                    'created_at'        => $now,
                    'at_least'          => 1,
                    'sort'              => 14
                ],

                // section 6
                [
                    'form_section_id'   => 6,
                    'name'              => 'contact_person_details',
                    'display_name'      => 'Contact Person\'s Details',
                    'created_at'        => $now,
                    'type'              => 'multiple',
                    'at_least'          => 1,
                    'sort'              => 15
                ],
            ]);

            // insert all form fields
            FormField::insert([
                // section 1 fields
                [
                    'name'          => 'category',
                    'display_name'  => 'Category',
                    'type'          => 'select',
                    'placeholder'   => '-- select category --',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 1,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'sub_category',
                    'display_name'  => 'Sub Category',
                    'type'          => 'multi_select',
                    'placeholder'   => '-- select sub category --',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 2,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'type',
                    'display_name'  => 'Type',
                    'type'          => 'select',
                    'placeholder'   => '-- select type --',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 3,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'name_in_english',
                    'display_name'  => 'Name In English',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Name In English',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 4,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'name_in_secondary',
                    'display_name'  => 'Name in Secondary',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Name in Secondary',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 5,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'alternate_name_in_english',
                    'display_name'  => 'Alternate Name In English',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Alternate Name In English',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 6,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'alternate_name_in_secondary',
                    'display_name'  => 'Alternate Name In Secondary',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Alternate Name In Secondary',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 7,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'company_registration_number',
                    'display_name'  => 'Company Registration Number',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Company Registration Number',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 8,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'tax_organization_type',
                    'display_name'  => 'Tax Organization Type',
                    'type'          => 'select',
                    'placeholder'   => '-- select tax type --',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 9,
                    'created_at'    => $now
                ],


                [
                    'name'          => 'parent_supplier',
                    'display_name'  => 'Parent Supplier',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Parent Supplier',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 10,
                    'created_at'    => $now
                ],

                // attachments
                [
                    'name'          => 'description',
                    'display_name'  => 'Description',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Description',
                    'class'         => 'col-md-12 mb-4',
                    'sort'          => 11,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'upload_file',
                    'display_name'  => 'File Name',
                    'type'          => 'file_uploader',
                    'placeholder'   => null,
                    'class'         => 'col-md-12 mb-4',
                    'sort'          => 12,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'attachment_type',
                    'display_name'  => 'Type',
                    'type'          => 'select',
                    'placeholder'   => '-- select attachment type --',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 13,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'expiry_date',
                    'display_name'  => 'Expiry Date',
                    'type'          => 'date',
                    'placeholder'   => 'YYYY-MM-DD',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 14,
                    'created_at'    => $now
                ],

                [
                    'name'          => 'partners',
                    'display_name'  => 'Partners',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Partners',
                    'class'         => 'col-md-12 mb-4',
                    'sort'          => 15,
                    'created_at'    => $now
                ],

                [
                    'name'          => 'affiliations',
                    'display_name'  => 'Affiliations',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Affiliations',
                    'class'         => 'col-md-12 mb-4',
                    'sort'          => 16,
                    'created_at'    => $now
                ],


                [
                    'name'          => 'certification_name',
                    'display_name'  => 'Certification Name',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Certification Name',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 17,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'expiry_date',
                    'display_name'  => 'Expiry Date',
                    'type'          => 'date',
                    'placeholder'   => 'YYYY-MM-DD',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 18,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'upload_fIle',
                    'display_name'  => 'File Name',
                    'type'          => 'file_uploader',
                    'placeholder'   => null,
                    'class'         => 'col-md-12 mb-4',
                    'sort'          => 19,
                    'created_at'    => $now
                ],


                // section 2 fields
                [
                    'name'          => 'year_established',
                    'display_name'  => 'Year Established',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Year Established',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 20,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'supplier_website',
                    'display_name'  => 'Supplier Website',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Supplier Website',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 21,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'mission_statement_in_english',
                    'display_name'  => 'Mission Statement In English',
                    'type'          => 'textarea',
                    'placeholder'   => 'Enter Mission Statement In English',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 22,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'mission_statement_in_secondary',
                    'display_name'  => 'Mission Statement In Secondary',
                    'type'          => 'textarea',
                    'placeholder'   => 'Enter Mission Statement In Secondary',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 23,
                    'created_at'    => $now
                ],


                [
                    'name'          => 'shareholder_name',
                    'display_name'  => 'Name',
                    'type'          => 'text',
                    'placeholder'   => 'Enter shareholder name',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 24,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'shareholder_percentage',
                    'display_name'  => 'Percentage',
                    'type'          => 'text',
                    'placeholder'   => 'Enter shareholder percentage',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 25,
                    'created_at'    => $now
                ],

                [
                    'name'          => 'customer_name',
                    'display_name'  => 'Name',
                    'type'          => 'text',
                    'placeholder'   => 'Enter customer name',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 26,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'customer_email',
                    'display_name'  => 'Email',
                    'type'          => 'text',
                    'placeholder'   => 'Enter customer email',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 27,
                    'created_at'    => $now
                ],

                // section 3 fields
                [
                    'name'          => 'preferred_functional_currency',
                    'display_name'  => 'Preferred Functional Currency',
                    'type'          => 'select',
                    'placeholder'   => '-- select currency --',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 28,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'payment_method',
                    'display_name'  => 'Payment Method',
                    'type'          => 'multi_select',
                    'placeholder'   => '-- select currency --',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 29,
                    'created_at'    => $now
                ],


                [
                    'name'          => 'vat',
                    'display_name'  => 'VAT',
                    'type'          => 'checkbox',
                    'placeholder'   => null,
                    'class'         => 'col-md-4',
                    'sort'          => 30,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'vat_input',
                    'display_name'  => 'Percentage',
                    'type'          => 'text',
                    'placeholder'   => 'Enter percentage',
                    'class'         => 'col-md-4 mb-4',
                    'sort'          => 31,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'svat',
                    'display_name'  => 'SVAT',
                    'type'          => 'checkbox',
                    'placeholder'   => null,
                    'class'         => 'col-md-4',
                    'sort'          => 32,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'svat_input',
                    'display_name'  => 'Percentage',
                    'type'          => 'text',
                    'placeholder'   => 'Enter percentage',
                    'class'         => 'col-md-4 mb-4',
                    'sort'          => 33,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'wht',
                    'display_name'  => 'WHT',
                    'type'          => 'checkbox',
                    'placeholder'   => null,
                    'class'         => 'col-md-4',
                    'sort'          => 34,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'wht_input',
                    'display_name'  => 'Percentage',
                    'type'          => 'text',
                    'placeholder'   => 'Enter percentage',
                    'class'         => 'col-md-4 mb-4',
                    'sort'          => 35,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'tax_registration_number',
                    'display_name'  => 'Tax Registration Number',
                    'type'          => 'text',
                    'placeholder'   => 'Enter tax registration number',
                    'class'         => 'col-md-12 mb-4',
                    'sort'          => 36,
                    'created_at'    => $now
                ],


                [
                    'name'          => 'bank_name',
                    'display_name'  => 'Bank Name',
                    'type'          => 'text',
                    'placeholder'   => 'Enter bank name',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 37,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'branch_name',
                    'display_name'  => 'Branch Name',
                    'type'          => 'text',
                    'placeholder'   => 'Enter branch name',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 38,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'account_name',
                    'display_name'  => 'Account Name',
                    'type'          => 'text',
                    'placeholder'   => 'Enter account name',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 39,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'account_number',
                    'display_name'  => 'Account Number',
                    'type'          => 'text',
                    'placeholder'   => 'Enter account number',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 40,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'bank_is_primary',
                    'display_name'  => 'Is Primary',
                    'type'          => 'checkbox',
                    'placeholder'   => null,
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 41,
                    'created_at'    => $now
                ],

                // section 4 fields
                [
                    'name'          => 'product_service_description',
                    'display_name'  => 'Product/Service Description',
                    'type'          => 'textarea',
                    'placeholder'   => 'Enter Product Service Description',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 42,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'lead_time',
                    'display_name'  => 'Lead Time (In Days)',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Lead Time (In Days)',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 43,
                    'created_at'    => $now
                ],

                // section 5 fields
                [
                    'name'          => 'address_name_in_english',
                    'display_name'  => 'Address Name In English',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Address Name In English',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 44,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'address_name_in_secondary',
                    'display_name'  => 'Address Name In Secondary',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Address Name In Secondary',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 45,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'country',
                    'display_name'  => 'Country',
                    'type'          => 'select',
                    'placeholder'   => '-- select country --',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 46,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'address',
                    'display_name'  => 'Address',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Address',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 47,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'city',
                    'display_name'  => 'City',
                    'type'          => 'text',
                    'placeholder'   => 'Enter City',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 48,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'postal_code',
                    'display_name'  => 'Postal Code',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Postal Code',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 49,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'phone_number',
                    'display_name'  => 'Phone Number',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Phone Number',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 50,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'fax',
                    'display_name'  => 'Fax',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Fax',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 51,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'email',
                    'display_name'  => 'Email',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Email',
                    'class'         => 'col-md-12 mb-4',
                    'sort'          => 52,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'address_is_primary',
                    'display_name'  => 'Is Primary',
                    'type'          => 'checkbox',
                    'placeholder'   => null,
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 53,
                    'created_at'    => $now
                ],

                // section 6 fields
                [
                    'name'          => 'contact_person_salutation',
                    'display_name'  => 'Contact Person Salutation',
                    'type'          => 'select',
                    'placeholder'   => '-- select salutation --',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 54,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'job_title',
                    'display_name'  => 'Job Title',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Job Title',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 55,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'contact_person_first_name',
                    'display_name'  => 'First Name',
                    'type'          => 'text',
                    'placeholder'   => 'Enter First Name',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 56,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'middle_name',
                    'display_name'  => 'Middle Name',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Address',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 57,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'last_name',
                    'display_name'  => 'Last Name',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Last Name',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 58,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'contact_person_phone',
                    'display_name'  => 'Phone',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Phone',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 59,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'contact_person_mobile',
                    'display_name'  => 'Mobile',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Mobile',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 60,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'contact_person_email',
                    'display_name'  => 'Email',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Email',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 61,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'contact_person_fax',
                    'display_name'  => 'Fax',
                    'type'          => 'text',
                    'placeholder'   => 'Enter Fax',
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 62,
                    'created_at'    => $now
                ],
                [
                    'name'          => 'contact_person_is_primary',
                    'display_name'  => 'Is Primary',
                    'type'          => 'checkbox',
                    'placeholder'   => null,
                    'class'         => 'col-md-6 mb-4',
                    'sort'          => 63,
                    'created_at'    => $now
                ],

            ]);

            // insert form validators
            FormValidator::insert([
                // 1
                [
                    'type'          => REQUIRED,
                    'created_at'    => $now
                ],
                // 2
                [
                    'type'          => EMAIL,
                    'created_at'    => $now
                ],
                // 3
                [
                    'type'          => MIN_LENGTH,
                    'created_at'    => $now
                ],
                // 4
                [
                    'type'          => MAX_LENGTH,
                    'created_at'    => $now
                ],

                // 5
                [
                    'type'          => PATTERN,
                    'created_at'    => $now
                ],
                // 6
                [
                    'type'          => NO_SPECIAL_CHARACTER,
                    'created_at'    => $now
                ],
                // 7
                [
                    'type'          => YEAR,
                    'created_at'    => $now
                ],
                // 8
                [
                    'type'          => URL,
                    'created_at'    => $now
                ],
                // 9
                [
                    'type'          => NUMBER,
                    'created_at'    => $now
                ],
                // 10
                [
                    'type'          => LETTER_NUMBER_DASH,
                    'created_at'    => $now
                ],
                // 11
                [
                    'type'          => UNSIGNED_INTEGER,
                    'created_at'    => $now
                ],
                //12
                [
                    'type'          => LETTERS_ONLY,
                    'created_at'    => $now
                ],

                //13 
                [
                    'type'          => SPECIAL_CHARACTER_COMMAS,
                    'created_at'    => $now   
                ],
                //14
                [
                    'type'          => PHONE_NUMBER,
                    'created_at'    => $now      
                ]
            ]);

            // insert field validators
            FormFieldValidator::insert([
                [
                    'form_field_id'     => 1,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 2,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 3,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 4,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 4,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 4,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 4,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 5,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 5,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 5,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 6,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 6,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 6,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 6,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 7,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 7,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 7,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 8,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 8,
                    'form_validator_id' => 4,
                    'value'             => 20,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 8,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],


                [
                    'form_field_id'     => 9,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 10,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 10,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 10,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 11,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 11,
                    'form_validator_id' => 4,
                    'value'             => 50,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 11,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 12,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 13,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
  
                [
                    'form_field_id'     => 15,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 15,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 15,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 16,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 16,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 16,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 17,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 17,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 18,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 19,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                // section 2
                [
                    'form_field_id'     => 20,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 20,
                    'form_validator_id' => 7,
                    'value'             => YEAR,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 21,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 21,
                    'form_validator_id' => 8,
                    'value'             => URL,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 24,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 24,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 24,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 24,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                
                [
                    'form_field_id'     => 25,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 25,
                    'form_validator_id' => 9,
                    'value'             => NUMBER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 26,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 26,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 26,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 26,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 27,
                    'form_validator_id' => 1,
                    'value'             => true,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 27,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 27,
                    'form_validator_id' => 2,
                    'value'             => EMAIL,
                    'created_at'        => $now
                ],

                // section 3
                [
                    'form_field_id'     => 28,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 29,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 31,
                    'form_validator_id' => 9,
                    'value'             => NUMBER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 31,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 33,
                    'form_validator_id' => 9,
                    'value'             => NUMBER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 33,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 35,
                    'form_validator_id' => 9,
                    'value'             => NUMBER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 35,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 36,
                    'form_validator_id' => 10,
                    'value'             => LETTER_NUMBER_DASH,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 36,
                    'form_validator_id' => 4,
                    'value'             => 50,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 37,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ], 
                [
                    'form_field_id'     => 37,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 37,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 38,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 38,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 38,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 38,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 39,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 39,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 39,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 39,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 40,
                    'form_validator_id' => 11,
                    'value'             => UNSIGNED_INTEGER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 40,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],  

                // section 4
                [
                    'form_field_id'     => 42,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],  
                [
                    'form_field_id'     => 42,
                    'form_validator_id' => 13,
                    'value'             => SPECIAL_CHARACTER_COMMAS,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 43,
                    'form_validator_id' => 11,
                    'value'             => UNSIGNED_INTEGER,
                    'created_at'        => $now
                ],

                // section 5
                [
                    'form_field_id'     => 44,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 44,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 44,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 45,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 45,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 46,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 47,
                    'form_validator_id' => 4,
                    'value'             => 500,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 47,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ], 
                [
                    'form_field_id'     => 47,
                    'form_validator_id' => 13,
                    'value'             => SPECIAL_CHARACTER_COMMAS,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 48,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 48,
                    'form_validator_id' => 4,
                    'value'             => 50,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 48,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 48,
                    'form_validator_id' => 12,
                    'value'             => LETTERS_ONLY,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 49,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 49,
                    'form_validator_id' => 11,
                    'value'             => UNSIGNED_INTEGER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 49,
                    'form_validator_id' => 4,
                    'value'             => 10,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 49,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 50,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 50,
                    'form_validator_id' => 3,
                    'value'             => 10,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 50,
                    'form_validator_id' => 4,
                    'value'             => 15,
                    'created_at'        => $now
                ], 
                [
                    'form_field_id'     => 50,
                    'form_validator_id' => 14,
                    'value'             => PHONE_NUMBER,
                    'created_at'        => $now
                ], 
                
                [
                    'form_field_id'     => 51,
                    'form_validator_id' => 3,
                    'value'             => 10,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 51,
                    'form_validator_id' => 4,
                    'value'             => 15,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 51,
                    'form_validator_id' => 11,
                    'value'             => UNSIGNED_INTEGER,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 52,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 52,
                    'form_validator_id' => 2,
                    'value'             => EMAIL,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 54,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 55,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 55,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 55,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 55,
                    'form_validator_id' => 12,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],   

                [
                    'form_field_id'     => 56,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 56,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 56,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],  
                [
                    'form_field_id'     => 56,
                    'form_validator_id' => 12,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],   

                [
                    'form_field_id'     => 57,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 57,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 57,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ], 
                [
                    'form_field_id'     => 57,
                    'form_validator_id' => 12,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],   

                [
                    'form_field_id'     => 58,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 58,
                    'form_validator_id' => 6,
                    'value'             => NO_SPECIAL_CHARACTER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 58,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],  
                [
                    'form_field_id'     => 58,
                    'form_validator_id' => 12,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],   

                [
                    'form_field_id'     => 59,
                    'form_validator_id' => 4,
                    'value'             => 15,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 59,
                    'form_validator_id' => 14,
                    'value'             => PHONE_NUMBER,
                    'created_at'        => $now
                ], 
                [
                    'form_field_id'     => 59,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],  
                [
                    'form_field_id'     => 59,
                    'form_validator_id' => 3,
                    'value'             => 10,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 60,
                    'form_validator_id' => 3,
                    'value'             => 10,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 60,
                    'form_validator_id' => 4,
                    'value'             => 15,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 60,
                    'form_validator_id' => 14,
                    'value'             => PHONE_NUMBER,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 60,
                    'form_validator_id' => 1,
                    'value'             => REQUIRED,
                    'created_at'        => $now
                ],  

                [
                    'form_field_id'     => 61,
                    'form_validator_id' => 2,
                    'value'             => EMAIL,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 61,
                    'form_validator_id' => 4,
                    'value'             => 100,
                    'created_at'        => $now
                ],

                [
                    'form_field_id'     => 62,
                    'form_validator_id' => 3,
                    'value'             => 10,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 62,
                    'form_validator_id' => 4,
                    'value'             => 15,
                    'created_at'        => $now
                ],
                [
                    'form_field_id'     => 62,
                    'form_validator_id' => 11,
                    'value'             => UNSIGNED_INTEGER,
                    'created_at'        => $now
                ],
            ]);

            // insert form data
            FormData::insert([
                // category
                [
                    'value'         => 'it',
                    'text'          => 'IT',
                    'created_at'    => $now
                ], // 1
                [
                    'value'         => 'software',
                    'text'          => 'Software',
                    'created_at'    => $now
                ], // 2
                [
                    'value'         => 'service',
                    'text'          => 'Service',
                    'created_at'    => $now
                ], // 3

                // sub category
                [
                    'value'         => 'office_materials',
                    'text'          => 'Office Materials',
                    'created_at'    => $now
                ], // 4
                [
                    'value'         => 'vehicle_parts',
                    'text'          => 'Vehicle Parts',
                    'created_at'    => $now
                ], // 5
                [
                    'value'         => 'furniture',
                    'text'          => 'Furniture',
                    'created_at'    => $now
                ], // 6


//                [
//                    'value'         => 'composite_supply',
//                    'text'          => 'Composite supply',
//                    'created_at'    => $now
//                ],
//                [
//                    'value'         => 'short_term_supply',
//                    'text'          => 'Short term supply',
//                    'created_at'    => $now
//                ],
//                [
//                    'value'         => 'long_term_supply',
//                    'text'          => 'long term supply',
//                    'created_at'    => $now
//                ],
                // supplier types
                // supplier type
                [
                    'value'         => 'supplier',
                    'text'          => 'Supplier',
                    'created_at'    => $now
                ], // 7
                [
                    'value'         => 'contractor',
                    'text'          => 'Contractor',
                    'created_at'    => $now
                ], // 8
                [
                    'value'         => 'sub_contractor',
                    'text'          => 'Sub Contractor',
                    'created_at'    => $now
                ], // 9
                [
                    'value'         => 'construction',
                    'text'          => 'Construction',
                    'created_at'    => $now
                ], // 10
                [
                    'value'         => 'attorney',
                    'text'          => 'Attorney',
                    'created_at'    => $now
                ], // 11
                [
                    'value'         => 'carrier',
                    'text'          => 'Carrier',
                    'created_at'    => $now
                ], // 12
                [
                    'value'         => 'insurance_company',
                    'text'          => 'Insurance Company',
                    'created_at'    => $now
                ], // 13
                [
                    'value'         => 'manufacturing',
                    'text'          => 'Manufacturing',
                    'created_at'    => $now
                ], // 14
                [
                    'value'         => 'service',
                    'text'          => 'Service',
                    'created_at'    => $now
                ], // 15

                // tax organization types
                [
                    'value'         => 'corporation',
                    'text'          => 'Corporation',
                    'created_at'    => $now
                ], // 16
                [
                    'value'         => 'individual',
                    'text'          => 'Individual',
                    'created_at'    => $now
                ], // 17
                [
                    'value'         => 'partnership',
                    'text'          => 'Partnership',
                    'created_at'    => $now
                ], // 18
                [
                    'value'         => 'government_agency',
                    'text'          => 'Government Agency',
                    'created_at'    => $now
                ], // 19
                [
                    'value'         => 'super_lcc',
                    'text'          => 'Super LCC',
                    'created_at'    => $now
                ], // 20
                [
                    'value'         => 'riyadah',
                    'text'          => 'Riyadah',
                    'created_at'    => $now
                ], // 21
                [
                    'value'         => 'foreign_corporation',
                    'text'          => 'Foreign Corporation',
                    'created_at'    => $now
                ], // 22
                [
                    'value'         => 'foreign_individual',
                    'text'          => 'Foreign Individual',
                    'created_at'    => $now
                ], // 23
                [
                    'value'         => 'foreign_partnership',
                    'text'          => 'Foreign Partnership',
                    'created_at'    => $now
                ], // 24
                [
                    'value'         => 'foreign_government_agency',
                    'text'          => 'Foreign Government Agency',
                    'created_at'    => $now
                ], // 25

                // attachment types
                [
                    'value'         => 'certificate_of_incorporation',
                    'text'          => 'Certificate of Incorporation',
                    'created_at'    => $now
                ], // 26
                [
                    'value'         => 'trade_license',
                    'text'          => 'Trade License',
                    'created_at'    => $now
                ], // 27
                [
                    'value'         => 'other',
                    'text'          => 'Other',
                    'created_at'    => $now
                ], // 28

                // currencies
                [
                    'value'         => 'usd',
                    'text'          => 'USD',
                    'created_at'    => $now
                ], // 29
                [
                    'value'         => 'gbp',
                    'text'          => 'GBP',
                    'created_at'    => $now
                ], // 30
                [
                    'value'         => 'lkr',
                    'text'          => 'LKR',
                    'created_at'    => $now
                ], // 31

                // payment types
                [
                    'value'         => 'cheque',
                    'text'          => 'Cheque',
                    'created_at'    => $now
                ], // 32
                [
                    'value'         => 'electronic',
                    'text'          => 'Electronic',
                    'created_at'    => $now
                ], // 33
                [
                    'value'         => 'ap_ar_netting',
                    'text'          => 'AP/AR Netting',
                    'created_at'    => $now
                ], // 34

                // countries
                [
                    'value'         => 'oman',
                    'text'          => 'Oman',
                    'created_at'    => $now
                ], // 35
                [
                    'value'         => 'sri_lanka',
                    'text'          => 'Sri Lanka',
                    'created_at'    => $now
                ], // 36

                // titles
                [
                    'value'         => 'mr',
                    'text'          => 'Mr',
                    'created_at'    => $now
                ], // 37
                [
                    'value'         => 'mrs',
                    'text'          => 'Mrs',
                    'created_at'    => $now
                ], // 38
                [
                    'value'         => 'dr',
                    'text'          => 'Dr',
                    'created_at'    => $now
                ], // 39
            ]);

            FormFieldData::insert([
                // category
                [
                    'form_field_id' => 1,
                    'form_data_id'  => 1,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 1,
                    'form_data_id'  => 2,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 1,
                    'form_data_id'  => 3,
                    'created_at'    => $now
                ],

                // sub category
                [
                    'form_field_id' => 2,
                    'form_data_id'  => 4,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 2,
                    'form_data_id'  => 5,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 2,
                    'form_data_id'  => 6,
                    'created_at'    => $now
                ],

                // supplier types
                [
                    'form_field_id' => 3,
                    'form_data_id'  => 7,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 3,
                    'form_data_id'  => 8,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 3,
                    'form_data_id'  => 9,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 3,
                    'form_data_id'  => 10,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 3,
                    'form_data_id'  => 11,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 3,
                    'form_data_id'  => 12,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 3,
                    'form_data_id'  => 13,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 3,
                    'form_data_id'  => 14,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 3,
                    'form_data_id'  => 15,
                    'created_at'    => $now
                ],

                // tax types
                [
                    'form_field_id' => 9,
                    'form_data_id'  => 16,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 9,
                    'form_data_id'  => 17,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 9,
                    'form_data_id'  => 18,
                    'created_at'    => $now
                ],[
                    'form_field_id' => 9,
                    'form_data_id'  => 19,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 9,
                    'form_data_id'  => 20,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 9,
                    'form_data_id'  => 21,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 9,
                    'form_data_id'  => 22,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 9,
                    'form_data_id'  => 23,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 9,
                    'form_data_id'  => 24,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 9,
                    'form_data_id'  => 25,
                    'created_at'    => $now
                ],


                // attachment types
                [
                    'form_field_id' => 13,
                    'form_data_id'  => 26,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 13,
                    'form_data_id'  => 27,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 13,
                    'form_data_id'  => 28,
                    'created_at'    => $now
                ],

                // currency
                [
                    'form_field_id' => 28,
                    'form_data_id'  => 29,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 28,
                    'form_data_id'  => 30,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 28,
                    'form_data_id'  => 31,
                    'created_at'    => $now
                ],

                // payment types
                [
                    'form_field_id' => 29,
                    'form_data_id'  => 32,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 29,
                    'form_data_id'  => 33,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 29,
                    'form_data_id'  => 34,
                    'created_at'    => $now
                ],

                // country
                [
                    'form_field_id' => 46,
                    'form_data_id'  => 35,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 46,
                    'form_data_id'  => 36,
                    'created_at'    => $now
                ],

                // titles
                [
                    'form_field_id' => 54,
                    'form_data_id'  => 37,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 54,
                    'form_data_id'  => 38,
                    'created_at'    => $now
                ],
                [
                    'form_field_id' => 54,
                    'form_data_id'  => 39,
                    'created_at'    => $now
                ],
            ]);

            // config section and group
            FormGroupDetail::insert([
                // section 1
                [
                    'form_group_id' => 1,
                    'form_field_id' => 1,
                    'sort'          => 1,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 1,
                    'form_field_id' => 2,
                    'sort'          => 2,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 1,
                    'form_field_id' => 3,
                    'sort'          => 3,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 1,
                    'form_field_id' => 4,
                    'sort'          => 4,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 1,
                    'form_field_id' => 5,
                    'sort'          => 5,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 1,
                    'form_field_id' => 6,
                    'sort'          => 6,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 1,
                    'form_field_id' => 7,
                    'sort'          => 7,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 1,
                    'form_field_id' => 8,
                    'sort'          => 8,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 1,
                    'form_field_id' => 9,
                    'sort'          => 9,
                    'created_at'    => $now
                ],  
                [
                    'form_group_id' => 1,
                    'form_field_id' => 10,
                    'sort'          => 10,
                    'created_at'    => $now
                ],


                [
                    'form_group_id' => 3,
                    'form_field_id' => 11,
                    'sort'          => 11,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 3,
                    'form_field_id' => 12,
                    'sort'          => 12,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 3,
                    'form_field_id' => 13,
                    'sort'          => 13,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 3,
                    'form_field_id' => 14,
                    'sort'          => 14,
                    'created_at'    => $now
                ],


                [
                    'form_group_id' => 4,
                    'form_field_id' => 15,
                    'sort'          => 15,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 5,
                    'form_field_id' => 16,
                    'sort'          => 16,
                    'created_at'    => $now
                ],


                [
                    'form_group_id' => 6,
                    'form_field_id' => 17,
                    'sort'          => 17,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 6,
                    'form_field_id' => 18,
                    'sort'          => 18,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 6,
                    'form_field_id' => 19,
                    'sort'          => 19,
                    'created_at'    => $now
                ],

                // section 2
                [
                    'form_group_id' => 7,
                    'form_field_id' => 20,
                    'sort'          => 20,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 7,
                    'form_field_id' => 21,
                    'sort'          => 21,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 7,
                    'form_field_id' => 22,
                    'sort'          => 22,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 7,
                    'form_field_id' => 23,
                    'sort'          => 23,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 8,
                    'form_field_id' => 24,
                    'sort'          => 24,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 8,
                    'form_field_id' => 25,
                    'sort'          => 25,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 9,
                    'form_field_id' => 26,
                    'sort'          => 26,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 9,
                    'form_field_id' => 27,
                    'sort'          => 27,
                    'created_at'    => $now
                ],


                // section 3
                [
                    'form_group_id' => 10,
                    'form_field_id' => 28,
                    'sort'          => 28,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 10,
                    'form_field_id' => 29,
                    'sort'          => 29,
                    'created_at'    => $now
                ],


                [
                    'form_group_id' => 11,
                    'form_field_id' => 30,
                    'sort'          => 30,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 11,
                    'form_field_id' => 32,
                    'sort'          => 31,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 11,
                    'form_field_id' => 34,
                    'sort'          => 32,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 11,
                    'form_field_id' => 31,
                    'sort'          => 33,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 11,
                    'form_field_id' => 33,
                    'sort'          => 34,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 11,
                    'form_field_id' => 35,
                    'sort'          => 35,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 11,
                    'form_field_id' => 36,
                    'sort'          => 36,
                    'created_at'    => $now
                ],


                [
                    'form_group_id' => 12,
                    'form_field_id' => 37,
                    'sort'          => 37,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 12,
                    'form_field_id' => 38,
                    'sort'          => 38,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 12,
                    'form_field_id' => 39,
                    'sort'          => 39,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 12,
                    'form_field_id' => 40,
                    'sort'          => 40,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 12,
                    'form_field_id' => 41,
                    'sort'          => 41,
                    'created_at'    => $now
                ],


                // section 4
                [
                    'form_group_id' => 13,
                    'form_field_id' => 42,
                    'sort'          => 42,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 13,
                    'form_field_id' => 43,
                    'sort'          => 43,
                    'created_at'    => $now
                ],

                // section 5
                [
                    'form_group_id' => 14,
                    'form_field_id' => 44,
                    'sort'          => 44,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 14,
                    'form_field_id' => 45,
                    'sort'          => 45,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 14,
                    'form_field_id' => 46,
                    'sort'          => 46,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 14,
                    'form_field_id' => 47,
                    'sort'          => 47,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 14,
                    'form_field_id' => 48,
                    'sort'          => 48,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 14,
                    'form_field_id' => 49,
                    'sort'          => 49,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 14,
                    'form_field_id' => 50,
                    'sort'          => 50,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 14,
                    'form_field_id' => 51,
                    'sort'          => 51,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 14,
                    'form_field_id' => 52,
                    'sort'          => 52,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 14,
                    'form_field_id' => 53,
                    'sort'          => 53,
                    'created_at'    => $now
                ],

                // section 6
                [
                    'form_group_id' => 15,
                    'form_field_id' => 54,
                    'sort'          => 54,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 15,
                    'form_field_id' => 55,
                    'sort'          => 55,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 15,
                    'form_field_id' => 56,
                    'sort'          => 56,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 15,
                    'form_field_id' => 57,
                    'sort'          => 57,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 15,
                    'form_field_id' => 58,
                    'sort'          => 58,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 15,
                    'form_field_id' => 59,
                    'sort'          => 59,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 15,
                    'form_field_id' => 60,
                    'sort'          => 60,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 15,
                    'form_field_id' => 61,
                    'sort'          => 61,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 15,
                    'form_field_id' => 62,
                    'sort'          => 62,
                    'created_at'    => $now
                ],
                [
                    'form_group_id' => 15,
                    'form_field_id' => 63,
                    'sort'          => 63,
                    'created_at'    => $now
                ]
            ]);
        });  

        FormField::whereIn('id',[1,2]) 
        ->update([
            'data_from_tenant' => 1
        ]); 
    }
}
