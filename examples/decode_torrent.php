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

$torrent = Bencode::decodeFromFile(__DIR__.'/../examples/xubuntu-10.10-alternate-amd64.iso.torrent');

// Remove info.pieces as it would print too much binary garbage.
unset($torrent['info']['pieces']);

var_dump($torrent);
