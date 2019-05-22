<?php
/**
 * Created by PhpStorm.
 * User: liu.ya
 * Date: 2019/5/21
 * Time: 15:01
 */

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;

class ExcelExport implements FromArray
{
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function array(): array
    {
        return $this->invoices;
    }
}
