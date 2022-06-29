<?php

namespace app\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Helper function for paginator form. Without it, the sorting filters where deleted by Laravel paginator.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function paginationHelper(Request $request)
    {
        $previousURL = explode("/", url()->previous());
        $previousURL = end($previousURL);

        if (str_contains($previousURL, 'page')){
            $url = substr($previousURL, 0, -1);
            $url = "tickets/".$url.$request->page;
        }
        else if (str_contains($previousURL, 'sort') || str_contains($previousURL, 'order')){
            $url = "tickets/$previousURL&page=$request->page";
        }
        else{
            $url = "tickets/$previousURL?page=$request->page";
        }

        return redirect($url);
    }
}
