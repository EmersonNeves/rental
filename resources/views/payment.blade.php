@extends('template')

@section('main')
<div class="container-fluid container-fluid-90 margin-top-85 min-height">
	@if(Session::has('message'))
		<div class="row mt-5">
			<div class="col-md-12 text-13 alert mb-0 {{ Session::get('alert-class') }} alert-dismissable fade in  text-center opacity-1">
				<a href="#"  class="close " data-dismiss="alert" aria-label="close">&times;</a>
				{{ Session::get('message') }}
			</div>
		</div>
	@endif


	<div class="row justify-content-center">
		<div class="col-md-8 mb-5 mt-3 main-panel p-5 border rounded">
			<form id="checkout-form" action="{{ url('payments/create_booking') }}" method="post">
				{{ csrf_field() }}
				<div class="row justify-content-center">
				<input name="property_id" type="hidden" value="{{ $property_id }}">

			    <input name="family_price" type="hidden" value="@if(isset($family_price)){{ $family_price }}@endif">

				<input name="checkin" type="hidden" value="{{ $checkin }}">
				<input name="checkout" type="hidden" value="{{ $checkout }}">
				<input name="number_of_guests" type="hidden" value="{{ $number_of_guests }}">
				<input name="nights" type="hidden" value="{{ $nights }}">
				<input name="currency" type="hidden" value="{{ $result->property_price->code }}">
				<input name="booking_id" type="hidden" value="{{ $booking_id }}">
				<input name="booking_type" type="hidden" value="{{ $booking_type }}">

				@if($status == "" && $booking_type == "request")
					<div class="h2 pb-4 m-0 text-24">{{ trans('messages.listing_book.request_message') }}</div>
				@endif

				@if($booking_type == "instant"|| $status == "Processing" )

          <!-- CUSTOM - ADDRESS STREET -->
					<div class="col-sm-12 col-md-8 p-0 pr-md-1 pb-3">
            <label>
              {{trans('messages.payment.custom_address_street')}} <span class="text-danger">*</span>
            </label>
						<input
              required
              type="text"
              name="payment_address_street"
              id="payment_address_street"
              class="form-control text-16 mt-2"
              maxlength="20"
              data-msg-required="{{ trans('messages.jquery_validation.required') }}">
					</div>

          <!-- CUSTOM - ADDRESS NUMBER -->
					<div class="col-sm-12 col-md-4 p-0 pl-md-1 pb-3">
            <label>
              {{trans('messages.payment.custom_address_number')}} <span class="text-danger">*</span>
            </label>
						<input
              required
              type="text"
              name="payment_address_number"
              id="payment_address_number"
              class="form-control text-16 text-center mt-2"
              maxlength="6"
              data-msg-required="{{ trans('messages.jquery_validation.required') }}">
					</div>

          <!-- CUSTOM - ADDRESS NEIBORHOUGH -->
					<div class="col-sm-12 col-md-6 p-0 pr-md-1 pb-3">
            <label>
              {{trans('messages.payment.custom_address_neiborhough')}} <span class="text-danger">*</span>
            </label>
						<input
              required
              type="text"
              name="payment_address_neighborhood"
              id="payment_address_neighborhood"
              class="form-control text-16 mt-2"
              maxlength="30"
              data-msg-required="{{ trans('messages.jquery_validation.required') }}">
					</div>

          <!-- CUSTOM - ADDRESS COMPLEMENT -->
					<div class="col-sm-12 col-md-6 p-0 pl-md-1 pb-3">
            <label>
              {{trans('messages.payment.custom_address_complement')}}
            </label>
						<input
              type="text"
              name="payment_address_complement"
              id="payment_address_complement"
              class="form-control text-16 mt-2"
              maxlength="12">
					</div>

          <!-- CUSTOM - ADDRESS CITY -->
					<div class="col-sm-12 col-md-8 p-0 pr-md-1 pb-3">
            <label>
              {{trans('messages.payment.custom_address_city')}} <span class="text-danger">*</span>
            </label>
						<input
              required
              type="text"
              name="payment_address_city"
              id="payment_address_city"
              class="form-control text-16  mt-2"
              maxlength="20"
              data-msg-required="{{ trans('messages.jquery_validation.required') }}">
					</div>

          <!-- CUSTOM - ADDRESS ZIPCODE -->
					<div class="col-sm-12 col-md-4 p-0 pl-md-1 pb-3">
            <label>
              {{trans('messages.payment.custom_address_zipcode')}} <span class="text-danger">*</span>
            </label>
						<input
              required
              type="text"
              oninput="this.value = this.value.replace(/[^0-9]/g,'')"
              name="payment_address_zipcode"
              id="payment_address_zipcode"
              class="form-control text-16 text-center mt-2"
              placeholder="{{ trans('messages.payment.custom_only_digits1') }}"
              minlength="8"
              maxlength="8"
              data-msg-required="{{ trans('messages.jquery_validation.required') }}"
              data-msg-minlength="{{ trans('messages.jquery_validation.custom_minlength8') }}">
					</div>

          <!-- CUSTOM - ADDRESS COUNTRY -->
					<div class="col-sm-12 col-md-7 p-0 pr-md-1 pb-3">
            <label>
              {{trans('messages.payment.custom_address_country')}} <span class="text-danger">*</span>
            </label>
						<select name="payment_country" id="country-select" data-saving="basics1" class="form-control mb20">
							@foreach($country as $key => $value)
							<option value="{{ $key }}" {{ ($key == $default_country) ? 'selected' : '' }}>{{ $value }}</option>
							@endforeach
						</select>
					</div>

          <!-- CUSTOM - ADDRESS STATE -->
					<div class="col-sm-12 col-md-5 p-0 pl-md-1 pb-3">
            <label>
              {{trans('messages.payment.custom_address_state')}} <span class="text-danger">*</span>
            </label>
						<select
              name="payment_address_state_br"
              id="payment_address_state_br"
              class="form-control"
              style="display: hidden;">
              <option value="AC" selected>Acre</option>
              <option value="AL">Alagoas</option>
              <option value="AP">Amapá</option>
              <option value="AM">Amazonas</option>
              <option value="BA">Bahia</option>
              <option value="CE">Ceará</option>
              <option value="DF">Distrito Federal</option>
              <option value="ES">Espirito Santo</option>
              <option value="GO">Goiás</option>
              <option value="MA">Maranhão</option>
              <option value="MT">Mato Grosso</option>
              <option value="MS">Mato Grosso do Sul</option>
              <option value="MG">Minas Gerais</option>
              <option value="PA">Pará</option>
              <option value="PB">Paraíba</option>
              <option value="PR">Paraná</option>
              <option value="PE">Pernambuco</option>
              <option value="PI">Piauí</option>
              <option value="RJ">Rio de Janeiro</option>
              <option value="RN">Rio Grande do Norte</option>
              <option value="RS">Rio Grande do Sul</option>
              <option value="RO">Rondônia</option>
              <option value="RR">Roraima</option>
              <option value="SC">Santa Catarina</option>
              <option value="SP">São Paulo</option>
              <option value="SE">Sergipe</option>
              <option value="TO">Tocantins</option>
						</select>
						<input
              required
              type="text"
              name="payment_address_state"
              id="payment_address_state"
              class="form-control text-16 text-center"
              minlength="2"
              maxlength="2"
              data-msg-required="{{ trans('messages.jquery_validation.required') }}"
              data-msg-minlength="{{ trans('messages.jquery_validation.custom_minlength2') }}">
					</div>


					<!-- <div class="col-md-12 p-0">
						<label for="exampleInputEmail1">{{ trans('messages.payment.country') }}</label>
					</div>
					<div class="col-sm-12 p-0 pb-3">
						<select name="payment_country" id="country-select" data-saving="basics1" class="form-control mb20">
							@foreach($country as $key => $value)
							<option value="{{ $key }}" {{ ($key == $default_country) ? 'selected' : '' }}>{{ $value }}</option>
							@endforeach
						</select>
					</div>
           -->
					<div class="col-sm-12 col-md-8 pr-md-1 p-0 pb-3">
						<label for="exampleInputEmail1">{{ trans('messages.payment.payment_type') }}  <span class="text-danger">*</span></label>
						<select name="payment_method" class="form-control mb20" id="payment-method-select">
							@if($paypal_status->value == 1)
								<option value="paypal" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
								{{ trans('messages.payment.paypal') }}
								</option>
							@endif

							@if($stripe_status->value == 1)
								<option value="stripe" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
								{{ trans('messages.payment.stripe') }}
								</option>
							@else
								<!--<option value="">
								{{ trans('messages.payment.disable') }}
								</option>-->
							@endif

							@if($razorpay_status->value == 1)
								<option value="razorpay" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
								{{ trans('messages.payment.razorpay') }}
								</option>
							@endif

              <!-- CUSTOM -->
							@if($getnet_status->value == 1)
								<option value="getnet" data-payment-type="payment-method" data-cc-type="visa" data-cc-name="" data-cc-expire="">
								{{ trans('messages.payment.getnet') }}
								</option>
							@endif

							<option value="3" data-payment-type="payment-method" @if($price_list->total > $wallet->total) disabled @endif>
								{{ trans('messages.experience.wallet') }} ( {!! moneyFormat( $wallet->currency->symbol, $wallet->total) !!} )
							</option>

						</select>
							@if($paypal_status->value == 1)
						<div class="paypal-div">
							<span id='paypal-text'>{{ trans('messages.payment.redirect_to_paypal') }}</span>
						</div>
						@endif

            <!-- CUSTOM -->
            @if($getnet_status->value == 1)
						<div class="getnet-div">
							<span id='getnet-text'>{{ trans('messages.payment.redirect_to_getnet') }}</span>
						</div>
						@endif

					</div>

          <!-- CUSTOM - DOCUMENT -->
					<div class="col-sm-12 col-md-4 p-0 pl-md-1 pb-3">
            <label>
              {{trans('messages.payment.custom_document')}} <span class="text-danger">*</span>
            </label>
						<input
              required
              type="text"
              oninput="this.value = this.value.replace(/[^0-9]/g,'')"
              name="payment_document_number"
              id="payment_document_number"
              class="form-control text-16 text-center"
              placeholder="{{ trans('messages.payment.custom_only_digits2') }}"
              minlength="11"
              maxlength="11"
              data-msg-required="{{ trans('messages.jquery_validation.required') }}"
              data-msg-minlength="{{ trans('messages.jquery_validation.custom_minlength11') }}">
					</div>

				@endif

					<div class="col-sm-12 p-0">
						<label for="message"></label>
					</div>

					<div class="col-sm-12 p-0 pb-3">
						<textarea
              name="message_to_host"
              placeholder="{{ trans('messages.trips_active.type_message') }}"
              class="form-control mb20"
              rows="7"
              required
              data-msg-required="{{ trans('messages.jquery_validation.required') }}">
            </textarea>
					</div>

					<div class="col-sm-12 p-0 pb-3">
					    {{trans('messages.payment.cancel_desc')}}
					     <a href="{{ url('terms-of-service') }}" class="secondary-text-color" target="_blank">{{trans('messages.sign_up.service_term')}} </a>, <a href="{{ url('guest-refund') }}" class="secondary-text-color" target="_blank">{{trans('messages.sign_up.refund_policy')}}</a>, <a href="{{ url('cancellation-policies') }}" class="secondary-text-color" target="_blank">{{trans('messages.listing_sidebar.cancellation_policy')}}</a>
                    </div>

					<div class="col-sm-12 p-0 text-right mt-4">
						<button id="payment-form-submit" type="submit" class="btn vbtn-outline-success text-16 font-weight-700 pl-5 pr-5 pt-3 pb-3 getnet-script-trigger">
							<i class="spinner fa fa-spinner fa-spin d-none"></i>
							{{ ($booking_type == 'instant') ? trans('messages.listing_book.book_now') : trans('messages.property.continue') }}
						</button>
					</div>
				</div>
			</form>

      <!-- CUSTOM -->
      <div class="col-sm-12 p-0 pb-3 text-14">
        <div
          id="errors"
          class="mt-2 text-center font-weight-600"
          style="background-color: #C53B24; color: white; padding: 10px; display: none;">
        </div>
      </div>

		</div>
		<div class="col-md-4  mt-3 mb-5">
				<div class="card p-3">
					<a href="{{ url('/') }}/properties/{{ $result->id}}/{{ $result->slug}}">
						<img class="card-img-top p-2 rounded" src="{{ $result->cover_photo }}" alt="{{ $result->name }}" height="180px">
					</a>

					<div class="card-body p-2">
						<a href="{{ url('/') }}/properties/{{ $result->id}}/{{ $result->slug}}">
							<p class="text-16 font-weight-700 mb-0">{{ $result->name }}</p>
						</a>

						<p class="text-14 mt-2 text-muted mb-0">
							<i class="fas fa-map-marker-alt"></i>
							{{$result->property_address->address_line_1}}, {{ $result->property_address->state }}, {{ $result->property_address->country_name }}
						</p>

						@if($result->type=="property")
						<div class="border p-4 mt-4 text-center rounded-3">
							<p class="text-16 mb-0">
								<strong class="font-weight-700 secondary-text-color">{{ $result->property_type_name }}</strong>
								{{trans('messages.payment.for')}}
								<strong class="font-weight-700 secondary-text-color">{{ $number_of_guests }} {{trans('messages.payment.guest')}}</strong>
							</p>
							<div class="text-16"><strong>{{ date('D, M d, Y', strtotime($checkin)) }}</strong> to <strong>{{ date('D, M d, Y', strtotime($checkout)) }}</strong></div>
						</div>
						@endif
						<div class="border p-4 rounded-3 mt-4">
							@if($result->type=="property")

							@foreach( $price_list->date_with_price as $date_price)
							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{{ $date_price->date }}</p>
								</div>
								<div>
									<p class="pr-4">{!! $date_price->price !!}</p>
								</div>
							</div>
							@endforeach
							<hr>

							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{{trans('messages.payment.night')}}</p>
								</div>
								<div>
									<p class="pr-4">{{ $nights }}</p>
								</div>
							</div>

							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->property_price ) !!} x {{ $nights }} {{trans('messages.payment.nights')}}</p>
								</div>
								<div>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->total_night_price ) !!}</p>
								</div>
							</div>
							@endif

							@if($result->type=="experience" && $result->exp_booking_type=="1")
								<div class="d-flex justify-content-between text-16">
    								<div>
    									<p class="pl-4">{{trans('messages.header.check_in')}}</p>
    								</div>
    								<div>
    									<p class="pr-4">{{ date('D, M d, Y', strtotime($checkin)) }} </p>
    								</div>
							    </div>
								<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->property_price ) !!} x {{ $number_of_guests }} {{trans('messages.payment.guest')}}</p>
								</div>
								<div>
								    <?php $totol_price = $price_list->total_night_price * $number_of_guests;  ?>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $totol_price ) !!} </p>
								</div>
							</div>
							@endif

							@if($result->type=="experience" && $result->exp_booking_type=="2")
								<div class="d-flex justify-content-between text-16">
    								<div>
    									<p class="pl-4">{{trans('messages.header.check_in')}}</p>
    								</div>
    								<div>
    									<p class="pr-4">{{ date('D, M d, Y', strtotime($checkin)) }} </p>
    								</div>
							    </div>

								<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->property_price ) !!} x {{ $number_of_guests }} {{trans('messages.payment.guest')}}</p>
								</div>
								<div>
								    <?php $totol_price = $price_list->total_night_price * $number_of_guests;  ?>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $totol_price ) !!} </p>
								</div>
							</div>

							<div class="d-flex justify-content-between text-16">
								<div>
									<p class="pl-4">{{trans('messages.experience.time_slot')}}</p>
								</div>
								<div>
									<p class="pr-4">{{ Session::get('time_slot') }} </p>
								</div>
							</div>
							@endif



							@if($result->type=="experience" && $result->exp_booking_type=="3")
								<div class="d-flex justify-content-between text-16">
    								<div>
    									<p class="pl-4">{{trans('messages.header.check_in')}}</p>
    								</div>
    								<div>
    									<p class="pr-4">{{ date('D, M d, Y', strtotime($checkin)) }} </p>
    								</div>
							    </div>
							    <div class="justify-content-between text-16">

								@if(session('cart'))
    								<table class="table service-table ml-2">
                            			<thead class="thead-inverse">
                                			<tr>
                                				<th class="text-14">{{trans('messages.experience.packages_name')}}</th>
                                				<th class="text-14">{{trans('messages.experience.no_of_qty')}}</th>
                                				<th class="text-14">{{trans('messages.experience.price')}}</th>
                                			</tr>
                            			</thead>

                                            @foreach(session('cart') as $id => $details)
                                            <?php
                                                if($result->id == $details['property_id'])
                                                {
                                            ?>
                                    			<tbody>
                                        			<tr data-id="{{ $id }}">
                                        				<td class="text-14">{{ $details['name'] }}</td>
                                        				<td class="text-14">{{ $details['quantity'] }}</td>
                                        				<td class="text-14 pl-5">{!! $result->property_price->currency->symbol !!}{!! currency_fix($details['price'], $result->property_price->currency->code) !!}</td>

                                        			</tr>
                                        		</tbody>
                                        	<?php } ?>
                            			    @endforeach
    	                    	     </table>
	                    	     @endif

									<div>


									    @if(isset($family_query->price))
									    	<p class="pr-4">{!! $result->property_price->currency->symbol !!} {!! currency_fix($family_query->price, $result->property_price->currency->code) !!}</p>
										@endif

										@if($booking_id!="")

										        @if(isset($booking_packages))
    									<div class="">

                                                 <table class="table service-table ml-3">
                                        			<thead class="thead-inverse">
                                            			<tr>
                                            				<th class="text-14">{{trans('messages.experience.packages_name')}}</th>
                                            				<th class="text-14">{{trans('messages.experience.no_of_qty')}}</th>
                                            				<th class="text-14">{{trans('messages.experience.price')}}</th>
                                            			</tr>
                                        			</thead>
        		                                        @foreach($booking_packages as $booking_packages)
                                                        <?php
                                                            $pid = $booking_packages->packages_id;
                                                            $query =  DB::table('family_package')->where('id', $pid)->first();
                                                        ?>
                                                		<tbody>
                                            			<tr data-id="">
                                            				<td class="text-14">{{ $query->title }}</td>
                                            				<td class="text-14">{{ $booking_packages->qty }}</td>
                                            				<td class="text-14">{!! $result->property_price->currency->symbol !!}{!! currency_fix($query->price, $result->property_price->currency->code) !!}</td>

                                            			</tr>
                                            		</tbody>
                                            		@endforeach
	                                        	</table>
    									</div>
									@endif


    										@if(isset($family_price))
    										  <!--<p class="pr-4">{!! currency_fix($family_price, $result->property_price->currency->code) !!}</p>-->
    										@endif
										@endif
									</div>
								</div>
							@endif

							@if($price_list->service_fee)
								<div class="d-flex justify-content-between text-16">
									<div>
										<p class="pl-4">{{trans('messages.payment.service_fee')}}</p>
									</div>

									<div>
										<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->service_fee ) !!}</p>
									</div>
								</div>
							@endif

							@if($price_list->additional_guest)
								<div class="d-flex justify-content-between text-16">
									<div>
										<p class="pl-4">{{trans('messages.payment.additional_guest_fee')}}</p>
									</div>

									<div>
										<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->additional_guest ) !!}</p>
									</div>
								</div>
							@endif

							@if($price_list->security_fee)
								<div class="d-flex justify-content-between text-16">
									<div>
										<p class="pl-4">{{trans('messages.payment.security_deposit')}}</p>
									</div>

									<div>
										<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol,  $price_list->security_fee ) !!}</p>
									</div>
								</div>
							@endif

							@if($price_list->cleaning_fee)
								<div class="d-flex justify-content-between text-16">
									<div>
										<p class="pl-4">{{trans('messages.payment.cleaning_fee')}}</p>
									</div>

									<div>
										<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->cleaning_fee )!!}</p>
									</div>
								</div>
							@endif

							@if($price_list->iva_tax)
								<div class="d-flex justify-content-between text-16">
									<div>
										<p class="pl-4">{{trans('messages.property_single.iva_tax')}}</p>
									</div>

									<div>
										<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->iva_tax )!!}</p>
									</div>
								</div>
							@endif

							@if($price_list->accomodation_tax)
								<div class="d-flex justify-content-between text-16">
									<div>
										<p class="pl-4">{{trans('messages.property_single.accommodatiton_tax')}}</p>
									</div>

									<div>
										<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->accomodation_tax )!!}</p>
									</div>
								</div>
							@endif
							<hr>

							<div class="d-flex justify-content-between font-weight-700">
								<div>
									<p class="pl-4">{{trans('messages.payment.total')}}</p>
								</div>

								<div>
									<p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->total ) !!}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="card-body">
						<p class="exfont text-16">
							{{trans('messages.payment.paying_in')}}
							<strong><span id="payment-currency">{!! moneyFormat($currencyDefault->org_symbol,$currencyDefault->code) !!}</span></strong>.
							{{trans('messages.payment.your_total_charge')}}
							<strong><span id="payment-total-charge">{!! moneyFormat($currencyDefault->org_symbol, $price_eur) !!}</span></strong>.
							{{trans('messages.payment.exchange_rate_booking')}} {!! $currencyDefault->org_symbol !!} 1 to {!! moneyFormat($result->property_price->currency->org_symbol, $price_rate ) !!} {{ $result->property_price->currency_code }} ( {{trans('messages.listing_book.host_currency')}} ).
						</p>
					</div>
				</div>


		</div>
	</div>
