<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Invoice Removed Notification</title>
</head>
<body>
  <p>Dear {{ $tenantName }},</p>

  <blockquote style="border-left: 3px solid #ccc; margin: 10px 0; padding-left: 10px;">
    {{ $messageText }}
  </blockquote>

</body>
</html>
