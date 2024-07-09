<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


if (!function_exists('uploadDocument')) {
    function uploadDocument(Request $request, $folder, $name_file_on_request, $disk)
    {

        $fileNameOriginal = $request->file($name_file_on_request)->getClientOriginalName();
        $extension = Str::slug(pathinfo($fileNameOriginal, PATHINFO_FILENAME)) . '.' . pathinfo($fileNameOriginal, PATHINFO_EXTENSION);
        $file_name = Str::random(32) . '_' . $extension;

        $path = $request->file($name_file_on_request)->storeAs($folder, $file_name, $disk);

        return $path;
    }

}

if (!function_exists('uploadFile')) {
    function uploadFile($file, $folder, $disk)
    {
        $fileNameOriginal = $file->getClientOriginalName();
        $finalName = Str::slug(pathinfo($fileNameOriginal, PATHINFO_FILENAME)) . '.' . pathinfo($fileNameOriginal, PATHINFO_EXTENSION);;
        $file_name = time() . Str::random(15) . '.' . $finalName;

        return $file->storeAs($folder, $file_name, $disk);

    }

}


if (!function_exists('deleteFile')) {
    function deleteFile($disk, $path)
    {
        Storage::disk($disk)->delete($path);
    }
}

if (!function_exists('OTPMake')) {
    function OTPMake($user, $verificationServices)
    {
        $verification = [];
        $otp_services = $verificationServices;
        $verification['user_id'] = $user->id;
        $verification_data = $otp_services->setVerificationCode($verification);
        $message = $otp_services->getSMSVerifyMessage($verification_data->code);

    }

}


