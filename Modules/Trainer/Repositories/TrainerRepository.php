<?php
namespace Modules\Trainer\Repositories;

use Modules\Generic\Repositories\GenericRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\Trainer\Models\Trainer;


class TrainerRepository extends GenericRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Trainer::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
