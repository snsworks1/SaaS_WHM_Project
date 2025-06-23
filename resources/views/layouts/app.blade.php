<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Hostyle 웹호스팅') }}</title>

        <!-- 파비콘 -->
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/IconOnly_Transparent.png') }}">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <!-- Google tag (gtag.js) -->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-Y7BBE8FQ2H"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-Y7BBE8FQ2H');
</script>


<!-- Channel Plugin Load -->
<script>
(function(){
    var w = window;
    if(w.ChannelIO){ return w.console.error("ChannelIO script included twice."); }
    var ch = function(){ ch.c(arguments); };
    ch.q = [];
    ch.c = function(args){ ch.q.push(args); };
    w.ChannelIO = ch;

    function l(){
        if(w.ChannelIOInitialized){ return; }
        w.ChannelIOInitialized = true;
        var s = document.createElement("script");
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://cdn.channel.io/plugin/ch-plugin-web.js";
        var x = document.getElementsByTagName("script")[0];
        if(x.parentNode){ x.parentNode.insertBefore(s, x); }
    }

    if(document.readyState === "complete"){ l(); }
    else {
        w.addEventListener("DOMContentLoaded", l);
        w.addEventListener("load", l);
    }
})();
</script>

@auth
@php
    $service = auth()->user()->service; // hasOne 관계라고 가정
@endphp
<script>
ChannelIO('boot', {
  pluginKey: "d090c74f-23b3-40ae-8ba5-a5d6f84dcc31",
  memberId: "{{ auth()->user()->id }}",
  profile: {
    name: "{{ auth()->user()->name }}",
    email: "{{ auth()->user()->email }}",
    mobileNumber: "{{ auth()->user()->phone }}",
    server_domain: "{{ $service?->domain ?? '없음' }}",
    plan_name: "{{ $service?->plan?->name ?? '없음' }}"
  }
});
</script>
@else
<script>
ChannelIO('boot', {
  pluginKey: "d090c74f-23b3-40ae-8ba5-a5d6f84dcc31"
});
</script>
@endauth

    </body>
</html>
