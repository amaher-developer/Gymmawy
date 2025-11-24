<?php

namespace Modules\Addon\Http\Controllers\Front;

use Modules\Addon\Repositories\BodybuilderRepository;
use Modules\Generic\Http\Controllers\Front\GenericFrontController;
use Illuminate\Container\Container as Application;


class BodybuilderFrontController extends GenericFrontController
{
    public $bodybuilderRepository;

    public function __construct()
    {
        parent::__construct();

        $this->bodybuilderRepository = new BodybuilderRepository(new Application);
    }



    public function bodybuilder($id, $slug)
    {
        $bodybuilder = $this->bodybuilderRepository->with(['country', 'district.city', 'competitions' => function($q){
            $q->orderBy('year', 'asc');
            $q->orderBy('id', 'asc');
        }])->find($id);
        if (!$bodybuilder)
            return view('generic::Front.pages.404');

        $bodybuilder->increment('views', 1);
        $title = @$bodybuilder->name;
        $metaKeywords = $bodybuilder->name;
        if (@$bodybuilder->competitions) $metaKeywords .= ', ' . $bodybuilder->competitions->pluck('name')->implode(', ');

        $metaDescription = strip_tags($bodybuilder->description);
        $metaImage = $bodybuilder->image;

        return view('addon::Front.bodybuilder',
            compact('title', 'bodybuilder', 'metaKeywords', 'metaImage', 'metaDescription'));
    }

    public function bodybuilders()
    {
        $this->limit = 12;
        $page = request('page') ?? 1;
        $bodybuilders = $this->bodybuilderRepository->with(['country', 'district.city'])->orderBy('id', 'asc')->paginate($this->limit);

        $title = trans('global.bodybuilders') .' - '.trans('global.page').' '.$page;;


        return view('addon::Front.bodybuilders',
            compact('title', 'bodybuilders'));
    }

}
