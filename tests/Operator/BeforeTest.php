<?php
namespace Tests\SHJQLParser\Operator;

use PHPUnit\Framework\TestCase;
use SHJQLParser as Lib;

class BeforeTest extends TestCase
{
    /** @var Lib\Operator\Before */
    private $before;

    public function setUp()
    {
        $this->before = new Lib\Operator\Before();
    }

    /**
     * @dataProvider getCaseRegExDataProvider
     */
    public function testRegEx($query, $expects)
    {
        $regex = $this->before->getCaseRegEx();

        $this->assertTrue(preg_match('/' . $regex . '/i', $query, $result) > 0);
        $this->assertEquals([$expects], $result);
    }

    public function getCaseRegExDataProvider()
    {
        return [
            [
                'handle before "01/01/2017"',
                'handle before "01/01/2017"',
            ], [
                'handle before "2017-01-01"',
                'handle before "2017-01-01"',
            ], [
                'handle before "2017-01-01 00:00:00"',
                'handle before "2017-01-01 00:00:00"',
            ],
        ];
    }

    /**
     * @dataProvider isCaseDataProvider
     */
    public function testIsCase($query, $expects)
    {
        $this->assertEquals($expects, $this->before->isCase($query));
    }

    public function isCaseDataProvider()
    {
        return [
            [
                'handle before "01/01/2017"',
                true,
            ],
        ];
    }

    /**
     * @dataProvider createFilterDataProvider
     */
    public function testCreateFilter($query, $expects)
    {
        $this->assertEquals($expects, $this->before->createFilter($query));
    }

    public function createFilterDataProvider()
    {
        return [
            [
                'handle before "01/01/2017"',
                (new \SHJQLParser\Filter\KeyBeforeValue())
                    ->setKey('handle')
                    ->setValue('01/01/2017'),
            ],
        ];
    }
}
