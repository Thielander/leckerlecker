<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🍰Open Food Facts Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 20px;
        }
        .welcome-graphic {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }
    </style>
    <script>
        function setBarcode(barcode) {
            document.getElementById('barcode').value = barcode;
            document.getElementById('searchForm').submit();
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="bg-white shadow rounded p-4">
            <h1 class="mb-4">🍰Open Food Facts Dashboard</h1>
           
            <form id="searchForm" action="{{ route('search') }}" method="POST" class="mb-4">
                @csrf
                <div class="input-group mb-3">
                    <p>Beispiele: 
                        <a href="javascript:setBarcode('20203467')">20203467</a> | 
                        <a href="javascript:setBarcode('4008400404127')">4008400404127</a> | 
                        <a href="javascript:setBarcode('4000540003567')">4000540003567</a>
                    </p>
                </div>
                <div class="input-group">
                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode eingeben" required>
                    <button class="btn btn-primary" type="submit">Suchen</button>
                </div>
            </form>
            <div class="welcome-graphic">
                <img src="openfood.png" alt="Welcome Graphic">
            </div>  
        </div>
    </div>
</body>
</html>
