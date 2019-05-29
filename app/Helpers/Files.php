<?php


namespace App\Helpers;


class Files
{

    /**
     * Upload Files
     * @param $key file's name
     * @param $files $_FILES
     * @param $path  path of After downloading
     * @return bool|\Illuminate\Http\JsonResponse
     */
    static function uploadFiles($key, $files, $path, $filesChange = 1)
    {
        // Judge
        if ($filesChange == 1) {
            $type = gettype($_FILES[$key]["error"]);

            if ($_FILES[$key]["error"] > 0) {
                return response()->json(['Error' => 'Upload file false！']);
            }
            if ($type != 'integer') {
                if (in_array(1, $_FILES[$key]["error"])) {
                    return response()->json(['Error' => 'Upload file false！']);
                }
            }
            $files = $_FILES[$key]["tmp_name"];
        } else {
            $files = $files;
        }

        // upload
        return move_uploaded_file($files, $path);
    }

    /**
     * Get Files Name
     * @return mixed
     */
    static function getFilesName()
    {
        $info = explode('.', $_FILES["excel"]["name"]);
        return $info[0];
    }

    /**
     * Get Files Extension
     * @return mixed
     */
    static function getFilesExtension()
    {
        $info = explode('.', $_FILES["excel"]["name"]);
        return $info[1];
    }

    /**
     * Clear Document
     * @param $path
     */
    static function deleteDir($path)
    {
        return deldir($path);
    }

    /**
     * delete Files
     * @param $path
     * @return bool
     *
     */
    static function deleteFiles($path)
    {
        return unlink($path);
    }

}
