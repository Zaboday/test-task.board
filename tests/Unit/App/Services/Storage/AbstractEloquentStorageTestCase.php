<?php

declare(strict_types=1);

namespace Tests\Unit\App\Services\Storage;

use Mockery as m;
use Illuminate\Support\Str;
use Tests\Unit\AbstractUnitTestCase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Storage\EloquentStorage;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractEloquentStorageTestCase extends AbstractUnitTestCase
{
    /**
     * @return void
     */
    public function testGetModel(): void
    {
        $this->assertSame($model = $this->instanceModel(), $this->instanceFactory($model)->getModel());
    }

    /**
     * @return void
     */
    public function testCreate(): void
    {
        $data = ['foo' => Str::random(), 'bar' => Str::random()];

        $queryBuilder = m::mock(Builder::class)
            ->expects('create')
            ->withArgs(function ($attributes) use ($data) {
                $this->assertSame($attributes, $data);

                return true;
            })
            ->andReturn(true)->getMock();

        $model = m::mock(\get_class($this->instanceModel()))->expects('newQuery')->andReturn($queryBuilder)->getMock();

        $this->assertTrue($this->instanceFactory($model)->create($data));
    }

    /**
     * @return void
     */
    public function testFindBy(): void
    {
        $where = [Str::random(), Str::random(), Str::random()];

        $queryBuilder1 = m::mock(Builder::class)
            ->expects('first')->withNoArgs()
            ->andReturn($result = $this->instanceModel())->getMock();

        $queryBuilder2 = m::mock(Builder::class)
            ->expects('where')
            ->withArgs(function ($arg1, $arg2, $arg3) use ($where) {
                $this->assertSame($arg1, $where[0]);
                $this->assertSame($arg2, $where[1]);
                $this->assertSame($arg3, $where[2]);

                return true;
            })
            ->andReturn($queryBuilder1)->getMock();

        $model = m::mock(\get_class($this->instanceModel()))->expects('newQuery')->andReturn($queryBuilder2)->getMock();

        $this->assertSame($result, $this->instanceFactory($model)->findBy($where));
    }

    /**
     * @throws \Exception
     */
    public function testFind(): void
    {
        $queryBuilder = m::mock(Builder::class)
            ->expects('find')
            ->with($id = \random_int(1, 1000))
            ->andReturn($result = $this->instanceModel())->getMock();

        $model = m::mock(\get_class($this->instanceModel()))->expects('newQuery')->andReturn($queryBuilder)->getMock();

        $this->assertSame($result, $this->instanceFactory($model)->find($id));
    }

    /**
     * @throws \Exception
     */
    public function testCount(): void
    {
        $where = [Str::random(), Str::random(), Str::random()];

        $queryBuilder1 = m::mock(Builder::class)
            ->expects('count')->withNoArgs()
            ->andReturn($count = \random_int(1, 1000))->getMock();

        $queryBuilder2 = m::mock(Builder::class)
            ->expects('where')
            ->withArgs(function ($arg1, $arg2, $arg3) use ($where) {
                $this->assertSame($arg1, $where[0]);
                $this->assertSame($arg2, $where[1]);
                $this->assertSame($arg3, $where[2]);

                return true;
            })
            ->andReturn($queryBuilder1)->getMock();

        $model = m::mock(\get_class($this->instanceModel()))->expects('newQuery')->andReturn($queryBuilder2)->getMock();

        $this->assertSame($count, $this->instanceFactory($model)->count($where));
    }

    /**
     * @throws \Exception
     */
    public function testPageWhere(): void
    {
        $where = [Str::random(), Str::random(), Str::random()];

        $queryBuilder = m::mock(Builder::class)
            ->shouldReceive('where')->with($where[0], $where[1], $where[2])
            ->andReturnSelf()->getMock()
            ->shouldReceive('forPage')->with($page = \random_int(0, 10), $limit = \random_int(0, 10))
            ->andReturnSelf()->getMock()
            ->shouldReceive('get')->withNoArgs()->andReturn($result = new Collection())->getMock();

        $model = m::mock(\get_class($this->instanceModel()))->expects('newQuery')->andReturn($queryBuilder)
            ->getMock();

        $this->assertSame($result, $this->instanceFactory($model)->page($page, $limit, $where));
    }

    /**
     * @throws \Exception
     */
    public function testDelete(): void
    {
        $queryBuilder = m::mock(Builder::class)
            ->expects('find')->with($id = \random_int(1, 1000))->andReturnSelf()->getMock()
            ->expects('delete')->withNoArgs()->andReturnNull()->getMock();

        $model = m::mock(\get_class($this->instanceModel()))->expects('newQuery')->andReturn($queryBuilder)->getMock();

        $this->instanceFactory($model)->delete($id);
    }

    /**
     * Should return storage Model.
     *
     * @return Model
     */
    abstract public function instanceModel(): Model;

    /**
     * Should return Model Storage.
     *
     * @param mixed $model
     *
     * @return EloquentStorage
     */
    abstract public function instanceFactory($model): EloquentStorage;
}
