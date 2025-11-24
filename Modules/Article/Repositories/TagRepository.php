<?php
namespace Modules\Article\Repositories;

use Modules\Article\Models\Tag;
use Modules\Generic\Repositories\GenericRepository;
use Prettus\Repository\Criteria\RequestCriteria;


class TagRepository extends GenericRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Tag::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
