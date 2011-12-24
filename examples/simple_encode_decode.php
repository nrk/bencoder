<?php

/*
 * This file is part of the Bencoder package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__.'/../autoload.php';

use Bencoder\Bencode;

$structure = array(
    'nickname' => 'nrk',
    'letters'  => array('n', 'r', 'k'),
    'length'   => 3,
);

$encoded = Bencode::encode($structure);
$decoded = Bencode::decode($encoded);

var_dump($encoded);
var_dump($decoded);
