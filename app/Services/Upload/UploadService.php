<?php

namespace App\Services\Upload;

use Exception;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    public function __construct(){ }

    /**
     * Verify the specified resource from storage.
     *
     * @param  string  $file
     * @return boolean
     */
    public function verifyFile($file)
    {
        //verifica se o arquivo existe
        return Storage::exists($file) ? true : false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  string  $inputUpload
     * @param  string  $pathUpload
     * @return \Illuminate\Http\Response
     */
    public function uploadFileLogo($request, $inputUpload, $pathUpload)
    {
        try {
            //inicia com path vazio
            $path = '';
            //se fizer upload do arquivo
            if($request->hasFile($inputUpload) && $request->url_logo->isValid()){
                dd($pathUpload);
                $path = $request->url_logo->store($pathUpload); 
            }

            return $path;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  string  $inputUpload
     * @param  string  $pathUpload
     * @return \Illuminate\Http\Response
     */
    public function uploadFileProof($request, $inputUpload, $pathUpload)
    {
        try {
            //inicia com path vazio
            $path = '';
            //se fizer upload do arquivo
            if($request->hasFile($inputUpload) && $request->url_proof->isValid()){
                $path = $request->url_proof->store($pathUpload); 
            }

            return $path;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  string  $inputUpload
     * @param  string  $pathUpload
     * @return \Illuminate\Http\Response
     */
    public function uploadFileInvoice($request, $inputUpload, $pathUpload)
    {
        try {
            //inicia com path vazio
            $path = '';
            //se fizer upload do arquivo
            if($request->hasFile($inputUpload) && $request->url_invoice->isValid()){
                $path = $request->url_invoice->store($pathUpload); 
            }

            return $path;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $file
     * @return boolean 
     */
    public function destroyFile($file)
    {
        if ($this->verifyFile($file)) {
            Storage::delete($file);
            return true;
        }

        return false;
    }

    public function pathFile($file){
        $host = env('APP_URL');
        //$host = 'http://127.0.0.1:8000';
        $path = $file != '' ? $host .'/'. $file : '';
        return $path;
    }

}
