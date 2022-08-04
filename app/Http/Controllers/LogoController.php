<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Services\Upload\UploadService;
use App\Services\User\UserCorporateService;
use App\Services\Logo\LogoService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class LogoController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $uploadService;
    protected $userCorporateService;
    protected $logoService;
    
    public function __construct(
        UploadService $uploadService,
        UserCorporateService $userCorporateService,
        LogoService $logoService
    )
    {
        $this->middleware(["auth", "verified"]);
        $this->uploadService = $uploadService;
        $this->userCorporateService = $userCorporateService;
        $this->logoService = $logoService;
    }

    public function getByUser()
    {
        try {
            $urlLogo = $this->logoService->getPathLogo();
            $path = $this->uploadService->pathFile($urlLogo);
            
            return $this->success(['path' => $path], HttpStatus::SUCCESS);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function updateLogo(Request $request)
    {
        
        try {
            $id = $this->logoService->getUserCorporateId();

            $pathNew = $this->uploadService->uploadFileLogo($request, 'url_logo', 'logos');
            if ($this->uploadService->verifyFile($pathNew)) {
                $userCorporate = $this->userCorporateService->findById($id);
                //pega o nome do arquivo
                $pathOld = $userCorporate->url_logo;

                //apaga o arquivo
                $pathOld != '' ? $this->uploadService->destroyFile($pathOld) : '';
            }

            $this->userCorporateService->update($id, ['url_logo' => $pathNew]);

            $path = $this->uploadService->pathFile('storage/'.$pathNew);

            return $this->success(['path' => $path], HttpStatus::SUCCESS);
        } catch (AuthorizationException $aE) {
            return $this->error($aE->getMessage(), HttpStatus::FORBIDDEN);
        } catch (ModelNotFoundException $m) {
            return $this->error($m->getMessage(), HttpStatus::NOT_FOUND);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function destroyLogo()
    {
        try {
            $id = $this->logoService->getUserCorporateId();
            //pega o nome do arquivo que esta no banco
            $file = $this->logoService->getPathLogo();

            $file = str_replace('storage/', '', $file);

            //apaga o arquivo
            $isDeleted = $this->uploadService->destroyFile($file);
            
            //caso tenha deletado o arquivo apaga o valor do campo url_logo
            $isDeleted ? $this->userCorporateService->update($id, ['url_logo' => '']) : '';
            
            //pega o nome do arquivo default
            $file = $this->logoService->getPathLogo();

            //pega o caminho do arquivo default
            $path = $this->uploadService->pathFile($file);
            
            return $this->success(['path' => $path], HttpStatus::SUCCESS);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
