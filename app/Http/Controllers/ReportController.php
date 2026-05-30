<?phpnamespace App\Http\Controllers;use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use Excel;
use App\Helpers\InitHelp;
use App\Exports\LaporanTransaksiSanitasi;
use App\Exports\LaporanTransaksiService;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade\Pdf;use Intervention\Image\ImageManagerStatic as Image;
class ReportController extends Controller
{
    protected $redirectTo = '/';
	public function __construct()
    {		
        //$this->middleware('guest', ['except' => 'logout']);
    }
    public function index()
    {		    		
		$data = array(			
			'title' => 'LAPORAN CHILLER SERVICE FORM',
			'head' => 'LAPORAN',			
			'classForm' => 'form-control form-control-sm',
			'btnClass' => 'btn btn-primary btn-sm',
			'classFormSelect2' => 'single-select select-2',			'can_insert' => hasPermission('reportService', 'tambah', Session::get('role')),            'can_update' => hasPermission('reportService', 'ubah', Session::get('role')),            'can_delete' => hasPermission('reportService', 'hapus', Session::get('role')),
		);    
        return view('report.index', compact('data'));        
    }            
    public function getData(Request $request)
    {
		/* $tglAwal = $request->tglAwal;
		$tglAkhir = $request->tglAkhir; */
		$jenis = $request->laporan;
		$kriteria = Crypt::decrypt($request->kriteria);
		$param = $request->search;
		$tabel = "service";
		$field = "service_when";
		$primary = "service_id";		
		$dataTable = InitHelp::LapTransaksi($field,$tabel,$jenis,$primary,$kriteria,$param);						 		
		$dataTablex = $dataTable->map(function ($row) {
			return [
				'service_id'        		=> $row->service_id,
				'teknisi_nama'      		=> $row->teknisi_nama ?? '',
				'tgl_permintaan_service'	=> $row->tgl_permintaan_service ?? '',
				'permasalahan_chiller' 		=> $row->permasalahan_chiller ?? '',
				'tindakan_chiller'   		=> $row->tindakan_chiller ?? '',
				'outlet_nama'   			=> $row->outlet_nama ?? '',
				'outlet_alamat'   			=> $row->outlet_alamat ?? '',
				'outlet_pic'   				=> $row->outlet_pic ?? '',
				'idx'   					=> Crypt::encrypt($row->service_id),					
			];
		});
		if($dataTablex) {
			return response()->json([
				'status'=>'success',
				'data' => $dataTablex,						'can_update' => hasPermission('reportService', 'ubah', Session::get('role')),								'can_delete' => hasPermission('reportService', 'hapus', Session::get('role')),
				]);
		} else {
			return response()->json(['status'=>'failed']);
		}
    }	
	public function getDataFile(Request $request)
    {
		$idService = Crypt::decrypt($request->param);
		$dataFile = InitHelp::dataFile($idService);			$can_delete = hasPermission('reportService', 'hapus', Session::get('role'));		
		$html1 = "";				
		if(count($dataFile) > 0){
			for($a=0; $a<count($dataFile); $a++){
				$jenis = ($dataFile[$a]->files_jenis == 1) ? "Foto Sebelum":"Foto Sesudah";
				$html1 .= '<tr>';			
					$html1 .= '<td>'.($a+1).'</td>';
					$html1 .= '<td>'.ucwords(strtolower($dataFile[$a]->teknisi_nama)).'</td>';
					$html1 .= '<td>'.ucwords(strtolower($dataFile[$a]->outlet_nama)).'</td>';
					$html1 .= '<td>'.$dataFile[$a]->image_name_ori.'</td>';
					$html1 .= '<td>'.$jenis.'</td>';
					$html1 .= '<td>'.$dataFile[$a]->image_when.'</td>';
					$html1 .= '<td class="text-center">';						$html1 .= '<button type="button" onclick="viewFile(\''.$dataFile[$a]->image_path.'\');" class="btn btn-info btn-sm"><i class="bx bx-search-alt me-0"></i></button>';						if(hasPermission('reportService', 'hapus', Session::get('role'))){							$html1 .= '&nbsp;<button type="button" onclick="delFile(\''.Crypt::encrypt($dataFile[$a]->files_id).'\',\''.Crypt::encrypt($dataFile[$a]->id_service).'\');" class="btn btn-danger btn-sm"><i class="bx bx-trash-alt me-0"></i></button>';						}					$html1 .= '</td>';
				$html1 .= '</tr>';
			}
		} else {
			$html1 .= '<tr>';
				$html1 .= '<td colspan="7">File Tidak Tersedia</td>';					
			$html1 .= '</tr>';
		}	
		return response()->json([					
			'data' => $html1
		]);
	}			public function tambahFotoBefore(Request $request){		try {						// upload file			$request->validate([				'file.*' => 'required|image|mimes:jpg,jpeg',							], [				'file.*.required' => 'File wajib diupload.',				'file.*.image'    => 'File harus berupa gambar yang valid.',				'file.*.mimes'    => 'Format file harus jpg atau jpeg.',											]);						if ($request->hasFile('file')) {								foreach ($request->file('file') as $file) {					if (!$file->isValid()) continue;					$originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);					$originalName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);									$size = $file->getSize();														$ext = $file->extension();																	$fileName = 'b_' . uniqid() . '.' . $ext;					$path = $file->storeAs('sanitasi/service/before', $fileName, 'public');					$image = Image::make($file)							->orientate()							->resize(1280, null, function ($constraint) {								$constraint->aspectRatio();								$constraint->upsize();							})							->encode('jpg', 100)							->save(storage_path('app/public/' . $path));													$newSize = filesize(storage_path('app/public/' . $path));					$insertFile = DB::table('files_service')->insert([						"files_id"=> InitHelp::GetID('files_service', 'files_id'),						"id_service"=> Crypt::decrypt($request->idBefore),						"files_jenis"=> 1,						"image_name_ori"=> $originalName,						"image_name"=> $fileName,						"image_path"=> 'storage/' . $path,						"image_size"=> $newSize,						"image_ext"=> $ext,						"image_when"=> now(),					]);				}	 															$msg = 'Data Berhasil Diupload.';				$status = 'success'; 						}		} catch (\Illuminate\Validation\ValidationException $e) {			$msg = $e->validator->errors()->first();						$status = 'error';			} catch (\Exception $e) {			$msg = $e->getMessage();						$status = 'error';					}				return response()->json([								'status' => $status,            			'message' => $msg,				]);     }		public function tambahFotoAfter(Request $request){		try {						// upload file			$request->validate([								'file1.*' => 'required|image|mimes:jpg,jpeg',			], [								'file1.*.required' => 'File pada input kedua wajib diupload.',				'file1.*.image'    => 'File pada input kedua harus berupa gambar yang valid.',				'file1.*.mimes'    => 'File pada input kedua harus jpg atau jpeg.',							]);						if ($request->hasFile('file1')) {								foreach ($request->file('file1') as $file) {					if (!$file->isValid()) continue;					$originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);					$originalName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);									$size = $file->getSize();														$ext = $file->extension();																	$fileName = 'a_' . uniqid() . '.' . $ext;					$path = $file->storeAs('sanitasi/service/after', $fileName, 'public');					$image = Image::make($file)							->orientate()							->resize(1280, null, function ($constraint) {								$constraint->aspectRatio();								$constraint->upsize();							})							->encode('jpg', 100)							->save(storage_path('app/public/' . $path));													$newSize = filesize(storage_path('app/public/' . $path));					$insertFile = DB::table('files_service')->insert([						"files_id"=> InitHelp::GetID('files_service', 'files_id'),						"id_service"=> Crypt::decrypt($request->idAfter),						"files_jenis"=> 2,						"image_name_ori"=> $originalName,						"image_name"=> $fileName,						"image_path"=> 'storage/' . $path,						"image_size"=> $newSize,						"image_ext"=> $ext,						"image_when"=> now(),					]);				}	 																				$msg = 'Data Berhasil Diupload.';				$status = 'success'; 						}		} catch (\Illuminate\Validation\ValidationException $e) {			$msg = $e->validator->errors()->first();						$status = 'error';			} catch (\Exception $e) {			$msg = $e->getMessage();						$status = 'error';					}				return response()->json([								'status' => $status,            			'message' => $msg,				]);        }	
	public function export(Request $request){
		try {
			$jenis =$request->laporan;
			$param = $request->param;
			$file_name = 'ExcelService'.date('mdyhis').'.xlsx';			                
			return Excel::download(new LaporanTransaksiService($jenis,$param), $file_name);			
		} catch (exception $e) {
			return back()->with('error', 'Export gagal: ' . $e->getMessage());
		}    
    }	
	public function view_service(Request $request, $param)
    {
		$teknisi = InitHelp::dataTeknisi(2,1);
		$branding = InitHelp::dataBranding(2,3,1);
		$sparepart = InitHelp::dataSparepart(2,3,1);
		$dataService = InitHelp::dataInputService($param);
		$cleanBranding = str_replace(['{','}'], '', $dataService[0]->id_branding);	
		$cleanSparepart = str_replace(['{','}'], '', $dataService[0]->id_sparepart);	
		$dataImageBefore = InitHelp::dataImageBefore($dataService[0]->service_id,1);
		$dataImageAfter = InitHelp::dataImageAfter($dataService[0]->service_id,2);
		$data = array(			
			'title' => 'CHILLER SERVICE FORM',			
			'head' => 'Form',
			'teknisi' => $teknisi,
			'branding' => $branding,
			'sparepart' => $sparepart,
			'classForm' => 'form-control form-control-sm',
			'classFormSelect2' => 'single-select select-2',
			'service' => $dataService,
			'imageb' => $dataImageBefore,
			'imagea' => $dataImageAfter,
			'selectedBranding' => array_map('trim', explode(",", $cleanBranding)),
			'selectedSparepart' => array_map('trim', explode(",", $cleanSparepart))
		);
		return view('report.service', compact('data'));
    }		public function updateService(Request $request)
    {
		try {			// implode checkbox
			$array_branding = [];
			if (!empty($request->branding) && count($request->branding) > 0) {
				foreach ($request->branding as $row) {
					$array_branding[] = $row;
				}
				unset($row);
			}
			$branding = '{' . implode(",", $array_branding) . '}';
			$array_sparepart = [];
			if (!empty($request->sparepart) && count($request->sparepart) > 0) {
				foreach ($request->sparepart as $row) {
					$array_sparepart[] = $row;
				}
				unset($row);
			}
			$sparepart = '{'. implode(",", $array_sparepart). '}';
			$serviceId = Crypt::decrypt($request->id);			
			$update = DB::table('service')->where('service_id', $serviceId)
					->update([						
						"outlet_nama"=> trim($request->nama_outlet),
						"outlet_alamat"=> trim($request->alamat_outlet),
						"outlet_pic"=> trim($request->nama_pic_outlet),
						"tgl_pengecekan"=> $request->tgl_pengecekan,
						"tgl_permintaan_service"=> $request->tgl_permintaan_service,
						"tgl_pengerjaan_service"=> $request->tgl_pengerjaan_service,
						"jam_mulai"=> $request->waktu_mulai,
						"jam_selesai"=> $request->waktu_selesai,
						"teknisi_nama"=> trim($request->nama_teknisi),
						"merk_chiller"=> trim($request->merk_chiller),
						"tipe_chiller"=> trim($request->tipe_chiller),
						"no_seri_chiller"=> trim($request->no_seri_chiller),
						"id_branding"=> $branding,
						"permasalahan_chiller"=> trim($request->masalah_chiller),
						"tindakan_chiller"=> trim($request->tindakan_chiller),
						"id_sparepart"=> $sparepart,
						"other_sparepart"=> trim($request->other_sparepart),
						"other_branding"=> trim($request->other_branding),
						"penundaan_pengerjaan"=> trim($request->penundaan_pengerjaan),
						"pengisian_freon"=> $request->isi_freon,
					]);
			$msg = 'Data Berhasil Diupload.';
			$status = 'success'; 								
		} catch (\Illuminate\Validation\ValidationException $e) {
			$msg = $e->validator->errors()->first();			
			$status = 'error';				
		} catch (\Exception $e) {
			$msg = $e->getMessage();			
			$status = 'error';			
		}		
		return response()->json([					
			'status' => $status,            
			'message' => $msg,		
		]);    
	}
	public function downloadPdfService(Request $request, $param)
    {
		$dataService = InitHelp::dataInputService($param);		
		$dataImageBefore = InitHelp::dataImageBefore($dataService[0]->service_id,1);
		$dataImageAfter = InitHelp::dataImageAfter($dataService[0]->service_id,2);
        $data = array(			
			'title' => 'CHILLER SERVICE FORM',						
			'service' => $dataService,	
			'imageb' => $dataImageBefore,
			'imagea' => $dataImageAfter,			
		);
        $pdf = Pdf::loadView('service.service_pdf', $data);
        return $pdf->download('laporan-service-'.$dataService[0]->service_id.'.pdf');
    }
	public function destroy(Request $request, $id)
    {
		$query = DB::table('service')->where('service_id', Crypt::decrypt($id))->delete();				
		if($query) {
			return response()->json(['status'=>'success','message' => 'Data Berhasil Dihapus']);
		} else {
			return response()->json(['status'=>'error','message' => 'Data Tidak Ditemukan']);
		}
    }	public function destroyFile(Request $request, $id)    {		$query = DB::table('files_service')->where('files_id', Crypt::decrypt($id))->delete();						if($query) {			return response()->json(['status'=>'success','message' => 'Data Berhasil Dihapus']);		} else {			return response()->json(['status'=>'error','message' => 'Data Tidak Ditemukan']);		}    }	
    private function validateRequest($request, $id=0){        $messages = [
            'required' => 'Kolom <b>:attribute</b> harus diisi.',
            'min' => 'Panjang minimal <b>:attribute</b> huruf.',
            'numeric' => 'Inputan harus angka.',
            'unique' => 'Data <b>:attribute</b> ":input" sudah ada, tidak boleh sama.',
        ];
        return Validator::make($request->all(), [
//            "nomor_rekening" => "required|unique:t_rekening_nasabah,nomor_rekening".($id ? ",".$id.",id" : "" ),            
        ], $messages);
    }    
}
