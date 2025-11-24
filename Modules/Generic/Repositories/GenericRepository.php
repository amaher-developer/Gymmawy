<?php

namespace Modules\Generic\Repositories;

use Illuminate\Container\Container as Application;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Eloquent\BaseRepository;



class GenericRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function model()
    {

    }


    public function when($value, callable $callback, callable $default = null)
    {
        if ($value) {
            return $callback($this, $value);
        } elseif ($default) {
            return $default($this, $value);
        }

        return $this;
    }

    public function onlyTrashed()
    {
        return $this->model->onlyTrashed();
    }


    public function withTrashed()
    {
        return $this->model->withTrashed();
    }

    public function trashed()
    {
        return $this->model->trashed();
    }

    public function restore()
    {
        return $this->model->restore();
    }


    public function where($field, $operator = "=", $value = '')
    {
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = "=";
        }
        return $this->model->where($field, $operator, $value);
    }


    public function total()
    {
        return $this->model->total();
    }

//    public function count()
//    {
//        return $this->model->count();
//    }

    public function insert($inputs)
    {
        return $this->model->insert($inputs);
    }


    public function attach($inputs)
    {
        return $this->model->attach($inputs);
    }

    public function groupBy($input)
    {
        return $this->model->groupBy($input);
    }




    public function uploadPath()
    {

    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {

    }

}
