<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Config;
use Validator;
use App\Model\Bank;
use App\Model\User;
use Illuminate\Support\Facades\Crypt;
class BankController extends Controller{
    public function getRekening(Request $req){
        // $bank = Bank::where('recordownerid', $req->userinfo->recordownerid)->whereNull('tgltutuprekening');
        $bank = Bank::whereNull('tgltutuprekening');
        if ($bank->count() > 0) {
            return response()->json([
                        'status'   =>'success',
                        'message'  =>'data found',
                        'data' => [
                            'rekeningbank' => $bank->get()
                        ]
                    ]);
        } else {
            return response()->json([
                        'status'   =>'fail',
                        'message'  =>'no data found',
                        'data' => [
                            'rekeningbank' => []
                        ]
                    ]);
        }
    }
}
