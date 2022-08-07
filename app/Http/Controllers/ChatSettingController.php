<?php

namespace App\Http\Controllers;

use App\ChatSetting;
use Illuminate\Http\Request;


class ChatSettingController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('permission:site-settings.chat-setting', ['only' => ['index', 'update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $chat = ChatSetting::all();

        return view('admin.chat_setting.index', compact('chat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ChatSetting  $chatSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChatSetting $chatSetting)
    {
        if (env('DEMO_LOCK') == 1) {
            return back()->with('deleted', __('This action is disabled in the demo !'));
        }

        foreach ($request->ids as $key => $k) {
            ChatSetting::where('id', '=', $key)->update([

                'script' => $k != 'whatsapp' && isset($request->script[$key]) ? $request->script[$key] : null,
                'enable_messanger' => isset($request->enable_messanger[$key]) && $request->keyname[$key] == 'messanger' && $request->enable_messanger[$key] ? '1' : '0',
                'mobile' => $k != 'messanger' && isset($request->mobile[$key]) ? $request->mobile[$key] : null,
                'text' => $k != 'messanger' && isset($request->text[$key]) ? $request->text[$key] : null,
                'header' => $k != 'messanger' && isset($request->header[$key]) ? $request->header[$key] : null,
                'size' => $k != 'messanger' && isset($request->size[$key]) ? $request->size[$key] : 30,
                'color' => $k != 'messanger' && isset($request->color[$key]) && $request->keyname[$key] == 'whatsapp' && $request->color[$key] ? $request->color[$key] : '#52D668',
                'enable_whatsapp' => isset($request->enable_whatsapp[$key]) && $request->keyname[$key] == 'whatsapp' && $request->enable_whatsapp[$key] ? '1' : '0',
                'position' => isset($request->position[$key]) && $request->keyname[$key] == 'whatsapp' && $request->position[$key] ? "left" : "right",
            ]);

        }

        return back()->with('added', __('Chat settings successfully updated!'));
    }

}
