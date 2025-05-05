
<!DOCTYPE html>
<html>
<head>
    <title>Reply to Your Message</title>
</head>
<body>
    <h2>Hello {{ $name }},</h2>
    
    <p>Thank you for your message. Here is our reply:</p>
    
    <div style="padding: 15px; background-color: #f5f5f5; border-left: 4px solid #007bff; margin: 20px 0;">
        {{ $replyText }}
    </div>
    
    <p><strong>Your original message:</strong></p>
    <div style="padding: 15px; background-color: #f5f5f5; border-left: 4px solid #6c757d; margin: 20px 0;">
        {{ $originalMessage }}
    </div>
    
    <p>Best regards,<br>Admin Team</p>
</body>
</html>