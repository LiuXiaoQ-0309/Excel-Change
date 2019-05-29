<?php


namespace App\Services;


use App\Exports\ExcelExport;
use App\Imports\ExcelImports;
use Chumper\Zipper\Zipper;
use Maatwebsite\Excel\Facades\Excel;
use Overtrue\Pinyin\Pinyin;

/**
 * Assist ExcelControllers
 * Class ExcelService
 * @package App\Services
 */
class ExcelService
{

    public function __construct(Pinyin $pinyin, ExcelImports $excelImports)
    {
        $this->pinyin = $pinyin;

        $this->excelImports = $excelImports;
    }


    /**
     * Get Excel Data Pinyin
     * @param $array
     * @return mixed
     */
    public function getNamePinyin($array)
    {
        if ($array) {
            foreach ($array as $key => &$value) {
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
        }
        return $array;
    }


    /**
     * Excel Import To Array
     * @param $path
     * @return array
     */
    public function excelImportToArray($path)
    {
        return Excel::toArray($this->excelImports, $path);
    }


    /**
     * Save Excel Export Files
     * @param $data
     * @param $name
     * @return bool
     */
    public function excelExportStore($data, $name)
    {
        $export = new ExcelExport($data);
        return Excel::store($export, $name);
    }


    /**
     * Download Excel
     * @param $data
     * @param $name
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function excelExportDownload($data, $name)
    {
        $export = new ExcelExport($data);
        return Excel::download($export, $name);
    }


    /**
     * Set Zipper
     * @param $filesPath
     * @param $zipPath
     * @throws \Exception
     */
    public function setZipper($filesPath, $zipPath)
    {
        $zipper = new Zipper();
        $zipData = glob($filesPath);
        return $zipper->make($zipPath)->add($zipData)->close();
    }
}
