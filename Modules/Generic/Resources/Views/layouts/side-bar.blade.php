{{-- get the identifier of from the route--}}
@php
    $identifier = request()->segment(2);
@endphp

@if(env('APP_ENV')=='local')
    <li aria-haspopup="true" class="start">
        <a href="{{route('listModules')}}" class="nav-link nav-toggle">
            <i class="icon-folder"></i>
            <span class="title">Modules </span>
            @if($migrate)<span class="title" style="   font-weight: bold;   font-size: 14px;   color: red;">(migrate) </span>@endif
        </a>
    </li>
@endif
{{--@if(env('APP_ENV')=='local'||has_any_access([],$currentUser) > 0)--}}
{{--    <li aria-haspopup="true" class="start">--}}
{{--        <a href="{{url('telescope')}}" class="nav-link nav-toggle" target="_blank">--}}
{{--            <i class="icon-ghost"></i>--}}
{{--            <span class="title">Telescope </span>--}}
{{--        </a>--}}
{{--    </li>--}}
{{--@endif--}}


@if(has_any_access(['user-index','admin-index','role-index', 'training_client-index'],$currentUser) > 0)
    <li aria-haspopup="true"
        class="menu-dropdown classic-menu-dropdown {{ in_array($identifier,['role','admin','user', 'training_client'])  ? 'font-green' : '' }}">
        <a href="javascript:;" class="{{ in_array($identifier,['role','admin','user', 'training_client'])  ? 'font-green' : '' }}">
            <i class="icon-users"></i> {{trans('admin.clients')}}
        </a>
        <ul class="dropdown-menu">
            @if(has_any_access(['role-index'],$currentUser) > 0)
                <li aria-haspopup="true" class="{{ in_array($identifier,['role'])  ? 'font-green' : '' }}">
                    <a href="{{route('listRoles')}}" class="nav-link ">
                        <i class="icon-arrow-right "></i>
                        <span class="title">{{trans('admin.roles')}}</span>
                    </a>
                </li>
            @endif


            @if(has_any_access(['admin-index'],$currentUser) > 0)
                <li aria-haspopup="true" class="{{ in_array($identifier,['admin'])  ? 'font-green' : '' }}">
                    <a href="{{route('listAdmins')}}" class="nav-link ">
                        <i class="icon-arrow-right "></i>
                        <span class="title">{{trans('admin.users')}}</span>
                    </a>
                </li>
            @endif


            @if(has_any_access(['user-index'],$currentUser) > 0)

                <li aria-haspopup="true" class=" {{ in_array($identifier,['user'])  ? 'font-green' : '' }}">
                    <a href="{{route('listUser')}}?limit=10" class="nav-link ">
                        <i class="icon-user"></i>
                        <span class="title">{{trans('admin.customers')}}</span>
                    </a>
                </li>

            @endif
                @if(has_any_access(['user-index'],$currentUser) > 0)

                    <li aria-haspopup="true" class=" {{ in_array($identifier,['user'])  ? 'font-green' : '' }}">
                        <a href="{{route('listClient')}}?limit=10" class="nav-link ">
                            <i class="icon-user"></i>
                            <span class="title">{{trans('admin.clients')}}</span>
                        </a>
                    </li>

                @endif

                @if(has_any_access(['training_client-index'],$currentUser) > 0)

                    <li aria-haspopup="true" class=" {{ in_array($identifier,['training_client'])  ? 'font-green' : '' }}">
                        <a href="{{route('listTrainingClient')}}?limit=10" class="nav-link ">
                            <i class="icon-user"></i>
                            <span class="title">{{trans('admin.training_clients')}}</span>
                        </a>
                    </li>

                @endif
        </ul>
    </li>
@endif







