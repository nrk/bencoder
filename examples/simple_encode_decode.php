<?php

require 'shared.php';

use Nrk\Bencode\Bencoder;

$structure = array(
    'nickname' => 'nrk',
    'letters'  => array('n', 'r', 'k'),
    'length'   => 3,
);

$encoded = BEncoder::serialize($structure);
$decoded = BEncoder::unserialize($encoded);

var_dump($encoded);
var_dump($decoded);
