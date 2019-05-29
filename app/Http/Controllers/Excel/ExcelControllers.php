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
            // This Name Include Suffix
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

        if (count($_FILES) > 0) {
            // Excel Get Name
            if ($_FILES["excel"]["error"] > 0 || in_array(1, $_FILES["img"]["error"])) {
                return response()->json(['Error' => 'Upload file false！']);
            }
            $result = move_uploaded_file($_FILES["excel"]["tmp_name"], public_path('Img-Name/name/nameExcel.xlsx'));
            if ($result) {
                // get excel connect
                $name = Excel::toArray($this->import, public_path('Img-Name/name/nameExcel.xlsx'));
                $nameFix = $request->input('common');
                // upload image
                if (count($_FILES['img']['name']) > 0) {
                    // delete image
                    deldir(public_path('Img-Name/img'));
                    unlink(public_path('Img-Name/image.zip'));

                    foreach ($_FILES['img']['tmp_name'] as $key => $valus) {
                        $result = move_uploaded_file($_FILES["img"]["tmp_name"][$key], public_path('Img-Name/img/' . $nameFix . $name[0][$key][2] . '.jpg'));
                    }
                    if ($result) {
                        // use zip
                        $zipper = new \Chumper\Zipper\Zipper();
                        $zipData = glob(public_path('Img-Name/img'));
                        $zipper->make(public_path('Img-Name/image.zip'))->add($zipData)->close();
                        return response()->json(['code' => 1, 'message' => 'http://localhost:8090/Excel-Change/public/Img-Name/image.zip']);
                    }
                    return response()->json(['code' => 0, 'message' => 'Move excel false!']);
                }
            }
        }
        return response()->json(['code' => 0, 'message' => 'No Upload File！']);
    }
}
