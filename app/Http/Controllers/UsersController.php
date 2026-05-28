<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class UsersController extends Controller
{
    public function login(Request $req)
{
    $validator = Validator::make($req->all(), [
        'employee_id' => 'required|integer|digits:8|exists:employees,employee_id',
        'password' => 'required|min:8|max:32',
    ]);

    // バリデーション追加
    $validator->after(function ($validator) use ($req) {

    $employee = Employee::where('employee_id', $req->employee_id)->first();

        // 社員が存在しない場合は終了
        if (!$employee) {
            return;
        }

        // パスワード一致判定
        if (!Hash::check($req->password, $employee->password)) {
            $validator->errors()->add(
            'password',
            'パスワードが違います'
        );
    }
    });

    // エラー時
    if ($validator->fails()) {
        return redirect("/")
            ->withErrors($validator)
            ->withInput();
    }

    // ログイン成功後処理
    $employee = Employee::where('employee_id', $req->employee_id)->first();

    $employee_data = [
        'employee_id' => $employee->employee_id,
        'department_id' => $employee->department_id,
        'employee_name' => $employee->employee_name,
        'display_name' => $employee->display_name,
    ];

    $req->session()->put('session_data', $employee_data);

    return view('Books.index');
    }
}
