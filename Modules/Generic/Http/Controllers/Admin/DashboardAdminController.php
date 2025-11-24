<?php

namespace Modules\Generic\Http\Controllers\Admin;

use Modules\Access\Models\User;
use Modules\Article\Models\Article;
use Modules\Gym\Models\Gym;
use Modules\Trainer\Models\Trainer;

class DashboardAdminController extends GenericAdminController
{
    public function showHome()
    {
        $gym_active_count = Gym::where('published', 1)->count();
        $gym_not_active_count = Gym::where('published', 0)->count();
        $trainer_active_count = Trainer::where('published', 1)->count();
        $trainer_not_active_count = Trainer::where('published', 0)->count();
        $article_active_count = Article::where('published', 1)->count();
        $article_not_active_count = Article::where('published', 0)->count();
        $user_count = User::count();
        $user_guest_count = User::where('guest', '1')->count();

        $gyms = Gym::with('call_center_log', 'gym_brand')->orderBy('district_id', 'desc')->paginate($this->limit);


        return view('generic::Admin.dashboard', ['title' => 'Dashboard',
            'gym_active_count' => $gym_active_count,
            'gym_not_active_count' => $gym_not_active_count,
            'trainer_active_count' => $trainer_active_count,
            'trainer_not_active_count' => $trainer_not_active_count,
            'article_active_count' => $article_active_count,
            'article_not_active_count' => $article_not_active_count,
            'user_count' => $user_count,
            'user_guest_count' => $user_guest_count,
            'gyms' => $gyms,
            ]);
    }

    public function backupDB()
    {
        /* backup the db OR just a table */
        $host = 'localhost';
        $user = '4252084e59e3';
        $pass = 'a7eee42d90aa92b2';
        $DbName = 'fakahany';
        $tables = '*';

        $link = mysqli_connect($host, $user, $pass, $DbName);
        $link->set_charset("utf8");
        //get all of the tables
        if ($tables == '*') {
            $tables = array();
            $result = mysqli_query($link, 'SHOW TABLES');
            while ($row = mysqli_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        //cycle through
        $return = '';
        foreach ($tables as $table) {
            $result = mysqli_query($link, 'SELECT * FROM ' . $table);
            $num_fields = mysqli_num_fields($result);

            $return .= 'DROP TABLE ' . $table . ';';
            $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE ' . $table));
            $return .= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    $return .= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $return .= '"' . $row[$j] . '"';
                        } else {
                            $return .= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return .= ',';
                        }
                    }
                    $return .= ");\n";
                }
            }
            $return .= "\n\n\n";
        }
        $fileName = 'db-backup-' . date('Y-m-d') . '-' . (md5(implode(',', $tables))) . '.sql';
        $filePath = base_path() . '/uploads/backupDB/' . $fileName;
        //save file
        $handle = fopen($filePath, 'w+');
        fwrite($handle, $return);
        fclose($handle);

        //download file
        $headers = array(
            'Content-Type: application/octet-stream',
        );
        return response()->download($filePath, $fileName, $headers);
    }



    public function noJs()
    {
        $home = url('/operate');
        return 'Sorry, You have to enable Javascript to be able to continue.<br><a href="' . $home . '">Try Again.</a> ';
    }
}
