<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama'       => 'required|max:100|min:3',
            'jenis'       => 'required|numeric|exists:jenis,id',
            'harga_beli'       => 'required|numeric|min:1',
            'pembulatan'       => 'required|numeric|min:0',
            'markup_1'       => 'required|numeric',
            'rp_1'       => 'required|numeric',
            'harga_jual_1'       => 'required|numeric|min:1',
            'markup_2'       => 'required|numeric',
            'rp_2'       => 'required|numeric',
            'harga_jual_2'       => 'required|numeric|min:1',
            'markup_3'       => 'required|numeric',
            'rp_3'       => 'required|numeric',
            'harga_jual_3'       => 'required|numeric|min:1'
        ];
    }

    public function messages()
    {
        return [

            'nama.required'  => 'Nama barang harus ada',
            'nama.max'  => 'Nama barang maksimal :max karakter',
            'nama.min'  => 'Nama barang minimal :min karakter',

            'jenis.required'  => 'Jenis barang harus ada',
            'jenis.numeric'  => 'ID jenis barang harus berupa angka',
            'jenis.exists'  => 'Jenis barang tidak terdaftar di database',

            'harga_beli.required'  => 'Harga beli harus ada',
            'harga_beli.numeric'  => 'Harga beli harus berupa angka',
            'harga_beli.min'  => 'Harga beli minimal :min',

            'pembulatan.required'  => 'Nilai pembulatan harus ada',
            'pembulatan.numeric'  => 'Nilai pembulatan harus berupa angka',

            'markup_1.required'  => 'Nilai markup harga 1 harus ada',
            'markup_1.numeric'  => 'Nilai markup harga 1 harus berupa angka',

            'rp_1.required'  => 'Nilai tambahan harga 1 harus ada',
            'rp_1.numeric'  => 'Nilai tambahan harga 1 harus berupa angka',

            'harga_jual_1.required'  => 'Harga jual 1 harus ada',
            'harga_jual_1.numeric'  => 'Harga jual 1 harus berupa angka',
            'harga_jual_1.min'  => 'Harga jual 1 minimal :min',

            'markup_2.required'  => 'Nilai markup harga 2 harus ada',
            'markup_2.numeric'  => 'Nilai markup harga 2 harus berupa angka',

            'rp_2.required'  => 'Nilai tambahan harga 2 harus ada',
            'rp_2.numeric'  => 'Nilai tambahan harga 2 harus berupa angka',

            'harga_jual_2.required'  => 'Harga jual 2 harus ada',
            'harga_jual_2.numeric'  => 'Harga jual 2 harus berupa angka',
            'harga_jual_2.min'  => 'Harga jual 2 minimal :min',

            'markup_3.required'  => 'Nilai markup harga 3 harus ada',
            'markup_3.numeric'  => 'Nilai markup harga 3 harus berupa angka',

            'rp_3.required'  => 'Nilai tambahan harga 3 harus ada',
            'rp_3.numeric'  => 'Nilai tambahan harga 3 harus berupa angka',

            'harga_jual_3.required'  => 'Harga jual 3 harus ada',
            'harga_jual_3.numeric'  => 'Harga jual 3 harus berupa angka',
            'harga_jual_3.min'  => 'Harga jual 3 minimal :min',
            
        ];
    }
}
