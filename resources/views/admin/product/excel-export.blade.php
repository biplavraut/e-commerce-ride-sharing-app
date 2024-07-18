<table>
  <thead>
    <tr>
      <th>category_slug</th>
      <th>title</th>
      <th>vendor_phone</th>
      <th>badge</th>
      <th>size</th>
      <th>color</th>
      <th>price</th>
      <th>stock</th>
      <th>description</th>
      <th>discount_type</th>
      <th>discount</th>
      <th>batch_no</th>
      <th>expire_date</th>
      <th>unit</th>
      <th>hide</th>
      <th>gogo_price</th>
      <th>vat_percentage</th>
      <th>service_charge_percentage</th>
      <th>elite_percent</th>
    </tr>
  </thead>
  <tbody>
    @foreach($products as $product)
    <tr>
      <td>{{ $product->category? $product->category->slug : '-' }}</td>
      <td>{{ $product->title }}</td>
      <td>{{ $product->vendor_id }}</td>
      <td>{{ implode("|", $product->badge) }}</td>
      <td>{{ implode("|", $product->size) }}</td>
      <td>{{  implode("|", $product->color) }}</td>
      <td>{{ $product->price }}</td>
      <td>{{ $product->opening_stock }}</td>
      <td>{{ $product->description }}</td>
      <td>{{ $product->discount_type }}</td>
      <td>{{ $product->discount }}</td>
      <td>{{ $product->batch_no }}</td>
      <td>{{ $product->expire_date }}</td>
      <td>{{ $product->unit }}</td>
      <td>{{ $product->hide }}</td>
      <td>{{ $product->price_1 }}</td>
      <td>{{ $product->vat_percentage }}</td>
      <td>{{ $product->service_charge_percentage }}</td>
      <td>{{ $product->elite_percent }}</td>
    </tr>
    @endforeach
  </tbody>
</table>