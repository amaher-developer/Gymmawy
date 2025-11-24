<?php
namespace Modules\Gym\Repositories;

use Modules\Generic\Repositories\GenericRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\Gym\Models\GymBrand;


class GymBrandRepository extends GenericRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return GymBrand::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
