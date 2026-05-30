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
class SparepartController extends Controller
{    
	protected $redirectTo = '/';
	public function __construct()
    {
        //$this->middleware('guest', ['except' => ['logout']]);
    }
    public function index(Request $request)
    {
		$data = array(			
			'title' => 'SETUP SPAREPART',			
			'head' => 'Setup',			
			'classForm' => 'form-control form-control-sm',
			'btnClass' => 'btn btn-primary btn-sm',
			'classFormSelect2' => 'single-select select-2',			'can_insert' => hasPermission('sparepart', 'tambah', Session::get('role')),            'can_update' => hasPermission('sparepart', 'ubah', Session::get('role')),            'can_delete' => hasPermission('sparepart', 'hapus', Session::get('role')),
		);
		return view('sparepart.index', compact('data'));
    }
	public function getData(Request $request)
    {
		// parameter 2 tanpa where, (1: service, 2: sanitasi, 3: all)
		$data =  InitHelp::dataSparepart(0,0,2)->map(function($row) {
			return [
				'id'    => Crypt::encryptString($row->sparepart_id),
				'nama'  => $row->sparepart_nama,
				'tipe'  => $row->sparepart_tipe,
				'status'  => $row->sparepart_status,				'can_update' => hasPermission('sparepart', 'ubah', Session::get('role')),				'can_delete' => hasPermission('sparepart', 'hapus', Session::get('role')),
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
				$insert = DB::table('sparepart')->insert([
						"sparepart_id"=> InitHelp::GetID('sparepart','sparepart_id'),
						"sparepart_nama"=> $request->nama,
						"sparepart_tipe"=> $request->tipe,
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

				$update = DB::table('sparepart')->where('sparepart_id', Crypt::decryptString($request->id))->update([
						"sparepart_nama" => $request->nama,
						"sparepart_tipe"=> $request->tipe,
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
		
		$query = DB::table('sparepart')->where('sparepart_id', Crypt::decryptString($request->id))->delete();
		if($query) {
			return response()->json(['status'=>'success','message' => 'Data Berhasil Dihapus']);
		} else {
			return response()->json(['status'=>'error','message' => 'Data Tidak Ditemukan']);
		}		
        
    }

	public function NonAktif(Request $request)
    {           
        if($request->ajax()){
			
			DB::table('sparepart')->where('sparepart_id',Crypt::decryptString($request->id))->update([						
				'sparepart_status'=> 'n',			
			]);
				
			return response()->json(['status'=>'success']);
            
        } else {
			return response()->json(['status'=>'error']);
        }

    }
	
	public function Aktif(Request $request)
    {           
        if($request->ajax()){
			
			DB::table('sparepart')->where('sparepart_id',Crypt::decryptString($request->id))->update([						
				'sparepart_status'=> 'y',			
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
    