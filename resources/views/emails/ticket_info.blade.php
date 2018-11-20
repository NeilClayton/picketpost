<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Picketpost New Ticket!</title>
</head>
<body>
	<p>
		Your ticket has been created {{ ucfirst($user->name)}}:
	</p>

  	<p>Title: {{ $ticket->title }}</p>
    <p>Priority: {{ $ticket->priority }}</p>
    <p>Status: {{ $ticket->status }}</p>

    <p>
        You can view the ticket at any time at {{ url('tickets/'. $ticket->ticket_id) }}
    </p>

</body>
</html>