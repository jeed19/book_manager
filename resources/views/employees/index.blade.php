<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員管理（社員一覧・ロック解除）</title>
</head>
<body>
    <h1>社員管理システム（社員一覧）</h1>

    @if (session('status'))
        <div style="color: green; font-weight: bold; margin-bottom: 15px;">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h3>社員・アカウントステータス一覧</h3>

    @if($employees->isEmpty())
        <p>社員データが登録されていません。</p>
    @else
        <table border="1" cellpadding="8" style="border-collapse: collapse; width: 100%; max-width: 900px;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th>社員ID</th>
                    <th>氏名</th>
                    <th>表示名</th>
                    <th>部署ID</th>
                    <th>ステータス</th>
                    <th>ロック日時</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $emp)
                    <tr style="{{ $emp->locked_at ? 'background-color: #fff0f0;' : '' }}">
                        <td>{{ $emp->employee_id }}</td>
                        <td>{{ $emp->employee_name }}</td>
                        <td>{{ $emp->display_name }}</td>
                        <td>{{ $emp->department_id }}</td>
                        <td>
                            @if($emp->locked_at)
                                <span style="color: red; font-weight: bold; border: 1px solid red; padding: 2px 5px; background-color: white;">ロック中</span>
                            @else
                                <span style="color: green;">通常</span>
                            @endif
                        </td>
                        <td style="color: #666;">
                            {{ $emp->locked_at ? $emp->locked_at : '-' }}
                        </td>
                        <td>
                            @if($emp->locked_at)
                                <form action="/Employees/unlock" method="post" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $emp->employee_id }}">
                                    <input type="submit" value="ロック解除" onclick="return confirm('この社員（ID: {{ $emp->employee_id }}）のロックを解除しますか？');">
                                </form>
                            @else
                                <span style="color: #999; font-size: 0.9em;">対象なし</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <p style="margin-top: 20px;">
        <a href="{{ route('books.index') }}">書籍一覧画面へ戻る</a>
    </p>

    <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-danger">ログアウト</button>
    </form>
</body>
</html>