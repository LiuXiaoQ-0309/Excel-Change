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
    static function uploadFiles($key, $files, $path)
    {
        // Judge
        if ($files[$key]["error"] > 0 && in_array(1, $_FILES[$key]["error"])) {
            return response()->json(['Error' => 'Upload file false！']);
        }

        // upload
        return move_uploaded_file($_FILES[$key]["tmp_name"], $path);
    }

}
