<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

@php
$form_data = [
'page_title'=> 'Add Payment Plan',
'page_subtitle'=> '',
'form_name' => 'Add Payment Plan Form',
'form_id' => 'add_payment_plan',
'action' => URL::to('/').'/admin/settings/add-payment-plan',
'fields' => [
['type' => 'text', 'class' => '', 'label' => 'Name', 'name' => 'name', 'value' => ''],
['type' => 'text', 'class' => '', 'label' => 'Value', 'name' => 'value', 'value' => ''],
['type' => 'text', 'class' => '', 'label' => 'Description', 'name' => 'description', 'value' => ''],
['type' => 'text', 'class' => '', 'label' => 'Attributes', 'name' => 'attributes', 'value' => ''],
['type' => 'text', 'class' => 'form-control datepicker', 'label' => 'Validity Date', 'name' => 'validity_date', 'value' => '', 'id' => 'validity_date', 'hint' => ''],
['type' => 'select', 'options' => ['Owner' => 'Proprietário', 'Seller' => 'Corretora de Imóvel'], 'label' => 'Client Type', 'name' => 'client_type', 'value' => ''],
['type' => 'select', 'options' => ['Active' => 'Ativo', 'Inactive' => 'Inativo'], 'label' => 'Active', 'name' => 'active', 'value' => 'Active']
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

        $('#add_payment_plan').validate({
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

