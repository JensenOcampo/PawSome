<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8' />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <style>
        #calendar {
            width: 90%;
            margin: 0 auto;
            background-color: #A6AEBF; 
            border: 1px solid #1A1A1D;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.7);
            margin-top: 10px;
        }

        .fc-toolbar {
            background-color: #81BFDA;
            color: white;
            border-radius: 5px;
            padding: 10px;
        }

        .fc-toolbar button {
            background-color: white;
            color: #4caf50;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .fc-toolbar button:hover {
            background-color: #45a049;
            color: white;
        }

        /* Days Grid Styling */
        .fc-daygrid-day {
            border: 1px solid #1A1A1D; 
            background-color: #fff; 
        }

        .fc-daygrid-day:hover {
            background-color: #f0f0f0; 
        }

        /* Highlight Today's Date */
        .fc-day-today {
            background-color: #A8CD89 !important;
            font-weight: bold;
        }

        /* Event Styling */
        .fc-event {
            background-color: #2196f3;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.85em;
            cursor: pointer;
        }

        .fc-event:hover {
            background-color: #1976d2;
        }

        /* Day Numbers */
        .fc-daygrid-day-number {
            font-size: 3em;
            font-weight: bold;
            color: #333;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var today = new Date();
            today.setHours(0, 0, 0, 0);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                dateClick: function(info) {
                    // checking the past dates
                    var selectedDate = new Date(info.dateStr);
                    if (selectedDate < today) {
                        alert('You cannot select past dates.');
                        return;
                    }
                    $('#schedule').val(info.dateStr); // storing clicked date in the hidden input field

                    // Load the form dynamically from appointment_add.php and pass the schedule as a query parameter
                    $('#appointment-modal .modal-body').load('appointment_add.php?schedule=' + info.dateStr, function() {
                        $('#appointment-modal').modal('show');
                    });
                },
                dayCellDidMount: function(cellInfo) {
                    var cellDate = new Date(cellInfo.date);
                    if (cellDate < today) {
                        cellInfo.el.style.pointerEvents = 'none'; 
                        cellInfo.el.style.opacity = '0.6'; 
                    }
                }
            });
            calendar.render();
        });

        // Handle the form submission inside the modal
        $(document).on('submit', '#appointment-form', function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: 'appointment_add.php?schedule=' + $('#schedule').val(), 
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        $('#appointment-modal .modal-body').html('<div class="alert alert-success">' + response.message + '</div>');
                        setTimeout(function() {
                            $('#appointment-modal').modal('hide');
                        }, 3000);
                    } else {
                        $('#appointment-modal .modal-body').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function() {
                    $('#appointment-modal .modal-body').html('<div class="alert alert-danger">There was an error processing the request.</div>');
                }
            });
        });
    </script>
</head>

<body>
    <?php include_once('navbar.php'); ?>
    
    <!-- FullCalendar -->
    <div id='calendar'></div>

    <!-- Modal -->
    <div class="modal fade" id="appointment-modal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">New Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                
                </div>
            </div>
        </div>
    </div>
</body>
</html>
