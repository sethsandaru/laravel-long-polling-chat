<?php
/**
 * Created by PhpStorm.
 * User: Seth Phat
 * Date: 8/8/2018
 * Time: 10:54 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "Message";
    protected $primaryKey = "ID"; // default
    public $timestamps = false;

    public static function GetMessageAfter($date, $name) {
        $query = self::query()
                        ->where('Name', 'NOT LIKE', $name)
                        ->where('CreatedDate', '>', $date)
                        ->get();

        if ($query->count() == 0) {
            return false;
        }

        return $query;
    }
}