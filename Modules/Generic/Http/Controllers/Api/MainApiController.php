<?php

namespace Modules\Generic\Http\Controllers\Api;



use Modules\Access\Models\User;
use Carbon\Carbon;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class MainApiController extends GenericApiController
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @SWG\Post(
     *     method="POST",
     *   path="/api/post-example",
     *   summary="Order Details for saidalany",
     *   operationId="Order Details as pharmacy",
     *   @SWG\Parameter(
     *     name="order_id",
     *     in="formData",
     *     required=true,
     *     @SWG\Schema(type="string"),
     *     type="string"
     *   ),
     *    @SWG\Parameter(
     *     name="device_type",
     *     in="formData",
     *     required=true,
     *     @SWG\Schema(type="string"),
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="access token"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * ),
     */
    public function SwaggerPostExample()
    {
        return $this->successResponse();

    }

    /**
     * @SWG\Get(
     *   path="/api/",
     *   summary="Example For Api Documentation",
     *   operationId="main",
     *   @SWG\Parameter(
     *     name="customerId",
     *     in="path",
     *     description="Target customer.",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     description="Filter results based on query string value.",
     *     required=false,
     *     enum={"active", "expired", "scheduled"},
     *     type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */
    public function SwaggerGetExample()
    {
        return $this->successResponse();
    }



    public function saveBackupDB(Request $request)
    {

        $db = $request->backup;
        $email = $request->email;
        $password = $request->password;
        $credentials = ['email' => $email, 'password' => $password];



        if($db && (Auth::validate($credentials))){

            $fileName = 'db-backup-' . date('Y-m-d') . '-' . (md5($email)) ;
            $filePath = base_path() . '/uploads/backupDB/' . $fileName . '.sql';
            //save file
            $handle = fopen($filePath, 'w+');
            fwrite($handle, $db);
            fclose($handle);

            if (!File::exists( base_path('/exports'))) {
                File::makeDirectory(base_path('/exports'), $mode = 0755, true, true);
            }

            Zipper::make('exports/'.$fileName.'.zip')->add($filePath)->close();
            chmod(base_path('exports/'.$fileName.'.zip'), 0777);
            unlink($filePath);
            // update info of user
            User::where('email', $email)->update(['db_backup_name' => $fileName, 'db_backup_date' => Carbon::now()]);

            return "1";
        }
        return "0";
    }
}
