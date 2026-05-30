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

class RoleController extends Controller
{    
	 
	protected $redirectTo = '/';
	public function __construct()
    {
        //$this->middleware('guest', ['except' => ['logout']]);
    }
	
	
    public function index(Request $request)
    {
		$data = array(			
			'title' => 'SETUP MANAJEMEN ROLE',			
			'head' => 'Setup',			
			'classForm' => 'form-control form-control-sm',
			'btnClass' => 'btn btn-primary btn-sm',
			'classFormSelect2' => 'single-select select-2',
			'can_insert' => hasPermission('role', 'tambah', Session::get('role')),
            'can_update' => hasPermission('role', 'ubah', Session::get('role')),
            'can_delete' => hasPermission('role', 'hapus', Session::get('role')),
		);
		return view('role.index', compact('data'));
    }
	
	public function getData(Request $request)
    {
		
		$data =  InitHelp::roleData()->map(function($row) {
			return [
				'id'    => Crypt::encrypt($row->role_id),
				'nama'  => $row->role_nama,				
				'status'  => $row->role_status,
				'can_update' => hasPermission('role', 'ubah', Session::get('role')),
				'can_delete' => hasPermission('role', 'hapus', Session::get('role')),
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
				
				$insert = DB::table('role')->insert([
						"role_id"=> InitHelp::GetID('role','role_id'),
						"role_nama"=> $request->role_nama,						
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
				$update = DB::table('role')->where('role_id', Crypt::decrypt($request->id))->update([
						"role_nama" => $request->role_nama,						
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
		
		$query = DB::table('role')->where('role_id', Crypt::decrypt($request->id))->delete();
		if($query) {
			return response()->json(['status'=>'success','message' => 'Data Berhasil Dihapus']);
		} else {
			return response()->json(['status'=>'error','message' => 'Data Tidak Ditemukan']);
		}		
        
    }
	public function NonAktif(Request $request)
    {           
        if($request->ajax()){
			
			DB::table('role')->where('role_id',Crypt::decrypt($request->id))->update([						
				'role_status'=> 'n',			
			]);
				
			return response()->json([
				'status'=>'success',
				'msg'=>'Akses Berhasil Dihapus',
			]);
            
        } else {
			return response()->json([
				'status'=>'error',
				'msg'=>'Akses Gagal Dihapus',
			]);
        }
    }
	
	public function Aktif(Request $request)
    {           
        if($request->ajax()){
			
			DB::table('role')->where('role_id',Crypt::decrypt($request->id))->update([						
				'role_status'=> 'y',			
			]);
				
			return response()->json([
				'status'=>'success',
				'msg'=>'Akses Berhasil Ditambahkan',
				]);
            
        } else {
			return response()->json([
				'status'=>'error',
				'msg'=>'Akses Gagal Ditambahkan',
				]);
        }
    }
	
	public function detail(Request $request, $id)
    {

		$dataRole = InitHelp::roleDataEdit(Crypt::decrypt($id));
		$data = array(

			'title' => 'DETAIL AKSES - '.strtoupper($dataRole[0]->role_nama),
			'head' => 'Setup',
			'classForm' => 'form-control form-control-sm',
			'btnClass' => 'btn btn-primary btn-sm',
			'classFormSelect2' => 'single-select select-2',
			'idRole' => $id,
			'role' => $dataRole,
		);

		return view('role.detail', compact('data'));

    }
	
	public function getDataDet(Request $request, $id)
    {
		$dataAkses = DB::select(
        
			DB::raw('
				select b.*, a.*, c.submenu_nama as menu_nama 
				from m_submenu as b 
				left join m_submenu_det as a on b.submenu_id = a.id_sub_menu and a.id_role = '.Crypt::decrypt($id).'
				left join m_submenu as c on b.submenu_parent = c.submenu_id 
				where b.submenu_status = "y" 
				and b.submenu_link is not null 				
				order by b.submenu_nama_alias asc
			')
        );

        if($dataAkses) {
            return response()->json([
                'status'=>'successful',
                'data' => $dataAkses,				
                ]);
        } else {
            return response()->json(['status'=>'failed']);
        }
				
    }
	
	public function saveMenu(Request $request, $idSubmenu, $status)
    {
        if($request->ajax()){            
						
            DB::beginTransaction();
            try {
				$id = explode("-",$idSubmenu);
				$id_submenu = $id[0];
				$id_role = $id[1];
				
				$insert = DB::table('m_submenu_det')->insert([
					"submenu_det_id"=> InitHelp::GetID('m_submenu_det','submenu_det_id'),
					"id_sub_menu"=> $id_submenu,
					"id_role"=> Crypt::decrypt($id_role),						
				]);

				if($insert) {
					DB::commit();             
					return response()->json(['status'=>'insert_successful','msg'=>'Data Berhasil Ditambahkan']);                    
				} else {
					return response()->json(['status'=>'insert_failed','msg'=>'Data Gagal Ditambahkan']);
				}
				                
            } catch (\Throwable $e) {
                DB::rollback();            
                throw $e;            
                return response()->json(['status'=>'insert_failed','msg'=>'Data Gagal Ditambahkan']);
            }
			
        } else {
            return redirect('asset/');
        }

    }
	
	public function deleteMenu(Request $request, $id)
    {
		$query = DB::table('m_submenu_det')->where('submenu_det_id', $request->id)->delete();
		if($query) {
			return response()->json(['status'=>'delete_successful','message' => 'Data Berhasil Dihapus']);
		} else {
			return response()->json(['status'=>'delete_failed','message' => 'Data Tidak Ditemukan']);
		}
    }
	
	public function permission(Request $request)
    {
		if($request->ajax()){
			
			if($request->action == 'insert'){
				$field = 'tambah';
			}elseif($request->action == 'update'){
				$field = 'ubah';
			}elseif($request->action == 'delete'){
				$field = 'hapus';
			}else{
				$field = '';
			}
			
			$msg = ($request->value == 1) ? "Permission Ditambahkan":"Permission Dihapus";
			DB::table('m_submenu_det')
				->where('submenu_det_id',$request->id)
				->where('id_role',Crypt::decrypt($request->role))
				->update([						
					$field=> $request->value,			
				]);
				
			return response()->json(['status'=>'success','msg'=>$msg]);
            
        } else {
			return response()->json(['status'=>'error','msg'=>'Permission Gagal Simpan']);
        }
    }
	
	protected function guard()
    {
        return Auth::guard('web');
    }
}
    