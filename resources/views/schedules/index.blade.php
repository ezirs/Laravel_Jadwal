<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

	<link href="/assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<style>
        #context-menu {
            display: none;
            position: absolute;
            z-index: 1000;
            background: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        html,
        body {
            overflow: hidden;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 14px;
        }

        #calendar-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: 10px;
        }

        .fc-header-toolbar {
            padding-top: 1em;
            padding-left: 1em;
            padding-right: 1em;
        }
    </style>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/js/fullcalendar.js'])
</head>
<body>
    <div id="calendar-container">
        <div id="calendar"></div>
    </div>

	<div id="context-menu" class="dropdown-menu" style="display:none; position:absolute;">
        <a class="dropdown-item" href="#" onclick="$('#editModal').modal('show')">Edit</a>
        <a class="dropdown-item" href="#" onclick="deleteEvent()">Delete</a>
    </div>

	<script src="/assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
