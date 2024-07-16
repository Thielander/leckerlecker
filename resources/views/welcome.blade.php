<!DOCTYPE html>
<html>
<head>
    <title>Open Food Facts</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h1>Open Food Facts</h1>
        <form action="{{ route('search') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="barcode">Barcode:</label>
                <input type="text" name="barcode" id="barcode" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Search</button>
        </form>
        @isset($product)
            <div class="mt-5">
                <h2>Product Information</h2>
                <p><strong>Name:</strong> {{ $product['product_name'] ?? 'N/A' }}</p>
                <p><strong>Brands:</strong> {{ $product['brands'] ?? 'N/A' }}</p>
                <p><strong>Categories:</strong> {{ $product['categories'] ?? 'N/A' }}</p>
                <p><strong>Countries:</strong> {{ $product['countries'] ?? 'N/A' }}</p>
                <p><strong>Image:</strong> <img src="{{ $product['image_url'] }}" alt="Product Image"></p>
                
                <h3>Ingredients</h3>
                <p>{{ $product['ingredients_text'] ?? 'N/A' }}</p>

                <h3>Nutriments</h3>
                @if(isset($product['nutriments']) && is_array($product['nutriments']))
                    <ul>
                        @foreach($product['nutriments'] as $key => $value)
                            <li>{{ $key }}: {{ $value }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>N/A</p>
                @endif

                <h3>Ecoscore Data</h3>
                @if(isset($product['ecoscore_data']) && is_array($product['ecoscore_data']))
                    <ul>
                        @foreach($product['ecoscore_data'] as $key => $value)
                            <li>{{ $key }}: {{ $value }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>N/A</p>
                @endif

                <h3>Nutriscore</h3>
                <p>Grade: {{ $product['nutriscore_grade'] ?? 'N/A' }}</p>
                <p>Score: {{ $product['nutriscore_score'] ?? 'N/A' }}</p>

                <h3>Miscellaneous</h3>
                <p><strong>Barcode:</strong> {{ $product['code'] }}</p>
                <p><strong>Quantity:</strong> {{ $product['quantity'] }}</p>
                <p><strong>Expiration Date:</strong> {{ $product['expiration_date'] ?? 'N/A' }}</p>
                <p><strong>Stores:</strong> {{ $product['stores'] ?? 'N/A' }}</p>
                <p><strong>Creator:</strong> {{ $product['creator'] ?? 'N/A' }}</p>
            </div>
        @endisset
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
