<!DOCTYPE html>
<html>
<body>
<h2>Contact {{ ucfirst($action) }}</h2>
<p>The contact <strong>{{ $contact->name }}</strong> was {{ $action }}.</p>
<p>Email: {{ $contact->email }}</p>
<p>Phone: {{ $contact->phone }}</p>
</body>
</html>
