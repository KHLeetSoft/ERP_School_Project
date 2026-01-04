@php
// Example user (replace with auth user data as needed)
$user = [
  'avatar' => 'https://i.pravatar.cc/60?img=3',
  'name' => 'Priya Sharma',
  'role' => 'Admin',
];

// Define menu structure directly in the sidebar
$menus = [
        // User Management
    [
        'title' => 'User Management',
        'icon' => 'fas fa-users',
        'submenu' => [
            [ 'title' => 'Teachers',   'path' => 'admin/users/teachers',   'icon' => 'fas fa-chalkboard-teacher' ],
            [ 'title' => 'Accountants','path' => 'admin/users/accountants','icon' => 'fas fa-calculator' ],
            [ 'title' => 'Librarians', 'path' => 'admin/users/librarians', 'icon' => 'fas fa-book' ],
            [ 'title' => 'Parents',    'path' => 'admin/users/parents',    'icon' => 'fas fa-user-friends' ],
            [ 'title' => 'Students',   'path' => 'admin/users/students',   'icon' => 'fas fa-graduation-cap' ],
        ]
    ],

    // Front Office
    [
        'title' => 'Front Office',
        'icon' => 'fas fa-building',
        'submenu' => [
            [ 'title' => 'Admission Enquiry', 'path' => 'admin/office/enquiry',        'icon' => 'fas fa-user-plus' ],
            [ 'title' => 'Visitor Book',      'path' => 'admin/office/visitors',       'icon' => 'fas fa-user-check' ],
            [ 'title' => 'Phone Call Log',    'path' => 'admin/office/calllogs',       'icon' => 'fas fa-phone' ],
            [ 'title' => 'Postal Dispatch',   'path' => 'admin/office/dispatch',       'icon' => 'fas fa-paper-plane' ],
            [ 'title' => 'Postal Receive',    'path' => 'admin/office/receive',        'icon' => 'fas fa-download' ],
            [ 'title' => 'Complaint Box',     'path' => 'admin/office/complaintbox',   'icon' => 'fas fa-exclamation-triangle' ],
            [ 'title' => 'Setup Front Office','path' => 'admin/office/visitorspurpose','icon' => 'fas fa-cog' ],
        ]
    ],

    // Student Information
    [
        'title' => 'Student Information',
        'icon' => 'fas fa-user-graduate',
        'submenu' => [
            [ 'title' => 'Student Details',     'path' => 'admin/students/details',       'icon' => 'fas fa-id-card' ],
            [ 'title' => 'Class & Section',     'path' => 'admin/students/class_sections','icon' => 'fas fa-sitemap' ],
            [ 'title' => 'Fee Records',         'path' => 'admin/students/fees',          'icon' => 'fas fa-wallet' ],
            [ 'title' => 'Promotions',          'path' => 'admin/students/promotions',    'icon' => 'fas fa-arrow-up' ],
            [ 'title' => 'Health Records',      'path' => 'admin/students/health',        'icon' => 'fas fa-heartbeat' ],
            [ 'title' => 'Student Documents',   'path' => 'admin/students/documents',     'icon' => 'fas fa-file-alt' ],
            [ 'title' => 'Parent Communication','path' => 'admin/students/communication','icon' => 'fas fa-comments' ],
            [ 'title' => 'Portal Access',       'path' => 'admin/students/portal-access', 'icon' => 'fas fa-globe' ],
        ]
    ],

    // Parents
    [
        'title' => 'Parents',
        'icon' => 'fas fa-user-friends',
        'submenu' => [
            [ 'title' => 'Parent Details',      'path' => 'admin/parents/details',       'icon' => 'fas fa-id-card' ],
            [ 'title' => 'Add Parent',          'path' => 'admin/users/parents/create',  'icon' => 'fas fa-user-plus' ],
            [ 'title' => 'Parent Communication','path' => 'admin/parents/communication','icon' => 'fas fa-comments' ],
            [ 'title' => 'Parent List',         'path' => 'admin/users/parents',        'icon' => 'fas fa-list' ],
        ]
    ],

    // Academic Management
    [
        'title' => 'Academic Management',
        'icon' => 'fas fa-book-open',
        'submenu' => [
            [ 'title' => 'Subjects',          'path' => 'admin/academic/subjects',          'icon' => 'fas fa-book' ],
            [ 'title' => 'Syllabus',          'path' => 'admin/academic/syllabus',          'icon' => 'fas fa-list-alt' ],
            [ 'title' => 'Lesson Plans',      'path' => 'admin/academic/lesson-plans',      'icon' => 'fas fa-calendar-alt' ],
            [ 'title' => 'Timetable',         'path' => 'admin/academic/timetable',         'icon' => 'fas fa-clock' ],
            [ 'title' => 'Substitution',      'path' => 'admin/academic/substitution',      'icon' => 'fas fa-exchange-alt' ],
            [ 'title' => 'Coverage',          'path' => 'admin/academic/coverage',          'icon' => 'fas fa-chart-line' ],
            [ 'title' => 'Resource Bookings', 'path' => 'admin/academic/resource-bookings', 'icon' => 'fas fa-calendar-check' ],
            [ 'title' => 'PTM',               'path' => 'admin/academic/ptm',               'icon' => 'fas fa-handshake' ],
            [ 'title' => 'Academic Calendar', 'path' => 'admin/academic/calendar',          'icon' => 'fas fa-calendar' ],
            [ 'title' => 'Academic Reports',  'path' => 'admin/academic/reports',           'icon' => 'fas fa-chart-bar' ],
        ]
    ],

    // Library Management
    [
        'title' => 'Library',
        'icon' => 'fas fa-book-reader',
        'submenu' => [
            [ 'title' => 'Books',       'path' => 'admin/library/books',      'icon' => 'fas fa-book' ],
            [ 'title' => 'Categories',  'path' => 'admin/library/categories', 'icon' => 'fas fa-tags' ],
            [ 'title' => 'Book Issues', 'path' => 'admin/library/issues',     'icon' => 'fas fa-hand-holding' ],
            [ 'title' => 'Book Returns','path' => 'admin/library/returns',    'icon' => 'fas fa-undo' ],
            [ 'title' => 'Members',     'path' => 'admin/library/members',    'icon' => 'fas fa-users' ],
        ]
    ],

    // Document Generation
    [
        'title' => 'Document Generation',
        'icon' => 'fas fa-file-export',
        'submenu' => [
            [ 'title' => 'ID Cards',                  'path' => 'admin/documents/idcard',                    'icon' => 'fas fa-id-card' ],
            [ 'title' => 'Transfer Certificate',      'path' => 'admin/documents/transfer-certificate',      'icon' => 'fas fa-file-alt' ],
            [ 'title' => 'Character Certificate',     'path' => 'admin/documents/conduct-certificate',       'icon' => 'fas fa-certificate' ],
            [ 'title' => 'Bonafide Certificate',      'path' => 'admin/documents/bonafide-certificate',      'icon' => 'fas fa-award' ],
            [ 'title' => 'Leaving Certificate',       'path' => 'admin/documents/leaving-certificate',       'icon' => 'fas fa-file-signature' ],
            [ 'title' => 'Marksheet',                 'path' => 'admin/documents/marksheet',                 'icon' => 'fas fa-chart-line' ],
            [ 'title' => 'Experience Certificate',    'path' => 'admin/documents/experience-certificate',    'icon' => 'fas fa-briefcase' ],
            [ 'title' => 'Study Certificate',         'path' => 'admin/documents/study-certificate',         'icon' => 'fas fa-graduation-cap' ],
            [ 'title' => 'Employee Conduct Certificate','path'=>'admin/documents/employee-conduct-certificate','icon'=>'fas fa-user-check' ],
        ]
    ],

    // Examination
    [
        'title' => 'Examination',
        'icon' => 'fas fa-clipboard-list',
        'submenu' => [
            [ 'title' => 'Exam Management', 'path' => 'admin/exams/exam',         'icon' => 'fas fa-tasks' ],
            [ 'title' => 'Exam Schedule',   'path' => 'admin/exams/schedule',     'icon' => 'fas fa-calendar-alt' ],
            [ 'title' => 'Grades',          'path' => 'admin/exams/grades',       'icon' => 'fas fa-star' ],
            [ 'title' => 'Marks',           'path' => 'admin/exams/marks',        'icon' => 'fas fa-chart-line' ],
            [ 'title' => 'SMS',             'path' => 'admin/exams/sms',          'icon' => 'fas fa-sms' ],
            [ 'title' => 'Tabulation',      'path' => 'admin/exams/tabulation',   'icon' => 'fas fa-table' ],
            [ 'title' => 'Attendance',      'path' => 'admin/exams/attendance',   'icon' => 'fas fa-user-check' ],
            [ 'title' => 'Progress Card',   'path' => 'admin/exams/progress-card','icon' => 'fas fa-id-card' ],
            [ 'title' => 'Results',         'path' => 'admin/students/results',   'icon' => 'fas fa-trophy' ],
        ]
    ],

    // Question Bank
    [
        'title' => 'Question Bank',
        'icon' => 'fas fa-question-circle',
        'submenu' => [
            [ 'title' => 'Categories',      'path' => 'admin/exams/question-bank/categories','icon'=>'fas fa-tags' ],
            [ 'title' => 'Questions',       'path' => 'admin/exams/question-bank/questions', 'icon'=>'fas fa-question' ],
            [ 'title' => 'Add Question',    'path' => 'admin/exams/question-bank/questions/create','icon'=>'fas fa-plus' ],
            [ 'title' => 'Import Questions','path' => 'admin/exams/question-bank/questions/import','icon'=>'fas fa-upload' ],
            [ 'title' => 'Question Papers', 'path' => 'admin/exams/question-bank/papers',    'icon'=>'fas fa-file-alt' ],
            [ 'title' => 'Create Paper',    'path' => 'admin/exams/question-bank/papers/create','icon'=>'fas fa-plus-circle' ],
        ]
    ],

    // Online Exam
    [
        'title' => 'Online Exam',
        'icon' => 'fas fa-laptop',
        'submenu' => [
            [ 'title' => 'Exam List',   'path' => 'admin/online-exam',           'icon'=>'fas fa-list' ],
            [ 'title' => 'Manage Exams','path' => 'admin/online-exam/manage',    'icon'=>'fas fa-cogs' ],
            [ 'title' => 'Questions',   'path' => 'admin/online-exam/questions', 'icon'=>'fas fa-question' ],
            [ 'title' => 'Results',     'path' => 'admin/online-exam/results',   'icon'=>'fas fa-trophy' ],
        ]
    ],

    // Result Announcement
    [
        'title' => 'Result Announcement',
        'icon' => 'fas fa-bullhorn',
        'submenu' => [
            [ 'title' => 'Announcements', 'path' => 'admin/result-announcement/announcement','icon'=>'fas fa-bullhorn' ],
            [ 'title' => 'Publications',  'path' => 'admin/result-announcement/publications','icon'=>'fas fa-newspaper' ],
            [ 'title' => 'Notifications', 'path' => 'admin/result-announcement/notification','icon'=>'fas fa-bell' ],
            [ 'title' => 'Statistics',    'path' => 'admin/result-announcement/statistics', 'icon'=>'fas fa-chart-bar' ],
        ]
    ],
    [
    'icon' => 'bx bx-rocket',
    'title' => 'AI Integration',
    'submenu' => [
        [ 'title' => 'AI Paper Generator (MCQ + Subjective)', 'path' => 'admin/ai/paper-generator',      'icon' => 'bx bx-bot', 'badge' => 'AI' ],
        [ 'title' => 'Student Performance Prediction',        'path' => 'admin/ai/performance-prediction','icon' => 'bx bx-line-chart', 'badge' => 'AI' ],
        [ 'title' => 'Chatbot (Students & Parents)',          'path' => 'admin/ai/chatbot',              'icon' => 'bx bx-message-square-dots', 'badge' => 'AI' ],
        [ 'title' => 'Plagiarism Checker (Assignments)',      'path' => 'admin/ai/plagiarism-checker',   'icon' => 'bx bx-shield-quarter', 'badge' => 'AI' ],
    ],
],
    // Attendance Management
    [
        'title' => 'Attendance',
        'icon' => 'fas fa-user-check',
        'submenu' => [
            [ 'title' => 'Staff Attendance','path'=>'admin/attendance/staff','icon'=>'fas fa-users' ],
            [ 'title' => 'Bulk Attendance', 'path'=>'admin/attendance/bulk', 'icon'=>'fas fa-users-cog' ],
            [ 'title' => 'RFID Attendance', 'path'=>'admin/attendance/rfid', 'icon'=>'fas fa-id-card' ],
            [ 'title' => 'Attendance Reports','path'=>'admin/attendance/reports','icon'=>'fas fa-chart-line' ],
        ]
    ],

    // Finance
    [
        'title' => 'Finance',
        'icon' => 'fas fa-rupee-sign',
        'submenu' => [
            [ 'title' => 'Invoices',         'path'=>'admin/finance/invoice',          'icon'=>'fas fa-file-invoice' ],
            [ 'title' => 'Student Payments', 'path'=>'admin/finance/student-payments', 'icon'=>'fas fa-credit-card' ],
            [ 'title' => 'Expenses',         'path'=>'admin/finance/expenses',         'icon'=>'fas fa-receipt' ],
            [ 'title' => 'Expense Categories','path'=>'admin/finance/expense-categories','icon'=>'fas fa-tags' ],
            [ 'title' => 'Financial Reports','path'=>'admin/finance/reports',          'icon'=>'fas fa-chart-pie' ],
            [ 'title' => 'Scholarships',     'path'=>'admin/finance/scholarships',     'icon'=>'fas fa-award' ],
        ]
    ],

    // Fee Management
    [
        'title' => 'Fee Management',
        'icon' => 'fas fa-wallet',
        'submenu' => [
            [ 'title' => 'Fee Heads',        'path'=>'admin/fees/fee-heads',        'icon'=>'fas fa-list' ],
            [ 'title' => 'Fee Structures',   'path'=>'admin/fees/fee-structures',   'icon'=>'fas fa-table' ],
            [ 'title' => 'Student Fees',     'path'=>'admin/fees/student-fees',     'icon'=>'fas fa-user-graduate' ],
            [ 'title' => 'Fee Collections',  'path'=>'admin/fees/fee-collections',  'icon'=>'fas fa-hand-holding-usd' ],
            [ 'title' => 'Fee Receipts',     'path'=>'admin/fees/fee-receipts',     'icon'=>'fas fa-receipt' ],
            [ 'title' => 'Payment Gateways', 'path'=>'admin/payment/gateways',         'icon'=>'fas fa-credit-card' ],
            [ 'title' => 'School QR Code',   'path'=>'admin/payment/school-qr-codes',  'icon'=>'fas fa-school' ],
        ]
    ],

    // Human Resources
    [
        'title' => 'Human Resources',
        'icon' => 'fas fa-users-cog',
        'submenu' => [
            [ 'title' => 'Staff Management','path'=>'admin/hr/staff','icon'=>'fas fa-users' ],
            [ 'title' => 'Payroll',         'path'=>'admin/hr/payroll','icon'=>'fas fa-money-bill-wave' ],
            [ 'title' => 'Leave Management','path'=>'admin/hr/leave-management','icon'=>'fas fa-calendar-times' ],
        ]
    ],

    // Communications
    [
        'title' => 'Communications',
        'icon' => 'fas fa-comments',
        'submenu' => [
            [ 'title' => 'Notice Board','path'=>'admin/communications/noticeboard','icon'=>'fas fa-clipboard' ],
            [ 'title' => 'Messages',    'path'=>'admin/communications/messages',   'icon'=>'fas fa-envelope' ],
            [ 'title' => 'SMS',         'path'=>'admin/communications/sms',        'icon'=>'fas fa-sms' ],
            [ 'title' => 'Email Templates','path'=>'admin/communications/email-templates','icon'=>'fas fa-envelope-open-text' ],
            [ 'title' => 'Newsletter',  'path'=>'admin/communications/newsletter', 'icon'=>'fas fa-newspaper' ],
        ]
    ],

    // Transportation
    [
        'title' => 'Transportation',
        'icon' => 'fas fa-bus',
        'submenu' => [
            [ 'title' => 'Routes',     'path'=>'admin/transport/tproutes','icon'=>'fas fa-route' ],
            [ 'title' => 'Vehicles',   'path'=>'admin/transport/vehicles','icon'=>'fas fa-car' ],
            [ 'title' => 'Assignments','path'=>'admin/transport/assign',  'icon'=>'fas fa-user-plus' ],
            [ 'title' => 'Drivers',    'path'=>'admin/transport/drivers', 'icon'=>'fas fa-user-tie' ],
            [ 'title' => 'Tracking',   'path'=>'admin/transport/tracking','icon'=>'fas fa-map-marker-alt' ],
        ]
    ],

    // Accommodation (Hostel)
    [
        'title' => 'Accommodation',
        'icon' => 'fas fa-bed',
        'submenu' => [
            [ 'title' => 'Hostel Categories','path'=>'admin/accommodation/categories','icon'=>'fas fa-tags' ],
            [ 'title' => 'Rooms',            'path'=>'admin/accommodation/rooms',     'icon'=>'fas fa-door-open' ],
            [ 'title' => 'Room Allocation',  'path'=>'admin/accommodation/allocation','icon'=>'fas fa-users' ],
            [ 'title' => 'Hostel Fees',      'path'=>'admin/accommodation/fees',      'icon'=>'fas fa-rupee-sign' ],
            [ 'title' => 'Hostel Attendance','path'=>'admin/accommodation/attendance','icon'=>'fas fa-user-check' ],
        ]
    ],

    // Inventory Management
    [
        'title' => 'Inventory',
        'icon' => 'fas fa-box',
        'submenu' => [
            [ 'title' => 'Item Categories',   'path' => 'admin/inventory/categories', 'icon' => 'fas fa-tags' ],
            [ 'title' => 'Items',             'path' => 'admin/inventory/items',      'icon' => 'fas fa-box' ],
            [ 'title' => 'Issue Items',       'path' => 'admin/inventory/issues',     'icon' => 'fas fa-export' ],
            [ 'title' => 'Stock Management',  'path' => 'admin/inventory/stock',      'icon' => 'fas fa-chart-bar' ],
            [ 'title' => 'Suppliers',         'path' => 'admin/inventory/suppliers',  'icon' => 'fas fa-truck' ],
            [ 'title' => 'Purchase Orders',   'path' => 'admin/inventory/purchases',  'icon' => 'fas fa-shopping-cart' ],
        ]
    ],

    // Canteen Management
    [
        'title' => 'Canteen',
        'icon'  => 'fas fa-utensils',
        'submenu' => [
            [ 'title' => 'Food Items',   'path' => 'admin/canteen/items',       'icon' => 'fas fa-hamburger' ],
            [ 'title' => 'Sales',        'path' => 'admin/canteen/sales',       'icon' => 'fas fa-shopping-bag' ],
            [ 'title' => 'Inventory',    'path' => 'admin/canteen/inventory',   'icon' => 'fas fa-box' ],
            [ 'title' => 'Orders',       'path' => 'admin/canteen/orders',      'icon' => 'fas fa-receipt' ],
            [ 'title' => 'Menu Planning','path' => 'admin/canteen/menu',        'icon' => 'fas fa-list-alt' ],
            [ 'title' => 'Suppliers',    'path' => 'admin/canteen/suppliers',   'icon' => 'fas fa-truck' ],
            [ 'title' => 'Purchases',    'path' => 'admin/canteen/purchases',   'icon' => 'fas fa-file-invoice-dollar' ],
            [ 'title' => 'Reports',      'path' => 'admin/canteen/reports',     'icon' => 'fas fa-chart-line' ],
            [ 'title' => 'Offers',       'path' => 'admin/canteen/offers',      'icon' => 'fas fa-percentage' ],
            [ 'title' => 'Feedback',     'path' => 'admin/canteen/feedback',    'icon' => 'fas fa-comment-dots' ],
        ]
],

    // Events & Calendar
    [
        'title' => 'Events & Calendar',
        'icon' => 'fas fa-calendar-alt',
        'submenu' => [
            [ 'title' => 'Calendar View',     'path' => 'admin/events/calendar',   'icon' => 'fas fa-calendar' ],
            [ 'title' => 'Event Management',  'path' => 'admin/events/management', 'icon' => 'fas fa-calendar-plus' ],
            [ 'title' => 'Academic Calendar', 'path' => 'admin/events/academic',   'icon' => 'fas fa-calendar-week' ],
        ]
    ],

    // Help Center
    [
        'title' => 'Help Center',
        'icon' => 'fas fa-question-circle',
        'submenu' => [
            [ 'title' => 'Help Overview', 'path' => 'admin/help/overview', 'icon' => 'fas fa-question-circle' ],
            [ 'title' => 'Helpful Links', 'path' => 'admin/help/links',    'icon' => 'fas fa-link' ],
            [ 'title' => 'Help Desk',     'path' => 'admin/help/desk',     'icon' => 'fas fa-headset' ],
        ]
    ],

    // Clubs Management
    [
        'title' => 'Clubs',
        'icon' => 'fas fa-users',
        'submenu' => [
            [ 'title' => 'Manage Clubs',     'path' => 'admin/clubs/manage',      'icon' => 'fas fa-users' ],
            [ 'title' => 'Club Members',     'path' => 'admin/clubs/members',     'icon' => 'fas fa-user-plus' ],
            [ 'title' => 'Club Activities',  'path' => 'admin/clubs/activities',  'icon' => 'fas fa-calendar-check' ],
            [ 'title' => 'Club Events',      'path' => 'admin/clubs/events',      'icon' => 'fas fa-trophy' ],
            [ 'title' => 'Club Resources',   'path' => 'admin/clubs/resources',   'icon' => 'fas fa-archive' ],
            [ 'title' => 'Club Finance',     'path' => 'admin/clubs/finance',     'icon' => 'fas fa-wallet' ],
            [ 'title' => 'Club Achievements','path' => 'admin/clubs/achievements','icon' => 'fas fa-medal' ],
            [ 'title' => 'Club Gallery',     'path' => 'admin/clubs/gallery',     'icon' => 'fas fa-images' ],
            [ 'title' => 'Announcements',    'path' => 'admin/clubs/announcements','icon' => 'fas fa-bullhorn' ],
        ]
    ],

    // Alumni Management
    [
        'title' => 'Alumni',
        'icon' => 'fas fa-graduation-cap',
        'submenu' => [
            [ 'title' => 'Alumni Directory', 'path' => 'admin/alumni/directory',   'icon' => 'fas fa-id-card' ],
            [ 'title' => 'Alumni Events',    'path' => 'admin/alumni/events',      'icon' => 'fas fa-calendar-check' ],
            [ 'title' => 'Donations',        'path' => 'admin/alumni/donations',   'icon' => 'fas fa-heart' ],
            [ 'title' => 'Achievements',     'path' => 'admin/alumni/achievements','icon' => 'fas fa-award' ],
            [ 'title' => 'Jobs & Careers',   'path' => 'admin/alumni/jobs',        'icon' => 'fas fa-briefcase' ],
            [ 'title' => 'Mentorship',       'path' => 'admin/alumni/mentorship',  'icon' => 'fas fa-user-friends' ],
            [ 'title' => 'Newsletter',       'path' => 'admin/alumni/newsletter',  'icon' => 'fas fa-newspaper' ],
            [ 'title' => 'Photo Gallery',    'path' => 'admin/alumni/gallery',     'icon' => 'fas fa-images' ],
        ]
    ],

    // Digital Learning
    [
        'title' => 'Digital Learning',
        'icon' => 'fas fa-laptop',
        'submenu' => [
            [ 'title' => 'E-Learning Portal','path' => 'admin/digital/portal', 'icon' => 'fas fa-laptop' ],
            [ 'title' => 'Video Lessons',    'path' => 'admin/digital/videos', 'icon' => 'fas fa-video' ],
            [ 'title' => 'Live Classes',     'path' => 'admin/digital/live',   'icon' => 'fas fa-broadcast-tower' ],
        ]
    ],

    // Student Counseling
    [
        'title' => 'Student Counseling',
        'icon' => 'fas fa-hands-helping',
        'submenu' => [
            [ 'title' => 'Counseling Sessions', 'path' => 'admin/counseling/sessions', 'icon' => 'fas fa-comments' ],
            [ 'title' => 'Career Guidance',     'path' => 'admin/counseling/career',   'icon' => 'fas fa-bullseye' ],
            [ 'title' => 'Psychological Support','path' => 'admin/counseling/psychology','icon' => 'fas fa-heart' ],
        ]
    ],

    // Health & Medical
    [
        'title' => 'Health & Medical',
        'icon' => 'fas fa-heartbeat',
        'submenu' => [
            [ 'title' => 'Health Records',       'path' => 'admin/health/records',     'icon' => 'fas fa-file-medical' ],
            [ 'title' => 'Medical Visits',       'path' => 'admin/health/visits',      'icon' => 'fas fa-user-md' ],
            [ 'title' => 'Vaccination Records',  'path' => 'admin/health/vaccination', 'icon' => 'fas fa-shield-alt' ],
            [ 'title' => 'Health Alerts',        'path' => 'admin/health/alerts',      'icon' => 'fas fa-exclamation-triangle' ],
        ]
    ],

    // Visitor Management
    [
        'title' => 'Visitor Management',
        'icon' => 'fas fa-id-card',
        'submenu' => [
            [ 'title' => 'Visitor Log',           'path' => 'admin/visitors/log',        'icon' => 'fas fa-list' ],
            [ 'title' => 'Appointment Scheduler', 'path' => 'admin/visitors/appointments','icon' => 'fas fa-calendar-plus' ],
            [ 'title' => 'Gate Pass',             'path' => 'admin/visitors/gatepass',   'icon' => 'fas fa-key' ],
        ]
    ],

    // Payment Management
    [
        'title' => 'Payment Management',
        'icon' => 'fas fa-credit-card',
        'submenu' => [
            [ 'title' => 'Plan Selection',     'route' => 'admin.payment.plan-selection.index', 'icon' => 'fas fa-package' ],
            [ 'title' => 'School QR Codes',    'route' => 'admin.payment.school-qr-codes.index', 'icon' => 'fas fa-qr-code' ],
            [ 'title' => 'Payment History',    'path' => 'admin/payment/history', 'icon' => 'fas fa-history' ],
        ]
    ],

    // Admin Settings
    [
        'title' => 'Admin Settings',
        'icon' => 'fas fa-cog',
        'submenu' => [
            [ 'title' => 'Profile',          'path' => 'admin/settings/profile',   'icon' => 'fas fa-user-circle' ],
            [ 'title' => 'Change Password',  'path' => 'admin/settings/password',  'icon' => 'fas fa-lock' ],
            [ 'title' => 'Notifications',    'path' => 'admin/settings/notify',    'icon' => 'fas fa-bell' ],
            [ 'title' => 'Theme',            'path' => 'admin/settings/theme',     'icon' => 'fas fa-palette' ],
            [ 'title' => 'Canteen Settings', 'path' => 'admin/settings/canteen',   'icon' => 'fas fa-utensils' ],
            [ 'title' => 'Transport',        'path' => 'admin/settings/transport', 'icon' => 'fas fa-bus' ],
            [ 'title' => 'Inventory',        'path' => 'admin/settings/inventory', 'icon' => 'fas fa-box' ],
            [ 'title' => 'Activity Logs',    'path' => 'admin/settings/logs',      'icon' => 'fas fa-history' ],
            [ 'title' => 'Reports',          'path' => 'admin/settings/reports',   'icon' => 'fas fa-chart-bar' ],
        ]
    ]
];

