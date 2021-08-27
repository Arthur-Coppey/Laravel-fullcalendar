<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ $title ?? "FullCalendar" }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let url = "{{url('/')}}/";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            const dateTimeFormat = "YYYY-MM-DD HH:mm:ss"
            let calendarElement = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarElement, {
                editable: true,
                googleCalendarApiKey: "AIzaSyBEv4PxCTi2zBlkuAIcC6g7tFM_OVboGdk",
                eventSources: [
                    function (info, success, error) {
                        let start = moment(info.start).format(dateTimeFormat);
                        console.log(start)
                        let end = moment(info.end).format(dateTimeFormat);
                        $.ajax({
                            url: url + 'fullcalendar',
                            data: 'start=' + start + '&end=' + end,
                            type: 'GET',
                            success: function (resp) {
                                console.log("events fetched", resp);
                                success(resp);
                            },
                            error: function (err) {
                                console.error("failed to fetch events", err);
                                error(err);
                            }
                        });
                    },
                    {
                        googleCalendarId: "5dcgsjup0ebe6miqrgm62jigtg@group.calendar.google.com"
                    }
                ],
                displayEventTime: true,
                initialView: 'dayGridMonth',
                selectable: true,
                select: function (e) {
                    let title = prompt('Event Title:');
                    if (title) {
                        let start = moment(e.start).format(dateTimeFormat);
                        let end = moment(e.end).format(dateTimeFormat);
                        $.ajax({
                            url: url + 'fullcalendar/create',
                            data: 'title=' + title + '&start=' + start + '&end=' + end,
                            type: 'POST',
                            success: function (resp) {
                                console.log("added successfully", resp);
                                calendar.refetchEvents();
                            },
                            error: function (err) {
                                console.error("failed to add event", err);
                            }
                        });
                    }
                    calendar.unselect();
                },
                eventDrop: function (e) {
                    let start = moment(e.event.start).format(dateTimeFormat);
                    let end = moment(e.event.end).format(dateTimeFormat);
                    $.ajax({
                        url: url + 'fullcalendar/update',
                        data: 'title=' + e.event.title + '&start=' + start + '&end=' + end + '&id=' + e.event.id,
                        type: 'PUT',
                        success: function (resp) {
                            console.log("updated successfully", resp);
                        },
                        error: function (err) {
                            console.error("failed to update event", err);
                        }
                    });
                },
                eventClick: function (e) {
                    if (!e.event.url) {
                        if (confirm("Delete this event ?")) {
                            $.ajax({
                                url: url + 'fullcalendar/delete',
                                data: 'id=' + e.event.id,
                                type: 'DELETE',
                                success: function (resp) {
                                    calendar.getEventById(e.event.id).remove();
                                    console.log("deleted successfully", resp);
                                },
                                error: function (err) {
                                    console.error("failed to delete event", err);
                                }
                            });
                        }
                    }
                }
            });

            document.getElementById('refreshEvents').addEventListener('click', function () {
                calendar.refetchEvents();
            });

            let viewButtons = document.getElementsByClassName('view-change');

            for (let i = 0; i < viewButtons.length; i++) {
                viewButtons[i].addEventListener('click', function (e) {
                    calendar.changeView(e.target.id);
                });
            }

            setTimeout(function () {
                console.log(calendar.getEvents());
            }, 2000)

            calendar.render();
        });
    </script>
</head>
<body>
<div class="container">
    <div id="calendar"></div>
    <div class="fc fc-media-screen fc-direction-ltr fc-theme-standard">
        <div class="fc-footer-toolbar fc-toolbar">
            <div class="fc-toolbar-chunk">
                <button class="view-change fc-button fc-button-primary" id="dayGridMonth">Month</button>
                <button class="view-change fc-button fc-button-primary" id="timeGridWeek">Week</button>
                <button class="view-change fc-button fc-button-primary" id="timeGridDay">Day</button>
                <button class="view-change fc-button fc-button-primary" id="listDay">Day - List</button>
                <button class="view-change fc-button fc-button-primary" id="listWeek">Week - List</button>
                <button class="view-change fc-button fc-button-primary" id="listMonth">Month - List</button>
                <button class="view-change fc-button fc-button-primary" id="listYear">Year - List</button>
            </div>
            <div class="fc-toolbar-chunk">
                <button class="fc-button fc-button-primary" id="refreshEvents">Refresh Events</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
