<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
    			'kode', 'nama', 
    			'jenis_id', 'harga_beli', 'pembulatan', 'stok', 
    			'markup_1', 'rp_1', 'harga_jual_1',
    			'markup_2', 'rp_2', 'harga_jual_2',
    			'markup_3', 'rp_3', 'harga_jual_3'
    		];

    public function jenis() {
        return $this->belongsTo('App\Models\Jenis');
    }

    public function pembeliandetail() {
    	return $this->hasMany('App\Models\PembelianDetail');
    }

    public function penjualandetail() {
        return $this->hasMany('App\Models\PenjualanDetail');
    }

    public function sementara() {
    	return $this->hasMany('App\Models\Sementara');
    }

    public function opname() {
    	return $this->hasMany('App\Models\Opname');
    }

    public function hilang() {
    	return $this->hasMany('App\Models\Hilang');
    }

    public function history() {
    	return $this->hasMany('App\Models\History');
    }
}