@endphp

<style>
  .sidebar-modern {
    background: linear-gradient(135deg, #232946 60%, #3a3f7a 100%);
    color: #f4f4f4;
    width: 250px;
    min-width: 70px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1040;
    transition: width 0.3s;
    box-shadow: 2px 0 16px rgba(44,62,80,0.13);
    display: flex;
    flex-direction: column;
    font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
  }
  
  .sidebar-modern.collapsed {
    width: 70px;
  }
  
  .sidebar-modern .sidebar-header {
    padding: 1.3rem 1.7rem 1.1rem 1.7rem;
    display: flex;
    align-items: center;
    border-bottom: 1px solid #2e335a;
    justify-content: space-between;
  }
  
  .sidebar-modern .brand {
    font-size: 17px;
    font-weight: 700;
    color: #fff;
    white-space: nowrap;
  }
  
  .sidebar-modern .sidebar-toggle {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.7rem;
    cursor: pointer;
    transition: color 0.2s;
  }
  
  .sidebar-modern .sidebar-toggle:hover {
    color: #a7a9be;
  }
  
  .sidebar-modern .sidebar-profile {
    display: flex;
    align-items: center;
    gap: 1.1rem;
    padding: 1.2rem 1.7rem 0.8rem 1.7rem;
    border-bottom: 1px solid #2e335a;
    cursor: pointer;
    position: relative;
  }
  
  .sidebar-modern .avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    border: 2.5px solid #3a3f7a;
  }
  
  .sidebar-modern .profile-info {
    display: flex;
    flex-direction: column;
  }
  
  .sidebar-modern .profile-name {
    font-size: 15px;
    font-weight: 600;
    color: #fff;
    margin-bottom: 2px;
  }
  
  .sidebar-modern .profile-role {
    font-size: 13px;
    color: #bfc0d4;
  }
  
  .sidebar-modern .profile-dropdown {
    position: absolute;
    left: 0;
    top: 70px;
    background: #232946;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(44,62,80,0.13);
    min-width: 170px;
    display: none;
    flex-direction: column;
    padding: 0.5rem 0;
    z-index: 1000;
  }
  
  .sidebar-modern .profile-dropdown a {
    color: #fff;
    padding: 0.7rem 1.2rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    font-size: 1rem;
    transition: background 0.2s;
  }
  
  .sidebar-modern .profile-dropdown a:hover {
    background: #393e6c;
  }
  
  .sidebar-modern .sidebar-menu {
    flex: 1 1 auto;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 1.1rem 0.7rem 1.1rem 0.7rem;
    /* Custom scrollbar styling */
    scrollbar-width: thin;
    scrollbar-color: #3a3f7a #232946;
  }
  
  .sidebar-modern .sidebar-menu::-webkit-scrollbar {
    width: 6px;
  }
  
  .sidebar-modern .sidebar-menu::-webkit-scrollbar-track {
    background: #232946;
    border-radius: 3px;
  }
  
  .sidebar-modern .sidebar-menu::-webkit-scrollbar-thumb {
    background: #3a3f7a;
    border-radius: 3px;
  }
  
  .sidebar-modern .sidebar-menu::-webkit-scrollbar-thumb:hover {
    background: #4a4f8a;
  }
  
  .sidebar-modern .menu-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  
  .sidebar-modern .menu-item {
    margin-bottom: 5px;
    position: relative;
  }
  
  .sidebar-modern .menu-link {
    display: flex;
    align-items: center;
    color: #f4f4f4;
    text-decoration: none;
    padding: 11px 10px;
    border-radius: 7px;
    font-size: 14px;
    font-weight: 600;
    transition: background 0.2s, color 0.2s;
    cursor: pointer;
  }
  
  .sidebar-modern .menu-link:hover,
  .sidebar-modern .menu-link.active {
    background: linear-gradient(90deg, #3a3f7a 60%, #232946 100%);
    color: #00d4ff;
  }
  
  .sidebar-modern .menu-link i {
    font-size: 1.2rem;
    margin-right: 1rem;
    min-width: 24px;
    text-align: center;
  }
  
  .sidebar-modern .menu-title {
    white-space: nowrap;
  }
  
  .sidebar-modern .menu-caret {
    margin-left: auto;
    font-size: 1.18rem;
    transition: transform 0.3s;
    color: #bfc0d4;
  }
  
  .sidebar-modern .menu-item.open > .menu-link .menu-caret {
    transform: rotate(90deg);
    color: #00d4ff;
  }
  
  .sidebar-modern .submenu {
    list-style: none;
    padding: 0;
    margin: 0;
    display: none;
    position: relative;
    background: linear-gradient(135deg, #232946 60%, #3a3f7a 100%);
    border-radius: 7px;
    box-shadow: 0 2px 8px rgba(44,62,80,0.10);
    margin-top: 5px;
    margin-left: 20px;
    border-left: 3px solid #00d4ff;
  }
  
  .sidebar-modern .menu-item.open .submenu {
    display: block !important;
  }
  
  @keyframes dropdownFadeIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .sidebar-modern .submenu-link {
    display: flex;
    align-items: center;
    color: #f4f4f4;
    text-decoration: none;
    padding: 8px 10px;
    border-radius: 5px;
    font-size: 12px;
    font-weight: 500;
    transition: background 0.2s, color 0.2s;
    margin: 2px 5px;
  }
  
  .sidebar-modern .submenu-link:hover,
  .sidebar-modern .submenu-link.active {
    background: linear-gradient(90deg, #3a3f7a 60%, #232946 100%);
    color: #00d4ff;
  }
  
  .sidebar-modern .submenu-link i {
    font-size: 0.9rem;
    margin-right: 0.6rem;
    min-width: 16px;
    text-align: center;
  }
  
  .sidebar-modern.collapsed .menu-title,
  .sidebar-modern.collapsed .menu-caret {
    display: none !important;
  }
  
  .sidebar-modern.collapsed .profile-info {
    display: none;
  }
  
  .sidebar-modern.collapsed .submenu {
    left: 70px;
  }
  
  .sidebar-modern .sidebar-footer {
    margin-top: auto;
    padding: 1rem 1.5rem;
    border-top: 1px solid #2e335a;
    text-align: center;
  }
  
  .sidebar-modern .footer-text {
    color: #a7a9be;
    font-size: 11px;
    line-height: 1.4;
    margin: 0;
    font-weight: 400;
  }
  
  .sidebar-modern.collapsed .footer-text {
    display: none;
  }
  
  @media (max-width: 768px) {
    .sidebar-modern {
      position: fixed;
      left: -240px;
      width: 240px;
      transition: left 0.3s;
    }
    .sidebar-modern.show {
      left: 0;
    }
  }
</style>

<div class="sidebar-modern" id="modernSidebar">
  <!-- Header -->
  <div class="sidebar-header">
    <span class="brand">School Admin</span>
    <button class="sidebar-toggle" id="sidebarToggleBtn">
        <i class="bx bx-chevron-left"></i>
      </button>
    </div>
  
  <!-- Profile -->
  <div class="sidebar-profile" id="sidebarProfile">
    <img src="{{ $user['avatar'] }}" alt="Avatar" class="avatar">
    <div class="profile-info">
      <span class="profile-name">{{ $user['name'] }}</span>
      <span class="profile-role">{{ $user['role'] }}</span>
    </div>
    <div class="profile-dropdown" id="profileDropdown">
      <a href="#"><i class="bx bx-user"></i> Profile</a>
      <a href="#"><i class="bx bx-cog"></i> Settings</a>
      <a href="#" id="logoutBtn"><i class="bx bx-log-out"></i> Logout</a>
    </div>
  </div>
  
  <!-- Menu -->
  <nav class="sidebar-menu">
    <ul class="menu-list">
      <!-- Dashboard -->
      <li class="menu-item">
        <a href="{{ url('admin/dashboard') }}" class="menu-link">
          <i class="bx bx-home"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      
      <!-- Dynamic Menu Items -->
      @foreach($menus as $menu)
        <li class="menu-item">
          @if(isset($menu['submenu']) && count($menu['submenu']) > 0)
            <!-- Parent Menu with Submenu -->
            <a href="javascript:void(0);" class="menu-link parent-link">
              <i class="{{ $menu['icon'] }}"></i>
              <span class="menu-title">{{ $menu['title'] }}</span>
              <i class="bx bx-chevron-right menu-caret"></i>
            </a>
            <ul class="submenu">
              @foreach($menu['submenu'] as $child)
                <li>
                  @php
                    $href = isset($child['path']) ? url($child['path']) : ($child['route'] ?? '#');
                  @endphp
                  <a href="{{ $href }}" class="submenu-link">
                    <i class="{{ $child['icon'] ?? 'bx bx-circle' }}"></i>
                    {{ $child['title'] }}
                    @if(isset($child['badge']))
                      <span class="badge">{{ $child['badge'] }}</span>
                    @endif
                  </a>
                </li>
              @endforeach
            </ul>
          @else
            <!-- Direct Menu Link -->
            @php
              $href = isset($menu['path']) ? url($menu['path']) : ($menu['route'] ?? '#');
            @endphp
            <a href="{{ $href }}" class="menu-link">
              <i class="{{ $menu['icon'] }}"></i>
              <span class="menu-title">{{ $menu['title'] }}</span>
            </a>
          @endif
        </li>
      @endforeach
    </ul>
  </nav>
  
  <!-- Footer -->
  <div class="sidebar-footer">
    <p class="footer-text">
      © 2024 Kh Leetsoft Innovation<br>
      All rights reserved
    </p>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
  // Initialize sidebar functionality
  
  // Sidebar toggle functionality
  $('#sidebarToggleBtn').click(function() {
    $('#modernSidebar').toggleClass('collapsed');
    $(this).find('i').toggleClass('bx-chevron-left bx-chevron-right');
  });
  
  // Submenu dropdown toggle
  $(document).on('click', '.parent-link', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    var $parent = $(this).closest('.menu-item');
    var $submenu = $parent.find('.submenu');
    
    // Close all other open menus first
    $('.menu-item.open').not($parent).removeClass('open');
    $('.submenu').not($submenu).hide();
    
    // Toggle current menu
    if ($parent.hasClass('open')) {
      $parent.removeClass('open');
      $submenu.hide();
    } else {
      $parent.addClass('open');
      $submenu.show();
    }
  });
  
  // Close dropdown when clicking outside
  $(document).click(function(e) {
    if (!$(e.target).closest('.menu-item').length) {
      $('.menu-item.open').removeClass('open').find('.submenu').hide();
    }
  });
  
  // Handle submenu link clicks
  $(document).on('click', '.submenu-link', function(e) {
    var href = $(this).attr('href');
    
    // If it's a valid link, navigate to it
    if (href && href !== '#' && href !== 'javascript:void(0);') {
      window.location.href = href;
    }
  });
  
  // Profile dropdown toggle
  $('#sidebarProfile').click(function(e) {
    e.stopPropagation();
    $('#profileDropdown').toggle();
  });
  
  // Close profile dropdown when clicking outside
  $(document).click(function(e) {
    if (!$(e.target).closest('#sidebarProfile').length) {
      $('#profileDropdown').hide();
    }
  });
  
  // Logout functionality
  $('#logoutBtn').click(function(e) {
    e.preventDefault();
    if (confirm('Are you sure you want to logout?')) {
      // Add your logout logic here
      window.location.href = '/logout';
    }
  });
});
</script>

