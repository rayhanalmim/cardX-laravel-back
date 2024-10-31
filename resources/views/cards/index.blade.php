<!-- resources/views/cards/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card List</title>
</head>
<body>
    <h1>Card List</h1>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <table border="1">
        <thead>
            <tr>
                <th>Email</th>
                <th>Company Name</th>
                <th>Designation</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Mobile Number</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cards as $card)
                <tr>
                    <td>{{ $card->email }}</td>
                    <td>{{ $card->companyName }}</td>
                    <td>{{ $card->designation }}</td>
                    <td>{{ $card->name }}</td>
                    <td>{{ $card->phoneNumber }}</td>
                    <td>{{ $card->mobileNumber }}</td>
                    <td>{{ $card->address }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
