<?php

/**
 * PropertyBeds Model
 *
 * PropertyBeds Model manages PropertyBeds operation.
 *
 * @category   PropertyBeds
 * @package    migrateshop
 * @author     Migrateshop
 * @copyright  2020 migrateshop.com
 * @license
 * @version    4.0
 * @link       http://migrateshop.com
 * @since      Version 1.3
 * @deprecated None
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyBeds extends Model
{
    protected $table   = 'property_beds';
    public $timestamps = false;

    public function properties()
    {
        return $this->belongsTo('App\Models\Properties', 'property_id', 'id');
    }

    public function bed_type()
    {
        return $this->hasOne('App\Models\BedType', 'bed_temp_id', 'id');
    }
}
