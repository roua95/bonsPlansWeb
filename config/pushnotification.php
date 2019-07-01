<?php

return [
  'gcm' => [
      'priority' => 'normal',
      'dry_run' => false,
      'apiKey' => 'My_ApiKey',
  ],
  'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'My_ApiKey',
  ],
  'apn' => [
      'certificate' => __DIR__ . '/iosCertificates/PushCert.pem',
      'passPhrase' => '1234', //Optional
      'passFile' => __DIR__ . '/Desktop/PushKey.pem', //Optional
      'dry_run' => true
  ]
];