<div class="sidebar">
    <h5 class="text-center mb-4">Admin Panel</h5>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="{{ url('admin/dashboard') }}">
                <i class="bx bx-home me-2"></i>Dashboard
            </a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#userManagement">
                <i class="fas fa-users me-2"></i>User Management
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="userManagement">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/users/teachers') }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/users/accountants') }}">
                            <i class="fas fa-calculator me-2"></i>Accountants
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/users/librarians') }}">
                            <i class="fas fa-book me-2"></i>Librarians
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/users/parents') }}">
                            <i class="fas fa-user-friends me-2"></i>Parents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/users/students') }}">
                            <i class="fas fa-graduation-cap me-2"></i>Students
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#frontOffice">
                <i class="fas fa-building me-2"></i>Front Office
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="frontOffice">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/office/enquiry') }}">
                            <i class="fas fa-user-plus me-2"></i>Admission Enquiry
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/office/visitors') }}">
                            <i class="fas fa-user-check me-2"></i>Visitor Book
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/office/calllogs') }}">
                            <i class="fas fa-phone me-2"></i>Phone Call Log
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/office/dispatch') }}">
                            <i class="fas fa-paper-plane me-2"></i>Postal Dispatch
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/office/receive') }}">
                            <i class="fas fa-download me-2"></i>Postal Receive
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/office/complaintbox') }}">
                            <i class="fas fa-exclamation-triangle me-2"></i>Complaint Box
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#studentInfo">
                <i class="fas fa-user-graduate me-2"></i>Student Information
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="studentInfo">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/students/details') }}">
                            <i class="fas fa-id-card me-2"></i>Student Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/students/class_sections') }}">
                            <i class="fas fa-sitemap me-2"></i>Class & Section
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/students/fees') }}">
                            <i class="fas fa-wallet me-2"></i>Fee Records
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/students/promotions') }}">
                            <i class="fas fa-arrow-up me-2"></i>Promotions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/students/health') }}">
                            <i class="fas fa-heartbeat me-2"></i>Health Records
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#academic">
                <i class="fas fa-book-open me-2"></i>Academic Management
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="academic">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/academic/subjects') }}">
                            <i class="fas fa-book me-2"></i>Subjects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/academic/syllabus') }}">
                            <i class="fas fa-list-alt me-2"></i>Syllabus
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/academic/timetable') }}">
                            <i class="fas fa-clock me-2"></i>Timetable
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/academic/calendar') }}">
                            <i class="fas fa-calendar me-2"></i>Academic Calendar
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#examination">
                <i class="fas fa-clipboard-list me-2"></i>Examination
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="examination">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/exams/exam') }}">
                            <i class="fas fa-tasks me-2"></i>Exam Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/exams/schedule') }}">
                            <i class="fas fa-calendar-alt me-2"></i>Exam Schedule
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/exams/grades') }}">
                            <i class="fas fa-star me-2"></i>Grades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/exams/marks') }}">
                            <i class="fas fa-chart-line me-2"></i>Marks
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#finance">
                <i class="fas fa-rupee-sign me-2"></i>Finance
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="finance">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/finance/invoice') }}">
                            <i class="fas fa-file-invoice me-2"></i>Invoices
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/finance/student-payments') }}">
                            <i class="fas fa-credit-card me-2"></i>Student Payments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/finance/expenses') }}">
                            <i class="fas fa-receipt me-2"></i>Expenses
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#library">
                <i class="fas fa-book-reader me-2"></i>Library
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="library">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/library/books') }}">
                            <i class="fas fa-book me-2"></i>Books
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/library/categories') }}">
                            <i class="fas fa-tags me-2"></i>Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/library/issues') }}">
                            <i class="fas fa-hand-holding me-2"></i>Book Issues
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#attendance">
                <i class="fas fa-user-check me-2"></i>Attendance
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="attendance">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/attendance/staff') }}">
                            <i class="fas fa-users me-2"></i>Staff Attendance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/attendance/bulk') }}">
                            <i class="fas fa-users-cog me-2"></i>Bulk Attendance
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#communications">
                <i class="fas fa-comments me-2"></i>Communications
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="communications">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/communications/noticeboard') }}">
                            <i class="fas fa-clipboard me-2"></i>Notice Board
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/communications/messages') }}">
                            <i class="fas fa-envelope me-2"></i>Messages
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/communications/sms') }}">
                            <i class="fas fa-sms me-2"></i>SMS
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#transport">
                <i class="fas fa-bus me-2"></i>Transportation
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="transport">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/transport/tproutes') }}">
                            <i class="fas fa-route me-2"></i>Routes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/transport/vehicles') }}">
                            <i class="fas fa-car me-2"></i>Vehicles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/transport/drivers') }}">
                            <i class="fas fa-user-tie me-2"></i>Drivers
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#accommodation">
                <i class="fas fa-bed me-2"></i>Accommodation
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="accommodation">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/accommodation/categories') }}">
                            <i class="fas fa-tags me-2"></i>Hostel Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/accommodation/rooms') }}">
                            <i class="fas fa-door-open me-2"></i>Rooms
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/accommodation/allocation') }}">
                            <i class="fas fa-users me-2"></i>Room Allocation
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#inventory">
                <i class="bx bx-box me-2"></i>Inventory
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="inventory">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/inventory/categories') }}">
                            <i class="bx bx-category me-2"></i>Item Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/inventory/items') }}">
                            <i class="bx bx-box me-2"></i>Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/inventory/issues') }}">
                            <i class="bx bx-export me-2"></i>Issue Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/inventory/stock') }}">
                            <i class="bx bx-stats me-2"></i>Stock Management
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#canteen">
                <i class="bx bx-restaurant me-2"></i>Canteen
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="canteen">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/canteen/items') }}">
                            <i class="bx bx-food-menu me-2"></i>Food Items
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/canteen/sales') }}">
                            <i class="bx bx-shopping-bag me-2"></i>Sales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/canteen/inventory') }}">
                            <i class="bx bx-box me-2"></i>Inventory
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#events">
                <i class="bx bx-calendar me-2"></i>Events & Calendar
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="events">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/events/calendar') }}">
                            <i class="bx bx-calendar me-2"></i>Calendar View
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/events/management') }}">
                            <i class="bx bx-calendar-event me-2"></i>Event Management
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/events/academic') }}">
                            <i class="bx bx-calendar-week me-2"></i>Academic Calendar
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#help">
                <i class="bx bx-help-circle me-2"></i>Help Center
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="help">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/help/overview') }}">
                            <i class="bx bx-help-circle me-2"></i>Help Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/help/links') }}">
                            <i class="bx bx-link me-2"></i>Helpful Links
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/help/desk') }}">
                            <i class="bx bx-support me-2"></i>Help Desk
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#clubs">
                <i class="bx bx-group me-2"></i>Clubs
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="clubs">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/clubs/manage') }}">
                            <i class="bx bx-group me-2"></i>Manage Clubs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/clubs/members') }}">
                            <i class="bx bx-user-plus me-2"></i>Club Members
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/clubs/activities') }}">
                            <i class="bx bx-calendar-event me-2"></i>Club Activities
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/clubs/events') }}">
                            <i class="bx bx-trophy me-2"></i>Club Events
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/clubs/resources') }}">
                            <i class="bx bx-archive me-2"></i>Club Resources
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/clubs/finance') }}">
                            <i class="bx bx-wallet me-2"></i>Club Finance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/clubs/achievements') }}">
                            <i class="bx bx-medal me-2"></i>Club Achievements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/clubs/gallery') }}">
                            <i class="bx bx-photo-album me-2"></i>Club Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/clubs/announcements') }}">
                            <i class="bx bx-bullhorn me-2"></i>Announcements
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#alumni">
                <i class="bx bx-graduation me-2"></i>Alumni
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="alumni">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/alumni/directory') }}">
                            <i class="bx bx-id-card me-2"></i>Alumni Directory
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/alumni/events') }}">
                            <i class="bx bx-calendar-event me-2"></i>Alumni Events
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/alumni/donations') }}">
                            <i class="bx bx-donate-heart me-2"></i>Donations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/alumni/achievements') }}">
                            <i class="bx bx-award me-2"></i>Achievements
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/alumni/jobs') }}">
                            <i class="bx bx-briefcase me-2"></i>Jobs & Careers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/alumni/mentorship') }}">
                            <i class="bx bx-user-voice me-2"></i>Mentorship
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/alumni/newsletter') }}">
                            <i class="bx bx-news me-2"></i>Newsletter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/alumni/gallery') }}">
                            <i class="bx bx-image me-2"></i>Photo Gallery
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#digital">
                <i class="bx bx-monitor me-2"></i>Digital Learning
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="digital">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/digital/portal') }}">
                            <i class="bx bx-laptop me-2"></i>E-Learning Portal
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/digital/videos') }}">
                            <i class="bx bx-video me-2"></i>Video Lessons
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/digital/live') }}">
                            <i class="bx bx-broadcast me-2"></i>Live Classes
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#counseling">
                <i class="bx bx-lifebuoy me-2"></i>Student Counseling
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="counseling">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/counseling/sessions') }}">
                            <i class="bx bx-conversation me-2"></i>Counseling Sessions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/counseling/career') }}">
                            <i class="bx bx-target-lock me-2"></i>Career Guidance
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/counseling/psychology') }}">
                            <i class="bx bx-heart me-2"></i>Psychological Support
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#health">
                <i class="bx bx-heart me-2"></i>Health & Medical
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="health">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/health/records') }}">
                            <i class="bx bx-file me-2"></i>Health Records
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/health/visits') }}">
                            <i class="bx bx-plus-medical me-2"></i>Medical Visits
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/health/vaccination') }}">
                            <i class="bx bx-shield me-2"></i>Vaccination Records
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/health/alerts') }}">
                            <i class="bx bx-alarm me-2"></i>Health Alerts
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#visitors">
                <i class="bx bx-vcard me-2"></i>Visitor Management
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="visitors">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/visitors/log') }}">
                            <i class="bx bx-list-ul me-2"></i>Visitor Log
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/visitors/appointments') }}">
                            <i class="bx bx-calendar-plus me-2"></i>Appointment Scheduler
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/visitors/gatepass') }}">
                            <i class="bx bx-key me-2"></i>Gate Pass
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#settings">
                <i class="bx bx-slider me-2"></i>Admin Settings
                <i class="bx bx-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="settings">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/settings/profile') }}">
                            <i class="bx bx-user-circle me-2"></i>Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/settings/password') }}">
                            <i class="bx bx-lock-open me-2"></i>Change Password
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/settings/notify') }}">
                            <i class="bx bx-bell me-2"></i>Notifications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/settings/theme') }}">
                            <i class="bx bx-paint me-2"></i>Theme
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/settings/canteen') }}">
                            <i class="bx bx-restaurant me-2"></i>Canteen Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/settings/transport') }}">
                            <i class="bx bx-bus me-2"></i>Transport
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/settings/inventory') }}">
                            <i class="bx bx-package me-2"></i>Inventory
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/settings/logs') }}">
                            <i class="bx bx-history me-2"></i>Activity Logs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('admin/settings/reports') }}">
                            <i class="bx bx-bar-chart-alt-2 me-2"></i>Reports
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        
        
        <li class="nav-item mt-3">
            <a class="nav-link" href="{{ route('admin.logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bx bx-log-out me-2"></i>Logout
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Clear all active states
  function clearAllActiveStates() {
    const allMenuLinks = document.querySelectorAll('.menu-link, .submenu-link');
    allMenuLinks.forEach(link => {
      link.classList.remove('active');
    });
  }
  
  // Save active menu to localStorage
  function saveActiveMenu(activeLink) {
    const href = activeLink.getAttribute('href');
    const isSubmenu = activeLink.classList.contains('submenu-link');
    
    localStorage.setItem('activeMenu', JSON.stringify({
      href: href,
      isSubmenu: isSubmenu,
      timestamp: Date.now()
    }));
  }
  
  // Load active menu from localStorage
  function loadActiveMenu() {
    const savedMenu = localStorage.getItem('activeMenu');
    if (savedMenu) {
      try {
        const menuData = JSON.parse(savedMenu);
        const allMenuLinks = document.querySelectorAll('.menu-link, .submenu-link');
        
        allMenuLinks.forEach(link => {
          const href = link.getAttribute('href');
          if (href === menuData.href) {
            link.classList.add('active');
            
            // If it's a submenu link, also activate parent
            if (link.classList.contains('submenu-link')) {
              const parentMenu = link.closest('.menu-item');
              if (parentMenu) {
                const parentLink = parentMenu.querySelector('.parent-link');
                const submenu = parentMenu.querySelector('.submenu');
                
                if (parentLink) {
                  parentLink.classList.add('active');
                }
                if (submenu) {
                  submenu.classList.add('show');
                }
              }
            }
          }
        });
      } catch (e) {
        console.log('Error loading saved menu:', e);
      }
    }
  }
  
  // Set active menu based on current URL (fallback)
  function setActiveMenuByURL() {
    const currentPath = window.location.pathname;
    const currentUrl = window.location.href;
    
    // Find all menu links
    const allMenuLinks = document.querySelectorAll('.menu-link, .submenu-link');
    
    allMenuLinks.forEach(link => {
      const href = link.getAttribute('href');
      
      if (href && href !== '#' && href !== 'javascript:void(0);') {
        // Check if current URL matches this link
        if (currentUrl.includes(href) || currentPath.includes(href.replace(window.location.origin, ''))) {
          link.classList.add('active');
          
          // If it's a submenu link, also activate parent
          if (link.classList.contains('submenu-link')) {
            const parentMenu = link.closest('.menu-item');
            if (parentMenu) {
              const parentLink = parentMenu.querySelector('.parent-link');
              const submenu = parentMenu.querySelector('.submenu');
              
              if (parentLink) {
                parentLink.classList.add('active');
              }
              if (submenu) {
                submenu.classList.add('show');
              }
            }
          }
        }
      }
    });
  }
  
  // Open all submenus
  function openAllSubmenus() {
    const allSubmenus = document.querySelectorAll('.submenu');
    allSubmenus.forEach(submenu => {
      submenu.classList.add('show');
    });
  }
  
  // Add click listeners
  function addClickListeners() {
    const allMenuLinks = document.querySelectorAll('.menu-link, .submenu-link');
    
    allMenuLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        // Clear all active states
        clearAllActiveStates();
        
        // Add active to clicked link
        this.classList.add('active');
        
        // Save to localStorage
        saveActiveMenu(this);
        
        // If it's a submenu link, also activate parent
        if (this.classList.contains('submenu-link')) {
          const parentMenu = this.closest('.menu-item');
          if (parentMenu) {
            const parentLink = parentMenu.querySelector('.parent-link');
            const submenu = parentMenu.querySelector('.submenu');
            
            if (parentLink) {
              parentLink.classList.add('active');
            }
            if (submenu) {
              submenu.classList.add('show');
            }
          }
        }
      });
    });
  }
  
  // Initialize
  openAllSubmenus();
  
  // Try to load saved menu first, then fallback to URL-based detection
  const savedMenu = localStorage.getItem('activeMenu');
  if (savedMenu) {
    clearAllActiveStates();
    loadActiveMenu();
  } else {
    setActiveMenuByURL();
  }
  
  addClickListeners();
});
</script>