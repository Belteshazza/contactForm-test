<p>A new contact submission has been received:</p>

<ul>
    <li><strong>Name:</strong> {{ $submission->name }}</li>
    <li><strong>Email:</strong> {{ $submission->email }}</li>
    <li><strong>Attachment:</strong> {{ asset($submission->attachment) }}</li>
    <li><strong>Message:</strong> {{ $submission->message }}</li>
</ul>