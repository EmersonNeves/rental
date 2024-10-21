<?php

/**
 * Country Controller
 *
 * Country Controller manages countries.
 *
 * @category   PaymentPlan
 * @package    migrateshop
 * @author     Migrateshop
 * @copyright  2020 migrateshop.com
 * @license
 * @version    4.0
 * @link       http://migrateshop.com
 * @email      support@migrateshop.com
 * @since      Version 1.3
 * @deprecated None
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\PaymentPlanDataTable;
use App\Http\Helpers\Common;
use App\Models\PaymentPlan;
use Validator;

class PaymentPlanController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }

    public function index(PaymentPlanDataTable $dataTable)
    {
        return $dataTable->render('admin.paymentPlan.view');
    }

    public function add(Request $request)
    {
        if (!$request->isMethod('post')) {
            return view('admin.paymentPlan.add');
        } elseif ($request->isMethod('post')) {
            $rules = array(
                'name' => 'required|max:100',
                // 'value' => 'required|numeric',
                // 'description' => 'nullable|max:255',
                // 'attributes' => 'nullable|max:255',
                // 'validity_date' => 'nullable|date',
                // 'active' => 'required|boolean',
                // 'client_type' => 'required|in:corretora_imobiliaria,proprietario,ambos',
            );

            $fieldNames = array(
                'name' => 'Nome',
                'value' => 'Valor',
                'description' => 'Descritivo',
                'attributes' => 'Atributos',
                'validity_date' => 'Data de Validade',
                'client_type' => 'Tipo de Cliente',
                'active' => 'Ativo'
            );

            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);



            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $paymentPlan = new PaymentPlan;
                $paymentPlan->name = $request->name;
                $paymentPlan->value = $request->value;
                $paymentPlan->description = $request->description;
                $paymentPlan->validity_date = $request->validity_date;
                $paymentPlan->client_type = $request->client_type;
                $paymentPlan->active = $request->active;
                $paymentPlan->save();

                if ($request->has('attributes')) {
                    $attributes = explode(',',  $request->input('attributes'));
                    foreach ($attributes as $attribute) {
                        $paymentPlan->payment_plan_attributes()->create(['attribute' => trim($attribute)]);
                    }
                }

                $this->helper->one_time_message('success', 'Plano de Pagamento adicionado com sucesso');
                return redirect('admin/settings/payment-plan');
            }
        }
    }

    public function update(Request $request)
    {
        if (!$request->isMethod('post')) {
            $data['result'] = PaymentPlan::with('payment_plan_attributes')->find($request->id);
            \Log::info($data['result']);
            return view('admin.paymentPlan.edit', $data);
        } elseif ($request->isMethod('post')) {
            $rules = array(
                'name'              => 'required|max:100' . $request->id,
                // 'value'             => 'required|max:100',
                // 'description'       => 'required|max:100',
                // 'attributes'        => 'required|max:100',
                // 'validity_date'     => 'required|max:10',
                // 'client_type'       => 'required',
                // 'active'            => 'required'

            );

            $fieldNames = array(
                'name'              => 'Name',
                'value'             => 'Value',
                'description'       => 'Description',
                'validity_date'     => 'Validity Date',
                'active'            => 'Active',
                'client_type'       => 'Client Type',
                'active'            => 'Active'
            );
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($fieldNames);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            } else {
                $paymentPlan = PaymentPlan::findOrFail($request->id);

                $paymentPlan->name = $request->name;
                $paymentPlan->value = $request->value;
                $paymentPlan->description = $request->description;
                $paymentPlan->validity_date = $request->validity_date;
                $paymentPlan->client_type = $request->client_type;
                $paymentPlan->active = $request->active;
                $paymentPlan->save();
    
                // Excluir atributos que não estão mais presentes
                if ($request->has('attributes')) {
                    $existingAttributes = $paymentPlan->payment_plan_attributes;
    
                    // Deletar atributos que não estão mais presentes
                    foreach ($existingAttributes as $attribute) {
                        if (!in_array($attribute->id, $request->input('attributes'))) {
                            $attribute->delete();
                        }
                    }
    
                    // Adicionar novos atributos
                    $attributes = explode(',',  $request->input('attributes'));
                    foreach ($attributes as $attribute) {
                        $paymentPlan->payment_plan_attributes()->create(['attribute' => trim($attribute)]);
                    }
                }
                $this->helper->one_time_message('success', 'Updated Successfully');
                return redirect('admin/settings/payment-plan');
            }
        }
    }


    public function delete(Request $request)
    {
        if (env('APP_MODE', '') != 'test') {
            PaymentPlan::find($request->id)->delete();
        }
        $this->helper->one_time_message('success', 'Deleted Successfully');
        return redirect('admin/settings/payment-plan');
    }
}