</div>
@push('scripts')
<script type="text/javascript" src="{{ url('public/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript">

$('#payment-method-select').on('change', function(){
  var payment = $(this).val();
  if(payment == 'stripe' || payment == 'razorpay'){
    $('.cc-div').addClass('display-off');
    $('.paypal-div').addClass('display-off');
  }else {
    $('#paypal-text').html('You will be redirected to PayPal.');
    $('.cc-div').addClass('display-off');
    $('.paypal-div').removeClass('display-off');
  }

  //CUSTOM
  if (payment == 'getnet')
  {
    $('.getnet-div').removeClass('display-off');
    $('.paypal-div').addClass('display-off');
  }
  else
    $('.getnet-div').addClass('display-off');
});


//CUSTOM
function addGetnetScript()
{
  // var element = document.fiquerySelector('[data-getnet-button-class="getnet-script-trigger"]');

  // if (element)
  //   element.remove();

  let getnet_customer_document_number       = $('#payment_document_number').val();
  let getnet_customer_address_street        = $('#payment_address_street').val();
  let getnet_customer_address_street_number = $('#payment_address_street_number').val();
  let getnet_customer_address_complementary = $('#payment_address_complement').val();
  let getnet_customer_address_neighborhood  = $('#payment_address_neighborhood').val();
  let getnet_customer_address_city          = $('#payment_address_city').val();
  let getnet_customer_country               = $('#country-select').find('option:selected').val();
  let getnet_customer_address_state         = getnet_customer_country == 'BR' ? $('#payment_address_state_br').find('option:selected').val() : $('#payment_address_state').val();
  let getnet_customer_address_zipcode       = $('#payment_address_zipcode').val();

  var script = document.createElement('script');
  script.setAttribute('async','');
  script.setAttribute('src',"{{ $getnet_checkout_url }}");
  script.setAttribute('data-getnet-button-class','getnet-script-trigger');
  script.setAttribute('data-getnet-sellerid',"{{ $getnet_seller_id }}");
  script.setAttribute('data-getnet-token',"{{ $getnet_token }}");
  script.setAttribute('data-getnet-payment-methods-disable',"{{ $getnet_payment_methods_disable }}");
  script.setAttribute('data-getnet-amount',"{{ $getnet_amount }}");
  script.setAttribute('data-getnet-customerid',"{{  $getnet_customerid }}");
  script.setAttribute('data-getnet-orderid',"{{ $getnet_orderid }}");
  script.setAttribute('data-getnet-installments',"{{ $getnet_installments }}");
  script.setAttribute('data-getnet-customer-first-name',"{{ $getnet_customer_first_name }}");
  script.setAttribute('data-getnet-customer-last-name',"{{ $getnet_customer_last_name }}");
  script.setAttribute('data-getnet-customer-document-type',"{{ $getnet_customer_document_type }}");
  script.setAttribute('data-getnet-customer-email',"{{ $getnet_customer_email }}");
  script.setAttribute('data-getnet-customer-phone-number',"{{ $getnet_customer_phone_number }}");
  script.setAttribute('data-getnet-items',"{{ $getnet_items }}");
  script.setAttribute('data-getnet-card-caixa',"{{ $getnet_card_caixa }}");
  script.setAttribute('data-getnet-cvv-hidden',"false");
  script.setAttribute('data-getnet-customer-document-number',getnet_customer_document_number);
  script.setAttribute('data-getnet-customer-address-street',getnet_customer_address_street);
  script.setAttribute('data-getnet-customer-address-street-number',getnet_customer_address_street_number);
  script.setAttribute('data-getnet-customer-address-complementary',getnet_customer_address_complementary);
  script.setAttribute('data-getnet-customer-address-neighborhood',getnet_customer_address_neighborhood);
  script.setAttribute('data-getnet-customer-address-city',getnet_customer_address_city);
  script.setAttribute('data-getnet-customer-country',getnet_customer_country);
  script.setAttribute('data-getnet-customer-address-state',getnet_customer_address_state);
  script.setAttribute('data-getnet-customer-address-zipcode',getnet_customer_address_zipcode);
  script.setAttribute('data-getnet-url-callback','');

  document.body.appendChild(script);
}

