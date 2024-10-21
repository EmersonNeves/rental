<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

@php
$form_data = [
'page_title'=> 'Edit Payment Plane',
'page_subtitle'=> '',
'form_name' => 'Edit Payment Plan Form',
'form_id' => 'edit_payment_plan',
'action' => URL::to('/').'/admin/settings/edit-payment-plan/'.$result->id,
'fields' => [
['type' => 'text', 'class' => '', 'label' => 'Name', 'name' => 'name', 'value' => $result->name],
['type' => 'text', 'class' => '', 'label' => 'Value', 'name' => 'value', 'value' => $result->value],
['type' => 'text', 'class' => '', 'label' => 'Description', 'name' => 'description', 'value' => $result->description],
['type' => 'text', 'class' => '', 'label' => 'Attributes', 'name' => 'attributes', 'value' => $result->payment_plan_attributes->pluck('attribute')->implode(', ')],
['type' => 'text', 'class' => 'form-control datepicker', 'label' => 'Validity Date', 'name' => 'validity_date', 'value' => $result->validity_date, 'id' => 'validity_date', 'hint' => ''],
['type' => 'select', 'options' => ['Owner' => 'Proprietário', 'Seller' => 'Corretora de Imóvel'], 'label' => 'Client Type', 'name' => 'client_type', 'value' => $result->name],
['type' => 'select', 'options' => ['Active' => 'Ativo', 'Inactive' => 'Inativo'], 'label' => 'Active', 'name' => 'active', 'value' => $result->active]
]
];
@endphp
@include("admin.common.form.setting", $form_data)

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd', // Formato da data
            autoclose: true, // Fecha o calendário após a seleção
            todayHighlight: true // Destaque a data de hoje
        });
    });
</script>


<script type="text/javascript">
    $(document).ready(function() {

        $('#edit_payment_plan').validate({
            rules: {
                name: {
                    required: true
                },
                value: {
                    required: true
                },
                description: {
                    required: true
                },
                attributes: {
                    required: true
                },
                validity_date: {
                    required: false
                },
                client_type: {
                    required: true
                },
                active: {
                    required: true
                }

            }
        });
    });
</script>