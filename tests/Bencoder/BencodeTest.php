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
     * @group serialization
     */
    public function testIntegerSerialization()
    {
        $this->assertSame('i0e', B::serialize(0));
        $this->assertSame('i1e', B::serialize(1));

        $this->assertSame('i42e', B::serialize(42));
        $this->assertSame('i-42e', B::serialize(-42));

        $this->assertSame('i2147483647e', B::serialize(2147483647));
        $this->assertSame('i-2147483647e', B::serialize(-2147483647));
    }

    /**
     * @group deserialization
     */
    public function testIntegerDeserialization()
    {
        $this->assertSame(0, B::unserialize('i0e'));
        $this->assertSame(1, B::unserialize('i1e'));

        $this->assertSame(42, B::unserialize('i42e'));
        $this->assertSame(-42, B::unserialize('i-42e'));

        $this->assertSame(2147483647, B::unserialize('i2147483647e'));
        $this->assertSame(-2147483647, B::unserialize('i-2147483647e'));
    }

    /**
     * @group serialization
     * @expectedException Bencoder\SerializationException
     * @expectedExceptionMessage Invalid type for serialization: double
     */
    public function testIntegerSerializationFailsOnBigNumbers()
    {
        B::serialize(2147483650);
    }

    /**
     * @group deserialization
     * @expectedException Bencoder\DeserializationException
     * @expectedExceptionMessage Invalid integer: -2147483650
     */
    public function testIntegerDeserializationFailsOnBigNumbers()
    {
        B::unserialize('i-2147483650e');
    }

    /**
     * @group serialization
     */
    public function testStringSerialization()
    {
        $this->assertSame('0:', B::serialize(''));
        $this->assertSame('2:  ', B::serialize('  '));
        $this->assertSame('3:'.chr(0).chr(127).chr(255), B::serialize(chr(0).chr(127).chr(255)));

        $this->assertSame('15:This is a test.', B::serialize('This is a test.'));
        $this->assertSame('9:123456789', B::serialize('123456789'));
    }

    /**
     * @group deserialization
     */
    public function testStringDeserialization()
    {
        $this->assertSame('', B::unserialize('0:'));
        $this->assertSame('  ', B::unserialize('2:  '));
        $this->assertSame(chr(0).chr(127).chr(255), B::unserialize('3:'.chr(0).chr(127).chr(255)));

        $this->assertSame('This is a test.', B::unserialize('15:This is a test.'));
        $this->assertSame('123456789', B::unserialize('9:123456789'));
    }

    /**
     * @group serialization
     */
    public function testArraySerialization()
    {
        $this->assertSame('le', B::serialize(array()));

        $this->assertSame('li1ei2ei3ee', B::serialize(array(1,2,3)));
        $this->assertSame('l1:a1:b1:ce', B::serialize(array('a','b','c')));
        $this->assertSame('l1:ai1e1:bi2e1:ci3ee', B::serialize(array('a',1,'b',2,'c',3)));

        $this->assertSame('llee', B::serialize(array(array())));
        $this->assertSame('ll1:ai1eel1:bi2eel1:ci3eee', B::serialize(array(array('a',1),array('b',2),array('c',3))));
    }

    /**
     * @group deserialization
     */
    public function testArrayDeserialization()
    {
        $this->assertSame(array(), B::unserialize('le'));

        $this->assertSame(array(1,2,3), B::unserialize('li1ei2ei3ee'));
        $this->assertSame(array('a','b','c'), B::unserialize('l1:a1:b1:ce'));
        $this->assertSame(array('a',1,'b',2,'c',3), B::unserialize('l1:ai1e1:bi2e1:ci3ee'));

        $this->assertSame(array(array()), B::unserialize('llee'));
        $this->assertSame(array(array('a',1),array('b',2),array('c',3)), B::unserialize('ll1:ai1eel1:bi2eel1:ci3eee'));
    }

    /**
     * @group serialization
     */
    public function testNamedArraySerialization()
    {
        $this->assertSame('d1:ai1e1:bi2e1:ci3ee', B::serialize(array('c'=>3,'a'=>1,'b'=>2)));
        $this->assertSame('d1:0i2e1:ai1e1:ci3ee', B::serialize(array('a'=>1, 2,'c'=>3)));

        $this->assertSame('d1:al1:ni1ee1:bl1:ni2eee', B::serialize(array('a'=>array('n',1),'b'=>array('n',2))));
        $this->assertSame('d1:ad1:ni1ee1:bd1:ni2eee', B::serialize(array('a'=>array('n'=>1),'b'=>array('n'=>2))));
    }

    /**
     * @group deserialization
     */
    public function testNamedArrayDeserialization()
    {
        $this->assertSame(array(), B::unserialize('de'));

        $this->assertSame(array('a'=>1,'b'=>2,'c'=>3), B::unserialize('d1:ai1e1:bi2e1:ci3ee'));
        $this->assertSame(array(2,'a'=>1,'c'=>3), B::unserialize('d1:0i2e1:ai1e1:ci3ee'));

        $this->assertSame(array('a'=>array('n',1),'b'=>array('n',2)), B::unserialize('d1:al1:ni1ee1:bl1:ni2eee'));
        $this->assertSame(array('a'=>array('n'=>1),'b'=>array('n'=>2)), B::unserialize('d1:ad1:ni1ee1:bd1:ni2eee'));
    }

    /**
     * @group deserialization
     */
    public function testDeserializationAllowsIntegerKeysForDictionaries()
    {
        $this->assertSame(array(1 => 'a'), B::unserialize('di1e1:ae'));
    }
    /**
     * @group serialization
     */
    public function testBooleanSerialization()
    {
        $this->assertSame('i1e', B::serialize(true));
        $this->assertSame('i0e', B::serialize(false));
    }

    /**
     * @group serialization
     * @expectedException Bencoder\SerializationException
     * @expectedExceptionMessage Invalid type for serialization: NULL
     */
    public function testIntegerSerializationFailsOnNull()
    {
        B::serialize(null);
    }

    /**
     * @group serialization
     * @expectedException Bencoder\SerializationException
     * @expectedExceptionMessage Invalid type for serialization: object
     */
    public function testSerializationFailsOnNotSupportedObjectTypes()
    {
        B::serialize(new \stdClass());
    }

    /**
     * @group deserialization
     */
    public function testDeserializationHandlesSequenceOfEmptyStrings()
    {
        $this->assertSame(array('','',''), B::unserialize('l0:0:0:e'));
    }

    /**
     * @group deserialization
     * @expectedException Bencoder\DeserializationException
     * @expectedExceptionMessage Invalid integer: 1.23
     */
    public function testDeserializationFailsOnDoubleValues()
    {
        B::unserialize('i1.23e');
    }

    /**
     * @group deserialization
     * @expectedException Bencoder\DeserializationException
     * @expectedExceptionMessage Unknown prefix: a
     */
    public function testDeserializationFailsOnInvalidBuffer()
    {
        B::unserialize('abc');
    }

    /**
     * @group serialization
     */
    public function testComplexSerialization()
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

        $this->assertSame($expected, B::serialize($tree));
    }

    /**
     * @group deserialization
     */
    public function testComplexDeserialization()
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

        $this->assertSame($tree, B::unserialize($buffer));
    }

    /**
     * @group deserialization
     */
    public function testDeserializationFromFile()
    {
        $torrent = B::unserializeFromFile(__DIR__.'/../../examples/xubuntu-10.10-alternate-amd64.iso.torrent');

        $this->assertInternalType('array', $torrent);
        $this->assertInternalType('array', $torrent['info']);
        $this->assertSame('xubuntu-10.10-alternate-amd64.iso', $torrent['info']['name']);
        $this->assertSame(699566080 ,$torrent['info']['length']);
    }

    /**
     * @group serialization
     */
    public function testConversionFromJSON()
    {
        $json = '{"a":1,"b":2,"list":[1,2,"foo","bar"]}';
        $bencode = 'd1:ai1e1:bi2e4:listli1ei2e3:foo3:baree';

        $this->assertSame($bencode, B::convertFromJSON($json));
    }

    /**
     * @group deserialization
     */
    public function testConversionToJSON()
    {
        $json = '{"a":1,"b":2,"list":[1,2,"foo","bar"]}';
        $bencode = 'd1:ai1e1:bi2e4:listli1ei2e3:foo3:baree';

        $this->assertSame($json, B::convertToJSON($bencode));
    }
}
