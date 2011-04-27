<?php

require 'shared.php';

use Bencoder\Bencode;

$structure = array(
    'nickname' => 'nrk',
    'letters'  => array('n', 'r', 'k'),
    'length'   => 3,
);

$encoded = Bencode::serialize($structure);
$decoded = Bencode::unserialize($encoded);

var_dump($encoded);
var_dump($decoded);
