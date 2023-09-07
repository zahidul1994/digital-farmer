<?php
namespace App\Http\Controllers;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Http\Middleware\Admin;
use App\Models\Shop;
use App\Models\User;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Session;
use Artesaos\SEOTools\Facades\OpenGraph;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function index(){
        SEOMeta::setRobots('index, follow');
        OpenGraph::addProperty('type', 'website');
        JsonLd::setType('website');
        SEOTools::setTitle('Shoes  Pos Software');
        SEOTools::setDescription('Best Shoes Shop Pos Software  In Bangladesh.Try For Free');
        SEOMeta::addKeyword('agency');
        OpenGraph::addImage(url(Helper::setting()->logo),['height' => 315, 'width' => 600]);
       SEOTools::opengraph()->setUrl(url('/'));
       $companies= User::join('profiles', 'profiles.user_id', '=', 'users.id')->select('users.*', 'profiles.rating', 'profiles.comment', 'profiles.company_name', 'profiles.company_logo','profiles.country')->whereuser_type('admin')->wherestatus(1)->get();
      
       
  return view("frontend.welcome",compact('companies'));
    }
    // public function main()
    // {
    //   SEOMeta::setRobots('index, follow');
    //   OpenGraph::addProperty('type', 'website');
    //   JsonLd::setType('website');
    //   SEOTools::setTitle('Shoes POS  Software');
    //   SEOTools::setDescription('Best Shoes POS  Software  In Bangladesh.Try For Free');
    //   SEOMeta::addKeyword('agency');
    //   OpenGraph::addImage(url(Helper::setting()->logo),['height' => 315, 'width' => 600]);
             
    //     return view("frontend.index");
    // }

    public function aboutUs(){
        return view("frontend.pages.about_us");
    }
    public function contactStore(Request $request){
        

            $this->validate($request,[
                  'name' => 'required|min:1|max:198',
                  'email' => 'required|email|min:5|max:288',
                  'message' => 'required|min:3|max:1000',
      
                 ]);
                 $userinfo=Contact::whereipaddress($request->ip())->wherestatus(0)->first(); 
                 if(!isset($userinfo)){
          $list = new Contact();
          $list->name = $request->name;
          $list->email = $request->email;
          $list->subject = $request->subject;
          $list->message = $request->message;
            $list->ipaddress = $request->ip();
          $list->save();
          Session::flash('message', 'Message Sent Successfully!');
          return Redirect::to('/'); 
      } else {
        Session::flash('message', 'Something went wrong! Please try again !');
          return back();
    }
    }

   
}
