<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of AuthToken
 *
 * @author kas
 */
class AuthToken extends Model {

    //put your code here
    protected $table = "auth_token";
    public $timestamps = false;

    public function populate($data = []) {
        if (array_key_exists('access_token', $data)) {
            $this->access_token = $data ['access_token'];
        }
        if (array_key_exists('resource_type', $data)) {
            $this->resource_type = $data ['resource_type'];
        }
        if (array_key_exists('resource_id', $data)) {
            $this->resource_id = $data ['resource_id'];
        }

        $this->created_at = \Carbon\Carbon::now();
        return $this;
    }

    public static function findTokenByRelation($resource_id, $resource_type) {
        return self::where('resource_type', '=', $resource_type)->where('resource_id', '=', $resource_id)->first();
    }

}
