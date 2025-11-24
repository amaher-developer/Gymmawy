<?php
namespace Modules\Article\Repositories;

use Modules\Generic\Repositories\GenericRepository;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\Article\Models\Article;


class ArticleRepository extends GenericRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Article::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
