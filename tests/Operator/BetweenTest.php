<?php
namespace Tests\SHJQLParser\Operator;

use PHPUnit\Framework\TestCase;
use SHJQLParser as Lib;

class BetweenTest extends TestCase
{
    /** @var Lib\Operator\Between */
    private $between;

    public function setUp()
    {
        $this->between = new Lib\Operator\Between();
    }

    /**
     * @dataProvider getCaseRegExDataProvider
     */
    public function testRegEx($query, $expects)
    {
        $regex = $this->between->getCaseRegEx();

        $this->assertTrue(preg_match('/' . $regex . '/i', $query, $result) > 0);
        $this->assertEquals([$expects], $result);
    }

    public function getCaseRegExDataProvider()
    {
        return [
            [
                'handle between "01/01/2017" and "02/01/2017"',
                'handle between "01/01/2017" and "02/01/2017"',
            ], [
                'handle between "2017-01-01" and "2017-01-02"',
                'handle between "2017-01-01" and "2017-01-02"',
            ], [
                'handle between "2017-01-01 00:00:00" and "2017-01-02 23:59:59"',
                'handle between "2017-01-01 00:00:00" and "2017-01-02 23:59:59"',
            ],
        ];
    }

    /**
     * @dataProvider isCaseDataProvider
     */
    public function testIsCase($query, $expects)
    {
        $this->assertEquals($expects, $this->between->isCase($query));
    }

    public function isCaseDataProvider()
    {
        return [
            [
                'handle between "01/01/2017" and "02/01/2017"',
                true,
            ],
        ];
    }

    /**
     * @dataProvider createFilterDataProvider
     */
    public function testCreateFilter($query, $expects)
    {
        $this->assertEquals($expects, $this->between->createFilter($query));
    }

    public function createFilterDataProvider()
    {
        return [
            [
                'handle between "01/01/2017" and "02/01/2017"',
                (new \SHJQLParser\Filter\KeyBetweenValue())
                    ->setKey('handle')
                    ->setValues(
                        new \SHJQLParser\Filter\ValueCollection(['01/01/2017', '02/01/2017'])
                    ),
            ],
        ];
    }
}
