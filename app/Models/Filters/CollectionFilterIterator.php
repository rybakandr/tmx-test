<?php

namespace app\Models\Filters;

class CollectionFilterIterator extends \FilterIterator
{
    private $filters;

    public function __construct(\Iterator $collection, array $userFilters = [])
    {
        parent::__construct($collection);
        $this->filters = $userFilters;
    }

    public function accept(): bool
    {
        return (bool)$this->current()->filter(...$this->filters);
    }
}