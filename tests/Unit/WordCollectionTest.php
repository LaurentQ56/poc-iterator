<?php

declare(strict_types=1);

namespace Tests\Unit;

use POCIterator\WordsCollection;
use PHPUnit\Framework\TestCase;

final class WordCollectionTest extends TestCase
{
    private WordsCollection $collection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->collection = new WordsCollection();
        $this->collection->addItem('first');
        $this->collection->addItem('second');
        $this->collection->addItem('third');
    }

    public function testCollectionAddItem(): void
    {
        // Arrange
        $collection = new WordsCollection();

        // Act
        $collection->addItem('first');

        // Assert
        self::assertEquals(['first'], $collection->getItems());
    }

    public function testCollectionGetIterator(): void
    {
        // Assert
        $iterator = $this->collection->getIterator();
        self::assertEquals('first', $iterator->current());
        self::assertEquals(0, $iterator->key());
        $iterator->next();
        self::assertEquals('second', $iterator->current());
        self::assertEquals(1, $iterator->key());
        $iterator->next();
        self::assertEquals('third', $iterator->current());
        self::assertEquals(2, $iterator->key());
        $iterator->rewind();
        self::assertEquals('second', $iterator->current());
        self::assertEquals(1, $iterator->key());
        $iterator->rewind();
        self::assertEquals('first', $iterator->current());
        self::assertEquals(0, $iterator->key());
    }

    public function testCollectionGetIteratorReverse(): void
    {
        // Assert
        $iteratorReverse = $this->collection->getIteratorReverse();
        self::assertEquals('first', $iteratorReverse->current());
        self::assertEquals(0, $iteratorReverse->key());
        $iteratorReverse->rewind();
        self::assertEquals('second', $iteratorReverse->current());
        self::assertEquals(1, $iteratorReverse->key());
        $iteratorReverse->rewind();
        self::assertEquals(2, $iteratorReverse->key());
        self::assertEquals('third', $iteratorReverse->current());
        $iteratorReverse->next();
        self::assertEquals('second', $iteratorReverse->current());
        self::assertEquals(1, $iteratorReverse->key());
        $iteratorReverse->next();
        self::assertEquals('first', $iteratorReverse->current());
        self::assertEquals(0, $iteratorReverse->key());
    }

    public function testCollectionGetIteratorTestPosition(): void
    {
        // Act
        $iteratorTestLess = $this->collection->getIterator();
        $iteratorTestLess->rewind();

        $iteratorTestMore = $this->collection->getIterator();
        $iteratorTestMore->next();
        $iteratorTestMore->next();
        $iteratorTestMore->next();

        // Assert
        self::assertFalse($iteratorTestLess->valid());
        self::assertFalse($iteratorTestMore->valid());
    }
}
