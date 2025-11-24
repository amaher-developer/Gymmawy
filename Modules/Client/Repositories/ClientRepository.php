<?php
namespace Modules\Client\Repositories;

use Modules\Generic\Repositories\GenericRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Modules\Client\Models\Client;


class ClientRepository extends GenericRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Client::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
