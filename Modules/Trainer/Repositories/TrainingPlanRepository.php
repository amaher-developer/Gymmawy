<?php
namespace Modules\Trainer\Repositories;

use Modules\Generic\Repositories\GenericRepository;
use Modules\Trainer\Models\TrainingPlan;
use Modules\Trainer\Models\TrainingSubscription;
use Prettus\Repository\Criteria\RequestCriteria;


class TrainingPlanRepository extends GenericRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TrainingPlan::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