//CUSTOM
$('#payment-method-select').trigger('change');

$(document).ready(function() {
    $('#checkout-form').validate({
        submitHandler: function(form,e)
        {
          //CUSTOM
          e.preventDefault();

          var errors  = $('#errors').hide();
          var payment = $('#payment-method-select').val();

          if (payment == 'getnet')
          {
            addGetnetScript();
            setTimeout(() => {
              $('#payment-form-submit').trigger('click');
            },500);
          //   var script = $('script[data-getnet-button-class="getnet-script-trigger"]')[0];

          //   if ($(script).attr('data-getnet-token') == '')
          //   {
          //     $('#errors').html('Não foi possível estabecer conexão com o serviço Getnet.');
          //     $('#errors').show();
          //     return;
          //   }

          //   let getnet_customer_document_number       = $('#payment_document_number').val();
          //   let getnet_customer_address_street        = $('#payment_address_street').val();
          //   let getnet_customer_address_street_number = $('#payment_address_street_number').val();
          //   let getnet_customer_address_complementary = $('#payment_address_complement').val();
          //   let getnet_customer_address_neighborhood  = $('#payment_address_neighborhood').val();
          //   let getnet_customer_address_city          = $('#payment_address_city').val();
          //   let getnet_customer_country               = $('#country-select').find('option:selected').val();
          //   let getnet_customer_address_state         = getnet_customer_country == 'BR' ? $('#payment_address_state_br').find('option:selected').val() : $('#payment_address_state').val();
          //   let getnet_customer_address_zipcode       = $('#payment_address_zipcode').val();

          //   $(script).attr('data-getnet-customer-document-number',getnet_customer_document_number);
          //   $(script).attr('data-getnet-customer-address-street',getnet_customer_address_street);
          //   $(script).attr('data-getnet-customer-address-street-number',getnet_customer_address_street_number);
          //   $(script).attr('data-getnet-customer-address-complementary',getnet_customer_address_complementary);
          //   $(script).attr('data-getnet-customer-address-neighborhood',getnet_customer_address_neighborhood);
          //   $(script).attr('data-getnet-customer-address-city',getnet_customer_address_city);
          //   $(script).attr('data-getnet-customer-address-state',getnet_customer_address_state);
          //   $(script).attr('data-getnet-customer-country',getnet_customer_country);
          //   $(script).attr('data-getnet-customer-address-zipcode',getnet_customer_address_zipcode);
          // }
          // else
          // {
          //   $('#checkout-form').submit();
          // }

          // $("#payment-form-submit").on("click", function (e)
          // {
          //   $("#payment-form-submit").attr("disabled", true);
          //   e.preventDefault();
          // });

          // $(".spinner").removeClass('d-none');
          // $("#save_btn-text").text("{{trans('messages.users_profile.save')}} ..");

          return true;
        }
      }
    });
});

