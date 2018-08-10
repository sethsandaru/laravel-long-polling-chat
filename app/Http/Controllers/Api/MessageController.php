<?php
/**
 * Created by PhpStorm.
 * User: Seth Phat
 * Date: 8/8/2018
 * Time: 10:54 PM
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function RetrieveNewMess(Request $rq) {
        /**
         * Because in long polling, we keep this request for n seconds, so maybe in above you have something that changed the $_SESSION, you must to write
         * the session before the polling process. So that in another request can use your new/edited SESSION.
         */
        session_write_close();

        // set some config
        $time = 0;
        $time_limit = 60; // so it'll keep for 60s before existed.
        set_time_limit($time_limit + 5); // +5 for sure...

        // get current date from request, otherwise we get the current by ourself.
        $last_ajax_call = $rq->get('CreatedDate');
        if (empty($last_ajax_call)) {
            $last_ajax_call = now();
        }

        // retrieve user name
        $name = $rq->get('Name');


        // check for new message every seconds...
        $has_new_mess = Message::GetMessageAfter($last_ajax_call, $name);
        while ($has_new_mess === false && $time < $time_limit) {
            sleep(1);
            $time++;
            $has_new_mess = Message::GetMessageAfter($last_ajax_call, $name);
        }

        // if we don't got any message, return time, otherwise return message.
        if ($time >= $time_limit) {
            return json_encode(['time' => $last_ajax_call]);
        } else {
            return json_encode(['data' => $has_new_mess]);
        }
    }

    public function PostText(Request $rq) {
        $rules = [
            'Name' => 'required',
            'Message' => 'required',
        ];

        $valid = Validator::make($rq->all(), $rules);
        if ($valid->fails()) {
            return response()->json(['error' => $valid->errors()->first()]);
        }

        // post new text
        $new_mess = new Message;
        $new_mess->Name = $rq->post('Name');
        $new_mess->Message = $rq->post('Message');
        $new_mess->CreatedDate = now()->format("Y-m-d H:i:s");
        $new_mess->save();
        $new_mess->refresh();

        return response()->json(['status' => 'ok', 'message' => $new_mess->toArray()]);
    }

    public function PostImage(Request $rq) {
        $rules = [
            'Name' => 'required',
            'Image' => 'required|image|max:5000',
        ];

        $valid = Validator::make($rq->all(), $rules);
        if ($valid->fails()) {
            return response()->json(['error' => $valid->errors()->first()]);
        }

        // solving upload
        $file = $rq->file('Image');
        if (!$file->isValid()) {
            return response()->json(['error' => "Upload image to server failed, please try again."]);
        }

        // upload file
        $path = "/uploads/attachments/";
        $new_file_name = "ATTR_" . rand(0,999999). "_" . time() . "_" . md5($rq->post('Name')) . "." . $file->getClientOriginalExtension();
        $file->move(public_path($path), $new_file_name);

        // post new text
        $new_mess = new Message;
        $new_mess->Name = $rq->post('Name');
        $new_mess->Type = 'image';
        $new_mess->Attachment = $path . $new_file_name;
        $new_mess->CreatedDate = now()->format("Y-m-d H:i:s");
        $new_mess->save();
        $new_mess->refresh();

        return response()->json(['status' => 'ok', 'message' => $new_mess->toArray()]);
    }
}