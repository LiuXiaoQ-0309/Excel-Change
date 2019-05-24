<?php


namespace App\Http\Controllers\Excel;


use App\Exports\ExcelExport;
use App\Http\Controllers\Controller;
use App\Imports\ExcelImports;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Overtrue\Pinyin\Pinyin;


class ExcelControllers extends Controller
{

    public function __construct(ExcelImports $import, Pinyin $pinyin)
    {
        $this->import = $import;
        $this->pinyin = $pinyin;
    }

    /**
     * Pinyin (中文名字获取拼音)
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function getPy()
    {
        // upload
        if (count($_FILES) > 0) {
            if ($_FILES["excel"]["error"] > 0) {
                return response()->json(['Error' => $_FILES["excel"]["error"]]);
            }
            $result = move_uploaded_file($_FILES["excel"]["tmp_name"], public_path('Excel/' . $_FILES["excel"]["name"]));
            if ($result) {
                // Import
                $array = Excel::toArray($this->import, public_path('Excel/' . $_FILES["excel"]["name"]));
                // Pinyin
                foreach ($array[0] as $key => &$value) {
                    if ($value[0]) {
                        $len = mb_strlen($value[0], 'utf-8');
                        $name = $this->pinyin->name($value[0]);
                        $namePy = '';
                        if ($len === 2) {
                            $name[0] = ucfirst($name[0]);
                            $name[1] = ucfirst($name[1]);
                            $namePy = $name[0] . ' ' . $name[1];
                        }
                        if ($len === 3) {
                            $name[0] = ucfirst($name[0]);
                            $name[1] = ucfirst($name[1]);
                            $namePy = $name[0] . ' ' . $name[1] . $name[2];
                        }
                        array_push($value, $namePy);
                    }
                }

                // Export
                $export = new ExcelExport($array[0]);
//            return Excel::download($export, 'export.xlsx');
                if (Excel::store($export, $_FILES["excel"]["name"])) {
                    return response()->json(['code' => 1, 'message' => 'http://localhost:8090/Excel-Change/storage/app/' . $_FILES["excel"]["name"]]);
                }
                return response()->json(['code' => 0, 'message' => 'Export false!']);
            }
        }
        return response()->json(['code' => 0, 'message' => 'No Upload File！']);
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
