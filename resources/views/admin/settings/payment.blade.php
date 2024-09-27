@php
$form_data = [
	'page_title'=> 'Payment Setting Form',
	'page_subtitle'=> 'Payment Setting Page',
	'tab_names' => ['paypal' => 'Paypal', 'stripe' => 'Stripe', 'razorpay' => 'Razorpay', 'getnet' => 'Getnet'],
	'tab_forms' => [
		'paypal' => [
			'action' => URL::to('/').'/admin/settings/payment-methods',
			'form_class' => 'form-submit-jquery',
			'form_id' => 'pay_form',
			'fields' => [
				['type' => 'hidden', 'class' => '', 'label' => '', 'id' =>'paypal', 'name' => 'gateway', 'value' => 'paypal'],
				['type' => 'text', 'class' => 'validate_field', 'label' => 'PayPal Username', 'name' => 'username', 'value' => $paypal['username']],
	      		['type' => 'text', 'class' => 'validate_field', 'label' => 'PayPal Password', 'name' => 'password', 'value' => $paypal['password']],
	      		['type' => 'text', 'class' => 'validate_field', 'label' => 'PayPal Signature', 'name' => 'signature', 'value' => $paypal['signature']],
	      		['type' => 'select', 'options' => ['sandbox' => 'Sandbox', 'live' => 'Live'], 'class' => 'validate_field', 'label' => 'PayPal Mode', 'name' => 'mode', 'value' => $paypal['mode']],
	      		['type' => 'select', 'options' => ['0' => 'Inactive', '1' => 'Active'], 'class' => 'validate_field', 'label' => 'Paypal Status', 'name' => 'paypal_status', 'value' => $paypal['paypal_status']],
			]
		],
		'stripe' => [
			'action' => URL::to('/').'/admin/settings/payment-methods',
			'form_class' => 'form-submit-jquery',
			'fields' => [
				['type' => 'hidden', 'class' => '', 'label' => '', 'id' =>'stripe', 'name' => 'gateway', 'value' => 'stripe'],
	      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Stripe Secret Key', 'name' => 'secret_key', 'value' => $stripe['secret']],
	      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Stripe Publishable Key', 'name' => 'publishable_key', 'value' => $stripe['publishable']],
	      		['type' => 'select', 'options' => ['0' => 'Inactive', '1' => 'Active'], 'class' => 'validate_field', 'label' => 'Stripe Status', 'name' => 'stripe_status', 'value' => $stripe['stripe_status']],
			]
		],
		'razorpay' => [
			'action' => URL::to('/').'/admin/settings/payment-methods',
			'form_class' => 'form-submit-jquery',
			'fields' => [
				['type' => 'hidden', 'class' => '', 'label' => '', 'id' =>'razorpay', 'name' => 'gateway', 'value' => 'razorpay'],
	      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Razorpay Key', 'name' => 'razorpay_key', 'value' => $razorpay['razorpay_key']],
	      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Razorpay Secret', 'name' => 'razorpay_secret', 'value' => $razorpay['razorpay_secret']],
	      		['type' => 'select', 'options' => ['0' => 'Inactive', '1' => 'Active'], 'class' => 'validate_field', 'label' => 'Razorpay Status', 'name' => 'razorpay_status', 'value' => $razorpay['razorpay_status']],
			]
		],

    //CUSTOM
		'getnet' => [
			'action' => URL::to('/').'/admin/settings/payment-methods',
			'form_class' => 'form-submit-jquery',
			'fields' => [
				['type' => 'hidden', 'class' => '', 'label' => '', 'id' =>'getnet', 'name' => 'gateway', 'value' => 'getnet'],
	      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Getnet Auth URL', 'name' => 'getnet_auth_url', 'value' => $getnet['getnet_auth_url']],
	      		['type' => 'text', 'class' => 'validate_field', 'label' => 'Getnet Seller ID', 'name' => 'getnet_seller_id', 'value' => $getnet['getnet_seller_id']],
            ['type' => 'text', 'class' => 'validate_field', 'label' => 'Getnet Client ID', 'name' => 'getnet_client_id', 'value' => $getnet['getnet_client_id']],
            ['type' => 'text', 'class' => 'validate_field', 'label' => 'Getnet Client Secret', 'name' => 'getnet_client_secret', 'value' => $getnet['getnet_client_secret']],
            ['type' => 'text', 'class' => 'validate_field', 'label' => 'Getnet Checkout URL', 'name' => 'getnet_checkout_url', 'value' => $getnet['getnet_checkout_url']],
            ['type' => 'text', 'class' => 'validate_field', 'label' => 'Getnet Disabled Methods', 'name' => 'getnet_disabled_methods', 'value' => $getnet['getnet_disabled_methods']],
            ['type' => 'select', 'options' => ['1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','6' => '6','7' => '7','8' => '8','9' => '9','10' => '10','11' => '11'], 'class' => 'validate_field', 'label' => 'Getnet Installments', 'name' => 'getnet_installments', 'value' => $getnet['getnet_installments']],
            ['type' => 'select', 'options' => ['0' => 'Inactive', '1' => 'Active'], 'class' => 'validate_field', 'label' => 'Getnet Virtual Card Caixa', 'name' => 'getnet_card_caixa', 'value' => $getnet['getnet_card_caixa']],
	      		['type' => 'select', 'options' => ['0' => 'Inactive', '1' => 'Active'], 'class' => 'validate_field', 'label' => 'Getnet Status', 'name' => 'getnet_status', 'value' => $getnet['getnet_status']],
			]
		],
	]
];
@endphp

@include("admin.common.form.setting-multi-tab", $form_data)


