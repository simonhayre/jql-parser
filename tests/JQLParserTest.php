<?php
namespace Tests\SHJQLParser;

use PHPUnit\Framework\TestCase;
use SHJQLParser as Lib;

class JQLParserTest extends TestCase
{
    /** @var Lib\JQLParser */
    private $jqlParser;

    public function setUp()
    {
        $this->jqlParser = new Lib\JQLParser();
    }

    public function testNoFiltersAreReturnedWhenEmptyStringIsPassed()
    {
        $this->assertEquals((new Lib\Filter\FilterCollection()), $this->jqlParser->parse(''));
    }

    public function testFilterReturnsWhenSimpleSearchIsPassed()
    {
        $expectedFilterCollection =
            (new Lib\Filter\FilterCollection())
                ->add(
                    (new Lib\Filter\KeyValue())
                        ->setKey('reporter')
                        ->addValue('simonhayre')
                )
                ->add(
                    (new Lib\Filter\OrderBy())
                        ->setKey('created')
                        ->setDirection(Lib\Filter\OrderBy::DIRECTION_DESC)
                );

        $this->assertEquals(
            $expectedFilterCollection,
            $this->jqlParser->parse('reporter in (simonhayre) order by created DESC')
        );
    }

    public function testFilterReturnsWhenSimpleHasEmailAddressSearchIsPassed()
    {
        $expectedFilterCollection =
            (new Lib\Filter\FilterCollection())
                ->add(
                    (new Lib\Filter\KeyValue())
                        ->setKey('reporter')
                        ->addValue('simon-hayre@simon_hayre.co.uk')
                )
                ->add(
                    (new Lib\Filter\OrderBy())
                        ->setKey('created')
                        ->setDirection(Lib\Filter\OrderBy::DIRECTION_DESC)
                );

        $this->assertEquals(
            $expectedFilterCollection,
            $this->jqlParser->parse('reporter in (simon-hayre@simon_hayre.co.uk) order by created DESC')
        );
    }

    public function testFilterReturnsWhenSimpleSearchWithANotIsPassed()
    {
        $expectedFilterCollection =
            (new Lib\Filter\FilterCollection())
                ->add(
                    (new Lib\Filter\KeyValue())
                        ->setKey('reporter')
                        ->addValue('simonhayre')
                        ->setNot(true)
                )
                ->add(
                    (new Lib\Filter\OrderBy())
                        ->setKey('created')
                        ->setDirection(Lib\Filter\OrderBy::DIRECTION_DESC)
                );

        $this->assertEquals(
            $expectedFilterCollection,
            $this->jqlParser->parse('reporter not in (simonhayre) order by created DESC')
        );
    }

    public function testFilterReturnsWhenMultipleKeysInTheSearchIsPassed()
    {
        $expectedFilterCollection =
            (new Lib\Filter\FilterCollection())
                ->add(
                    (new Lib\Filter\KeyValue())
                        ->setKey('reporter')
                        ->addValue('simon-hayre@simon_hayre.co.uk')
                )
                ->add(
                    (new Lib\Filter\KeyValue())
                        ->setKey('subject')
                        ->setNot(true)
                        ->addValue('Missing Key')
                )
                ->add(
                    (new Lib\Filter\OrderBy())
                        ->setKey('created')
                        ->setDirection(Lib\Filter\OrderBy::DIRECTION_DESC)
                )
                ->add(
                    (new Lib\Filter\OrderBy())
                        ->setKey('name')
                        ->setDirection(Lib\Filter\OrderBy::DIRECTION_ASC)
                );

        $this->assertEquals(
            $expectedFilterCollection,
            $this->jqlParser->parse('reporter in (simon-hayre@simon_hayre.co.uk) subject not in ("Missing Key") order by created DESC name ASC')
        );
    }
}
