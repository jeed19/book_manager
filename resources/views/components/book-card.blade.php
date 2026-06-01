<div class="book-card" style="
    display: flex; 
    align-items: flex-start; 
    gap: 20px; 
    border: 1px solid #e0e0e0; 
    padding: 15px; 
    margin-bottom: 15px; 
    border-radius: 8px;
    background-color: #fff;
">
    <!-- 📸 左側：画像エリア -->
    <div class="book-image" style="flex-shrink: 0; width: 120px;">
        <img src="{{$imageUrl}}" 
             alt="{{ $title }}" 
             style="width: 100%; height: auto; border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    </div>

    <!-- ✍️ 右側：テキスト情報エリア -->
    <div class="book-info" style="flex-grow: 1;">
        <h3 style="margin: 0 0 10px 0; font-size: 1.2rem; color: #333;">
            {{ $title }}
        </h3>
        <p style="margin: 0 0 5px 0; color: #666; font-size: 0.9rem;">
            <strong>著者:</strong> {{ $author }}
        </p>
    </div>
</div>