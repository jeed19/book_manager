<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password; // パスワードルール
use App\Models\Employee;
use App\Models\Department;
use App\Http\Controllers\BooksController;

class EmployeesController extends Controller
{
    /**
     * ログイン処理
     */
    public function login(Request $req)
    {
        
        // 1. 形式のバリデーション（existsはセキュリティのため外す）
        $validator = Validator::make($req->all(), [
            'employee_id' => 'required|integer|digits:8',
            'password' => $this->passwordRules(false), // 共通ルール（ログイン）
        ], [
            'employee_id.required' => '社員IDを入力してください。',
            'employee_id.integer'  => '社員IDは数値で入力してください。',
            'employee_id.digits'   => '社員IDは8桁で入力してください。',
        ]);

        // 2. 形式エラーがなければ、アカウントロックとパスワードの照合を行う
        if (!$validator->fails()) {
            $validator->after(function ($validator) use ($req) {
                $employee = Employee::where('employee_id', $req->employee_id)->first();

                // アカウントロック状態の確認
                if ($employee && $employee->locked_at !== null) {
                    $validator->errors()->add('auth_failed', 'このアカウントはロックされています。管理者に問い合わせてください。');
                    return;
                }

                // 「社員が存在しない」または「パスワードが一致しない」場合
                if (!$employee || !Hash::check($req->password, $employee->password)) {
                    
                    // 社員が存在している場合は失敗カウンターを回す
                    if ($employee) {
                        $employee->login_failure_count += 1;
                        if ($employee->login_failure_count >= 3) {
                            $employee->locked_at = now(); // 3回でロック
                        }
                        $employee->save();
                    }

                    // どちらが間違っていても同じエラーメッセージを返す（セキュリティ考慮）
                    $validator->errors()->add('auth_failed', '社員IDまたはパスワードが違います');
                } else {
                    // 認証成功時はカウンターをリセット
                    if ($employee->login_failure_count > 0) {
                        $employee->login_failure_count = 0;
                        $employee->save();
                    }
                }
            });
        }

        if ($validator->fails()) {
            return redirect("/")
                ->withErrors($validator)
                ->withInput();
        }

        // ログイン成功後処理
        $employee = Employee::where('employee_id', $req->employee_id)->first();
        
        // ★追記：部署マスターから権限フラグを取得する
        $department = \App\Models\Department::find($employee->department_id);

        $employee_data = [
            'employee_id'     => $employee->employee_id,
            'department_id'   => $employee->department_id,
            'employee_name'   => $employee->employee_name,
            'display_name'    => $employee->display_name,
            'can_register_book'    => $department ? $department->can_register_book : false,
            'can_unlock'      => $department ? $department->can_unlock : false,
        ];

        // セッション開始
        $req->session()->put('session_data', $employee_data);
        return redirect()->action([BooksController::class, 'index']);
    }

    /**
     * 社員管理画面（社員一覧 兼 ロック解除画面）
     */
    public function index(Request $req)
    {
        // 1. 認可チェック（Fail-Fast）
        $session_data = $req->session()->get('session_data');
        if (!$session_data) {
            return redirect('/');
        }

        // ログイン中の部署の権限を調べる
        $department = Department::find($session_data['department_id']);
        
        // ロック解除権限がない部署の場合は弾く
        if (!$department || !$department->can_unlock) {
            return redirect()->action([BooksController::class, 'index'])
                ->withErrors(['auth_failed' => '社員管理画面へのアクセス権限がありません。']);
        }

        // 2. データ取得（★変更：全社員のデータを取得する）
        $employees = Employee::all();

        // 3. 画面表示
        return view('Employees.index', [
            'employees' => $employees // ★全社員データをビューに渡す
        ]);
    }

    /**
     * ユーザ情報変更画面の表示
     */
    public function edit(Request $req)
    {
        if ($req->isMethod('get')) {
            // セッションからログイン中のユーザーデータを取得
            $session_data = $req->session()->get('session_data');
            if (!$session_data) {
                return redirect('/');
            }

            $data = [
                'record' => Employee::where('employee_id', $session_data['employee_id'])->first()
            ];
            return view('Employees.edit', $data);

        } elseif ($req->isMethod('post')) {
            $data = [
                'record' => Employee::where('employee_id', $req->employee_id)->first()
            ];
            return view('Employees.edit', $data);
        } else {
            return redirect('/');
        }
    }

