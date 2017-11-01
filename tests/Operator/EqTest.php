<?php
namespace Tests\SHJQLParser\Operator;

use PHPUnit\Framework\TestCase;
use SHJQLParser as Lib;

class EqTest extends TestCase
{
    /** @var Lib\Operator\eq */
    private $eq;

    public function setUp()
    {
        $this->eq = new Lib\Operator\eq();
    }

    /**
     * @dataProvider getCaseRegExDataProvider
     */
    public function testRegEx($query, $expects)
    {
        $regex = $this->eq->getCaseRegEx();

        $this->assertTrue(preg_match('/' . $regex . '/i', $query, $result) > 0);
        $this->assertEquals([$expects], $result);
    }

    public function getCaseRegExDataProvider()
    {
        return [
            [
                'handle = "simon-hayre@simon_hayre.co.uk"',
                'handle = "simon-hayre@simon_hayre.co.uk"',
            ], [
                'handle = simon-hayre@simon_hayre.co.uk',
                'handle = simon-hayre@simon_hayre.co.uk',
            ], [
                'handle = "simon-hayre@simon hayre.co.uk"',
                'handle = "simon-hayre@simon hayre.co.uk"',
            ],
        ];
    }

    /**
     * @dataProvider isCaseDataProvider
     */
    public function testIsCase($query, $expects)
    {
        $this->assertEquals($expects, $this->eq->isCase($query));
    }

    public function isCaseDataProvider()
    {
        return [
            [
                'handle = "simon-hayre@simon_hayre.co.uk"',
                true,
            ], [
                'handle = simon-hayre@simon_hayre.co.uk',
                true,
            ], [
                'handle = "simon-hayre@simon hayre.co.uk"',
                true,
            ],
        ];
    }

    /**
     * @dataProvider createFilterDataProvider
     */
    public function testCreateFilter($query, $expects)
    {
        $this->assertEquals($expects, $this->eq->createFilter($query));
    }

    public function createFilterDataProvider()
    {
        return [
            [
                'handle = "simon-hayre@simon_hayre.co.uk"',
                (new \SHJQLParser\Filter\KeyValue())
                    ->setKey('handle')
                    ->setValue('simon-hayre@simon_hayre.co.uk'),
            ], [
                'handle = simon-hayre@simon_hayre.co.uk',
                (new \SHJQLParser\Filter\KeyValue())
                    ->setKey('handle')
                    ->setValue('simon-hayre@simon_hayre.co.uk'),
            ], [
                'handle = "simon-hayre@simon hayre.co.uk"',
                (new \SHJQLParser\Filter\KeyValue())
                    ->setKey('handle')
                    ->setValue('simon-hayre@simon hayre.co.uk'),
            ],
        ];
    }
}
