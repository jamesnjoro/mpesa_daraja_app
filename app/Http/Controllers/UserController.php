<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = User::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $class = $row->approved == 0? "edit btn btn-primary btn-sm":"edit btn btn-primary btn-sm disabled";
                    $text = $row->approved == 0? "Approve user":"User approved";
                    $btn = '<a href="javascript:void(0)" onclick="approve('.$row->id.')" class="'.$class.'">'.$text.'</a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users');
    }

    public function approve(Request $request){
        $validator = Validator::make($request->all(), [
            'userID'=>'required'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }

        User::where('id',$request->userID)
            ->update(['approved' => 1]);

        return response("Okay",200);
    }
}
