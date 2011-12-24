<?php

/*
 * This file is part of the Bencoder package.
 *
 * (c) Daniele Alessandri <suppakilla@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bencoder;

use Bencoder\Bencode as B;

/**
 *
 */
class BencodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group encoding
     */
    public function testIntegerEncoding()
    {
        $this->assertSame('i0e', B::encode(0));
        $this->assertSame('i1e', B::encode(1));

        $this->assertSame('i42e', B::encode(42));
        $this->assertSame('i-42e', B::encode(-42));

        $this->assertSame('i2147483647e', B::encode(2147483647));
        $this->assertSame('i-2147483647e', B::encode(-2147483647));
    }

    /**
     * @group decoding
     */
    public function testIntegerDecoding()
    {
        $this->assertSame(0, B::decode('i0e'));
        $this->assertSame(1, B::decode('i1e'));

        $this->assertSame(42, B::decode('i42e'));
        $this->assertSame(-42, B::decode('i-42e'));

        $this->assertSame(2147483647, B::decode('i2147483647e'));
        $this->assertSame(-2147483647, B::decode('i-2147483647e'));
    }

    /**
     * @group encoding
     * @expectedException Bencoder\EncodingException
     * @expectedExceptionMessage Invalid type for encoding: double
     */
    public function testIntegerEncodingFailsOnBigNumbers()
    {
        B::encode(2147483650);
    }

    /**
     * @group decoding
     * @expectedException Bencoder\DecodingException
     * @expectedExceptionMessage Invalid integer: -2147483650
     */
    public function testIntegerDecodingFailsOnBigNumbers()
    {
        B::decode('i-2147483650e');
    }

    /**
     * @group encoding
     */
    public function testStringEncoding()
    {
        $this->assertSame('0:', B::encode(''));
        $this->assertSame('2:  ', B::encode('  '));
        $this->assertSame('3:'.chr(0).chr(127).chr(255), B::encode(chr(0).chr(127).chr(255)));

        $this->assertSame('15:This is a test.', B::encode('This is a test.'));
        $this->assertSame('9:123456789', B::encode('123456789'));
    }

    /**
     * @group decoding
     */
    public function testStringDecoding()
    {
        $this->assertSame('', B::decode('0:'));
        $this->assertSame('  ', B::decode('2:  '));
        $this->assertSame(chr(0).chr(127).chr(255), B::decode('3:'.chr(0).chr(127).chr(255)));

        $this->assertSame('This is a test.', B::decode('15:This is a test.'));
        $this->assertSame('123456789', B::decode('9:123456789'));
    }

    /**
     * @group encoding
     */
    public function testArrayEncoding()
    {
        $this->assertSame('le', B::encode(array()));

        $this->assertSame('li1ei2ei3ee', B::encode(array(1,2,3)));
        $this->assertSame('l1:a1:b1:ce', B::encode(array('a','b','c')));
        $this->assertSame('l1:ai1e1:bi2e1:ci3ee', B::encode(array('a',1,'b',2,'c',3)));

        $this->assertSame('llee', B::encode(array(array())));
        $this->assertSame('ll1:ai1eel1:bi2eel1:ci3eee', B::encode(array(array('a',1),array('b',2),array('c',3))));
    }

    /**
     * @group decoding
     */
    public function testArrayDecoding()
    {
        $this->assertSame(array(), B::decode('le'));

        $this->assertSame(array(1,2,3), B::decode('li1ei2ei3ee'));
        $this->assertSame(array('a','b','c'), B::decode('l1:a1:b1:ce'));
        $this->assertSame(array('a',1,'b',2,'c',3), B::decode('l1:ai1e1:bi2e1:ci3ee'));

        $this->assertSame(array(array()), B::decode('llee'));
        $this->assertSame(array(array('a',1),array('b',2),array('c',3)), B::decode('ll1:ai1eel1:bi2eel1:ci3eee'));
    }

    /**
     * @group encoding
     */
    public function testNamedArrayEncoding()
    {
        $this->assertSame('d1:ai1e1:bi2e1:ci3ee', B::encode(array('c'=>3,'a'=>1,'b'=>2)));
        $this->assertSame('d1:0i2e1:ai1e1:ci3ee', B::encode(array('a'=>1, 2,'c'=>3)));

        $this->assertSame('d1:al1:ni1ee1:bl1:ni2eee', B::encode(array('a'=>array('n',1),'b'=>array('n',2))));
        $this->assertSame('d1:ad1:ni1ee1:bd1:ni2eee', B::encode(array('a'=>array('n'=>1),'b'=>array('n'=>2))));
    }

    /**
     * @group decoding
     */
    public function testNamedArrayDecoding()
    {
        $this->assertSame(array(), B::decode('de'));

        $this->assertSame(array('a'=>1,'b'=>2,'c'=>3), B::decode('d1:ai1e1:bi2e1:ci3ee'));
        $this->assertSame(array(2,'a'=>1,'c'=>3), B::decode('d1:0i2e1:ai1e1:ci3ee'));

        $this->assertSame(array('a'=>array('n',1),'b'=>array('n',2)), B::decode('d1:al1:ni1ee1:bl1:ni2eee'));
        $this->assertSame(array('a'=>array('n'=>1),'b'=>array('n'=>2)), B::decode('d1:ad1:ni1ee1:bd1:ni2eee'));
    }

    /**
     * @group decoding
     */
    public function testDecoderAllowsIntegerKeysForDictionaries()
    {
        $this->assertSame(array(1 => 'a'), B::decode('di1e1:ae'));
    }
    /**
     * @group encoding
     */
    public function testBooleanEncoding()
    {
        $this->assertSame('i1e', B::encode(true));
        $this->assertSame('i0e', B::encode(false));
    }

    /**
     * @group encoding
     * @expectedException Bencoder\EncodingException
     * @expectedExceptionMessage Invalid type for encoding: NULL
     */
    public function testIntegerEncodingFailsOnNull()
    {
        B::encode(null);
    }

    /**
     * @group encoding
     * @expectedException Bencoder\EncodingException
     * @expectedExceptionMessage Invalid type for encoding: object
     */
    public function testEncodingFailsOnNotSupportedObjectTypes()
    {
        B::encode(new \stdClass());
    }

    /**
     * @group decoding
     */
    public function testDecoderHandlesSequenceOfEmptyStrings()
    {
        $this->assertSame(array('','',''), B::decode('l0:0:0:e'));
    }

    /**
     * @group decoding
     * @expectedException Bencoder\DecodingException
     * @expectedExceptionMessage Invalid integer: 1.23
     */
    public function testDecodingFailsOnDoubleValues()
    {
        B::decode('i1.23e');
    }

    /**
     * @group decoding
     * @expectedException Bencoder\DecodingException
     * @expectedExceptionMessage Unknown prefix: a
     */
    public function testDecodingFailsOnInvalidBuffer()
    {
        B::decode('abc');
    }

    /**
     * @group encoding
     */
    public function testComplexEncoding()
    {
        $tree = array(
            'letters' => array('a','b','c','d'),
            'numbers' => array(1,2,3,4),
            'mixed' => array('a'=>1,'b'=>2,'c'=>3,'d'=>4),
            'empty' => '',
            'empty_list' => array(),
        );
        $tree['subtree'] = $tree;

        $expected = 'd5:empty0:10:empty_listle7:lettersl1:a1:b1:c1:de5:mixedd1:ai1e1:bi2e1:ci3e1:di4ee7:numbersli1ei2ei3ei4ee7:subtreed5:empty0:10:empty_listle7:lettersl1:a1:b1:c1:de5:mixedd1:ai1e1:bi2e1:ci3e1:di4ee7:numbersli1ei2ei3ei4eeee';

        $this->assertSame($expected, B::encode($tree));
    }

    /**
     * @group decoding
     */
    public function testComplexDecoding()
    {
        $tree = array(
            'empty' => '',
            'empty_list' => array(),
            'letters' => array('a','b','c','d'),
            'mixed' => array('a'=>1,'b'=>2,'c'=>3,'d'=>4),
            'numbers' => array(1,2,3,4),
        );
        $tree['subtree'] = $tree;

        $buffer = 'd5:empty0:10:empty_listle7:lettersl1:a1:b1:c1:de5:mixedd1:ai1e1:bi2e1:ci3e1:di4ee7:numbersli1ei2ei3ei4ee7:subtreed5:empty0:10:empty_listle7:lettersl1:a1:b1:c1:de5:mixedd1:ai1e1:bi2e1:ci3e1:di4ee7:numbersli1ei2ei3ei4eeee';

        $this->assertSame($tree, B::decode($buffer));
    }

    /**
     * @group decoding
     */
    public function testDecodingFromFile()
    {
        $torrent = B::decodeFromFile(__DIR__.'/../../examples/xubuntu-10.10-alternate-amd64.iso.torrent');

        $this->assertInternalType('array', $torrent);
        $this->assertInternalType('array', $torrent['info']);
        $this->assertSame('xubuntu-10.10-alternate-amd64.iso', $torrent['info']['name']);
        $this->assertSame(699566080 ,$torrent['info']['length']);
    }

    /**
     * @group encoding
     */
    public function testConversionFromJSON()
    {
        $json = '{"a":1,"b":2,"list":[1,2,"foo","bar"]}';
        $bencode = 'd1:ai1e1:bi2e4:listli1ei2e3:foo3:baree';

        $this->assertSame($bencode, B::convertFromJSON($json));
    }

    /**
     * @group decoding
     */
    public function testConversionToJSON()
    {
        $json = '{"a":1,"b":2,"list":[1,2,"foo","bar"]}';
        $bencode = 'd1:ai1e1:bi2e4:listli1ei2e3:foo3:baree';

        $this->assertSame($json, B::convertToJSON($bencode));
    }
}
