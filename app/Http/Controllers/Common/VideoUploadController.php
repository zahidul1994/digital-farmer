<?php

namespace App\Http\Controllers\Common;
use App\Models\VideoUpload;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Yajra\DataTables\DataTables;
use Iman\Streamer\VideoStreamer;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;

class VideoUploadController extends Controller
{
    private $User;
    function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->User = Auth::user();
            if ($this->User->status == 0) {
                $request->session()->flush();
                Toastr::error('Your Account was Deactive Please Contact with Support Center', "Error");
                return redirect('login');
            }
            return $next($request);
        });

        $this->middleware('permission:video-upload-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:video-upload-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:video-upload-edit', ['only' => ['edit', 'update', 'updateStatus']]);
        $this->middleware('permission:video-upload-delete', ['only' => ['destroy']]);
    }
  
    public function index(Request $request)
    {
         
          try {
            // dd(VideoUpload::with('user')->get());
            $User = $this->User;

            if ($User->user_type == 'Superadmin' || $User->user_type == 'Admin') {
                $data = VideoUpload::with('user')->latest();
            } elseif ($User->user_type == 'Staff') {

                $data =  VideoUpload::with('user')->whereuser_id(Auth::id())->latest();
            } else {

                $data =  VideoUpload::with('user')->whereuser_id(Auth::user()->user_id)->latest();
            }
            if ($request->ajax()) {

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($data) use ($User) {
                        $btn = '<a href=' . route(request()->segment(1) . '.video-uploads.show', (encrypt($data->id))) . ' class="btn btn-success btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-eye"></i></a>';
                        $btn .= '<a href=' . route(request()->segment(1) . '.video-uploads.edit', (encrypt($data->id))) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fa fa-trash"></i></a>';
                        return $btn;
                    })
                    ->addColumn('status', function ($data) {
                        if ($data->status == 0) {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        } else {
                            return '<div class="form-check form-switch"><input type="checkbox" id="flexSwitchCheckDefault" checked="" onchange="updateStatus(this)" class="form-check-input"  value=' . $data->id . ' /></div>';
                        }
                    })
                    
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            $breadcrumbs = [
                ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
                ['link' => route(request()->segment(1) . '.video-uploads.index'), 'name' => "Video"],
                ['name' => 'Video Upload List'],
            ];
            return view('backend.common.video_uploads.index', compact('breadcrumbs'));
        } catch (\Exception $e) {
            $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
            Toastr::error($response['message'], "Error");
            return back();
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link' => route(request()->segment(1) . '.dashboard'), 'name' => "Home"],
            ['link' => route(request()->segment(1) . '.video-uploads.index'), 'name' => "VideoUpload"],
            ['name' => 'Create'],
        ];
        return view('backend.common.video_uploads.create', compact('breadcrumbs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));

        if (!$receiver->isUploaded()) {
            // file not uploaded
        }

        $fileReceived = $receiver->receive(); // receive file
        if ($fileReceived->isFinished()) { // file uploading is complete / all chunks are uploaded
            $file = $fileReceived->getFile(); // get file
            $extension = $file->getClientOriginalExtension();
            $fileName = str_replace('.'.$extension, '', Str::slug($file->getClientOriginalName())); //file name without extenstion
            $fileName .= '_' . md5(time()) . '.' . $extension; // a unique file name

            $disk = Storage::disk('public');
            $path = $disk->putFileAs('videos', $file, $fileName);

            // delete chunked file
            unlink($file->getPathname());

               $uploadVideo=new VideoUpload();
               $uploadVideo->user_id= Auth::id();;
               $uploadVideo->upload_type="video";
               $uploadVideo->text_title=substr($file->getClientOriginalName(),0, -4);
               $uploadVideo->video_link='storage/' . $path;
               $uploadVideo->created_by_user_id= Auth::id();
               $uploadVideo->updated_by_user_id= Auth::id();
               $uploadVideo->save();
            return [
                'path' => asset('storage/' . $path),
                'filename' => $fileName
            ];
        }

        // otherwise return percentage informatoin
        $handler = $fileReceived->handler();
        return [
            'done' => $handler->getPercentageDone(),
            'status' => true
        ];
    }
   
   
    public function show($id)
    {
        $video = VideoUpload::findOrFail(decrypt($id));
              return view('backend.common.video_uploads.show', compact('video'));
       

    }
    public function getDemoVideo()
    {
        $video = VideoUpload::latest()->first();
        $path= $video->video_link;
       return VideoStreamer::streamFile($path);

    }
    public function edit($id)
    {
        $video = VideoUpload::findOrFail(decrypt($id));
        $path= public_path($video->video_link);
        unlink($path);
        $video->delete();
    }
    public function updateStatus(Request $request)
    {
        $equipment = VideoUpload::findOrFail($request->id);
        $equipment->status = $request->status;
        if ($equipment->save()) {
            return 1;
        }
        return 0;
    }
    }
   