<?php

namespace App\Observers;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Contact;
use App\Models\User;
use App\Notifications\Usernotification;

class ContactObserver
{
    /**
     * Handle the Contact "created" event.
     *
     * @param  \App\Models\Contact  $contact
     * @return void
     */
    public function created(Contact $contact)
    {
        if($contact->superadmin_id==null){
        $data= array(
            'name'=> $contact->name,
            'subject'=>'Contact  Email',
            'message' => 'Your Email Send Succefully. Thank You',
             );
           
              Mail::to($contact)->send(new ContactMail($data));
              $data = [
                'message' => 'Hi ' . $contact->name . ' . Send a contact Mail',

            ];
        $data = [
            
            'superadminboady' =>'<a class="black-text"  href="'. url('/superadmin/replymail/'.$contact->id) . '"> New Email  ' .$contact->name. '</a>',
  ];
      $superAdmins = User::first();
     
          $superAdmins->notify(new Usernotification($data));
    }

}
}