$('#country-select').on('change', function() {
  var country = $(this).find('option:selected').text();
  $('#country-name-set').html(country);

  //CUSTOM
  if ($(this).find('option:selected').val() == 'BR')
  {
    $('#payment_address_state').hide();
    $('#payment_address_state_br').show();
  }
  else
  {
    $('#payment_address_state').show();
    $('#payment_address_state_br').hide();
  }
})

//CUSTOM
$('#country-select').trigger('change');
</script>


<!-- <script async
  src="{{ $getnet_checkout_url }}"
  data-getnet-sellerid="{{ $getnet_seller_id }}"
  data-getnet-token="{{ $getnet_token }}"
  data-getnet-payment-methods-disable="{{ $getnet_payment_methods_disable }}"
  data-getnet-amount="{{ $getnet_amount }}"
  data-getnet-customerid="{{ $getnet_customerid }}"
  data-getnet-orderid="{{ $getnet_orderid }}"
  data-getnet-installments="{{ $getnet_installments }}"
  data-getnet-customer-first-name="{{ $getnet_customer_first_name }}"
  data-getnet-customer-last-name="{{ $getnet_customer_last_name }}"
  data-getnet-customer-document-type="{{ $getnet_customer_document_type }}"
  data-getnet-customer-email="{{ $getnet_customer_email }}"
  data-getnet-customer-phone-number="{{ $getnet_customer_phone_number }}"
  data-getnet-items="{{ $getnet_items }}"
  data-getnet-card-caixa="{{ $getnet_card_caixa }}"
  data-getnet-cvv-hidden="false"
  data-getnet-customer-document-number=""
  data-getnet-customer-address-street=""
  data-getnet-customer-address-street-number=""
  data-getnet-customer-address-complementary=""
  data-getnet-customer-address-neighborhood=""
  data-getnet-customer-address-city=""
  data-getnet-customer-address-state=""
  data-getnet-customer-country=""
  data-getnet-customer-address-zipcode=""
  data-getnet-url-callback=""
  data-getnet-button-class="getnet-script-trigger">
</script> -->


@endpush
@stop