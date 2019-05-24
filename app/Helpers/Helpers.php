<?php
/**
 * Created by PhpStorm.
 * User: liu.ya
 * Date: 2019/5/24
 * Time: 15:03
 */
if (!function_exists('deldir')) {

    /**
     * 删除文件夹包含的文件(不删除文件夹)
     * @param $dir
     */
    function deldir($dir)
    {
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
                }
            }
        }
        closedir($dh);
    }
}
