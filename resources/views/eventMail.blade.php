<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訂閱確認</title>
</head>
<body>
    <h1>訂閱確認</h1>
    <p>{{ $user->name }} 您好，</p>
    <p>此電子郵件確認您已成功訂閱以下項目：</p>

    @if($eventIds)
        <ul>
            @foreach($eventIds as $eventId)
                <li>活動編號: {{ $eventId }}</li>
            @endforeach
        </ul>
    @else
        <p>目前沒有訂閱的活動。</p>
    @endif

     <p>感謝您的訂閱！如果您有任何問題，請隨時與我們聯繫。</p>

    <p>祝您有美好的一天！</p>
</body>
</html>
