<?php

declare(strict_types=1);

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use Tests\Unit\AbstractUnitTestCase;

/**
 * Model Tests.
 */
abstract class AbstractModelTestCase extends AbstractUnitTestCase
{
    public function testFillableProperties()
    {
        $this->assertSame($this->getFillable(), $this->instanceFactory()->getFillable());
    }

    public function testHiddenProperties()
    {
        $this->assertSame($this->getHidden(), $this->instanceFactory()->getHidden());
    }

    /**
     * Get model fillable fields.
     *
     * @return array
     */
    abstract protected function getFillable(): array;

    /**
     * Get model hidden fields.
     *
     * @return array
     */
    abstract protected function getHidden(): array;

    /**
     * Get model instance.
     *
     * @return Model
     */
    abstract protected function instanceFactory(): Model;
}
