<?php


namespace App\Http\Controllers\Excel;


use App\Exports\ExcelExport;
use App\Http\Controllers\Controller;
use App\Imports\ExcelImports;
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
//        if ($_FILES["excel"]["error"] > 0) {
//            return response()->json(['Error' => $_FILES["excel"]["error"]]);
//        }
//        $result = move_uploaded_file($_FILES["excel"]["tmp_name"], public_path('Excel/' . $_FILES["excel"]["name"]));
        $result = true;
        if ($result) {
            // Import
//            $array = Excel::toArray($this->import, public_path('Excel/' . $_FILES["excel"]["name"]));
            $array = Excel::toArray($this->import, public_path('Excel/excel.xlsx'));
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
            return Excel::download($export, 'export.xlsx');

        }
    }

    public function imgChangeName()
    {
        return [123];
        // upload
//        if ($_FILES["excel"]["error"] > 0) {
//            return response()->json(['Error' => $_FILES["excel"]["error"]]);
//        }
    }
}
