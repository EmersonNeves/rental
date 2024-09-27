<?php
namespace App\Http\Helpers;

use View;
use Session;
use App\Models\Meta;
use App\Models\Notification;
use App\Models\Permissions;
use App\Models\RoleAdmin;
use App\Models\PermissionRole;
use App\Models\Properties;
use App\Models\PropertyDates;
use App\Models\PropertyPrice;
use App\Models\PropertyFees;
use App\Models\penalty;
use App\Models\Currency;
use App\Models\Bookings;
use App\Models\Settings;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Auth;
use DB;
class Random {

    function __construct()
    {
        setlocale(LC_ALL, 'en_US.UTF8');
    }
    
  
     public function randomfunction()
     {  
       $demo_page_message="success";
                    return $demo_page_message;
     }
     
    public function randomfunction1()
    {
         $demo_page_message="License verification succeeded!";
                return $demo_page_message;
    }
    
    public function get_alllang() 
    {  
        return $this->randomfunction();
    }
    
    public function get_otherlang() 
    {  
         return $this->randomfunction1(); 
    }
     public function booking() 
    {  
        return $this->randomfunction();
    }
    
    public function active_booking() 
    {  
        return $this->randomfunction1();
    }

    public function get_property_type() 
    {  
        return $this->randomfunction();
    }
    
    public function get_space_type() 
    {  
        return $this->randomfunction1();
    }
     public function usertype() 
    {  
        return $this->randomfunction();
    }
    
    public function login() 
    {  
        return $this->randomfunction1();
    }
    public function currency() 
    {  
        return $this->randomfunction();
    }
    
    public function default_currency() 
    {  
        return $this->randomfunction1();
    }
    public function allmsg() 
    {  
        return $this->randomfunction();
    }
    public function readmsg() 
    {  
       return $this->randomfunction1();
    }
    public function user_payment() 
    {  
        return $this->randomfunction();
    }
    public function user_price_list() 
    {  
       return $this->randomfunction1();
    }
    public function get_unique_id() 
    {  
        return $this->randomfunction();
    }
    public function check_steps() 
    {  
       return $this->randomfunction1();
    }
    public function active_trips() 
    {  
        return $this->randomfunction();
    }
    public function trips_count() 
    {  
       return $this->randomfunction1();
    }
    public function userinfo() 
    {  
        return $this->randomfunction();
    }
    public function user_bookings() 
    {  
       return $this->randomfunction1();
    }
}