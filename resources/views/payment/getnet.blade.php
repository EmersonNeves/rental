@extends('template')
@section('main')
<div class="container-fluid container-fluid-90 margin-top-85 min-height">
  <div class="row justify-content-center">

    <!-- PAYMENT DATA -->
    <div class="col-md-8 mb-5 mt-3 main-panel p-5 pt-10 border rounded">

      <!-- HEADER -->
      <div
        style="display: flex; justify-content: space-between;">
        <span>
          {{ trans('messages.payment.custom_getnet_title') }} GETNET
        </span>
        <span>
          <!-- <button
            id="back"
            class="btn vbtn-outline-success text-16 font-weight-700 pl-5 pr-5"
            style="margin-top: -18px;"
            onclick="history.back()">
            {{ trans('messages.payment.custom_getnet_back_message') }}
          </button> -->
        </span>
      </div>
      <hr
        class="mt-0 mb-5"/>

      <!-- TRIGGER -->
      <button
        class="getnet-script-trigger display-off">
      </button>

      <!-- MESSAGE -->
      <div
        id="message"
        class="mt-3 text-center font-weight-600">
      </div>

      <!-- LOADER -->
      <div
        id="loader"
        class="single-load ">
        <img src="{{URL::to('/')}}/public/front/img/green-loader.gif" alt="loader">
      </div>

      <!-- BUTTONS -->
      <div
        style="display: flex; justify-content: center;">
        <div>
          <button
            id="reopen"
            class="btn vbtn-success text-16 font-weight-700 mt-5 mr-2 pl-5 pr-5 pt-3 pb-3 display-off"
            onclick="$('#getnet-checkout').attr('src',$('#getnet-checkout').attr('src'))">
            {{ trans('messages.payment.custom_getnet_reopen_message') }}
          </button>
        </div>
        <div>

        </div>
      </div>

      <!-- SUCCESS -->
      <div
        id="success"
        class="text-center mt-5 display-off">
        <img
          src="{{URL::to('/')}}/public/front/img/success.gif"
          style="height: 90px;">
      </div>

    </div>

    <!-- BOOKING DATA -->
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

            <!-- CUSTOM -->
            <div class="text-16">
              <?php
                $current_lang = Session::get('language');

                if ($current_lang == 'pt')
                {
                  echo '<b>'.abbrDatePT($checkin).'</b> até ';
                  echo '<b>'.abbrDatePT($checkout).'</b>';
                }
                else
                {
                  echo '<b>'.date('D, M d, Y',strtotime($checkin)).'</b> to ';
                  echo '<b>'.date('D, M d, Y', strtotime($checkout)).'</b>';
                }
              ?>
            </div>

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
                if ($result->id == $details['property_id']) {
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
                <p class="pr-4">{!! moneyFormat($result->property_price->currency->symbol, $price_list->security_fee ) !!}</p>
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
<script async
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
  data-getnet-customer-document-number="{{ $getnet_customer_document_number }}"
  data-getnet-customer-address-street="{{ $getnet_customer_address_street }}"
  data-getnet-customer-address-street-number="{{ $getnet_customer_address_number }}"
  data-getnet-customer-address-complementary="{{ $getnet_customer_address_complement }}"
  data-getnet-customer-address-neighborhood="{{ $getnet_customer_address_neighborhood }}"
  data-getnet-customer-address-city="{{ $getnet_customer_address_city }}"
  data-getnet-customer-address-state="{{ $getnet_customer_address_state }}"
  data-getnet-customer-country="{{ $getnet_customer_address_country }}"
  data-getnet-customer-address-zipcode="{{ $getnet_customer_address_zipcode }}"
  data-getnet-url-callback=""
  data-getnet-button-class="getnet-script-trigger">
</script>
<script>
  (() =>
  {
    var getnet_token = "{{ $getnet_token }}";

    var back    = $('#back');
    var reopen  = $('#reopen');
    var loader  = $('#loader');
    var success = $('#success');
    var message = $('#message');

    message.html("{{ trans('messages.payment.custom_getnet_loading_message') }}");

    setTimeout(() =>
    {
      if (!getnet_token)
      {
        $(loader).hide();
        $(message).html("{{ trans('messages.payment.custom_getnet_connection_error') }}");

        return;
      }

      //Callback
      window.addEventListener('message',function(e)
      {
        if (e.data && e.data == 'loaded')
        {
          $(loader).hide();
          $(reopen).show();
          $(message).html("{{ trans('messages.payment.custom_getnet_loaded_message') }}");
        }
        else
        if (e.data && e.data == 'success')
        {
          $(loader).hide();
          $(reopen).hide();
          $(back).hide();
          $(success).show();
          $(message).html("{{ trans('messages.payment.custom_getnet_payment_success') }}");

          $('#getnet-checkout').hide();
        }
      });

      //Trigger
      $('.getnet-script-trigger').trigger('click');

    },1000);
  })();
</script>
@endpush
@stop