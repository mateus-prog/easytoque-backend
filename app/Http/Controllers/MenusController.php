<?php

namespace App\Http\Controllers;

use App\Http\HttpStatus;
use Illuminate\Http\Request;
use App\Services\menu\MenuService;
use App\Traits\ApiResponser;
use App\Traits\Pagination;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;

class MenusController extends Controller
{
    use ApiResponser;
    use Pagination;

    protected $menuService;
    
    public function __construct(MenuService $menuService)
    {
        $this->middleware(["auth", "verified"]);
        $this->menuService = $menuService;
    }
    
    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getMenuByRole($roleId)
    {
        $menus = $this->menuService->getMenuByRole($roleId);

        return $this->success($menus, HttpStatus::SUCCESS);
    }
}
