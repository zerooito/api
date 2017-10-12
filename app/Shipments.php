<?php

namespace App;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

class Shipments extends Model
{

    /**
     * The name table rewrite
     *
     * @var array
     */
    protected $table = 'shipments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id', 'user_id', 'track_url', 'track_code', 
        'nfe_key', 'nfe_serie', 'nfe_number', 'company',
        'service'
    ];

    public static function updateShipmentIfExist($data)
    {
        $Shipments = Shipments::where(['order_id' => $data['order_id']])->first();
        
        if (is_null($Shipments)) {
            $data['created_at'] = date('Y-m-d H:i:s');

            Shipments::insert($data);

            return Shipments::where(['order_id' => $data['order_id']])->first();
        }

        $Shipments::where(['order_id' => $data['order_id']])->update($data);

        return Shipments::where(['order_id' => $data['order_id']])->first();
    }

}
