<html>
<head>
    <title>SethPhat Long Polling Chat</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" type="text/css" rel="stylesheet" />
    <meta name="csrf-token" content="{{csrf_token()}}" />
</head>
<body>
<div class="container">
    <h3 class="text-center p-4">SethPhat Long Polling Chat</h3>
    <div id="app"></div>
</div>

<script>
    var base_url = "{{url("/")}}/";
</script>
<script src="{{asset('js/app.js')}}"></script>
</body>
</html>