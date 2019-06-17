<?php
return array(
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'from' => array('address' => 'app.mailing.test@gmail.com', 'name' => 'amit'),
    'encryption' => 'tls',
    'username' => 'app.mailing.test@gmail.com',
    'password' => 'mailfortest',
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => false,

);