@if(has_any_access(['user-index','admin-index','role-index','training_plan-index'],$currentUser) > 0)
    <li aria-haspopup="true"
        class="menu-dropdown classic-menu-dropdown {{ in_array($identifier,['setting','notification','dashboard', 'training_plan'])  ? 'font-green' : '' }}">
        <a href="javascript:;"
           class="{{ in_array($identifier,['setting','notification','dashboard','contact','newsletter-subscriber', 'training_plan'])  ? 'font-green' : '' }}">
            <i class="icon-settings"></i> {{trans('admin.general_settings')}}
        </a>
        <ul class="dropdown-menu">
            @if(has_any_access(['setting-edit'],$currentUser) > 0)

                <li aria-haspopup="true" class="{{ in_array($identifier,['setting'])  ? 'font-green' : '' }}">
                    <a href="{{route('editSetting',1)}}" class="nav-link ">
                        <i class="icon-notebook "></i>
                        <span class="title">{{trans('admin.website_info')}}</span>
                    </a>
                </li>
            @endif


                @if(has_any_access(['notification-index'],$currentUser) > 0)
            <li aria-haspopup="true" class="{{ in_array($identifier,['notification'])  ? 'font-green' : '' }}">
                <a href="{{route('listNotification')}}" class="nav-link nav-toggle">
                    <i class="icon-bell"></i>
                    <span class="title">{{trans('admin.notifications')}}</span>
                </a>
            </li>
                @endif


                @if(has_any_access(['gym-advice-index'],$currentUser) > 0)
            <li aria-haspopup="true" class="{{ in_array($identifier,['gym-advice'])  ? 'font-green' : '' }}">
                <a href="{{route('listGymAdvice')}}" class="nav-link nav-toggle">
                    <i class="icon-bell"></i>
                    <span class="title">{{trans('admin.gym_advice')}}</span>
                </a>
            </li>
                @endif



                    @if(has_any_access(['contact-index'],$currentUser) > 0)
            <li aria-haspopup="true" class="{{ in_array($identifier,['contact'])  ? 'font-green' : '' }}">
                <a href="{{route('listContact')}}" class="nav-link nav-toggle">
                    <i class="icon-envelope"></i>
                    <span class="title">{{trans('admin.contacts')}}</span>
                </a>
            </li>
                    @endif
                    @if(has_any_access(['whatsapp-create'],$currentUser) > 0)
            <li aria-haspopup="true" class="{{ in_array($identifier,['whatsapp'])  ? 'font-green' : '' }}">
                <a href="{{route('createWhatsapp')}}" class="nav-link nav-toggle">
                    <i class="fa fa-whatsapp"></i>
                    <span class="title">{{trans('admin.whatsapp')}}</span>
                </a>
            </li>
                    @endif



                        @if(has_any_access(['newsletter-subscriber-index'],$currentUser) > 0)
            <li aria-haspopup="true" class="{{ in_array($identifier,['newsletter-subscriber'])  ? 'font-green' : '' }}">
                <a href="{{route('listNewsletterSubscriber')}}" class="nav-link nav-toggle">
                    <i class="icon-envelope"></i>
                    <span class="title">{{trans('admin.newsletter_subscriber')}}</span>
                </a>
            </li>
                        @endif
                @if(has_any_access(['training_plan-index'],$currentUser) > 0)

                    <li aria-haspopup="true" class=" {{ in_array($identifier,['training_plan'])  ? 'font-green' : '' }}">
                        <a href="{{route('listTrainingPlan')}}?limit=10" class="nav-link ">
                            <i class="icon-user"></i>
                            <span class="title">{{trans('admin.training_plan')}}</span>
                        </a>
                    </li>

                @endif
            {{--@if(has_any_access(['dashboard'],$currentUser) > 0)--}}
                {{--<li class=" {{ in_array($identifier,['dashboard'])  ? 'font-green' : '' }}">--}}
                    {{--<a href="{{route('backupDB')}}" class="nav-link ">--}}
                        {{--<i class="icon-cloud-download"></i>--}}
                        {{--<span class="title">Database backup</span>--}}
                    {{--</a>--}}
                {{--</li>--}}
            {{--@endif--}}

        </ul>
    </li>
@endif


