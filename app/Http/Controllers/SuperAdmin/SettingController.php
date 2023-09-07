<?php

namespace App\Http\Controllers\Superadmin;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Sohibd\Laravelslug\Generate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{

    public function index(Request $request)
    {

        try {
            $settingInfo = Setting::first();
            $profileInfo = User::with('profile')->find(Auth::id());
            return view('backend.superadmin.setting.index', compact('settingInfo', 'profileInfo'));
        } catch (\Exception $e) {
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    public function settingUpdate(Request $request)
    {
       
        $this->validate(
            $request,
            [
                'company_name' => 'required|min:1|max:290',
            ]
        );

        // try {
            DB::beginTransaction();
            $setting = Setting::firstOrFail();
            $setting->superadmin_id = Auth::id();
            $setting->company_name = $request->company_name;
            $setting->project_name = $request->project_name;
            $setting->website_name = $request->website_name;
            $setting->website_title = $request->website_title;
            $setting->address = $request->address;
            $setting->currency_name = $request->currency_name;
            $setting->phone = $request->phone;
            $setting->email = $request->email;
            $setting->currency_name = $request->currency_name;
            $setting->currency_symbole = $request->currency_symbole;
            $setting->bin_number = $request->bin_number;
            $setting->vat_number = $request->vat_number;
            $setting->facebook = $request->facebook;
            $setting->youtube = $request->youtube;
            $setting->twitter = $request->twitter;
            $setting->instagram = $request->instagram;
           if ($request->hasFile('favicon')) {
                $this->validate($request, [
                    'favicon' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024',

                ]);

                if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/")) {
                    mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/", 0777, true);
                }
                if (!is_dir(storage_path() . "/app/public/files/shares/uploads/" . Auth::id() . "/thumbs/")) {
                    mkdir(storage_path() .  "/app/public/files/shares/uploads/" . Auth::id() . "/thumbs/", 0777, true);
                }

                $ex = $request->favicon->extension();
                $rand = uniqid(Generate::Slug(Str::limit($request->company_name, 40)));
                $name = $rand . "." . $ex;
                $request->favicon->move(storage_path('/app/public/files/shares/uploads/' . Auth::id() . '/'), $name, 60);

                $resizedImage_thumbs = Image::make(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/' . $name)->resize(35, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $resizedImage_thumbs->save(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/thumbs/' . $name, 60);

                $setting->favicon = url('storage/files/shares/uploads/' . Auth::id()) . '/' . $name;
            }
            if ($request->hasFile('logo')) {
                $this->validate($request, [
                    'logo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:1024',

                ]);
                $ex = $request->logo->extension();
                $rand = uniqid(Generate::Slug(Str::limit($request->company_name, 40)));
                $name = $rand . "." . $ex;
                $request->logo->move(storage_path('/app/public/files/shares/uploads/' . Auth::id() . '/'), $name, 60);
                $resizedImage_thumbs = Image::make(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/' . $name)->resize(35, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $resizedImage_thumbs->save(storage_path() . '/app/public/files/shares/uploads/' . Auth::id() . '/thumbs/' . $name, 60);
                $setting->logo = url('storage/files/shares/uploads/' . Auth::id()) . '/' . $name;
            }

            $setting->save();
            Cache::forget('setting');
            DB::commit();
            Toastr::success('Setting Update Successfully', 'Success');
            return redirect()->route(request()->segment(1) . '.setting');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     $response = ErrorTryCatch::createResponse(
        //         false,
        //         500,
        //         'Internal Server Error.',
        //         null
        //     );
        //     Toastr::error($response['message'], 'Error');
        //     return back();
        // }
    }


    public function smtpIndex()
    {
        return view('backend.superadmin.setting.smtp');
    }

    public function envKeyUpdate(Request $request)
    {
        foreach ($request->types as $key => $type) {
            $this->overWriteEnvFile($type, $request[$type]);
        }

        Toastr::success("SMTP updated successfully", "Success");
        return back();
    }

    public function overWriteEnvFile($type, $val)
    {
        if (env('DEMO_MODE') != 'On') {
            $path = base_path('.env');
            if (file_exists($path)) {
                $val = '"' . trim($val) . '"';
                if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
                    file_put_contents(
                        $path,
                        str_replace(
                            $type . '="' . env($type) . '"',
                            $type . '=' . $val,
                            file_get_contents($path)
                        )
                    );
                } else {
                    file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
                }
            }
        }
    }
}
