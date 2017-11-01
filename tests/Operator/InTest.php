<?php
namespace Tests\SHJQLParser\Operator;

use PHPUnit\Framework\TestCase;
use SHJQLParser as Lib;

class InTest extends TestCase
{
    /** @var Lib\Operator\in */
    private $in;

    public function setUp()
    {
        $this->in = new Lib\Operator\in();
    }

    /**
     * @dataProvider getCaseRegExDataProvider
     */
    public function testRegEx($query, $expects)
    {
        $regex = $this->in->getCaseRegEx();

        $this->assertTrue(preg_match('/' . $regex . '/i', $query, $result) > 0);
        $this->assertEquals([$expects], $result);
    }

    public function getCaseRegExDataProvider()
    {
        return [
            [
                'handle in ("simon-hayre@simon_hayre.co.uk")',
                'handle in ("simon-hayre@simon_hayre.co.uk")',
            ], [
                'handle in (simon-hayre@simon_hayre.co.uk)',
                'handle in (simon-hayre@simon_hayre.co.uk)',
            ], [
                'handle in ("simon-hayre@simon hayre.co.uk")',
                'handle in ("simon-hayre@simon hayre.co.uk")',
            ], [
                'handle in ("simon-hayre@simon_hayre.co.uk" "second Result")',
                'handle in ("simon-hayre@simon_hayre.co.uk" "second Result")',
            ],
        ];
    }

    /**
     * @dataProvider isCaseDataProvider
     */
    public function testIsCase($query, $expects)
    {
        $this->assertEquals($expects, $this->in->isCase($query));
    }

    public function isCaseDataProvider()
    {
        return [
            [
                'handle in ("simon-hayre@simon_hayre.co.uk")',
                true,
            ], [
                'handle in (simon-hayre@simon_hayre.co.uk)',
                true,
            ], [
                'handle in ("simon-hayre@simon hayre.co.uk")',
                true,
            ], [
                'handle in ("simon-hayre@simon_hayre.co.uk" "second Result")',
                true,
            ],
        ];
    }

    /**
     * @dataProvider createFilterDataProvider
     */
    public function testCreateFilter($query, $expects)
    {
        $this->assertEquals($expects, $this->in->createFilter($query));
    }

    public function createFilterDataProvider()
    {
        return [
            [
                'handle in ("simon-hayre@simon_hayre.co.uk")',
                (new \SHJQLParser\Filter\KeyInValues())
                    ->setKey('handle')
                    ->setValues(
                        new \SHJQLParser\Filter\ValueCollection(['simon-hayre@simon_hayre.co.uk'])
                    ),
            ], [
                'handle in (simon-hayre@simon_hayre.co.uk)',
                (new \SHJQLParser\Filter\KeyInValues())
                    ->setKey('handle')
                    ->setValues(
                        new \SHJQLParser\Filter\ValueCollection(['simon-hayre@simon_hayre.co.uk'])
                    ),
            ], [
                'handle in ("simon-hayre@simon hayre.co.uk")',
                (new \SHJQLParser\Filter\KeyInValues())
                    ->setKey('handle')
                    ->setValues(
                        new \SHJQLParser\Filter\ValueCollection(['simon-hayre@simon hayre.co.uk'])
                    ),
            ], [
                'handle in ("simon-hayre@simon_hayre.co.uk" "second Result")',
                (new \SHJQLParser\Filter\KeyInValues())
                    ->setKey('handle')
                    ->setValues(
                        new \SHJQLParser\Filter\ValueCollection(['simon-hayre@simon_hayre.co.uk', 'second Result'])
                    ),
            ],
        ];
    }
}