@if(has_any_access(['article-index'],$currentUser) > 0)
{{--    <li aria-haspopup="true" class="start">--}}
{{--        <a href="{{route('listArticle')}}" class="nav-link nav-toggle">--}}
{{--            <i class="icon-note"></i>--}}
{{--            <span class="title">{{trans('global.articles')}} </span>--}}
{{--        </a>--}}
{{--    </li>--}}




    <li aria-haspopup="true"
        class="menu-dropdown classic-menu-dropdown {{ in_array($identifier,['article','article-category','tag', 'article-image'])  ? 'font-green' : '' }}">
        <a href="javascript:;"
           class="{{ in_array($identifier,['article','article-category','tag'])  ? 'font-green' : '' }}">
            <i class="icon-note"></i> {{trans('global.articles')}}
        </a>
        <ul class="dropdown-menu">
            @if(has_any_access(['article-index'],$currentUser) > 0)

                <li aria-haspopup="true" class="{{ in_array($identifier,['article'])  ? 'font-green' : '' }}">
                    <a href="{{route('listArticle')}}" class="nav-link ">
                        <i class="icon-note"></i>
                        <span class="title">{{trans('global.articles')}} </span>
                    </a>
                </li>
            @endif
            @if(has_any_access(['article-category-index'],$currentUser) > 0)

                <li aria-haspopup="true" class="{{ in_array($identifier,['article-category'])  ? 'font-green' : '' }}">
                    <a href="{{route('listArticleCategory')}}" class="nav-link ">
                        <i class="icon-note"></i>
                        <span class="title">{{trans('global.article_categories')}} </span>
                    </a>
                </li>
            @endif
            @if(has_any_access(['tag-index'],$currentUser) > 0)

                <li aria-haspopup="true" class="{{ in_array($identifier,['tag'])  ? 'font-green' : '' }}">
                    <a href="{{route('listTag')}}" class="nav-link ">
                        <i class="icon-note"></i>
                        <span class="title">{{trans('global.tags')}} </span>
                    </a>
                </li>
            @endif
            @if(has_any_access(['article-image-index'],$currentUser) > 0)

                <li aria-haspopup="true" class="{{ in_array($identifier,['article-image-index'])  ? 'font-green' : '' }}">
                    <a href="{{route('listArticleImages')}}" class="nav-link ">
                        <i class="icon-note"></i>
                        <span class="title">{{trans('global.images')}} </span>
                    </a>
                </li>
            @endif

        </ul>
    </li>

@endif
@if(has_any_access(['ask-index'],$currentUser) > 0)
    <li aria-haspopup="true" class="start">
        <a href="{{route('listAsk')}}" class="nav-link nav-toggle">
            <i class="icon-question"></i>
            <span class="title">{{trans('admin.questions')}} </span>
        </a>
    </li>
@endif
@if(has_any_access(['gym-index'],$currentUser) > 0)
    <li aria-haspopup="true" class="start">
        <a href="{{route('listGym')}}" class="nav-link nav-toggle" >
            <i class=" icon-fire"></i>
            <span class="title">{{trans('global.gym')}} </span>
        </a>
    </li>
@endif

@if(has_any_access(['trainer-index'],$currentUser) > 0)
    <li aria-haspopup="true" class="start">
        <a href="{{route('listTrainer')}}" class="nav-link nav-toggle">
            <i class="icon-shield"></i>
            <span class="title">{{trans('global.trainers')}} </span>
        </a>
    </li>
@endif
@if(has_any_access(['bodybuilder-index'],$currentUser) > 0)
    <li aria-haspopup="true" class="start">
        <a href="{{route('listBodybuilder')}}" class="nav-link nav-toggle">
            <i class="icon-user"></i>
            <span class="title">{{trans('admin.bodybuilders')}} </span>
        </a>
    </li>
@endif
@if(has_any_access(['banner-index'],$currentUser) > 0)
    <li aria-haspopup="true" class="start">
        <a href="{{route('listBanner')}}" class="nav-link nav-toggle">
            <i class="icon-screen-desktop"></i>
            <span class="title">{{trans('admin.banners')}} </span>
        </a>
    </li>
@endif
