@extends('layouts.base')
@section('main')
    <p>ようこそ、{{$session_data['display_name']}} さん！</p>

<table style="width: 100%; border-collapse: separate; border-spacing: 0; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; margin: 20px 0;">
    <thead>
        <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
            <th style="padding: 14px 16px; text-align: left; color: #475569; font-size: 14px; font-weight: 600;">画像</th>
            <th style="padding: 14px 16px; text-align: left; color: #475569; font-size: 14px; font-weight: 600;">ISBN</th>
            <th style="padding: 14px 16px; text-align: left; color: #475569; font-size: 14px; font-weight: 600;">タイトル</th>
            <th style="padding: 14px 16px; text-align: left; color: #475569; font-size: 14px; font-weight: 600;">著者名</th>
            <th style="padding: 14px 16px; text-align: right;"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($records as $record)
        <tr style="border-bottom: 1px solid #f1f5f9; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='#ffffff'">
            <td style="padding: 12px 16px; vertical-align: middle;">
                <img src="{{ $record->cover_image }}" alt="{{ $record->book_name }}" style="width: 60px; height: auto; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: block;">
            </td>
            <td style="padding: 12px 16px; vertical-align: middle; color: #64748b; font-family: monospace; font-size: 13px;">
                {{ $record->isbn }}
            </td>
            <td style="padding: 12px 16px; vertical-align: middle; color: #1e293b; font-weight: 500;">
                {{ $record->book_name }}
            </td>
            <td style="padding: 12px 16px; vertical-align: middle; color: #334155;">
                {{ $record->author_name }}
            </td>
            <td style="padding: 12px 16px; vertical-align: middle; text-align: right;">
                <a href="{{ route('books.show', ['isbn' => $record->isbn]) }}" style="inline-block; padding: 8px 14px; background-color: #ffffff; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.05); cursor: pointer; transition: all 0.2s;">
                    詳細を見る
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection