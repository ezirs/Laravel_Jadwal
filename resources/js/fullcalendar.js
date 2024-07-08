import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import momentTimezonePlugin from '@fullcalendar/moment-timezone'
import interactionPlugin from '@fullcalendar/interaction';

let calendar;

document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');

    let calendar = new Calendar(calendarEl, {
        timeZone: 'Asia/Jakarta',
        locale: 'id',
        height: '100%',
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin, momentTimezonePlugin],
        customButtons: {
            tambahJadwal: {
                text: 'Tambah Jadwal',
                click: function() {
                    alert('Tombol berfungsi!');
                }
            }
        },
        headerToolbar: {
            start: 'title',
            end: 'today prevYear prev,next nextYear'
        },
        footerToolbar: {
            start: 'tambahJadwal',
            end: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day',
            list: 'List'
        },
        noEventsContent: 'Tidak ada jadwal untuk ditampilkan',
        events: '/api/schedules',
        editable: true,
        selectable: true,
        select: function(info) {
            let title = prompt('Enter Event Title:');
            let description = prompt('Enter Event Description:');
            if (title) {
                fetch('/api/schedules', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        title: title,
                        description: description,
                        start: info.startStr,
                        end: info.endStr
                    })
                }).then(response => {
                    if (response.ok) {
                        calendar.refetchEvents();
                    }
                });
            }
        },
        eventClick: function(info) {
            $('#editModal').modal('show');
            $('#editTitle').val(info.event.title);
            $('#editDescription').val(info.event.extendedProps.description);
            $('#editStart').val(info.event.start.toISOString().slice(0, 16));
            $('#editEnd').val(info.event.end.toISOString().slice(0, 16));
            $('#editEventId').val(info.event.id);
        },
        eventDidMount: function(info) {
            info.el.addEventListener('contextmenu', function(ev) {
                ev.preventDefault();
                let contextMenu = document.getElementById('context-menu');
                contextMenu.style.top = ev.clientY + 'px';
                contextMenu.style.left = ev.clientX + 'px';
                contextMenu.style.display = 'block';
                $('#editEventId').val(info.event.id);
            });
        }
    });

    calendar.render();
});

// Hide context menu on click outside
document.addEventListener('click', function() {
    document.getElementById('context-menu').style.display = 'none';
});

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

