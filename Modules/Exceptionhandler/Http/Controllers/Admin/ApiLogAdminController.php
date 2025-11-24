<?php

namespace Modules\Exceptionhandler\Http\Controllers\Admin;

use Modules\Generic\Http\Controllers\Admin\GenericAdminController;
use Modules\Exceptionhandler\Http\Requests\ApiLogRequest;
use Modules\Exceptionhandler\Models\ApiLog;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApiLogAdminController extends GenericAdminController
{
    public function index()
    {
        $title = 'apilogs List';
        $apilogs = ApiLog::where('count', '>=', 0)->get();
        return view('exceptionhandler::Admin.apilog_admin_list', compact('apilogs', 'title'));
    }

    public function destroy(ApiLog $apilog)
    {
        $apilog->delete();
        sweet_alert()->success('Done', 'ApiLog deleted successfully');
        return redirect(route('listApiLog'));
    }

}
