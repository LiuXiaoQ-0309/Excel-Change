<?php


namespace App\Http\Controllers\Excel;


use App\Exports\ExcelExport;
use App\Helpers\Files;
use App\Http\Controllers\Controller;
use App\Services\ExcelService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;


class ExcelControllers extends Controller
{

    public function __construct(ExcelService $excelService)
    {
        $this->excelService = $excelService;
    }

    /**
     * Pinyin (中文名字获取拼音)
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getPy()
    {
        if (count($_FILES) > 0) {
            // This Name Include Extension
            $fileName = $_FILES["excel"]["name"];
            $path = public_path('Excel/' . $fileName);
            $result = Files::uploadFiles('excel', $_FILES, $path);
            if ($result) {
                // Import
                $array = $this->excelService->excelImportToArray($path);
                // Pinyin
                $array = $this->excelService->getNamePinyin($array[0]);
                // Export
                if ($this->excelService->excelExportStore($array, $fileName)) {
                    return response()->json(['code' => 1, 'message' => config('excel.getPyUrl') . $fileName]);
                }
            }
        }
        return response()->json(['code' => 0, 'message' => 'No Upload File Or Export false!']);
    }

    /**
     * ImageName（照片名称命名）
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function imgChangeName(Request $request)
    {
        if (key_exists('excel', $_FILES) && key_exists('img', $_FILES)) {
            // Name Excel
            $path = public_path('Img-Name/name/nameExcel.xlsx');
            $result = Files::uploadFiles('excel', $_FILES, $path);
            if ($result) {
                $name = $array = $this->excelService->excelImportToArray($path);
                $nameFix = $request->input('common');
                if (count($_FILES['img']['name']) > 0) {
                    // Image
                    $dirPath = public_path('Img-Name/img');
                    $zipPath = public_path('Img-Name/image.zip');
                    Files::deleteDir($dirPath);
                    Files::deleteFiles($zipPath);
                    foreach ($_FILES['img']['tmp_name'] as $key => $valus) {
                        Files::uploadFiles('img', $_FILES["img"]["tmp_name"][$key], $dirPath . '/' . $nameFix . $name[0][$key][2] . '.jpg', 2);
                    }
                    $this->excelService->setZipper($dirPath, $zipPath);
                    return response()->json(['code' => 1, 'message' => config('excel.zipUrl')]);
                }
            }
        }
        return response()->json(['code' => 0, 'message' => 'No Upload File Or Move excel false！']);
    }
}
