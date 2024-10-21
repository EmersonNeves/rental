<?php

namespace App\DataTables;

use App\Models\PaymentPlan;
use Yajra\DataTables\Services\DataTable;

class PaymentPlanDataTable extends DataTable
{
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->addColumn('attributes', function ($paymentPlan) {
                return $paymentPlan->payment_plan_attributes->pluck('attribute')->implode(', ');
            })
            ->addColumn('action', function ($paymentPlan) {

                $edit = '<a href="' . url('admin/settings/edit-payment-plan/' . $paymentPlan->id) . '" class="btn btn-xs svbtn"><i class="fa fa-pencil"></i></a>';
                $delete = '<a href="' . url('admin/settings/delete-payment-plan/' . $paymentPlan->id) . '" class="btn btn-xs svbtn delete-warning"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit . ' ' . $delete;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function query()
    {
        $query = PaymentPlan::with('payment_plan_attributes');
        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'payment_plans.id', 'title' => 'ID'])
            ->addColumn(['data' => 'name', 'name' => 'payment_plans.name', 'title' => 'Name'])
            ->addColumn(['data' => 'value', 'name' => 'payment_plans.value', 'title' => 'Value'])
            ->addColumn(['data' => 'description', 'name' => 'payment_plans.description', 'title' => 'Description'])
            ->addColumn(['data' => 'attributes', 'name' => 'attributes', 'title' => 'Attributes'])
            ->addColumn(['data' => 'validity_date', 'name' => 'payment_plans.validity_date', 'title' => 'Validity Date'])
            ->addColumn(['data' => 'active', 'name' => 'payment_plans.active', 'title' => 'Active'])
            ->addColumn(['data' => 'client_type', 'name' => 'payment_plans.client_type', 'title' => 'Client Type'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
            ->parameters([
                'dom' => 'lBfrtip',
                'buttons' => [],
                'order' => [0, 'desc'],
                'pageLength' => \Session::get('row_per_page'),
            ]);
    }
    
    protected function getColumns()
    {
        return [
            'id',
            'created_at',
            'updated_at',
        ];
    }

    protected function filename()
    {
        return 'paymentPlandatatables_' . time();
    }
}
