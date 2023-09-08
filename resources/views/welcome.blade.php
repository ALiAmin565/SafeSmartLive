<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
     <!-- Bootstrap CSS -->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0F3P3LF170"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-0F3P3LF170');
</script>
    @vite(['resources/js/app.js'])
</head>
<body>


    {{-- <img src="{{ public_path('') }}" alt=""> --}}
    <div id="resultDiv">
        {{-- @foreach ($collection as $item)
           <h6> {{ $item->massage }}</h6>
         @endforeach --}}
    </div>

 <input type="text" id="ahmed">
   <script type="module" >



            var pusher = new Pusher('b2636ae7a9413d9bf90b', {
                cluster: 'ap2'
                });
                // Subscribe to the dynamic channel
                var channel = pusher.subscribe('recommendation.AdviceVIP');
                console.log(channel);
                // Listen for events on the channel
                channel.bind('recommendation.AdviceVIP', function (data) {
                console.log('Received recommendation event:', data);
                // Handle the received event data here

                });

                var channel = pusher.subscribe('chat.AdviceVIP');
                // console.log(channel);
                // Listen for events on the channel
                channel.bind('chat.AdviceVIP', function (data) {
                console.log('Received recommendation event:', data);
                // Handle the received event data here
                });


         var channel = pusher.subscribe('closeChat.Diamond');
                // console.log(channel);
                // Listen for events on the channel
                channel.bind('closeChat.Diamond', function (data) {
                console.log('Received recommendation event:', data);
                // Handle the received event data here
                });






// var x="plan1";
// // console.log('recommendation/'+x);
//     window.Echo.channel('recommendation')
//     .listen('.recommendation/'+x,(e)=>{
//        console.log(e);
//     });

// //chat

//     window.Echo.channel('ChatPlan')
//     .listen('.ChatPlan',(e)=>{
// console.log(e);
//         var result = e.Massage.original.massage.massage;
//         var resultText = document.createTextNode(result);

//         var resultDiv = document.getElementById("resultDiv");
//        resultDiv.appendChild(resultText);
//     });




  </script>

<video src=""></video>

<img src="{{ asset('media/1687005572_c8d59d95fee45f25efe21d7bd03d5e70.mp4') }}" alt="">


</body>
</html>


{{-- connectChannel({required String channelName,required dynamic onEvent}) async {
    debugPrint("connectChannel $channelName");

    await _pusher.subscribe(
      channelName: channelName,

      onEvent: onEvent,
      onSubscriptionSucceeded: (data) {
        debugPrint("success Connecting $channelName channel: ${data.toString()}");
      },
      onSubscriptionError: (error) {
        debugPrint("error: ${error.message}");
      },
    );
  } --}}
