<?php

declare(strict_types=1);

namespace Tests\Unit\IadExample;

use PHPUnit\Framework\TestCase;
use POCIterator\IadExample\People;
use POCIterator\IadExample\PeopleCollection;

final class PeopleCollectionTest extends TestCase
{
    private PeopleCollection $collection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->collection = new PeopleCollection();
        for ($i = 0; $i < 2; $i++) {
            $parent = People::create('Parent' . $i, $i + 1);
            for ($y = 0; $y < 4; $y++) {
                $child = People::create('Child' . $y, 0, $parent);
                for ($z = 0; $z < 2; $z++) {
                    People::create('GrandChild' . $z, 0, $child);
                }
            }
            $this->collection->addItem($parent);
        }
    }

    public function testPeopleCollectionAddItems(): void
    {
        // Arrange && Act
        $items = $this->collection->getItems();

        // Assert
        $left = 1;
        self::assertCount(2, $items);
        foreach ($items as $keyItem => $item) {
            self::assertInstanceOf(People::class, $item);
            self::assertEquals('Parent' . $keyItem, $item->username());
            self::assertEquals(1, $item->level());
            self::assertEquals($left, $item->left());
            self::assertCount(4, $item->children());
            $left++;
            foreach ($item->children() as $keyChild => $child) {
                self::assertInstanceOf(People::class, $child);
                self::assertEquals('Child' . $keyChild, $child->username());
                self::assertEquals(2, $child->level());
                self::assertEquals($left, $child->left());
                self::assertCount(2, $child->children());
                $left++;
                foreach ($child->children() as $keyGrand => $grandChild) {
                    self::assertInstanceOf(People::class, $grandChild);
                    self::assertEquals('GrandChild' . $keyGrand, $grandChild->username());
                    self::assertEquals(3, $grandChild->level());
                    self::assertEquals($left, $grandChild->left());
                    self::assertCount(0, $grandChild->children());
                    $left++;
                }
            }
        }
    }

    public function testPeopleCollectionGetTree(): void
    {
        // Arrange && Act
        $tree = $this->collection->getTree();

        // Assert
        self::assertEquals(
            [
                [
                    'username' => 'Parent0',
                    'slug' => 'parent0',
                    'children' => [
                        [
                            'username' => 'Child0',
                            'slug' => 'parent0-child0',
                            'children' => [
                                ['username' => 'GrandChild0', 'slug' => 'parent0-child0-grandchild0'],
                                ['username' => 'GrandChild1', 'slug' => 'parent0-child0-grandchild1'],
                            ],
                        ],
                        [
                            'username' => 'Child1',
                            'slug' => 'parent0-child1',
                            'children' => [
                                ['username' => 'GrandChild0', 'slug' => 'parent0-child1-grandchild0'],
                                ['username' => 'GrandChild1', 'slug' => 'parent0-child1-grandchild1'],
                            ],
                        ],
                        [
                            'username' => 'Child2',
                            'slug' => 'parent0-child2',
                            'children' => [
                                ['username' => 'GrandChild0', 'slug' => 'parent0-child2-grandchild0'],
                                ['username' => 'GrandChild1', 'slug' => 'parent0-child2-grandchild1'],
                            ],
                       ],
                        [
                            'username' => 'Child3',
                            'slug' => 'parent0-child3',
                            'children' => [
                                ['username' => 'GrandChild0', 'slug' => 'parent0-child3-grandchild0'],
                                ['username' => 'GrandChild1', 'slug' => 'parent0-child3-grandchild1'],
                            ],
                        ],
                    ],
                ],
                [
                    'username' => 'Parent1',
                    'slug' => 'parent1',
                    'children' => [
                        [
                            'username' => 'Child0',
                            'slug' => 'parent1-child0',
                            'children' => [
                                ['username' => 'GrandChild0', 'slug' => 'parent1-child0-grandchild0'],
                                ['username' => 'GrandChild1', 'slug' => 'parent1-child0-grandchild1'],
                            ],
                        ],
                        [
                            'username' => 'Child1',
                            'slug' => 'parent1-child1',
                            'children' => [
                                ['username' => 'GrandChild0', 'slug' => 'parent1-child1-grandchild0'],
                                ['username' => 'GrandChild1', 'slug' => 'parent1-child1-grandchild1'],
                            ],
                        ],
                        [
                            'username' => 'Child2',
                            'slug' => 'parent1-child2',
                            'children' => [
                                ['username' => 'GrandChild0', 'slug' => 'parent1-child2-grandchild0'],
                                ['username' => 'GrandChild1', 'slug' => 'parent1-child2-grandchild1'],
                            ],
                        ],
                        [
                            'username' => 'Child3',
                            'slug' => 'parent1-child3',
                            'children' => [
                                ['username' => 'GrandChild0', 'slug' => 'parent1-child3-grandchild0'],
                                ['username' => 'GrandChild1', 'slug' => 'parent1-child3-grandchild1'],
                            ],
                        ],
                    ],
                ],
            ],
            $tree,
        );
    }

    public function testPeopleCollectionGetIteratorByBranch(): void
    {
        // Arrange
        $iterator = $this->collection->getIteratorDown();

        // Act && Assert
        $current = $iterator->current();
        self::assertEquals('parent0', $current->slug());
        self::assertEquals(1, $current->level());
        self::assertEquals(1, $current->left());
        self::assertEquals(0, $iterator->key());
        $iterator->next();
        $current = $iterator->current();
        self::assertEquals('parent0-child0', $current->slug());
        self::assertEquals(2, $current->level());
        self::assertEquals(2, $current->left());
        self::assertEquals(1, $iterator->key());
        $iterator->next();
        $current = $iterator->current();
        self::assertEquals('parent0-child0-grandchild0', $current->slug());
        self::assertEquals(3, $current->level());
        self::assertEquals(3, $current->left());
        self::assertEquals(2, $iterator->key());
        for ($i = 0; $i < 10; $i++) {
            $iterator->next();
        }
        $current = $iterator->current();
        self::assertEquals('parent0-child3-grandchild1', $current->slug());
        self::assertEquals(3, $current->level());
        self::assertEquals(13, $current->left());
        self::assertEquals(12, $iterator->key());
        $iterator->next();
        $current = $iterator->current();
        self::assertEquals('parent1', $current->slug());
        self::assertEquals(1, $current->level());
        self::assertEquals(14, $current->left());
        self::assertEquals(13, $iterator->key());
    }
}
