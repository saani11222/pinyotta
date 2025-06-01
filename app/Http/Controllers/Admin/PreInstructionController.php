<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Preinstruction;
use App\Models\Setting;

class PreInstructionController extends Controller
{   

    // public function Preinstructions(Request $request,$user_id=null){

    //     if($request->isMethod('post')){

    //         if(!$request->id) {
    //             $setting = new Preinstruction;
    //         }
    //         else{
    //             $setting = Preinstruction::where(['id'=>$request->id])->first();
    //         }

    //         if($user_id == '-1'){
    //             $setting = Preinstruction::where(['user_id'=>$user_id])->first();
    //         }

    //         $setting->user_id = $user_id;
    //         $setting->description = $request->description;
    //         $setting->model_name = $request->model_name;
    //         $setting->temperature = $request->temperature;
    //         $setting->instruction_type = 'puzzle';
    //         $setting->maximum_length = $request->maximum_length;
    //         $setting->top_p = $request->top_p;
    //         $setting->frequency_penalty = $request->frequency_penalty;
    //         $setting->presence_penalty = $request->presence_penalty;
    //         $setting->system_prompt = $request->system_prompt;
    //         $setting->type = 'quiz_creation';
    //         $setting->save();

    //         return redirect()->back()->with(['success'=>'Record saved successfully']);

    //     }
    //     else{
        
    //       $setting = Preinstruction::where(['user_id'=> $user_id, 'instruction_type'=> 'puzzle'])->first();
    //       if(!$setting){
    //          $setting = Preinstruction::where(['user_id'=> '-1', 'instruction_type'=> 'puzzle'])->first();
    //       }
    //       return view('admin.preinstruction')->with(compact('setting','user_id'));

    //     }
    // }

    public function Preinstructions(Request $request,$id=null){

        if(!$request->type){
            $request->type = "shows";
        }
        
        $setting = Preinstruction::where(['type'=>$request->type,'user_id'=>'-1'])->first();
        if($request->isMethod('get')){
            return view('admin.preinstruction')->with(compact('setting','id'));
        }else{
            if(!$request->id) {
            $setting = new Preinstruction;
            }
            $setting->description = $request->description;
            $setting->model_name = $request->model_name;
            $setting->temperature = $request->temperature;
            $setting->maximum_length = $request->maximum_length;
            $setting->top_p = $request->top_p;
            $setting->frequency_penalty = $request->frequency_penalty;
            $setting->presence_penalty = $request->presence_penalty;
            $setting->system_prompt = $request->system_prompt;
            // $setting->company_type = 'default';
            $setting->user_id = $id;
            $setting->type = $request->type;
            $setting->save();

            return redirect()->back()->with(['success'=>'Record saved successfully']);
        }
    }

    public function CreateSetting(Request $request){

        if($request->isMethod('post')){

            $setting = Setting::first();

            $setting->type = $request->type;
            $setting->save();

            return redirect()->back()->with(['success'=>'Record saved successfully']);

        }
        else{
        
          $setting = Setting::first();
          return view('admin.setting')->with(compact('setting'));

        }
    }

}
