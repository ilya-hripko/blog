<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['memcached'] = array(
    'hostname' => '127.0.0.1',
    'port'     => 11211,
    'weight'   => 1,
    'ttl'      => 60 * 60 * 24,
        //'cache_on' => true
);

define('CACHE_ON', 0);
define('CACHE_TTL', 60 * 60 * 24);

/* End of file memcached.php */
/* Location: ./system/application/config/memcached.php */