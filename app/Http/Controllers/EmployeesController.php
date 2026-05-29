<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class EmployeesController extends Controller
{
    public function login(Request $req)
    {
        // 1. 形式のバリデーション（existsは外す）
        $validator = Validator::make($req->all(), [
        'employee_id' => 'required|integer|digits:8',
        'password' => 'required|min:8|max:32',
    ], [
        'employee_id.required' => '社員IDを入力してください。',
        'employee_id.integer'  => '社員IDは数値で入力してください。',
        'employee_id.digits'   => '社員IDは8桁で入力してください。',
        'password.required'    => 'パスワードを入力してください。',
        'password.min'         => 'パスワードは8文字以上で入力してください。',
        'password.max'         => 'パスワードは32文字以内で入力してください。',
    ]);

        // 形式チェックが通った場合のみ、DB照合とパスワードチェックを行う
        if (!$validator->fails()) {
            $validator->after(function ($validator) use ($req) {
                $employee = Employee::where('employee_id', $req->employee_id)->first();

                // 「社員が存在しない」または「パスワードが一致しない」のどちらでも同じエラーを出す
                if (!$employee || !Hash::check($req->password, $employee->password)) {
                    // employee_id フィールドにエラーを紐づける（画面の都合に合わせ password でも可）
                    $validator->errors()->add(
                        'auth_failed',
                        '社員IDまたはパスワードが違います'
                    );
                }
            });
        }

        // エラー時（形式エラー、またはID/パス不一致エラー）
        if ($validator->fails()) {
            return redirect("/")
                ->withErrors($validator)
                ->withInput();
        }

        // ログイン成功後処理（afterを通過していれば必ず存在する）
        $employee = Employee::where('employee_id', $req->employee_id)->first();

        $employee_data = [
            'employee_id' => $employee->employee_id,
            'department_id' => $employee->department_id,
            'employee_name' => $employee->employee_name,
            'display_name' => $employee->display_name,
        ];

        // セッション開始
        $req->session()->put('session_data', $employee_data);

        return redirect()->action([BooksController::class, 'index']);
    }

    public function edit(Request $req)
    {
        if($req->isMethod('get')){
            return view('Employees.edit');
        } elseif($req->isMethod('post')){
            $data = [
                'record' => Employee::where('employee_id', $req->employee_id)->first()
            ];
            return view('Employees.edit',$data);
        } else {
            redirect('/');
        }
    }

    // 表示名を変更する
    public function update(Request $req)
    {
        // 変更してDBを更新
        $new_display_name = $req->new_display_name;
        $employee = Employee::where('employee_id', $req->employee_id)->first();
        $employee->display_name = $new_display_name;
        $employee->save();

        return view('Employees.update', $new_display_name);
    }
}
