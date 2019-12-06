<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\HomeController;
use App\Models\Rekening;
use App\Models\Utility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class RekeningController extends HomeController
{
      public function __construct() {
    	parent::__construct();
    }

    public function index() {
    	return view('master.rekening.index');
    }

    public function rekenings() {
    	$users = Rekening::all();

        $cacah = 0;
        $data = [];

        foreach ($users as $i => $d) {
            
        	$data[$cacah] = [$d->no_rek, $d->deskripsi,$d->id];
        	$cacah++;    
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function getAutoCode() {
    	$rekening = DB::table('rekenings')
                ->where('no_rek', 'like', 'R-%')
                ->select('no_rek')
                ->orderBy('no_rek', 'desc')
                ->first();

        if ($rekening == null) {
        	return response()->json('R-001'); 
        } else {
        	$kembali = str_replace('R-', '', $rekening->no_rek);
        	$kembali = (int)$kembali;

        	$kembali = Utility::sisipkanNol(++$kembali, 3);

        	return response()->json('R-'.$kembali); 
        }
    }

    public function store(Request $request) {
        if ($request->ajax()) {
            $input = $request->all();

            // dd($input);

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                
                $hasil = $this->simpanTransaksiCreate($input);
                if ($hasil == '') {
                    return response()->json([
                        'data' => 'Sukses Menyimpan'
                    ]);
                } else {
                    return response()->json([
                        'data' => ['Gagal menyimpan data! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                    ], 422);
                }

            }
        }
    }

    protected function simpanTransaksiCreate($input) {
        DB::beginTransaction();

        try {

            $rekening = new Rekening();
            $rekening->no_rek = $input['no_rek'];
            $rekening->deskripsi = $input['deskripsi'];

            $rekening->save();
        } catch (ValidationException $ex) {
            DB::rollback();
            return $ex->getMessage();;
        } catch (Exception $ex) {
            DB::rollback();
            return $ex->getMessage();;
        }

        DB::commit();

        return '';
    }

    public function edit($id)
    {
        $rekening = Rekening::find($id);

        return response()->json([
            'id' => $rekening->id,
            'deskripsi' => $rekening->deskripsi,
            'no_rek'=>$rekening->no_rek
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {
            $input = $request->all();

            // dd($input);

            if (!isset($input['_token'])) {
                return response()->json([
                    'data' => $input->toArray()
                ]);
            } else {
                $rekening = Rekening::find($id);

                if ($rekening != null) {
                    if ($input['deskripsi'] == null) {
                        $input['deskripsi'] = '';
                    }
                    $hasil = $this->simpanTransaksiUpdate($input, $rekening);
                    if ($hasil == '') {
                        return response()->json([
                                'data' => 'Sukses Mengubah Data'
                            ]);
                    } else {
                            return response()->json([
                                'data' => ['Gagal mengubah data ! Periksa data anda dan pastikan server MySQL anda sedang aktif!']
                            ], 422);
                    }
                    
                } else {
                    return response()->json([
                        'data' => ['Gagal mengubah data ! Data tidak ditemukan di database']
                    ], 422);
                }
            }
        }
    }

    protected function simpanTransaksiUpdate($input, $rekening) {
        DB::beginTransaction();

        try {
            $dataubahrek = [
                'deskripsi' => $input['deskripsi'],
                'updated_at' => date('Y/m/d H:i:s')
            ];

            DB::table('rekenings')
                ->where('id', $rekening->id)
                ->update($dataubahrek);
        } catch (ValidationException $ex) {
            DB::rollback();
            return $ex->getMessage();;
        } catch (Exception $ex) {
            DB::rollback();
            return $ex->getMessage();;
        }

        DB::commit();

        return '';
    }

    public function destroy($id)
    {
        $rekening = Rekening::find($id);

        $hasil = $this->simpanTransaksiDelete($rekening);
        if ($hasil == '') {
            return response()->json([
                'data' => 'Sukses Menghapus Data'
            ]);
        } else {
            return response()->json([
                'data' => ['Gagal Menghapus data! Mungkin data ini sedang digunakan oleh data di tabel lainnya!']
            ], 422);
        }
    }

    protected function simpanTransaksiDelete($rekening)
    {
//        dd($input);
        DB::beginTransaction();

        try {
            $rekening->delete();
        } catch (ValidationException $ex) {
            DB::rollback();
            return $ex->getMessage();
        } catch (Exception $ex) {
            DB::rollback();
            return $ex->getMessage();
        }

        DB::commit();

        return '';
    }

}
