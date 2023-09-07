<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Helpers\Helper;
use App\Models\Databackup;
use Illuminate\Http\Request;
use App\Helpers\ErrorTryCatch;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


class DatabackupController extends Controller
{
    public function index(Request $request)
    {
        try {
           
         
            $data = Databackup::latest();
        
    if ($request->ajax()) {return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($data){
                $btn = '<a href=' . route(request()->segment(1) . '.databases.show', $data->id) . ' class="btn btn-info btn-sm waves-effect" style="margin-left: 5px"><i class="fas fa-download"></i></a>';
                $btn .= '<a  onclick="return confirm(`Are you sure?`)" href=' . route(request()->segment(1) . '.databases.edit', $data->id) . ' class="btn btn-danger btn-sm waves-effect" style="margin-left: 5px"><i class="fas fa-trash"></i></a>';
                 return $btn;
            })
            ->addColumn('filesize' ,function($data){
                
                 return $data->file_size.' MB';
           
          })
            ->rawColumns(['action','filesize'])
            ->make(true);
    }

    return view('backend.superadmin.databackups.index');
} catch (\Exception $e) {
    $response = ErrorTryCatch::createResponse(false, 500, 'Internal Server Error.', null);
    Toastr::error($response['message'], "Error");
    return back();
}
      
}

public function create(Request $request)
{
   Artisan::call('work:databasebackup');
   Toastr::success('Databse Backup Successfully', "Success");
    return back();
}


public function show($id)
{
    $databackup=Databackup::findOrFail($id);
     $path =   $databackup->file_path;
     $fileName = $databackup->backup_date.'.sql';

    return Response::download($path, $fileName, ['Content-Type: application/sql']);
}
public function edit($id)
{
    $databackup=Databackup::findOrFail($id);
    File::delete(@$databackup->file_path);
    $databackup->delete();
    Toastr::info('Databse Delete Successfully', "Success");
    return back();
}
}
