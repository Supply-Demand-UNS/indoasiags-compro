<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ResponseFormatter;
use App\Models\User;

class RouteHandler extends Controller
{
    public function contact()
    {
        $validator = \Validator::make(request()->all(), [
            'name' => 'required|min:5|max:30',
            'email' => 'required|email',
            'phone_number' => 'required|max:50',
            'message' => 'required|max:5000'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors());
        }

        $admin = \App\Models\Setting::where('key', 'email')->first()->value;
        \Mail::to($admin)->send(new \App\Mail\ContactEmail(request()->name, request()->email, request()->phone_number, request()->message));

        return ResponseFormatter::success([
            'message_sent' => true
        ]);
    }

    public function login()
    {
        $validator = \Validator::make(request()->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors());
        }

        $user = User::where('email', request()->email)->first();
        if (is_null($user)) {
            return ResponseFormatter::error(400, null, [
                'User tidak ditemukan'
            ]);
        }

        $userPassword = $user->password;
        if (\Hash::check(request()->password, $userPassword)) {
            $token = $user->createToken(config('app.name'))->plainTextToken;

            return ResponseFormatter::success([
                'token' => $token
            ]);
        }

        return ResponseFormatter::error(400, null, [
            'Password salah!'
        ]);
    }

    public function profile()
    {
        $user = request()->user()->only(['name', 'email']);

        return ResponseFormatter::success($user);
    }

    public function getSetting()
    {
        $settings = \App\Models\Setting::get(['key', 'value']);
        $results = [];
        foreach ($settings as $setting) {
            $results[$setting->key] = $setting->value;
        }

        return ResponseFormatter::success($results);
    }

    public function updateSetting()
    {
        $rules = [];
        $settings = \App\Models\Setting::get(['key', 'validation']);
        foreach ($settings as $setting) {
            $rules[$setting->key] = $setting->validation;
        }

        $validator = \Validator::make(request()->all(), $rules);
        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors());
        }

        $payload = $validator->validated();
        foreach ($payload as $key => $value) {
            \App\Models\Setting::where('key', $key)->update(['value' => $value]);
        }

        return $this->getSetting();
    }

    public function getPublicSetting()
    {
        $settings = cache()->remember('settings', 10, function(){
            return \App\Models\Setting::get(['key', 'value']);
        });
        $results = [];
        foreach ($settings as $setting) {
            $results[$setting->key] = $setting->value;
        }

        return ResponseFormatter::success($results);
    }
}
