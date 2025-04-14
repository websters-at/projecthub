<?php

return [
    'call_note' => [
        'resource' => [
            'group' => 'Call Notes',
            'name' => 'Call Note',
            'name_plural' => 'Call Notes',
        ],
        'form' => [
            'section_general' => 'General',
            'field_name' => 'Title of the call note',
            'field_description' => 'Description',
            'field_call_id' => 'Call',
        ],
        'table' => [
            'name' => 'Title',
            'description' => 'Description',
            'call' => 'Call',
            'filter_call' => 'Filter by Call',
        ],
    ],
    'call' => [
        'resource' => [
            'group' => 'Calls',
            'name' => 'Call',
            'name_plural' => 'Calls',
        ],
        'form' => [
            'section_general' => 'General',
            'section_contract' => 'Contract',
            'field_name' => 'Name',
            'field_description' => 'Description',
            'field_is_done' => 'Done',
            'field_on_date' => 'Date',
            'field_contract' => 'Contract',
        ],
        'table' => [
            'field_on_date' => 'Date',
            'field_customer' => 'Customer',
            'field_contract' => 'Contract',
            'field_user' => 'User',
            'field_is_done' => 'Done',
            'filter_is_done' => 'Completion Status',
            'filter_on_date' => 'Date Range',
            'filter_from' => 'From',
            'filter_until' => 'To',
            'filter_contract' => 'Contract',
        ],
    ],
    'contract_note' => [
        'resource' => [
            'group' => 'Contracts', // Navigation group
            'name' => 'Contract Note', // Singular resource name
            'name_plural' => 'Contract Notes', // Plural resource name
        ],
        'form' => [
            'section_general' => 'General Information',
            'section_contract' => 'Contract',
            'field_name' => 'Title of the note',
            'field_description' => 'Description',
            'field_date' => 'Date',
            'field_attachments' => 'Attachments',
            'field_contract' => "Contract",

        ],
        'table' => [
            'name' => 'Title',
            'date' => 'Date',
            'contract' => 'Contract',
            'customer' => 'Customer',
        ],
    ],
    'contract' => [
        'resource' => [
            'group' => 'Contracts',
            'name' => 'Contract',
            'name_plural' => 'Contracts',
        ],
        'form' => [
            'section_general' => 'General',
            'section_location' => 'Location',
            'section_customer' => 'Customer',
            'section_employees' => 'Employees',
            'section_attachments' => 'Attachments',
            'field_name' => 'Name',
            'field_description' => 'Description',
            'field_priority' => 'Priority',
            'field_due_to' => 'Due Date',
            'field_is_finished' => 'Finished',
            'field_customer' => 'Customer',
            'field_users' => 'Employees',
            'field_country' => 'Country',
            'field_state' => 'State',
            'field_city' => 'City',
            'field_zip_code' => 'ZIP Code',
            'field_address' => 'Address',
            'field_attachments' => 'Attachments',
        ],
        'table' => [
            'name' => 'Name',
            'description' => 'Description',
            'priority' => 'Priority',
            'due_to' => 'Due Date',
            'is_finished' => 'Finished',
            'customer' => 'Customer',
            'filter_priority' => 'Priority',
            'filter_customer' => 'Customer',
            'filter_users' => 'Employees',
            'filter_due_to' => 'Due Date Filter',
            'filter_is_finished' => 'Finished Status',
            'filter_name' => 'Company Name of Contract',
        ],
    ],
    'customer' => [
        'resource' => [
            'group' => 'Contracts',
            'name' => 'Customer',
            'name_plural' => 'Customers',
        ],
        'form' => [
            'section_general' => 'General',
            'section_address' => 'Address',
            'field_full_name' => 'Full Name',
            'field_company_name' => 'Company Name',
            'field_email' => 'Email',
            'field_phone' => 'Phone',
            'field_tax_id' => 'Tax ID',
            'field_country' => 'Country',
            'field_state' => 'State',
            'field_city' => 'City',
            'field_zip_code' => 'ZIP Code',
            'field_address' => 'Address',
        ],
        'table' => [
            'company_name' => 'Company Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'city' => 'City',
            'filter_country' => 'Country',
            'filter_state' => 'State',
            'filter_city' => 'City',
        ],
    ],
    'login_credentials' => [
        'resource' => [
            'group' => 'Contracts',
            'name' => 'Login Credentials',
            'name_plural' => 'Login Credentials',
        ],
        'form' => [
            'section_general' => 'General',
            'field_name' => 'Name',
            'field_description' => 'Description',
            'field_email' => 'Email',
            'field_password' => 'Password',
            'field_attachments' => 'Attachments',
            'section_contracts' => 'Contracts', // Added section_contracts

        ],
        'table' => [
            'name' => 'Name',
            'contracts' => 'Contracts',
            'description' => 'Description',
        ],
        'filter' => [
            'email' => [
                'label' => 'Email Domain',
                'placeholder' => 'Enter domain (e.g., example.com)',
            ],
            'name' => [
                'label' => 'Name',
                'placeholder' => 'Search by name',
            ],
        ],
    ],
    'permission' => [
        'resource' => [
            'group' => 'Settings',
            'name' => 'Permission',
            'name_plural' => 'Permissions',
        ],
        'form' => [
            'section_general' => 'General',
            'field_name' => 'Name',
        ],
        'table' => [
            'name' => 'Name',
        ],
    ],
    'user' => [
        'resource' => [
            'group' => 'Settings',
            'name' => 'User',
            'name_plural' => 'Users',
        ],
        'form' => [
            'section_general' => 'General',
            'field_name' => 'Name',
            'field_email' => 'Email',
            'field_password' => 'Password',
            'field_roles' => 'Roles',
        ],
        'table' => [
            'name' => 'Name',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ],
    ],
    'role' => [
        'resource' => [
            'group' => 'Settings',
            'name' => 'Role',
            'name_plural' => 'Roles',
        ],
        'form' => [
            'section_general' => 'General',
            'field_name' => 'Name',
            'field_permissions' => 'Permissions',
        ],
        'table' => [
            'id' => 'ID',
            'name' => 'Name',
            'created_at' => 'Created At',
        ],
    ],
    'todo' => [
        'resource' => [
            'group' => 'Contracts',
            'name' => 'Todo',
            'name_plural' => 'Todos',
        ],
        'form' => [
            'section_general' => 'General',
            'field_name' => 'Name',
            'field_due_to' => 'Due Date',
            'field_description' => 'Description',
            'field_is_done' => 'Completed',
            'field_priority_label' => 'Priority',
            'field_priority' => [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
            ],
            'field_attachments' => 'Attachments',
            'section_contract' => 'Contract',
            'field_contract_classification' => 'Contract',
        ],
        'table' => [
            'name' => 'Name',
            'contract' => 'Contract',
            'customer' => 'Customer',
            'due_to' => 'Due Date',
            'priority' => 'Priority',
            'is_done' => 'Completed',
        ],
    ],
    'time' => [
        'resource' => [
            'group' => 'Contracts',  // Navigation group label
            'name' => 'Time Entry',        // Singular name for the resource
            'name_plural' => 'Time Entries',  // Plural name for the resource
        ],
        'stats' => [
            'total_time_raw' => 'Total Time (Raw)',
            'total_time_raw_description' => 'Sum of all recorded times without rounding.',
            'total_time_rounded' => 'Total Time (Rounded)',
            'total_time_rounded_description' => 'Sum of all recorded times with commercial rounding (≥30 min rounded up).',
            'special_time' => 'Special Time',
            'special_time_description' => 'Sum of all times marked as "special".',
            'entries_count' => 'Entries Count',
            'entries_count_description' => 'Total number of time entries.',
        ],
        'form' => [
            'general' => 'General',
            'field_date' => 'Date*',
            'field_description' => 'Description',

            'time' => 'Time',
            'field_total_hours_worked' => 'Total Hours Worked*',
            'field_total_minutes_worked' => 'Total Minutes Worked',

            'contract' => 'Contract',
            'field_contract_label' => 'Contract*',

            'specification' => 'Specification',
            'field_is_special' => 'Special Time',

            'create' => 'Create',
            'create_and_add_another' => 'Create & Add Another',
        ],
        'table' => [
            'date' => 'Date',
            'description' => 'Description',
            'total_hours' => 'Total Hours',
            'total_minutes' => 'Total Minutes',
            'is_special' => 'Special Time',
        ],
        'filters' => [
            'contract_classification_user' => 'User',
            'contract_classification_contract' => 'Contract',
            'date_from' => 'From',
            'date_until' => 'Until',
        ],
        'bulk_actions' => [
            'select_all' => 'Select All Entries for Bulk Action',
            'deselect_all' => 'Deselect All Entries for Bulk Action',
        ],
    ],
    'bill' => [
        'resource' => [
            'name' => 'Bill',
            'group' => 'Contracts',  // Navigation group label
            'name_plural' => 'Bills',
        ],
        'bill_stats' => [
            'total_amount' => 'Total Amount',
            'total_amount_description' => 'Sum of all billed amounts.',
            'total_unpaid_amount' => 'Total Unpaid Amount',
            'total_unpaid_amount_description' => 'Sum of amounts not yet paid.',
            'total_paid_amount' => 'Total Paid Amount',
            'total_paid_amount_description' => 'Sum of amounts already paid.',
        ],
        'form' => [
            'field_flat_rate_amount' => 'Flat rate amount',
            'field_is_flat_rate_helper' => "Is this a flat rate?",
            'field_is_flat_rate' => 'flat rate',
            'section_general' => 'General Information',
            'field_name' => 'Bill Name',
            'field_hourly_rate' => 'Hourly Rate',
            'field_description' => 'Description',
            'field_due_to' => 'Due Date',
            'field_created_on' => 'Created On',
            'field_is_payed' => 'Paid',

            'section_contract' => 'Contract',
            'field_contract' => 'Contract',

            'section_attachments' => 'Attachments',
            'field_attachments' => 'Attachments',
        ],
        'table' => [
            'user' => 'User',
            'contract' => 'Contract',
            'name' => 'Name',
            'description' => 'Description',
            'calculated_total' => 'Total (€)',
            'is_payed' => 'Paid',
        ],
        'filters' => [
            'payed' => 'Paid',
            'not_payed' => 'Not Paid',
            'flat_rate' => 'Flat Rate',

            'user' => [
                'label' => 'User',
                'placeholder' => 'Select user',
            ],

            'contract' => [
                'label' => 'Contract',
                'placeholder' => 'Select contract',
            ],
            'due_to' => 'Due Date',
            'due_from' => 'Due From',
            'due_until' => 'Due Until',
            'created_on' => 'Creation Date',
            'created_from' => 'Created From',
            'created_until' => 'Created Until',
        ],
    ],
    'credentials' => [
        'resource' => [
            'name' => 'Credential',
            'group' => 'General',
            'name_plural' => 'Credentials',
        ],
        'form' => [
            'section_general' => 'General Information',
            'field_name' => 'Credential Name',
            'field_email' => 'Email',
            'field_password' => 'Password',
            'field_description' => 'Description',
            'field_attachments' => 'Attachments',
        ],
        'table' => [
            'name' => 'Name',
            'email' => 'Email',
            'description' => 'Description',
            'created_at' => 'Created At',
        ],
        'filters' => [
            'email' => 'Email Domain',
            'name' => 'Name Contains',
        ],
    ],
    'general_todo' => [
        'resource' => [
            'name' => 'General Todo',
            'group' => 'General',
            'name_plural' => 'General Todo',
        ],
        'navigation' => [
            'group' => 'General',
            'label' => 'Todos',
        ],
        'form' => [
            'name' => 'Name',
            'due_to' => 'Due Date',
            'description' => 'Description',
            'is_done' => 'Completed',
            'priority' => 'Priority',
            'attachments' => 'Attachments',
            'general' => 'General Information',
            'priority_options' => [
                'low' => 'Low',
                'mid' => 'Medium',
                'high' => 'High',
            ],
        ],
        'table' => [
            'name' => 'Name',
            'user' => 'User',
            'due_to' => 'Due Date',
            'priority' => 'Priority',
            'is_done' => 'Completed',
        ],
        'filters' => [
            'priority' => 'Priority',
            'is_done' => 'Completion Status',
            'due_to' => 'Due Date Range',
            'due_from' => 'From',
            'due_until' => 'To',
            'user' => 'User',
        ],
    ],
    'user_stats' => [
        'unpaid_bills' => 'Unpaid Bills',
        'your_contracts' => 'Your Contracts',
        'unpaid_bills_description' => 'Still open',
        'your_contracts_description' => 'Total number of your contracts',
    ],
    'general_overview' => [
        'todays_calls' => 'Today\'s Calls',
        'unpaid_amount' => 'Unpaid Amount',
        'contracts_due_3_days' => 'Contracts Due in 3 Days',
        'todos_due_3_days' => 'Todos Due in 3 Days',
        'general_todos_due_3_days' => 'General Todos Due in 3 Days',
        'todays_calls_description' => 'Total calls created today',
        'unpaid_amount_description' => 'Total amount of unpaid bills',
        'contracts_due_3_days_description' => 'Contracts nearing due date',
        'todos_due_3_days_description' => 'Todos nearing due date',
        'general_todos_due_3_days_description' => 'General todos nearing due date',
    ],

];
