@extends('template')
@push('css')
<link rel="stylesheet" type="text/css" href="{{ url('public/css/daterangepicker.min.css')}}" />
<link rel="stylesheet" type="text/css" href="{{ url('public/css/glyphicon.css') }}" />

@endpush
<?php
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
function isMobile()
{
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

if (isMobile()) {
    if (preg_match('/ipad/i', $ua)) {
        $platform = 'iPad';
    } else {
        $platform = "mobile";
    }
}
if (!isMobile()) {
    $platform = "desktop";
}

if (strstr($_SERVER['HTTP_USER_AGENT'], 'iPhone')) {
    $platform = "mobile";
}

$user_ip = getenv('REMOTE_ADDR');
$geo     = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
$address = $geo["geoplugin_city"] . ',' . $geo["geoplugin_region"] . ',' . $geo["geoplugin_countryName"];

?>

@section('main')
<input type="hidden" id="front_date_format_type" value="{{ Session::get('front_date_format_type')}}">

@if(Session::has('isuccess'))
<div class="row">
    <div class="col-md-12 p-4 text-15  alert alert-success alert-dismissable fade in opacity-1" style="z-index:99999">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ Session::get('isuccess') }}
    </div>
</div>
@endif

<section class="hero-banner magic-ball home">
    <!--Desktop view-->

    <div class="main-banner">
        <?php if ($platform == 'desktop' || $platform == 'iPad') { ?>
            <div class="align-items-center text-center text-md-left desk-search-form container mt-80">

                @if($enable_experience == "Yes")
                <ul class="nav text-center" id="myTab" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" id="property-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">{{trans('messages.home.places_to_stay')}}</a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" id="exp-tab" data-toggle="tab" href="#home" role="tab" aria-controls="profile" aria-selected="false">{{trans('messages.home.experience')}}</a>
                    </li>
                </ul>
                @endif
                <div class="tab-content pt-5" id="myTabContent">
                    <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row mt-3">
                            <div class="col-md-10 mx-auto">
                                <div class="main_formbg item animated zoomIn mob-form-bg">
                                    <form id="front-search-form" method="post" action="{{url('search')}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" id="type" name="type" value="property">
                                        <div class="row">
                                            <div class="col-md-4 exp_location">
                                                <label>{{trans('messages.search.location')}}</label>
                                                <input class="form-control p-3 text-14" id="front-search-field" placeholder="{{trans('messages.home.where_want_to_go')}}" autocomplete="off" name="location" type="text" required>
                                            </div>

                                            <div class="col-md-4 nopadding exp_dates">
                                                <div class="row" id="daterange-btn">
                                                    <div class="col-md-6 col-6 mt-4 mt-md-0 mob-pd-0">
                                                        <label>{{trans('messages.search.check_in')}}</label>
                                                        <input class="form-control p-3  text-14 checkinout" name="checkin" id="startDate" type="text" placeholder="{{trans('messages.search.add_dates')}}" autocomplete="off" readonly="readonly" required>
                                                    </div>
                                                    <div class="col-md-6 col-6 mt-4 mt-md-0 mob-pd-0">
                                                        <label>{{trans('messages.search.check_out')}}</label>
                                                        <input class="form-control p-3 text-14 checkinout" name="checkout" id="endDate" placeholder="{{trans('messages.search.add_dates')}}" type="text" readonly="readonly" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3 border-right-0 mt-4 mt-md-0" id="svguest">
                                                <label>{{trans('messages.home.guest')}}</label>
                                                <select id="front-search-guests" class="form-control  text-14">
                                                    <option class="p-4 text-14" value="1">1 {{trans('messages.home.guest')}}</option>
                                                    @for($i=2;$i<=16;$i++)
                                                        <option class="p-4 text-14" value="{{ $i }}"> {{ ($i == '16') ? $i.'+ '.trans('messages.home.guest') : $i.' '.trans('messages.property_single.guest') }} </option>
                                                        @endfor
                                                </select>
                                            </div>

                                            <div class="col-md-1 front-search mt-2 border-right-0 d-none d-sm-block">
                                                <button type="submit" class="btn vbtn-default btn-block p-3 text-16"><i class="fa fa-search"></i></button>
                                            </div>
                                            <div class="col-12 d-block d-sm-none front-search mt-2">
                                                <button type="submit" class="btn vbtn-default btn-block p-3 text-16"><i class="fa fa-search"></i> {{trans('messages.home.search')}}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    </div>

                </div>


            </div>
        <?php } ?>

        @php $licount = 0; @endphp
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
            <ol class="carousel-indicators">
                @foreach($banner as $row_photos)
                <li data-target="#carouselExampleIndicators" data-slide-to="{{ $licount }}" class="<?php if ($licount == 0) {
                                                                                                        echo 'active';
                                                                                                    } ?>"></li>
                @php $licount++; @endphp
                @endforeach
            </ol>

            <div class="carousel-inner">
                @php $count = 0; @endphp
                @foreach($banner as $row_photos)
                <div class="carousel-item <?php if ($count == 0) {
                                                echo 'active';
                                            } ?>">
                    <img class="d-block w-100" src="{{url('public/front/images/banners/'.$row_photos->image)}}" alt="First slide">
                    <div class="carousel-caption ">
                        <?php
                        $id = $row_photos->temp_id;
                        $query =  \App\Models\Banners::where('lang', '=', 'en')->where('temp_id', '=', $id)->first();
                        ?>
                        <div class="sv_home_subsec text-center">
                            @php echo('Teste') @endphp
                            <h2 class="banner-title">@if($row_photos->heading=="") {{ $query->heading }} @else {{ $row_photos->heading }} @endif</h2>
                            <h4 class="banner-title">@if($row_photos->subheading=="") {{ $query->subheading }} @else {{ $row_photos->subheading }} @endif</h4>
                            <div align="center" class="flexible-btn mt-5">
                                <a class="btn pl-5 pr-5 text-16 btn vbtn-default br-50" onclick="getLocation()" href="#">{{trans('messages.home.am_flexible')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @php $count++; @endphp
                @endforeach
            </div>
        </div>
    </div>

    <!--Desktop view end-->



    <!--Mobile view-->
    <?php if ($platform == "mobile") { ?>
        <div class="align-items-center text-center text-md-left svmobsearch desk-search-form container mt-3">

            @if($enable_experience == "Yes")
            <ul class="nav text-center" id="myTab" role="tablist">
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link active" id="property-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">{{trans('messages.home.places_to_stay')}}</a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link" id="exp-tab" data-toggle="tab" href="#home" role="tab" aria-controls="profile" aria-selected="false">{{trans('messages.home.experience')}}</a>
                </li>
            </ul>
            @endif
            <div class="tab-content pt-2" id="myTabContent">
                <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="row mt-3">
                        <div class="col-md-10 mx-auto">
                            <div class="main_formbg item animated zoomIn mob-form-bg">
                                <form id="front-search-form" method="post" action="{{url('search')}}">
                                    {{ csrf_field() }}
                                    <input type="hidden" id="type" name="type" value="property">
                                    <div class="row">
                                        <div class="col-md-4 exp_location">
                                            <label>{{trans('messages.search.location')}}</label>
                                            <input class="form-control p-3 text-14" id="front-search-field" placeholder="{{trans('messages.home.where_want_to_go')}}" autocomplete="off" name="location" type="text" required>
                                        </div>

                                        <div class="col-md-4 nopadding exp_dates">
                                            <div class="row" id="daterange-btn">
                                                <div class="col-md-6 col-6 mt-4 mt-md-0 mob-pd-0">
                                                    <label>{{trans('messages.search.check_in')}}</label>
                                                    <input class="form-control p-3  text-14 checkinout" name="checkin" id="startDate" type="text" placeholder="{{trans('messages.search.add_dates')}}" autocomplete="off" readonly="readonly" required>
                                                </div>
                                                <div class="col-md-6 col-6 mt-4 mt-md-0 mob-pd-0">
                                                    <label>{{trans('messages.search.check_out')}}</label>
                                                    <input class="form-control p-3 text-14 checkinout" name="checkout" id="endDate" placeholder="{{trans('messages.search.add_dates')}}" type="text" readonly="readonly" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 border-right-0 mt-4 mt-md-0" id="svguest">
                                            <label>{{trans('messages.home.guest')}}</label>
                                            <select id="front-search-guests" class="form-control  text-14">
                                                <option class="p-4 text-14" value="1">1 {{trans('messages.home.guest')}}</option>
                                                @for($i=2;$i<=16;$i++)
                                                    <option class="p-4 text-14" value="{{ $i }}"> {{ ($i == '16') ? $i.'+ '.trans('messages.home.guest') : $i.' '.trans('messages.property_single.guest') }} </option>
                                                    @endfor
                                            </select>
                                        </div>

                                        <div class="col-md-1 front-search mt-2 border-right-0 d-none d-sm-block">
                                            <button type="submit" class="btn vbtn-default btn-block p-3 text-16"><i class="fa fa-search"></i></button>
                                        </div>
                                        <div class="col-12 d-block d-sm-none front-search mt-2">
                                            <button type="submit" class="btn vbtn-default btn-block p-3 text-16"><i class="fa fa-search"></i> {{trans('messages.home.search')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                </div>

            </div>
        </div>
    <?php } ?>
    <!--Mobile view end-->
</section>


@if(!$properties->isEmpty())
<section class="recommandedbg bg-gray mt-4 magic-ball magic-ball-about pb-5">
    <div class="container-fluid container-fluid-90">
        <div class="row">
            <div class="recommandedhead col-md-12">
                <p class="item animated fadeIn text-30 font-weight-700 m-0">{{trans('messages.home.recommended_home')}}</p>
            </div>
        </div>
        <div class="row mt-3">
            @foreach($properties as $property)
            <div class="col-md-6 col-lg-4 col-xl-3 pl-3 pr-3 pb-3 mt-4">
                <div class="card h-100 card-shadow card-1">
                    <div class="">
                        <a href="properties/{{ $property->id }}/{{ $property->slug }}" aria-label="{{ $property->name}}">
                            <figure class="">
                                <img src="{{ $property->cover_photo }}" class="room-image-container200" alt="{{ $property->name}}" />
                                <figcaption>
                                </figcaption>
                            </figure>
                        </a>
                        <div class="wishicon svwishlist<?php echo $property->id; ?>">
                            <?php
                            if (Auth::check()) {
                                $countid = \App\Models\Wishlist::where('userid', '=', \Auth::user()->id)->where('propertyid', '=', $property->id)->get();
                                $totalCount = count($countid);

                                if ($totalCount > 0) {
                                    $status =  \App\Models\Wishlist::where('userid', '=', \Auth::user()->id)->where('propertyid', '=', $property->id)->first()->status;
                                    if ($status == 1) {
                            ?>
                                        <i class="icon icon-heart" id="closedid<?php echo $property->id; ?>" onclick="removethis<?php echo $property->id; ?>(<?php echo $property->id; ?>);"></i>
                                    <?php } else { ?>
                                        <i class="icon icon-heart-alt" id="wishlistid<?php echo $property->id; ?>" onclick="addthis<?php echo $property->id; ?>(<?php echo $property->id; ?>);"></i>
                                    <?php }
                                } else { ?>
                                    <i class="icon icon-heart-alt" id="wishlistid<?php echo $property->id; ?>" onclick="addthis<?php echo $property->id; ?>(<?php echo $property->id; ?>);"></i>
                                <?php } ?>

                            <?php } else { ?>
                                <i class="icon icon-heart-alt b-login" id="wishlistid<?php echo $property->id; ?>"></i>
                            <?php } ?>
                        </div>


                    </div>

                    <?php
                    $property_meta_count = \App\Models\PropertyMeta::where('property_id', '=', $property->id)->where('lang', '=', $current_lang1)->count();
                    if ($property_meta_count != "0") {
                        $property_meta = \App\Models\PropertyMeta::where('property_id', '=', $property->id)->where('lang', '=', $current_lang1)->first();
                        if ($property_meta->name == "") {
                            $property_name = $property->name;
                        } else {
                            $property_name = $property_meta->name;
                        }
                    } else {
                        $property_name = $property->name;
                    }
                    ?>

                    <div class="card-body p-0 pl-1 pr-1">
                        <div class="d-flex">
                            <div class="text">
                                <a class="text-color text-color-hover" href="properties/{{ $property->id }}/{{ $property->slug }}">
                                    <p class="text-14 font-weight-700 text margin-bottom-zero"> {{ $property_name }}</p>
                                </a>
                            </div>

                        </div>

                        <div>
                            <ul class="list-inline">
                                <li class="list-inline-item text-dark">
                                    <div class="vtooltip">
                                        <span class="text-13 text-muted">{{ $property->accommodates }} {{trans('messages.property_single.guest')}}</span>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="vtooltip">
                                        <span class="text-13 text-muted">{{ $property->bedrooms }} {{trans('messages.property_single.bedroom')}}</span>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="vtooltip">
                                        <span class="text-13 text-muted">{{ $property->bathrooms }} {{trans('messages.property_single.bathroom')}}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="review-0">
                            <div class="d-flex justify-content-between">

                                <div>
                                    <p class="text-13 mt-2 mb-0 text"><i class="fas fa-map-marker-alt"></i> {{$property->property_address->city}}</p>
                                </div>

                                <div>
                                    <span><i class="fa fa-star text-14 yellow_color"></i>
                                        @if( $property->guest_review)
                                        {{ $property->avg_rating }}
                                        @else
                                        0
                                        @endif
                                        ({{ $property->guest_review }})</span>
                                </div>

                            </div>
                        </div>

                        <div class="svred text-14">
                            <span class="font-weight-700">{!! moneyFormat( $property->property_price->currency->symbol, $property->property_price->price) !!} / {{trans('messages.property_single.night')}} </span>
                            @if($property->booking_type=="instant")
                            <i class="icon icon-instant-book yellow_color text-25" aria-hidden="true"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function addthis<?php echo $property->id; ?>(id) {
                    $.ajax({
                        type: 'post',
                        url: '{{url(' / wishlist / ')}}',
                        data: {
                            wishid: id,
                            '_token': '{{csrf_token()}}'
                        },
                        success: function(data) {
                            $(".svwishlist<?php echo $property->id; ?>").load(location.href + " .svwishlist<?php echo $property->id; ?>");
                            document.getElementById('wishlistid<?php echo $property->id; ?>').style.display = 'none';
                            document.getElementById('closedid<?php echo $property->id; ?>').style.display = 'block';
                        }
                    });
                }
            </script>

            <script>
                function removethis<?php echo $property->id; ?>(id) {
                    $.ajax({
                        type: 'post',
                        url: '{{url('
                        wishlistremove / ')}}',
                        data: {
                            wishid: id,
                            '_token': '{{csrf_token()}}'
                        },
                        success: function(data) {
                            $(".svwishlist<?php echo $property->id; ?>").load(location.href + " .svwishlist<?php echo $property->id; ?>");
                            document.getElementById('wishlistid<?php echo $property->id; ?>').style.display = 'block';
                            document.getElementById('closedid<?php echo $property->id; ?>').style.display = 'none';
                        }
                    });
                }
            </script>
            @endforeach
        </div>
    </div>
</section>
@endif


@if(!$recentproperties->isEmpty())
<section class="recommandedbg bg-gray mt-4 magic-ball magic-ball-about pb-5">
    <div class="container-fluid container-fluid-90">
        <div class="row">

            <div class="recommandedhead col-md-12">
                <p class="item animated fadeIn text-30 font-weight-700 m-0">{{trans('messages.home.recent_home')}}</p>
            </div>
        </div>

        <div class="row mt-3">

            @foreach($recentproperties as $property)
            <div class="col-md-6 col-lg-4 col-xl-3 pl-3 pr-3 pb-3 mt-4">
                <div class="card h-100 card-shadow card-1">
                    <div class="">
                        <a href="properties/{{ $property->id }}/{{ $property->slug }}" aria-label="{{ $property->name}}">
                            <figure class="">
                                <img src="{{ $property->cover_photo }}" class="room-image-container200" alt="{{ $property->name}}" />
                                <figcaption>
                                </figcaption>
                            </figure>
                        </a>
                    </div>

                    <div class="card-body p-0 pl-1 pr-1">
                        <div class="d-flex">
                            <?php
                            $property_meta_count = \App\Models\PropertyMeta::where('property_id', '=', $property->id)->where('lang', '=', $current_lang1)->count();
                            if ($property_meta_count != "0") {
                                $property_meta = \App\Models\PropertyMeta::where('property_id', '=', $property->id)->where('lang', '=', $current_lang1)->first();
                                if ($property_meta->name == "") {
                                    $property_name = $property->name;
                                } else {
                                    $property_name = $property_meta->name;
                                }
                            } else {
                                $property_name = $property->name;
                            }
                            ?>
                            <div class="text">
                                <a class="text-color text-color-hover" href="properties/{{ $property->id }}/{{ $property->slug }}">
                                    <p class="text-14 font-weight-700 text margin-bottom-zero"> {{ $property_name }} </p>
                                </a>
                            </div>
                        </div>

                        <div class="wishicon svwishlist<?php echo $property->id; ?>">
                            <?php
                            if (Auth::check()) {
                                $countid = \App\Models\Wishlist::where('userid', '=', \Auth::user()->id)->where('propertyid', '=', $property->id)->get();
                                $totalCount = count($countid);

                                if ($totalCount > 0) {
                                    $status =  \App\Models\Wishlist::where('userid', '=', \Auth::user()->id)->where('propertyid', '=', $property->id)->first()->status;
                                    if ($status == 1) {
                            ?>
                                        <i class="icon icon-heart" id="closedid<?php echo $property->id; ?>" onclick="removethis<?php echo $property->id; ?>(<?php echo $property->id; ?>);"></i>
                                    <?php } else { ?>
                                        <i class="icon icon-heart-alt" id="wishlistid<?php echo $property->id; ?>" onclick="addthis<?php echo $property->id; ?>(<?php echo $property->id; ?>);"></i>
                                    <?php }
                                } else { ?>
                                    <i class="icon icon-heart-alt" id="wishlistid<?php echo $property->id; ?>" onclick="addthis<?php echo $property->id; ?>(<?php echo $property->id; ?>);"></i>
                                <?php } ?>

                            <?php } else { ?>
                                <i class="icon icon-heart-alt b-login" id="wishlistid<?php echo $property->id; ?>"></i>
                            <?php } ?>
                        </div>

                        <div>
                            <ul class="list-inline">
                                <li class="list-inline-item text-dark">
                                    <div class="vtooltip">
                                        <span class="text-13 text-muted">{{ $property->accommodates }} {{trans('messages.property_single.guest')}}</span>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="vtooltip">
                                        <span class="text-13 text-muted">{{ $property->bedrooms }} {{trans('messages.property_single.bedroom')}}</span>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="vtooltip">
                                        <span class="text-13 text-muted">{{ $property->bathrooms }} {{trans('messages.property_single.bathroom')}}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="review-0">
                            <div class="d-flex justify-content-between">

                                <div>
                                    <p class="text-13 mt-2 mb-0 text"><i class="fas fa-map-marker-alt"></i> {{$property->property_address->city}}</p>
                                </div>

                                <div>
                                    <span><i class="fa fa-star text-14 yellow_color"></i>
                                        @if( $property->guest_review)
                                        {{ $property->avg_rating }}
                                        @else
                                        0
                                        @endif
                                        ({{ $property->guest_review }})</span>
                                </div>

                            </div>
                        </div>

                        <div class="svred">
                            <span class="font-weight-700">{!! moneyFormat( $property->property_price->currency->symbol, $property->property_price->price) !!} / {{trans('messages.property_single.night')}} </span>
                            @if($property->booking_type=="instant")
                            <i class="icon icon-instant-book yellow_color text-25" aria-hidden="true"></i>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
            <script>
                function addthis<?php echo $property->id; ?>(id) {
                    $.ajax({
                        type: 'post',
                        url: '{{url(' / wishlist / ')}}',
                        data: {
                            wishid: id,
                            '_token': '{{csrf_token()}}'
                        },
                        success: function(data) {
                            $(".svwishlist<?php echo $property->id; ?>").load(location.href + " .svwishlist<?php echo $property->id; ?>");
                            document.getElementById('wishlistid<?php echo $property->id; ?>').style.display = 'none';
                            document.getElementById('closedid<?php echo $property->id; ?>').style.display = 'block';
                        }
                    });
                }
            </script>

            <script>
                function removethis<?php echo $property->id; ?>(id) {
                    $.ajax({
                        type: 'post',
                        url: '{{url('
                        wishlistremove / ')}}',
                        data: {
                            wishid: id,
                            '_token': '{{csrf_token()}}'
                        },
                        success: function(data) {
                            $(".svwishlist<?php echo $property->id; ?>").load(location.href + " .svwishlist<?php echo $property->id; ?>");
                            document.getElementById('wishlistid<?php echo $property->id; ?>').style.display = 'block';
                            document.getElementById('closedid<?php echo $property->id; ?>').style.display = 'none';
                        }
                    });
                }
            </script>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($enable_experience == "Yes")
@if(!$experience->isEmpty())
<section class="recommandedbg bg-gray mt-4 magic-ball magic-ball-about pb-5 experience">
    <div class="container-fluid container-fluid-90">
        <div class="row">
            <div class="recommandedhead col-md-12">
                <p class="item animated fadeIn text-30 font-weight-700 m-0">{{trans('messages.experience.recent_experience')}}</p>
            </div>
        </div>

        <div class="row mt-3">

            @foreach($experience as $property)
            <div class="col-md-6 col-lg-6 col-xl-6 pl-3 pr-3 pb-3 mt-4">
                <div class="card h-100 card-shadow card-1">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="experiences/{{ $property->id }}/{{ $property->slug }}" aria-label="{{ $property->name}}">
                                <figure class="">
                                    <img src="{{ $property->cover_photo }}" class="room-image-container200" alt="{{ $property->name}}" />
                                    <figcaption>
                                    </figcaption>
                                </figure>
                            </a>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex card-body p-0 pl-1 pr-1">
                                <?php
                                $property_meta_count = \App\Models\PropertyMeta::where('property_id', '=', $property->id)->where('lang', '=', $current_lang1)->count();
                                if ($property_meta_count != "0") {
                                    $property_meta = \App\Models\PropertyMeta::where('property_id', '=', $property->id)->where('lang', '=', $current_lang1)->first();
                                    if ($property_meta->name == "") {
                                        $property_name = $property->name;
                                    } else {
                                        $property_name = $property_meta->name;
                                    }
                                } else {
                                    $property_name = $property->name;
                                }
                                ?>
                                <p class="text-13 mt-2 mb-0 text text-uppercase">{{$property->property_address->city}}</p>
                            </div>

                            <div class="text">
                                <a class="text-color text-color-hover" href="experiences/{{ $property->id }}/{{ $property->slug }}">
                                    <p class="text-14 font-weight-700 text margin-bottom-zero"> {{ $property_name }} </p>
                                </a>
                            </div>
                            <div>
                                <ul class="list-inline">
                                    <li class="list-inline-item text-dark">
                                        <div class="vtooltip">
                                            <span class="text-14 text-muted">{{ $property->accommodates }} {{trans('messages.experience.people')}}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <p class="text-14 mt-2 mb-0 text">{{$property->duration}}</p>
                            <div class="review-0 mt-5">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        @if($property->exp_booking_type=="3")
                                        <div class="svred">
                                            <span class="font-weight-700"> {{trans('messages.experience.packages')}}</span>
                                            @if($property->booking_type=="instant")
                                            <i class="icon icon-instant-book yellow_color text-25" aria-hidden="true"></i>
                                            @endif
                                        </div>
                                        @else
                                        <div class="svred">
                                            <span class="font-weight-700">{!! moneyFormat( $property->property_price->currency->symbol, $property->property_price->price) !!} </span>
                                            @if($property->booking_type=="instant")
                                            <i class="icon icon-instant-book yellow_color text-25" aria-hidden="true"></i>
                                            @endif
                                        </div>
                                        @endif

                                    </div>
                                    <div>
                                        <span><i class="fa fa-star text-14 yellow_color"></i>
                                            @if( $property->guest_review)
                                            {{ $property->avg_rating }}
                                            @else
                                            0
                                            @endif
                                            ({{ $property->guest_review }})</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif

@if($enable_experience == "Yes")
@if(!$recommended_experience->isEmpty())
<section class="recommandedbg bg-gray mt-4 magic-ball magic-ball-about pb-5 experience">
    <div class="container-fluid container-fluid-90">
        <div class="row">
            <div class="recommandedhead col-md-12">
                <p class="item animated fadeIn text-30 font-weight-700 m-0">{{trans('messages.experience.recommended_experience')}}</p>
            </div>
        </div>

        <div class="row mt-3">
            @foreach($recommended_experience as $property)
            <div class="col-md-3 col-lg-3 col-xl-3 pl-3 pr-3 pb-3 mt-4">
                <div class="card h-100 card-shadow card-1">

                    <a href="experiences/{{ $property->id }}/{{ $property->slug }}" aria-label="{{ $property->name}}">
                        <figure class="">
                            <img src="{{ $property->cover_photo }}" class="room-image-container200" alt="{{ $property->name}}" />
                            <figcaption>
                            </figcaption>
                        </figure>
                    </a>
                    <div class="d-flex card-body p-0 pl-1 pr-1">
                        <?php
                        $property_meta_count = \App\Models\PropertyMeta::where('property_id', '=', $property->id)->where('lang', '=', $current_lang1)->count();
                        if ($property_meta_count != "0") {
                            $property_meta = \App\Models\PropertyMeta::where('property_id', '=', $property->id)->where('lang', '=', $current_lang1)->first();
                            if ($property_meta->name == "") {
                                $property_name = $property->name;
                            } else {
                                $property_name = $property_meta->name;
                            }
                        } else {
                            $property_name = $property->name;
                        }
                        ?>
                        <p class="text-13 mt-2 mb-0 text text-uppercase">{{$property->property_address->city}}</p>
                    </div>

                    <div class="text">
                        <a class="text-color text-color-hover" href="experiences/{{ $property->id }}/{{ $property->slug }}">
                            <p class="text-14 font-weight-700 text margin-bottom-zero"> {{ $property_name }} </p>
                        </a>
                    </div>

                    <div>
                        <ul class="list-inline">
                            <li class="list-inline-item text-dark">
                                <div class="vtooltip">
                                    <span class="text-14 text-muted">{{ $property->accommodates }} {{trans('messages.experience.people')}}</span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <p class="text-14 mt-2 mb-0 text">{{$property->duration}}</p>
                    <div class="review-0">
                        <div class="d-flex justify-content-between">

                            <div>
                                @if($property->exp_booking_type=="3")
                                <div class="svred">
                                    <span class="font-weight-700"> {{trans('messages.experience.packages')}}</span>
                                    @if($property->booking_type=="instant")
                                    <i class="icon icon-instant-book yellow_color text-25" aria-hidden="true"></i>
                                    @endif
                                </div>
                                @else
                                <div class="svred">
                                    <span class="font-weight-700">{!! moneyFormat( $property->property_price->currency->symbol, $property->property_price->price) !!} </span>
                                    @if($property->booking_type=="instant")
                                    <i class="icon icon-instant-book yellow_color text-25" aria-hidden="true"></i>
                                    @endif
                                </div>
                                @endif
                            </div>
                            <div>
                                <span><i class="fa fa-star text-14 yellow_color"></i>
                                    @if( $property->guest_review)
                                    {{ $property->avg_rating }}
                                    @else
                                    0
                                    @endif
                                    ({{ $property->guest_review }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif

@if(!$starting_cities->isEmpty())
<section class="bg-gray mt-5">
    <div class="container-fluid container-fluid-90">
        <div class="row">
            <div class="col-md-12">
                <p class="item animated fadeIn text-30 font-weight-700 m-0 text-capitalize">{{trans('messages.home.top_destination')}}</p>
            </div>
        </div>

        <div class="row mt-2 svcity">
            @foreach($starting_cities as $city)
            <div class="col-xl-3 col-lg-3 col-md-6 mt-4 col-6 sv-city-pd">
                <a href="{{URL::to('/')}}/search?location={{ $city->name }}&checkin=&checkout=&guest=1">
                    <div class="grid item animated zoomIn">
                        <figure class="effect-ming">
                            <img src="{{ $city->image_url }}" alt="city" />
                            <figcaption>
                                <p class="text-15 font-weight-600">{{$city->name}}</p>
                            </figcaption>
                        </figure>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif



<section class="pb-70 mt-5">
    <div class="container-fluid container-fluid-90">
        <div class="row tryhosting" style="background:url(<?php echo $try_hosting_img; ?>);">
            <div class="col-md-12">
                <h2 class="font-weight-600 mb-2">{{ trans('messages.home.try_hosting') }}</h2>
                <p class="text-20">{{ trans('messages.home.hosting_desc') }} <br> {{ trans('messages.home.your_extra_space') }}</p>
                <a href="{{URL::to('/')}}/become-host"><button class="p-3 rounded-4 border-0 font-weight-500 mt-5">{{ trans('messages.home.get_started') }}</button></a>
            </div>
        </div>
    </div>
</section>



<!--<section class="recommandedbg bg-gray mt-70 pt-70 magic-ball magic-ball-about pb-5">
        <div class="container-fluid container-fluid-90">
            <div class="row">

                 <div class="col-md-3 mb-4">
                     <img src="{{ url('/') }}/public/images/home.png" class="button-rounded-4" width="120px">
                     <a href="{{ url('/') }}/search?location={{ $address }}&checkin=&checkout=&guest=1&type=property" class="text-20 font-weight-600 pl-3">{{trans('messages.home.home')}}</a>
                </div>
                 <div class="col-md-3 mb-4">
                     <img src="{{ url('/') }}/public/images/experience.png" class="button-rounded-4" width="120px">
                     <a href="{{ url('/') }}/expsearch?location={{ $address }}&checkin=&checkout=&guest=1&type=experience" class="text-20 font-weight-600 pl-3">{{trans('messages.home.experience')}}</a>
                </div>
            </div>
        </div>
    </section>-->

@stop



@push('scripts')
<script type="text/javascript" src='https://maps.google.com/maps/api/js?key={{ @$map_key }}&libraries=places'></script>
<script type="text/javascript" src="{{ url('public/js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/daterangepicker.min.js')}}"></script>
<script type="text/javascript" src="{{ url('public/js/front.min.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/daterangecustom.js')}}"></script>
<script type="text/javascript">
    $(function() {
        dateRangeBtn(moment(), moment());
    });
</script>

<!-- Owl Stylesheets -->
<link rel="stylesheet" href="{{asset('public/assets/owlcarousel/assets/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('public/assets/owlcarousel/assets/owl.theme.default.min.css')}}">
<script src="{{asset('public/assets/owlcarousel/owl.carousel.js')}}"></script>

<script>
    $(document).ready(function() {
        var owl = $('.property_type');
        owl.owlCarousel({

            margin: 10,
            nav: true,
            loop: true,
            mouseDrag: true,
            touchDrag: true,

            autoplay: false,
            autoplaySpeed: 3000,

            responsive: {
                0: {
                    items: 1,
                    mouseDrag: false,
                    touchDrag: true,
                    nav: false,
                },
                600: {
                    nav: true,
                    items: 3,
                    mouseDrag: false,
                    touchDrag: true
                },
                1000: {
                    nav: true,
                    items: 4,
                    mouseDrag: false,
                    touchDrag: true
                },
                1200: {
                    nav: true,
                    items: 12,
                    mouseDrag: true,
                    touchDrag: true
                }

            }
        })
    })
</script>

<script>
    $('body').on('click', '.b-login', function() {
        $("#loginmodel").modal('show');
    });

    $('body').on('click', '#exp-tab', function() {
        $('#svguest').hide();
        $('.exp_location').removeClass('col-md-4');
        $('.exp_location').addClass('col-md-5');

        $('.exp_dates').removeClass('col-md-4');
        $('.exp_dates').addClass('col-md-6');
        $('#type').val('experience');
        $("#front-search-field").focus();
        //$('.flexible-btn').hide();
    });

    $('body').on('click', '#property-tab', function() {
        $('#svguest').show();
        $('.exp_location').removeClass('col-md-5');
        $('.exp_location').addClass('col-md-4');

        $('.exp_dates').removeClass('col-md-6');
        $('.exp_dates').addClass('col-md-4');
        $('#type').val('property');
        $("#front-search-field").focus();
        //$('.flexible-btn').show();

    });
</script>
<script>
    function getLocation() {

        geocoder = new google.maps.Geocoder();
        if (navigator.geolocation) {

            navigator.geolocation.getCurrentPosition(
                // Success function
                showPosition,
                // Error function
                null,
                // Options. See MDN for details.
                {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                });
        } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        codeLatLng(position.coords.latitude, position.coords.longitude);
    }


    function codeLatLng(lat, lng) {
        geocoder = new google.maps.Geocoder();

        var latlng = new google.maps.LatLng(lat, lng);
        geocoder.geocode({
            latLng: latlng
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {

                if (results[1]) {
                    var arrAddress = results;
                    //console.log(results);
                    window.location = '{{ url(' / ') }}/search?location=' + results[1].formatted_address + '&checkin=&checkout=&guest=1';

                } else {
                    alert("No results found");
                }
            } else {
                alert("Geocoder failed due to: " + status);
            }
        });
    }

    $(document).on('keyup', '#front-search-field3', function() {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById("front-search-field3"));
    });
</script>


@endpush