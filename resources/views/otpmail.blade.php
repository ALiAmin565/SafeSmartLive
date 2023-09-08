<!DOCTYPE html>
<html>
<head>
    <title>{{ $otpData['subject'] ?? $otpDataaa['subject'] }} </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>
<div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
  <div style="margin:50px auto;width:70%;padding:20px 0">
    <div style="border-bottom:1px solid #eee">
      <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">SmartSolutions</a>
    </div>
    <p style="font-size:1.1em">Hi,</p>
    <p>Thank you for choosing SmartSolutions. Use the following OTP to {{ $otpData['subject'] ?? $otpDataaa['subject'] }}.</p>
    <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">{{ $otpData['otp'] ?? $otpDataaa['otp'] }}</h2>
    <p style="font-size:0.9em;">Regards,<br />SmartSolutions</p>
    <hr style="border:none;border-top:1px solid #eee" />
    <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
      <p>SmartSolutions</p>

    </div>
  </div>
</div>
</body>
</html>