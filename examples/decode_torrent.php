<?php

require 'shared.php';

use Bencoder\Bencode;

$bencode = file_get_contents(__DIR__.'/../examples/xubuntu-10.10-alternate-amd64.iso.torrent');
$torrent = Bencode::unserialize($bencode);

// Remove info.pieces as it would print too much binary garbage.
unset($torrent['info']['pieces']);

var_dump($torrent);
