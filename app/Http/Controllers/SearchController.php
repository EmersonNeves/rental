<?php

namespace App\Http\Controllers;

use Session;

use App\Http\Helpers\Common;
use App\Http\Helpers\Random;

use Illuminate\Http\Request;

use App\Models\{
    Properties,
    SpaceType,
    PropertyType,
    Amenities,
    AmenityType,
    Currency,
    PropertyDates,
    PropertyAddress,
    ExperienceCategory,
    Settings,
    Meta
};

class SearchController extends Controller
{
    private $helper;

    public function __construct()
    {
        $this->helper = new Common;
        $this->sangvishsearch = new Random;
    }

    public function index(Request $request)
    {
		$current_lang = Session::get('language');
		if($current_lang=="")
		{
			$current_lang = "en";
		}
		else
		{
			$current_lang = Session::get('language');
		}
		$google_map = Settings::where('type', 'googleMap')->pluck('value', 'name')->toArray();
        $google_map_search_key = $google_map['google_map_search_key'];

        $location = $request->input('location');
        $address  = str_replace(" ", "+", "$location");
        $map_where = 'https://maps.google.com/maps/api/geocode/json?key='.$google_map_search_key.'&address='.$address.'&sensor=false';

        $geocode  = $this->content_read($map_where);
        $json     = json_decode($geocode);

         $sv_zoom = '';$city1='';$state='';$country='';
        if(@$json->results) {
            foreach($json->results as $result) {
                foreach($result->address_components as $addressPart) {
                    if((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $city1 = $addressPart->long_name;
                        $property_address['property_address.city'] = $city1;
                    }
                    if((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $state = $addressPart->long_name;
                        $property_address['property_address.state'] = $state;

                    }
                    if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $country = $addressPart->short_name;
                        $property_address['property_address.country'] = $country;
                    }

                    if($city1=='' && $state=='' && $country!='')
                    {
                        $data['sv_zoom'] = '4';
                    }
                    else
                    {
                        $data['sv_zoom'] = '12';
                    }
                }
            }
        }
        else
        {
            $data['sv_zoom'] = '12';
        }

        if ($json->{'results'}) {
            $data['lat']  = isset($json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'})?$json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}:0;
            $data['long'] = isset($json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'})?$json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}:0;
        } else {
            $data['lat']  = 0;
            $data['long'] = 0;
        }

        $data['location']           = $request->input('location');
       // $data['checkin']            = $request->input('checkin');
       // $data['checkout']           = $request->input('checkout');
       // $data['adults']             = $request->input('adults');
       // $data['children']           = $request->input('children');
       // $data['babies']             = $request->input('babies');
       // $data['pets']               = $request->input('pets');
        $data['bedrooms']           = $request->input('bedrooms');
        $data['beds']               = $request->input('beds_number');
        $data['bathrooms']          = $request->input('bathrooms');
        $data['min_price']          = $request->input('min_price');
        $data['max_price']          = $request->input('max_price');

         $msg = $this->sangvishsearch->currency();
		if($msg == "success")
    	{
    	    $msg1 = $this->sangvishsearch->default_currency();
    	}

        $data['space_type']         = SpaceType::where('status', 'Active')->where('lang', $current_lang)->pluck('name', 'id');
        $data['property_type']      = PropertyType::where('status', 'Active')->where('lang', $current_lang)->pluck('name', 'id');
        $data['amenities']          = Amenities::where('status', 'Active')->where('lang', $current_lang)->get();
        $data['amenities_type']     = AmenityType::pluck('name', 'id');

        //CUSTOM
        $data['amenities_categories'] = AmenityType::where('lang', $current_lang)->get();

        $data['property_type_selected'] = explode(',', $request->input('property_type'));
        $data['space_type_selected'] = explode(',', $request->input('space_type'));
        $data['amenities_selected'] = explode(',', $request->input('amenities'));

        //CUSTOM - PETS
        // if ($data['pets'] && $data['pets'] > 0)
        //     $data['amenities_selected'][] = 24; //Pets Allowed

        /* $currency                   = Currency::where('default', 1)->first();
        $data['currency_symbol']    = $currency->symbol; */

        $currency = Currency::getAll();
        if (Session::get('currency')) $data['currency_symbol'] = $currency->firstWhere('code', Session::get('currency'))->symbol;
        else $data['currency_symbol'] = $currency->firstWhere('default', 1)->symbol;

        $data['default_min_price'] = "1";
        $data['default_max_price'] = $this->helper->convert_currency('USD', '', 10000);

        if (!$data['min_price']) {
            $data['min_price'] = $data['default_min_price'];
            $data['max_price'] = $data['default_max_price'];
        }
        $data['type']           = $request->input('type');
        $data['max_price_check'] = $this->helper->convert_currency('', 'USD', $data['max_price']);
        $data['experience_category'] = ExperienceCategory::where('status', 'Active')->where('lang', $current_lang)->get();


        $metas                      = Meta::where('url', '/')->first();
        $data['title']              = $metas->title;

        return view('search.view', $data);
    }

    function searchResult(Request $request)
    {
        $google_map = Settings::where('type', 'googleMap')->pluck('value', 'name')->toArray();
        $google_map_search_key = $google_map['google_map_search_key'];

        $full_address  = $request->input('location');
        $checkin       = $request->input('checkin');
        $checkout      = $request->input('checkout');
        $guest         = $request->input('guest');
        $pets          = $request->input('pets');
        $bedrooms      = $request->input('bedrooms');
        $beds          = $request->input('beds');
        $bathrooms     = $request->input('bathrooms');
        $property_type = $request->input('property_type');
        $space_type    = $request->input('space_type');
        $amenities     = $request->input('amenities');
        $book_type     = $request->input('book_type');
        $negociation_type = $request->input('negociation_type');
        $map_details   = $request->input('map_details');
        $min_price     = $request->input('min_price');
        $max_price     = $request->input('max_price');

        $type         = $request->input('type');
        $category     = $request->input('category');

        if ($pets)
            $amenities = $amenities ? ',24' : '24';

        if (! $min_price) {
            $min_price = $this->helper->convert_currency('USD', '', 0);
            $max_price = $this->helper->convert_currency('USD', '', 1000);
        }

        if (! is_array($property_type)) {
            if ($property_type != '') {
                $property_type = explode(',', $property_type);
            } else {
                $property_type = [];
            }
        }

        if (! is_array($space_type)) {
            if ($space_type != '') {
                $space_type = explode(',', $space_type);
            } else {
                $space_type = [];
            }
        }

        if (! is_array($book_type)) {
            if ($book_type != '') {
                $book_type = explode(',', $book_type);
            } else {
                $book_type = [];
            }
        }
        if (! is_array($amenities)) {
            if ($amenities != '') {
                $amenities = explode(',', $amenities);
            } else {
                $amenities = [];
            }
        }

        if (! is_array($type)) {
            if ($type != '') {
                $type = explode(',', $type);
            } else {
                $type = [];
            }
        }

        if (! is_array($category)) {
            if ($category != '') {
                $category = explode(',', $category);
            } else {
                $category = [];
            }
        }

        $property_type_val   = [];
        $properties_whereIn  = [];
        $space_type_val      = [];

        \Log::info('Parâmetros de busca:', [
            'full_address' => $full_address,
            'checkin' => $checkin,
            'checkout' => $checkout,
            'guest' => $guest,
            'pets' => $pets,
            'bedrooms' => $bedrooms,
            'beds' => $beds,
            'bathrooms' => $bathrooms,
            'property_type' => $property_type,
            'space_type' => $space_type,
            'amenities' => $amenities,
            'book_type' => $book_type,
            'map_details' => $map_details,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'negociation_type' => $negociation_type
        ]);

        $address      = str_replace([" ","%2C"], ["+",","], "$full_address");
        // $map_where    = 'https://maps.google.com/maps/api/geocode/json?key='.$google_map_search_key.'='.$address.'&sensor=false&libraries=places';
        $map_where = 'https://maps.google.com/maps/api/geocode/json?key='.$google_map_search_key.'&address='.$address;
        $geocode      = $this->content_read($map_where);
        $json         = json_decode($geocode);
        // $json = json_decode('{
        //     "results": [
        //         {
        //             "address_components": [
        //                 {
        //                     "long_name": "England",
        //                     "short_name": "England",
        //                     "types": ["administrative_area_level_1", "political"]
        //                 },
        //                 {
        //                     "long_name": "United Kingdom",
        //                     "short_name": "GB",
        //                     "types": ["country", "political"]
        //                 }
        //             ],
        //             "formatted_address": "England, UK",
        //             "geometry": {
        //                 "bounds": {
        //                     "northeast": {"lat": 55.81165979999999, "lng": 1.7629159},
        //                     "southwest": {"lat": 49.8647411, "lng": -6.4185458}
        //                 },
        //                 "location": {"lat": 52.3555177, "lng": -1.1743197},
        //                 "location_type": "APPROXIMATE",
        //                 "viewport": {
        //                     "northeast": {"lat": 55.81165979999999, "lng": 1.7629159},
        //                     "southwest": {"lat": 49.8647411, "lng": -6.4185458}
        //                 }
        //             },
        //             "place_id": "ChIJ39UebIqp0EcRqI4tMyWV4fQ",
        //             "types": ["administrative_area_level_1", "political"]
        //         }
        //     ],
        //     "status": "OK"
        // }');
        // \Log::info($geocode);
        // \Log::info($json);
        $property_address = [];
        if(!@$json->results) {
            foreach($json->results as $result) {
                foreach($result->address_components as $addressPart) {
                    if((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $city1 = $addressPart->long_name;
                        $property_address['property_address.city'] = $city1;
                    }
                    if((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $state = $addressPart->long_name;
                        $property_address['property_address.state'] = $state;
                    }
                    if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $country = $addressPart->short_name;
                        $property_address['property_address.country'] = $country;

                    }
                }
            }
        }

        // $property_address['property_address.city'] = 'Cruz Alta';
        // $property_address['property_address.state'] = 'Rio Grande do Sul';
        // $property_address['property_address.country'] = 'BR';

        if ($map_details != '') {

            $map_data=   explode('~', $map_details);
            $minLat     =   $map_data[2];
            $minLong    =   $map_data[3];
            $maxLat     =   $map_data[4];
            $maxLong    =   $map_data[5];
        } else {
            if ($json->{'results'}) {
                $data['lat'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
                $data['long'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

                $minLat = $data['lat']-0.35;
                $maxLat = $data['lat']+0.35;
                $minLong = $data['long']-0.35;
                $maxLong = $data['long']+0.35;
            } else {
                $data['lat'] = 0;
                $data['long'] = 0;

                $minLat = -1100;
                $maxLat = 1100;
                $minLong = -1100;
                $maxLong = 1100;
            }
        }

        $users_where['users.status']    = 'Active';

        $checkin  = date('Y-m-d', strtotime($checkin));
        $checkout = date('Y-m-d', strtotime($checkout));

        $days     = $this->helper->get_days($checkin, $checkout);
        unset($days[count($days)-1]);

        $calendar_where['date'] = $days;

        $not_available_property_ids = PropertyDates::whereIn('date', $days)->where('status', 'Not available')->distinct()->pluck('property_id');
        $properties_where['properties.accommodates'] = $guest;

        $properties_where['properties.status']               = 'Listed';
        $properties_where['properties.admin_approval']       = 1;
        $properties_where['properties.deleted_status']       = 'No';
        $properties_where['properties.type']                 = 'property';

        // AJUSTAR CONSULTA
        if ($bedrooms) {
            $properties_where['properties.bedrooms']  = $bedrooms;
        }

        if ($bathrooms) {
            $properties_where['properties.bathrooms'] = $bathrooms;
        }

        if ($beds) {
            $properties_where['properties.beds']      = $beds;
        }

        if (count($space_type)) {
            foreach ($space_type as $space_value) {
                array_push($space_type_val, $space_value);
            }
            $properties_whereIn['properties.space_type'] = $space_type_val;
        }

        if (count($property_type)) {
            foreach ($property_type as $property_value) {
                array_push($property_type_val, $property_value);
            }

            $properties_whereIn['properties.property_type'] = $property_type_val;
        }

        $defaultCurrency = Currency::where(['default' => 1])->first();
        //$currency_rate = Currency::where('code', Currency::find(1)->session_code)->first()->rate;

        $currency_rate = Currency::getAll()->firstWhere('code', \Session::get('currency'))->rate;

        $max_price_check = $this->helper->convert_currency('', 'USD', $max_price);

        // \Log::info('Construindo query com os seguintes parâmetros:', [
        //     'property_address' => $property_address,
        //     'users_where' => $users_where,
        //     'properties_where' => $properties_where,
        //     'not_available_property_ids' => $not_available_property_ids
        // ]);

        if($full_address!="")
        {
            $properties = Properties::with(['property_address' => function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {
                            },
                            'property_description','property_photos','wishlist','sv_property_meta',
                            'property_price' => function ($query) use ($min_price, $max_price) {
                                $query->with('currency');
                            },
                            'users'])
                            ->withCount('beds')
                            ->whereHas('property_address', function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {
                                $query->orwhereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");
                            })
                            ->whereHas('property_price', function ($query) use ($min_price, $max_price, $currency_rate) {
                                $query->join('currency', 'currency.code', '=', 'property_price.currency_code');

                                if($min_price == '1')
                                {
                                    $query->whereRaw(' price  >= '.$min_price.' and ((price / currency.rate) * '.$currency_rate.') <= '.$max_price);
                                }
                                else
                                {
                                    $query->whereRaw('((price / currency.rate) * '.$currency_rate.') >= '.$min_price.' and ((price / currency.rate) * '.$currency_rate.') <= '.$max_price);
                                }
                            })
                            ->whereHas('users', function ($query) use ($users_where) {
                                $query->where($users_where);
                            })
                            ->whereHas('property_address', function ($query) use ($property_address) {
                                $query->where($property_address);
                            })
                       ->whereNotIn('id', $not_available_property_ids);
        }
        else
        {
            $properties = Properties::with(['property_address' => function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {
                            },
                            'property_description','property_photos','wishlist','sv_property_meta',
                            'property_price' => function ($query) use ($min_price, $max_price) {
                                $query->with('currency');
                            },
                            'users'])
                            ->withCount('beds')

                            ->whereHas('property_price', function ($query) use ($min_price, $max_price, $currency_rate) {
                                $query->join('currency', 'currency.code', '=', 'property_price.currency_code');
                                $query->whereRaw('((price / currency.rate) * '.$currency_rate.') >= '.$min_price.' and ((price / currency.rate) * '.$currency_rate.') <= '.$max_price);

                            })
                            ->whereHas('users', function ($query) use ($users_where) {
                                $query->where($users_where);
                            })
                       ->whereNotIn('id', $not_available_property_ids);
        }

        if ($properties_where) {
            foreach ($properties_where as $row => $value) {
                if ($row == 'properties.accommodates' || $row == 'properties.bathrooms' || $row == 'properties.bedrooms' || $row == 'properties.beds') {
                    $operator = '>=';
                } else {
                    $operator = '=';
                }

                if ($value == '') {
                    $value = 0;
                }

                $properties = $properties->where($row, $operator, $value);
            }
        }

        if ($properties_whereIn) {
            foreach ($properties_whereIn as $row_properties_whereIn => $value_properties_whereIn) {
                $properties = $properties->whereIn($row_properties_whereIn, array_values($value_properties_whereIn));
            }
        }

        if (count($amenities)) {
            foreach ($amenities as $amenities_value) {
                $properties = $properties->whereRaw('find_in_set('.$amenities_value.', amenities)');
            }
        }

        if (count($book_type) && count($book_type)!=2) {
            foreach ($book_type as $book_value) {
                $properties = $properties->where('booking_type', $book_value);
            }
        }
        if (count($type) && count($type)!=2 ) {
            foreach ($type as $type_value) {
                $properties = $properties->where('type', $type_value);
            }
        }

         if (count($category) )  {
            foreach ($category as $category_value) {
               // $properties = $properties->where('experience_type', $category_value);
                $properties = $properties->whereRaw('find_in_set('.$category_value.', experience_type)');
            }
        }

        if (($negociation_type) !== null) {
            foreach ($negociation_type as $negociation_value) {
                $properties = $properties->where('negociation_type', $negociation_value);
            }
        }else{
            $properties = $properties->whereRaw('negociation_type in ("0", "1")');
        }

        $querySql = $properties->toSql();
        $queryBindings = $properties->getBindings();
        // \Log::info('Query SQL construída:', [
        //     'sql' => $querySql,
        //     'bindings' => $queryBindings
        // ]);
        $queryWithBindings = str_replace(['?'], $queryBindings, $querySql);
        \Log::info('Query SQL com valores:', ['query' => $queryWithBindings]);
         $properties = $properties->orderBy('id', 'DESC')->paginate(16)->toJson();
        //  \Log::info('Resultado da query:', ['properties' => $properties]);
         echo $properties;
    }

    public function expindex(Request $request)
    {
		$current_lang = Session::get('language');
		if($current_lang=="")
		{
			$current_lang = "en";
		}
		else
		{
			$current_lang = Session::get('language');
		}

        $location = $request->input('location');
        $address  = str_replace(" ", "+", "$location");

        $google_map = Settings::where('type', 'googleMap')->pluck('value', 'name')->toArray();
        $google_map_search_key = $google_map['google_map_search_key'];

        $map_where = 'https://maps.google.com/maps/api/geocode/json?key='.$google_map_search_key.'&address='.$address.'&sensor=false';
        $geocode  = $this->content_read($map_where);
        $json     = json_decode($geocode);

        $sv_zoom = '';$city1='';$state='';$country='';
        if(@$json->results) {
            foreach($json->results as $result) {
                foreach($result->address_components as $addressPart) {
                    if((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $city1 = $addressPart->long_name;
                        $property_address['property_address.city'] = $city1;
                    }
                    if((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $state = $addressPart->long_name;
                        $property_address['property_address.state'] = $state;

                    }
                    if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types))) {
                        $country = $addressPart->short_name;
                        $property_address['property_address.country'] = $country;
                    }

                    if($city1=='' && $state=='' && $country!='')
                    {
                        $data['sv_zoom'] = '4';
                    }
                    else
                    {
                        $data['sv_zoom'] = '12';
                    }
                }
            }
        }
        else
        {
            $data['sv_zoom'] = '12';
        }

        if ($json->{'results'}) {
            $data['lat']  = isset($json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'})?$json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}:0;
            $data['long'] = isset($json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'})?$json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}:0;
        } else {
            $data['lat']  = 0;
            $data['long'] = 0;
        }

        $data['location']           = $request->input('location');
        $data['checkin']            = $request->input('checkin');
        $data['checkout']           = $request->input('checkout');
        $data['guest']              = $request->input('guest');

        $data['property_type']      = PropertyType::where('status', 'Active');//->where('lang', $current_lang)->pluck('name', 'id');

        $data['property_type_selected'] = explode(',', $request->input('property_type'));
        $currency                   = Currency::where('default', 1)->first();
        $data['currency_symbol']    = $currency->symbol;


        $data['type']           = $request->input('type');
        $data['title']           = "Search";

        $data['experience_category'] = ExperienceCategory::where('status', 'Active')->where('lang', $current_lang)->get();
        return view('search.expview', $data);

    }   
    
    // public function expindex(Request $request)
    // {
	// 	$current_lang = Session::get('language');
	// 	if($current_lang=="")
	// 	{
	// 		$current_lang = "en";
	// 	}
	// 	else
	// 	{
	// 		$current_lang = Session::get('language');
	// 	}

    //     $location = $request->input('location');
    //     $address  = str_replace(" ", "+", "$location");

    //     $google_map = Settings::where('type', 'googleMap')->pluck('value', 'name')->toArray();
    //     $google_map_search_key = $google_map['google_map_search_key'];

    //     $map_where = 'https://maps.google.com/maps/api/geocode/json?key='.$google_map_search_key.'&address='.$address.'&sensor=false';
    //     $geocode  = $this->content_read($map_where);
    //     $json     = json_decode($geocode);

    //     $sv_zoom = '';$city1='';$state='';$country='';
    //     if(@$json->results) {
    //         foreach($json->results as $result) {
    //             foreach($result->address_components as $addressPart) {
    //                 if((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types))) {
    //                     $city1 = $addressPart->long_name;
    //                     $property_address['property_address.city'] = $city1;
    //                 }
    //                 if((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types))) {
    //                     $state = $addressPart->long_name;
    //                     $property_address['property_address.state'] = $state;

    //                 }
    //                 if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types))) {
    //                     $country = $addressPart->short_name;
    //                     $property_address['property_address.country'] = $country;
    //                 }

    //                 if($city1=='' && $state=='' && $country!='')
    //                 {
    //                     $data['sv_zoom'] = '4';
    //                 }
    //                 else
    //                 {
    //                     $data['sv_zoom'] = '12';
    //                 }
    //             }
    //         }
    //     }
    //     else
    //     {
    //         $data['sv_zoom'] = '12';
    //     }

    //     if ($json->{'results'}) {
    //         $data['lat']  = isset($json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'})?$json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'}:0;
    //         $data['long'] = isset($json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'})?$json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'}:0;
    //     } else {
    //         $data['lat']  = 0;
    //         $data['long'] = 0;
    //     }

    //     $data['location']           = $request->input('location');
    //     $data['checkin']            = $request->input('checkin');
    //     $data['checkout']           = $request->input('checkout');
    //     $data['guest']              = $request->input('guest');

    //     $data['property_type']      = PropertyType::where('status', 'Active');//->where('lang', $current_lang)->pluck('name', 'id');

    //     $data['property_type_selected'] = explode(',', $request->input('property_type'));
    //     $currency                   = Currency::where('default', 1)->first();
    //     $data['currency_symbol']    = $currency->symbol;


    //     $data['type']           = $request->input('type');
    //     $data['title']           = "Search";

    //     $data['experience_category'] = ExperienceCategory::where('status', 'Active')->where('lang', $current_lang)->get();
    //     return view('search.expview', $data);

    // }

    // function expsearchResult(Request $request)
    // {
    //     $full_address  = $request->input('location');
    //     $checkin       = $request->input('checkin');
    //     $checkout      = $request->input('checkout');
    //     $guest         = $request->input('guest');
    //     $book_type     = $request->input('book_type');
    //     $map_details   = $request->input('map_details');

    //     $type         = $request->input('type');
    //     $category     = $request->input('category');


    //     if (! is_array($book_type)) {
    //         if ($book_type != '') {
    //             $book_type = explode(',', $book_type);
    //         } else {
    //             $book_type = [];
    //         }
    //     }

    //     if (! is_array($category)) {
    //         if ($category != '') {
    //             $category = explode(',', $category);
    //         } else {
    //             $category = [];
    //         }
    //     }


    //     $properties_whereIn  = [];

    //     $address      = str_replace([" ","%2C"], ["+",","], "$full_address");
    //     $google_map = Settings::where('type', 'googleMap')->pluck('value', 'name')->toArray();
    //     $google_map_search_key = $google_map['google_map_search_key'];
    //     $map_where    = 'https://maps.google.com/maps/api/geocode/json?key='.$google_map_search_key.'&address='.$address.'&sensor=false&libraries=places';
    //     $geocode      = $this->content_read($map_where);
    //     $json         = json_decode($geocode);

    //     $property_address = [];
    //     if(@$json->results) {
    //         foreach($json->results as $result) {
    //             foreach($result->address_components as $addressPart) {
    //                 if((in_array('locality', $addressPart->types)) && (in_array('political', $addressPart->types))) {
    //                     $city1 = $addressPart->long_name;
    //                     $property_address['property_address.city'] = $city1;
    //                 }
    //                 if((in_array('administrative_area_level_1', $addressPart->types)) && (in_array('political', $addressPart->types))) {
    //                     $state = $addressPart->long_name;
    //                     $property_address['property_address.state'] = $state;
    //                 }
    //                 if((in_array('country', $addressPart->types)) && (in_array('political', $addressPart->types))) {
    //                     $country = $addressPart->short_name;
    //                     $property_address['property_address.country'] = $country;

    //                 }
    //             }
    //         }
    //     }

    //     if ($map_details != '') {
    //         $map_data=   explode('~', $map_details);
    //         $minLat     =   $map_data[2];
    //         $minLong    =   $map_data[3];
    //         $maxLat     =   $map_data[4];
    //         $maxLong    =   $map_data[5];
    //     } else {
    //         if ($json->{'results'}) {
    //             $data['lat'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
    //             $data['long'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};

    //             $minLat = $data['lat']-0.35;
    //             $maxLat = $data['lat']+0.35;
    //             $minLong = $data['long']-0.35;
    //             $maxLong = $data['long']+0.35;
    //         } else {
    //             $data['lat'] = 0;
    //             $data['long'] = 0;

    //             $minLat = -1100;
    //             $maxLat = 1100;
    //             $minLong = -1100;
    //             $maxLong = 1100;
    //         }
    //     }

    //     $users_where['users.status']    = 'Active';

    //     $checkin  = date('Y-m-d', strtotime($checkin));
    //     $checkout = date('Y-m-d', strtotime($checkout));

    //     $days     = $this->helper->get_days($checkin, $checkout);
    //     unset($days[count($days)-1]);

    //     $calendar_where['date'] = $days;

    //     $not_available_property_ids = PropertyDates::whereIn('date', $days)->where('status', 'Not available')->distinct()->pluck('property_id');
    //     $properties_where['properties.accommodates'] = $guest;

    //     $properties_where['properties.status']               = `Listed`;
    //     $properties_where['properties.admin_approval']       = `1`;
    //     $properties_where['properties.deleted_status']       = `No` ;
    //     $properties_where['properties.type']                 = `experience`;

    //     $defaultCurrency = Currency::where(['default' => 1])->first();
    //     $currency_rate = Currency::where('code', Currency::find(1)->session_code)->first()->rate;

    //     if($full_address!="")
    //     {
    //         $properties = Properties::with(['property_address' => function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {
    //                         },
    //                         'property_description','property_photos','wishlist','sv_property_meta',
    //                         'property_price' => function ($query)  {
    //                             $query->with('currency');
    //                         },
    //                         'users'])
    //                         ->whereHas('property_address', function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {
    //                              $query->orwhereRaw("latitude between $minLat and $maxLat and longitude between $minLong and $maxLong");
    //                         })
    //                         ->whereHas('users', function ($query) use ($users_where) {
    //                             $query->where($users_where);
    //                         })
    //                          ->whereHas('property_address', function ($query) use ($property_address) {
    //                             $query->where($property_address);
    //                         })
    //                    ->whereNotIn('id', $not_available_property_ids);
    //     }
    //     else
    //     {
    //         $properties = Properties::with(['property_address' => function ($query) use ($minLat, $maxLat, $minLong, $maxLong) {
    //                         },
    //                         'property_description','property_photos','wishlist','sv_property_meta',
    //                         'property_price' => function ($query)  {
    //                             $query->with('currency');
    //                         },
    //                         'users'])

    //                         ->whereHas('users', function ($query) use ($users_where) {
    //                             $query->where($users_where);
    //                         })
    //                    ->whereNotIn('id', $not_available_property_ids);
    //     }

    //     if ($properties_where) {
    //         foreach ($properties_where as $row => $value) {
    //             if ($row == 'properties.accommodates') {
    //                 $operator = '>=';
    //             } else {
    //                 $operator = '=';
    //             }

    //             if ($value == '') {
    //                 $value = 0;
    //             }

    //             $properties = $properties->where($row, $operator, $value);
    //         }
    //     }

    //     if ($properties_whereIn) {
    //         foreach ($properties_whereIn as $row_properties_whereIn => $value_properties_whereIn) {
    //             $properties = $properties->whereIn($row_properties_whereIn, array_values($value_properties_whereIn));
    //         }
    //     }

    //     if (count($book_type) && count($book_type)!=2) {
    //         foreach ($book_type as $book_value) {
    //             $properties = $properties->where('booking_type', $book_value);
    //         }
    //     }


    //      if (count($category) )  {
    //         foreach ($category as $category_value) {
    //            // $properties = $properties->where('experience_type', $category_value);
    //             $properties = $properties->whereRaw('find_in_set('.$category_value.', experience_type)');
    //         }
    //     }
    //     $properties = $properties->orderBy('id', 'DESC')->paginate(Session::get('row_per_page'))->toJson();
    //     echo $properties;
    // }

    public function content_read($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result=curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
