<?php

namespace App\Http\Controllers\Superadmin;

use App\Mail\Sendmail;
use App\Models\Contact;
use App\Mail\ContactMail;
use App\Jobs\SendSmsmessage;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Contact = Contact::wherestatus(0)->latest()->get();
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.contacts.index'), 'name' => "Contact"],
            ['name' => 'List'],
        ];
        return view('backend.superadmin.contact.index', ['breadcrumbs' => $breadcrumbs], ['contactus' => $Contact]);
    }

    public function create()
    {
        $Contact = Contact::wherestatus(1)->distinct('email')->select(
            DB::raw("CONCAT(name,' - ',email) AS name"),
            'email'
        )->pluck('name', 'email');
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.contacts.index'), 'name' => "Contact"],
            ['name' => 'Create'],
        ];
        
        return view('backend.superadmin.contact.create', ['breadcrumbs' => $breadcrumbs], ['Contact' => $Contact]);
    }


    public function sendemailindex()
    {
        $Contact = Contact::wherestatus(1)->latest()->get();
        $breadcrumbs = [
            ['link' => "superadmin/dashboard", 'name' => "Home"], ['link' => "superadmin/emaillist", 'name' => "Contact Email"]
        ];

        return view('backend.superadmin.contact.send', ['breadcrumbs' => $breadcrumbs], ['contactus' => $Contact]);
    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'subject' => 'required|min:3|max:192',
            'email.*' => 'required|email|min:3|max:300',
            'message' => 'required|min:3|max:5000',

        ]);
        for ($i = 0; $i < count($request->email); $i++) {
            $list = new Contact();
            $list->email = trim(strtolower($request->email[$i]));
            $list->subject = $request->subject;
            $list->message = $request->message;
            $list->name = $request->name;
            $list->superadmin_id = Auth::id();
            $list->reply = 'Send form Superadmin';
            $list->status = 1;
            $list->save();

            $data = array(
                'subject' => $request->subject,
                'name' => $list['name'],
                'email' => trim(strtolower($request->email[$i])),
                'message' => $request->message,
            );
            Mail::to($request->email)->send(new SendMail($data));
        }
        Toastr::success('Email Send  Successfully');
        return Redirect::to('superadmin/contacts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Contact = Contact::find($id);
        // dd($Contact);
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.contacts.index'), 'name' => "Contact"],
            ['name' => 'Reply Mail'],
        ];
        return view('backend.superadmin.contact.reply', ['breadcrumbs' => $breadcrumbs], ['contactinfo' => $Contact]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'subject' => 'required|min:3|max:192',
            'email' => 'required',
            'reply' => 'required|min:3|max:5000',

        ]);

        $list = Contact::find($id);
        $list->reply = $request->reply;
        $list->status = 1;
        $list->save();

        $data = array(
            'subject' => $request->subject,
            'name' => $list['name'],
            'email' => $request->email,
            'message' => $request->reply,
        );
        Mail::to($request->email)->send(new ContactMail($data));
        Toastr::success("Email Send  Successfully", "Success");
        return Redirect::to('superadmin/contacts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Contact::destroy($id);
       Toastr::error("Email Delete  Successfully", "Success");
        return Redirect::to('superadmin/contacts');
    }
    public function allEmailDestroy(Request $request)
    {
        $ids = $request->ids;
        Contact::whereIn('id', $ids)->delete();
        return response()->json(['success' => $ids]);
    }

    public function sendingsmsindex()
    {
        $smsapi = collect(array('SohiBdSMSAPI' => 'SohiBD SMS API', 'EjanananiSMSAPI' => 'Ejanani SMS API', 'FabricLagbesmsAPI' => 'Fabric Lagbe SMS API'));
        $breadcrumbs = [
            ['link' => "superadmin/dashboard", 'name' => "Home"], ['link' => "superadmin/sendingsms", 'name' => "Sending SMS"]
        ];

        return view('backend.superadmin.contact.sendingsmsindex', ['breadcrumbs' => $breadcrumbs], ['smsapi' => $smsapi]);
    }


    public function sendingsmsstore(Request $request, FlasherInterface $flasher)
    {
        if ($request->has('excelfile')) {
            $request->validate([
                'excelfile' => 'required|mimes:xlsx, csv, xls'
            ]);

            $rows = Excel::toArray([], $request->file('excelfile'));
            $phonenumber = count($rows[0]);
            for ($i = 1; $i < $phonenumber; $i++) {
                if ($request->smsapi == 'SohiBdSMSAPI') {
                    $data = [
                        'message' => $request->message,
                        'number' => $rows[0][$i][0],
                    ];
                    SendSmsmessage::dispatch($data);
                }
                if ($request->smsapi == 'EjanananiSMSAPI') {
                    $message = $request->message;
                    $number = $rows[0][$i][0];
                    Helper::EjanananiSMSAPI('880' . substr($number, 1), $message);
                }

                if ($request->smsapi == 'FabricLagbesmsAPI') {
                    $message = $request->message;
                    $number = $rows[0][$i][0];
                    Helper::FabricLagbesmsAPI('880' . substr($number, 1), $message);
                }
            }
        } else {



            $this->validate($request, [
                'smsapi' => 'required',
                'phone.*' => 'required|digits:11',
                'message' => 'required|min:2|max:500',

            ]);
            if ($request->smsapi == 'SohiBdSMSAPI') {
                for ($i = 0; $i < count($request->phone); $i++) {
                    $data = [
                        'message' => $request->message,
                        'number' => $request->phone[$i],
                    ];

                    SendSmsmessage::dispatch($data);
                }
            }
            if ($request->smsapi == 'EjanananiSMSAPI') {
                for ($i = 0; $i < count($request->phone); $i++) {
                    $message = $request->message;
                    $number = $request->phone[$i];
                    Helper::EjanananiSMSAPI('880' . substr($number, 1), $message);
                }
            }

            if ($request->smsapi == 'FabricLagbesmsAPI') {
                for ($i = 0; $i < count($request->phone); $i++) {
                    $message = $request->message;
                    $number = $request->phone[$i];
                    Helper::FabricLagbesmsAPI('880' . substr($number, 1), $message);
                }
            }
        }
        $flasher->addSuccess('SMS Send  Successfully');
        return Redirect::to('superadmin/sendingsms');
    }
}
