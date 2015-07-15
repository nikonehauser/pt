<?php
return [
  'common' => [
    'brand_name' => 'Help Yourself Association',
    'brand_name_short' => 'HYA',

  ],


    /* DATE FORMATS
    ---------------------------------------------*/
  'date_format_php' => [
    'default' => "m/d/Y",
    'short'   => "M. d",
    'long'    => "F d, Y",
  ],


    /* ERRORS
    ---------------------------------------------*/
  'error' => [
    'empty'  => 'Can not be empty',
    'email'  => 'Invalid email address',
    'int'    => 'Invalid integer',
    'accept' => 'Must be accepted',
    'password' => 'Invalid password',
    'password_conditions' => '5 characters or more, at least one small letter and one digit',
    'password_unequal' => 'Passwords were not equal',

    'referral_member_num' => 'Referer num does not exist',
    'age_of_18' => 'Must be 18 or older',
    'referer_paiment_outstanding' => 'The member exists but we didn´t receive the member fee yet. Unfornately you have to wait for this members full membership',

    'login' => 'Invalid login credentials'
  ],


    /* VIEWS
    ---------------------------------------------*/
  'view' => [
    'common' => [
      'navigation_links' => [
        'member' => 'Become Member',
        'projects' => 'Projects',
        'about' => 'About',
        'account' => 'Profile'
      ],
      'navigation_sublinks' => [
        'member' => [
          'index' => 'Member advertises members',
          'signup' => 'Signup',
        ],
        'projects' => [
          'index' => 'Social projects',
          '_prj_girls_schools'  => 'Schools for girls',
          '_prj_residential_home'  => 'Residential home',
          '_prj_vocational_education'  => 'Vocational education',
          '_prj_hospitals'  => 'Hospitals',
        ],
        'account' => [
          'index' => 'Information',
          'logout' => 'Logout',
        ]
      ],
      'member_login' => 'Member Login',
      'copyright_name' => 'Help Yourself Associaton',
    ],

    /* VIEWS - HOME
    ---------------------------------------------*/
    'home' => [
      'text' => [
        'Der Verein wurde für Menschen gegründet'
      ]
    ],

    /* VIEWS - MEMBER
    ---------------------------------------------*/
    'member' => [
      'btn' => [
        'signup' => 'Signup now'
      ],
      'text' => [
        'Member advertises member'
      ],

      'signup' => [
        'page_title' => 'Signup for membership',
        'form_labels' => [
          'contact' => 'Contact',
          'address' => 'Address',
          'bank_account' => 'Bank Account',
          'confirm' => 'Confirm',

          'referral_member_num' => 'Referer Member Number',
          'firstName' => 'First name',
          'lastName' => 'Last name',
          'title' => 'Title',
          'email' => 'Email (optional)',
          'age' => 'Age',
          'country' => 'Country',
          'city' => 'City',
          'country' => 'Country',
          'iban' => 'IBAN',
          'bic' => 'BIC',
          'bank_recipient' => 'Recipient',
          'password' => 'Your password',
          'password2' => 'Repeat password',

          'accept_agbs' => 'I have read and accept the terms and conditions',
          'accept_valid_country' => 'I confirm that i am NOT citizen of one of following countries:',
          'not_accepted_countries' => '',

          'submit' => 'Submit',

          'bank_account_info' => 'We will never charge your bank account. But you will receive your provisions for your referalls at this account.',
          'email_info' => 'We strongly recommend you to provide an email. It is neccessary for e.g. password reset or we will contact you if we encounter problems with your bank account. You will never receive spam or advertising emails from us.'
        ]
      ],

      'signup_success' => [
        'welcome' => 'Welcome to "Help Yourself Association".',
        'new_member_num' => 'Your member number is {num}. Please note this number.',
        'info' => 'Use it to invite new members to help them and yourself.',
      ]
    ],


    /* VIEWS - ACCOUNT
    ---------------------------------------------*/
    'account' => [
      'login' => [
        'page_title' => 'Memmber Login',
        'error' => 'Error',
        'form_labels' => [
          'login' => 'Login',

          'num' => 'Member Number',
          'pwd' => 'Password',

          'submit' => 'Submit',
        ]
      ],
      'index' => [
        'page_title' => 'Profile',
        'welcome' => 'Welcome {name}',
        'navigation_links' => [
          'index' => 'Information',
          'invoice' => 'Invoice',
          'tree' => 'Member Tree'
        ],
      ],
      'tabs' => [
        'index' => [
          'account' => 'Account',
          'your_num' => 'Your member number',
          'warning' => 'Warning!',
          'tank_you' => 'Thank You!',
          'unpaid_text' => 'We did not receive your member fee yet!',
          'paid_text' => 'We received your member fee.',
          'address' => 'Address',
          'email' => 'Email',
          'email_none' => '-none-',
          'bank_account' => 'Bank account',
          'bank_recipient' => 'Recipient Name',
          'iban' => 'IBAN (International Bank Account Number)',
          'bic' => 'BIC (Bank Identifier Code)'
        ],
        'invoice' => [
          'stats' => 'Statistics',
          'transactions' => 'Transactions',
          'oustanding_advertised_count' => 'Outstanding advertised members count',
          'advertised_member_count' => 'Advertised members count',
          'outstanding_total' => 'Total outstanding to be transferred',
          'transferred_total' => 'Total transferred',
          'transaction_reasons' => [
            'Advertised Lvl 1',
            'Advertised Lvl 2',
            'Advertised Indirect'
          ]
        ],
        'tree' => [
        ]
      ]
    ],


    /* VIEWS - PROJECTS
    ---------------------------------------------*/
    'projects' => [
      'text' => [
        'Social projects to help yourself'
      ]
    ],


    /* VIEWS - ABOUT
    ---------------------------------------------*/
    'about' => [
      'text' => [
        'Contact:'
      ]
    ]
  ]
];

?>