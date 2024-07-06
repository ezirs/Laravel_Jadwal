@extends('layouts.calendar')

@section('style')
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
@endsection

@section('contain')
    <div id="calendar-container">
        <div id="calendar"></div>
    </div>
@endsection

@section('modal')
    <div id="context-menu" class="dropdown-menu" style="display:none; position:absolute;">
        <a class="dropdown-item" href="#" onclick="$('#editModal').modal('show')">Edit</a>
        <a class="dropdown-item" href="#" onclick="deleteEvent()">Delete</a>
    </div>
@endsection

@section('script')
    <script>
        function updateEvent() {
            let id = document.getElementById('editEventId').value;
            let title = document.getElementById('editTitle').value;
            let description = document.getElementById('editDescription').value;
            let start = document.getElementById('editStart').value;
            let end = document.getElementById('editEnd').value;

            fetch(`/api/schedules/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: title,
                    description: description,
                    start: start,
                    end: end
                })
            }).then(response => {
                if (response.ok) {
                    $('#editModal').modal('hide');
                    calendar.refetchEvents();
                }
            });
        }

        function deleteEvent() {
            let id = document.getElementById('editEventId').value;

            fetch(`/api/schedules/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    $('#editModal').modal('hide');
                    calendar.refetchEvents();
                }
            });
        }
    </script>
@endsection
