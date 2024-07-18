<table>
    <thead>
    <tr>
      <th>S.No</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Phone</th>
      <th>Address</th>
    </tr>
    </thead>
    <tbody>
    @foreach($riders as $key => $rider)
      <tr>
        <td>{{ ++$key }}</td>
        <td>{{ $rider->first_name }}</td>
        <td>{{ $rider->last_name }}</td>
        <td>{{ $rider->phone }}</td>
        <td>{{ $rider->address }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>