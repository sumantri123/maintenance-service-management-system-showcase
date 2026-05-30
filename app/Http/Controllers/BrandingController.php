<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Hash;
use Validator;
use Carbon\Carbon;
use App\Helpers\InitHelp;
class BrandingController extends Controller
{    
	protected $redirectTo = '/';
	public function __construct()
    {
        //$this->middleware('guest', ['except' => ['logout']]);
    }
    public function index(Request $request)
    {
		$data = array(			
			'title' => 'SETUP BRANDING',			
			'head' => 'Setup',			
			'classForm' => 'form-control form-control-sm',
			'btnClass' => 'btn btn-primary btn-sm',
			'classFormSelect2' => 'single-select select-2',			'can_insert' => hasPermission('branding', 'tambah', Session::get('role')),            'can_update' => hasPermission('branding', 'ubah', Session::get('role')),            'can_delete' => hasPermission('branding', 'hapus', Session::get('role')),
		);
		return view('branding.index', compact('data'));
    }

	public function getData(Request $request)
    {
		// parameter 2 tanpa where, (1: service, 2: sanitasi, 3: all)
		$data =  InitHelp::dataBranding(0,0,2)->map(function($row) {
			return [
				'id'    => Crypt::encryptString($row->branding_id),
				'nama'  => $row->branding_nama,
				'tipe'  => $row->branding_tipe,
				'status'  => $row->branding_status,				'can_update' => hasPermission('branding', 'ubah', Session::get('role')),				'can_delete' => hasPermission('branding', 'hapus', Session::get('role')),
			];
		});
		return response()->json([
                'status'=>'successful',
                'data'=>$data
        ]);
    }
    
	public function store(Request $request)
    {
        if($request->ajax()){
            
            DB::beginTransaction();

            try {

				$insert = DB::table('branding')->insert([
						"branding_id"=> InitHelp::GetID('branding','branding_id'),
						"branding_nama"=> $request->branding_nama,
						"branding_tipe"=> $request->branding_tipe,
					]);

				if($insert) {
					DB::commit();
					$msg = 'Data Berhasil Disimpan.';
					$status = 'success'; 	
				} else {
					$msg = 'Data Gagal Disimpan.';
					$status = 'error'; 	
				}
				
				return response()->json([					
					'status' => $status,            
					'message' => $msg,		
				]); 
				
            } catch (\Throwable $e) {

				DB::rollback();            
				throw $e;            
				$msg = 'Data Gagal Disimpan';
				$status = 'error'; 	

				return response()->json([					
					'status' => $status,            
					'message' => $msg,		
				]); 

            }
        } else {
            return redirect('asset/');
        }

    }

	public function update(Request $request)
    {
        if($request->ajax()){
            
            DB::beginTransaction();

            try {

				$update = DB::table('branding')->where('branding_id', Crypt::decryptString($request->id))->update([
						"branding_nama" => $request->branding_nama,
						"branding_tipe"=> $request->branding_tipe,
					]);

				if($update) {
					DB::commit();
					$msg = 'Data Berhasil Diubah.';
					$status = 'success'; 	
				} else {
					$msg = 'Data Gagal Diubah.';
					$status = 'error'; 	
				}
				
				return response()->json([					
					'status' => $status,            
					'message' => $msg,		
				]); 
				
            } catch (\Throwable $e) {

				DB::rollback();            
				throw $e;            
				$msg = 'Data Gagal Diubah';
				$status = 'error'; 	

				return response()->json([					
					'status' => $status,            
					'message' => $msg,		
				]); 

            }
        } else {
            return redirect('asset/');
        }

    }

	public function destroy(Request $request, $id)
    {
		
		$query = DB::table('branding')->where('branding_id', Crypt::decryptString($request->id))->delete();
		if($query) {
			return response()->json(['status'=>'success','message' => 'Data Berhasil Dihapus']);
		} else {
			return response()->json(['status'=>'error','message' => 'Data Tidak Ditemukan']);
		}		
        
    }

	public function NonAktif(Request $request)
    {           
        if($request->ajax()){
			
			DB::table('branding')->where('branding_id',Crypt::decryptString($request->id))->update([						
				'branding_status'=> 'n',			
			]);
				
			return response()->json(['status'=>'success']);
            
        } else {
			return response()->json(['status'=>'error']);
        }

    }
	
	public function Aktif(Request $request)
    {           
        if($request->ajax()){
			
			DB::table('branding')->where('branding_id',Crypt::decryptString($request->id))->update([						
				'branding_status'=> 'y',			
			]);
				
			return response()->json(['status'=>'success']);
            
        } else {
			return response()->json(['status'=>'error']);
        }

    }

	protected function guard()
    {
        return Auth::guard('web');
    }
}
    