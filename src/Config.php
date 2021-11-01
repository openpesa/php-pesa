<?php



return [

    "airtelmoney"=>[
        "live"=>[
            "BASE_DOMAIN"=>"https://openapi.airtel.africa",
            "client_id"=>"",
            "client_secret"=>"",
            "grant_type"=>""
        ],

        "transact_type"=>[
            "collection"=>[
                'name' => 'Collection C2B',
                'url' => "/merchant/v1/payments/",
                'requred_param'=>[

                ]
            ],
            "desbusement"=>[
                "name"=>"Desbusement",
                "url" => "/standard/v1/disbursements/",
                'requred_param'=>[

                    ]
            ],
            "refund"=>[
                "name"=>"Business 2 payee",
                "url"=>"/merchant/v1/payments/",
                'requred_param'=>[

                    ]
            ],
            "status"=>[
                "name"=>"Transaction status",
                "url"=>"/standard/v1/payments/",  //replace id with transaction id
                'requred_param'=>[

                    ]
            ]
        ],

        "sandbox"=>[
            "BASE_DOMAIN"=>"https://openapiuat.airtel.africa",
            "client_id"=>"",
            "client_secret"=>"",
            "grant_type"=>"",
            "transact_type"=>[
                "collection"=>[
                    'name' => 'Collection C2B',
                    'url' => "/merchant/v1/payments/",
                    'requred_param'=>[

                    ]
                ],
                "desbusement"=>[
                    "name"=>"Desbusement",
                    "url" => "/standard/v1/disbursements/",
                    'requred_param'=>[

                        ]
                ],
                "refund"=>[
                    "name"=>"Business 2 payee",
                    "url"=>"/merchant/v1/payments/",
                    'requred_param'=>[

                        ]
                ],
                "query"=>[
                    "name"=>"Transaction status",
                    "url"=>"/standard/v1/payments/",  //replace id with transaction id
                    'requred_param'=>[

                        ]
                ]
            ],
        ],



    ],

    "mpesa"=>[
        "live"=>[
            "BASE_DOMAIN"=>"https://openapi.m-pesa.com",
            "api_key"=>"",
            "public_key"=>""
        ],

        "sandbox"=>[
            "BASE_DOMAIN"=>"https://openapi.m-pesa.com",
            "api_key"=>"",
            "public_key"=>""
        ],


        "transact_type" => [
            'c2b' => [
                'name' => 'Consumer 2 Business',
                'url' => "c2bPayment/singleStage/",
                'encryptSessionKey' => true,
                'rules' => []
            ],
            'b2c' => [
                'name' => 'Business 2 Consumer',
                'url' => "b2cPayment/singleStage/",
                'encryptSessionKey' => true,
                'rules' => []
            ],

            'b2b' => [
                'name' => 'Business 2 Business',
                'url' => "b2bPayment/",
                'encryptSessionKey' => true,
                'rules' => []
            ],
            'rt' => [
                'name' => 'Reverse Transaction',
                'url' => "reversal/",
                'encryptSessionKey' => true,
                'rules' => []
            ],
            'query' => [
                'name' => 'Query Transaction Status',
                'url' => "queryTransactionStatus/",
                'encryptSessionKey' => true,
                'rules' => []
            ],
            'ddc' => [
                'name' => 'Direct Debits create',
                'url' => "directDebitCreation/",
                'encryptSessionKey' => true,
                'rules' => []
            ],
            'ddp' => [
                'name' => 'Direct Debits payment',
                'url' => "directDebitPayment/",
                'encryptSessionKey' => false,
            ]

        ]

    ],

    "tigopesa"=>[

    ],
    "ezypesa"=>[

    ],

    "halopesa"=>[

    ]


];
