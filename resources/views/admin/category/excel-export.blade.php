<table>
  <thead>
  <tr>
    <th>Name</th>
    <th>Slug</th>
    <th>Image</th>
    <th>Parent Slug</th>
    <th>Parent</th>
  </tr>
  </thead>
  <tbody>
  @foreach($categories as $category)
    <tr>
      <td>{{ $category->name }}</td>
      <td>{{ $category->slug }}</td>
      <td>{{ $category->image }}</td>
      <td>{{ $category->parent_id ? $category->parentCategory->slug : null }}</td>
      <td>{{ $category->parent }}</td>
    </tr>
  @endforeach
  </tbody>
</table>