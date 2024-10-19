<!-- resources/views/emails/orders/shipped.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Confirmation</title>
</head>
<body>
    <h1>訂閱確認</h1>
    <p>{{ $users->name }} 您好，</p>
    <p>	此電子郵件確認你已訂閱下列項目：</p>
    @if(count($eventIds) > 0)
        <ul>
            @foreach($eventIds as $eventId)
                <li>Event ID: {{ $eventId }}</li>
            @endforeach
        </ul>
    @else
        <p>No events found.</p>
    @endif

    <p>感謝您的訂閱！如果您有任何問題，請隨時與我們聯繫。</p>

    <p>祝您有美好的一天！</p>
</body>
</html>