    /**
     * ユーザ情報の更新処理
     */
    public function update(Request $req)
    {
        // 認可チェック（本人以外の不正操作を即座に弾く）
        $session_data = $req->session()->get('session_data');
        if (!$session_data || $session_data['employee_id'] != $req->employee_id) {
            return redirect('/');
        }

        // 1. 基本的な形式バリデーション
        $validator = Validator::make($req->all(), [
            'employee_id' => 'required|exists:employees,employee_id',
            'new_display_name' => 'required|string|max:' . Employee::DISPLAY_NAME_MAX,
            
            // 「新しいパスワード」が入力されている場合のみ「現在のパスワード」を必須にする
            'current_password' => 'nullable|required_with:new_password|string', 
            
            'new_password' => $this->passwordRules(true), 
        ], [
            'new_display_name.required' => '表示名を入力してください。',
            'new_display_name.max' => '表示名は' . Employee::DISPLAY_NAME_MAX . '文字以内で入力してください。',
            
            'current_password.required_with' => 'パスワードを変更する場合は、現在のパスワードを入力してください。',
            
            'new_password.min' => '新しいパスワードは' . Employee::RAW_PASSWORD_MIN . '文字以上で入力してください。',
            'new_password.max' => '新しいパスワードは' . Employee::RAW_PASSWORD_MAX . '文字以内で入力してください。',
            'new_password.confirmed' => '確認用のパスワードと一致しません。',
        ]);

        // 2. 形式がOKなら、パスワードのロジック検証を行う
        if (!$validator->fails()) {
            $validator->after(function ($validator) use ($req) {
                $employee = Employee::where('employee_id', $req->employee_id)->first();
                if (!$employee) return;

                // 新しいパスワードが入力されている場合のみ、パスワードの検証を行う
                if (!empty($req->new_password)) {
                    
                    // 【ステップ1】「現在のパスワード」がDBのものと一致するかチェック（Fail-Fast）
                    if (!Hash::check($req->current_password, $employee->password)) {
                        $validator->errors()->add('current_password', '現在のパスワードが違います。');
                        return; // 一致しない場合はここで終了（新しいパスワードの同一性検証はしない）
                    }

                    // 【ステップ2】現在のパスワードが正しいことを確認した上で、新しいパスワードとの同一性をチェック
                    if (Hash::check($req->new_password, $employee->password)) {
                        $validator->errors()->add(
                            'new_password',
                            '新しいパスワードは、現在のパスワードと異なるものを入力してください。'
                        );
                    }
                }
            });
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 3. DB更新処理
        $employee = Employee::where('employee_id', $req->employee_id)->first();
        $employee->display_name = $req->new_display_name;

        // 新しいパスワードがある場合のみハッシュ化して保存
        if (!empty($req->new_password)) {
            $employee->password = Hash::make($req->new_password);
        }
        $employee->save();

        // 4. セッションデータの同期
        $session_data['display_name'] = $employee->display_name;
        $req->session()->put('session_data', $session_data);

        return redirect()
            ->action([BooksController::class, 'index'])
            ->with('status', 'ユーザー情報を更新しました。');
    }

    /**
     * アカウントのロックを解除する
     */
    public function unlock(Request $req)
    {
        // 1. 認可チェック（Fail-Fast）
        $session_data = $req->session()->get('session_data');
        if (!$session_data) {
            return redirect('/');
        }

        $department = Department::find($session_data['department_id']);
        if (!$department || !$department->can_unlock) {
            return redirect('/')->withErrors(['auth_failed' => 'この操作を行う権限がありません。']);
        }

        // 2. バリデーション
        $validator = Validator::make($req->all(), [
            'employee_id' => 'required|exists:employees,employee_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // 3. ロック解除
        $employee = Employee::where('employee_id', $req->employee_id)->first();
        if ($employee) {
            $employee->locked_at = null;
            $employee->login_failure_count = 0;
            $employee->save();
        }

        return redirect('/Employees/index')
            ->with('status', "社員ID: {$employee->employee_id} のロックを解除しました。");
    }

    /**
     * パスワードの共通バリデーションルールを定義
     */
    private function passwordRules($isUpdate = false)
    {
        // 基本のルール：文字列、8〜32文字、英大文字・小文字・数字混在
        $rule = [
            'string',
            Password::min(Employee::RAW_PASSWORD_MIN)
                ->max(Employee::RAW_PASSWORD_MAX)
                ->letters()   // アルファベット必須
                ->mixedCase() // 大文字小文字混在必須
                ->numbers(),  // 数字必須
        ];

        // ログイン時は「必須(required)」、更新時は「空欄でもOK(nullable)」と「確認欄一致(confirmed)」
        if ($isUpdate) {
            array_unshift($rule, 'nullable', 'confirmed');
        } else {
            array_unshift($rule, 'required');
        }

        return $rule;
    }

    /**
     * 従業員のログアウト処理
     */
    public function logout(Request $request)
    {
        // 1. 認証解除
        Auth::logout();

        // 2. 現在のセッションにあるデータをすべて削除
        $request->session()->invalidate();

        // 3. セッションのCSRFトークンを再生成（セッション固定攻撃の防止）
        $request->session()->regenerateToken();

        // 4. 任意のページ（例: ログイン画面）へリダイレクト
        return redirect()->to('/');
    }
}