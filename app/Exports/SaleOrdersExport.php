<?php

namespace App\Exports;

use App\Models\SaleOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SaleOrdersExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function query()
    {
        $query = SaleOrder::query()
            ->select(
                'sale_orders.id',
                'sale_orders.status',
                'customers.name as customer_name',
                'sale_orders.created_at'
            )
            ->join('customers', 'sale_orders.customer_id', '=', 'customers.id');

        if ($this->status) {
            $query->where('sale_orders.status', $this->status);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Status',
            'Customer Name',
            'Created At',
        ];
    }

    public function map($saleOrder): array
    {
        return [
            $saleOrder->id,
            $saleOrder->status,
            $saleOrder->customer_name,
            Carbon::parse($saleOrder->created_at)
                ->setTimezone('Asia/Ho_Chi_Minh')
                ->format('d-m-Y H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

        return [];
    }
}
