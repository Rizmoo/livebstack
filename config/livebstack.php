<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Layout Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the default layout and styling options for your CRUD components.
    |
    */
    'layout' => [
        'view' => 'livebstack::crud-layout',
        'theme' => 'bootstrap', // bootstrap, tailwind
        'modal_size' => 'lg',   // sm, lg, xl
    ],

    /*
    |--------------------------------------------------------------------------
    | Table Configuration
    |--------------------------------------------------------------------------
    |
    | Default settings for table display and functionality.
    |
    */
    'table' => [
        'per_page_options' => [5, 10, 25, 50, 100],
        'default_per_page' => 10,
        'loading_indicator' => true,
        'responsive' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Form Configuration
    |--------------------------------------------------------------------------
    |
    | Default settings for forms and fields.
    |
    */
    'form' => [
        'default_column_class' => 'col-md-12',
        'show_required_indicator' => true,
        'required_indicator' => '<span class="text-danger">*</span>',
        'show_help_text' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Configure file upload settings and paths.
    |
    */
    'uploads' => [
        'disk' => 'public',
        'directory' => 'uploads',
        'max_file_size' => 1024, // KB
        'allowed_file_types' => [
            'image/*',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Action Configuration
    |--------------------------------------------------------------------------
    |
    | Configure default actions and their settings.
    |
    */
    'actions' => [
        'show_icons' => true,
        'show_labels' => true,
        'confirm_delete' => true,
        'confirm_delete_message' => 'Are you sure you want to delete this item?',
        'default_actions' => [
            'create' => true,
            'edit' => true,
            'delete' => true,
        ],
        'button_classes' => [
            'create' => 'btn btn-primary',
            'edit' => 'btn btn-light btn-sm',
            'delete' => 'btn btn-light btn-sm text-danger',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Configuration
    |--------------------------------------------------------------------------
    |
    | Configure how notifications are displayed.
    |
    */
    'notifications' => [
        'position' => 'top-right', // top-right, top-left, bottom-right, bottom-left
        'timeout' => 3000, // ms
        'messages' => [
            'saved' => 'Record saved successfully!',
            'deleted' => 'Record deleted successfully!',
            'error' => 'An error occurred: :message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Stats Cards Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the default stats cards display.
    |
    */
    'stats' => [
        'enabled' => true,
        'default_color' => 'primary',
        'column_class' => 'col-12 col-sm-6 col-lg-3',
    ],

    /*
    |--------------------------------------------------------------------------
    | Component Customization
    |--------------------------------------------------------------------------
    |
    | Custom component overrides and styling.
    |
    */
    'components' => [
        'modal' => [
            'view' => 'livebstack::components.modal-form',
            'header_class' => 'modal-header',
            'body_class' => 'modal-body',
            'footer_class' => 'modal-footer',
        ],
        'table' => [
            'view' => 'livebstack::components.table',
            'wrapper_class' => 'table-responsive',
            'table_class' => 'table table-hover align-middle mb-0',
            'header_class' => 'table-light',
        ],
    ],
];