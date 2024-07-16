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
        .not-found {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 300px;
        }
    </style>
    <script>
        function setBarcode(barcode) {
            document.getElementById('barcode').value = barcode;
            document.getElementById('searchForm').submit();
        }
        function getProgressBarClass(value) {
            if (value >= 60) {
                return 'bg-danger';
            } else if (value >= 40) {
                return 'bg-warning';
            } else {
                return 'bg-success';
            }
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
                        <a href="javascript:setBarcode('5449000134264')">5449000134264</a> | 
                        <a href="javascript:setBarcode('4000540003567')">4000540003567</a>
                    </p>
                </div>
                <div class="input-group">
                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode eingeben" required>
                    <button class="btn btn-primary" type="submit">Suchen</button>
                </div>
            </form>
            @isset($productData)
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                         @empty(!$productData['image_front_url'])
                            <img src="{{ $productData['image_front_url'] }}" class="card-img-top" alt="Produktbild">
                            @endempty
                        <div class="card-body">
                            <h5 class="card-title">{{ $productData['product_name'] }}</h5>
                            <p class="card-text">Marken: {{ $productData['brands'] }}</p>
                            <p class="card-text">Kategorien: {{ $productData['categories'] }}</p>
                            <p class="card-text">Länder: {{ $productData['countries'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <h2>Nährwerte</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nährwert</th>
                                <th>Menge pro 100g</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productData['nutriments'] as $key => $value)
                                @if(strpos($key, '_100g') !== false)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_100g', '', $key)) }}</td>
                                        <td>{{ $value }} {{ $productData['nutriments'][str_replace('_100g', '_unit', $key)] ?? '' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <h2>Tagesbedarf</h2>
                    <div>
                        <p>Kalorien: {{ number_format($percentages['energy_kcal'], 2) }}% ( Ausgehend von 2000 kcal / Tag )</p>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentages['energy_kcal'] }}%;" aria-valuenow="{{ $percentages['energy_kcal'] }}" aria-valuemin="0" aria-valuemax="100" :class="getProgressBarClass({{ $percentages['energy_kcal'] }})">{{ number_format($percentages['energy_kcal'], 2) }}%</div>
                        </div>
                        <p>Zucker: {{ number_format($percentages['sugars'], 2) }}% ( Ausgehend von 50g / Tag )</p>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentages['sugars'] }}%;" aria-valuenow="{{ $percentages['sugars'] }}" aria-valuemin="0" aria-valuemax="100" :class="getProgressBarClass({{ $percentages['sugars'] }})">{{ number_format($percentages['sugars'], 2) }}%</div>
                        </div>
                        <p>Salz: {{ number_format($percentages['salt'], 2) }}% ( Ausgehend von 6g / Tag )</p>
                        <div class="progress mb-3">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percentages['salt'] }}%;" aria-valuenow="{{ $percentages['salt'] }}" aria-valuemin="0" aria-valuemax="100" :class="getProgressBarClass({{ $percentages['salt'] }})">{{ number_format($percentages['salt'], 2) }}%</div>
                        </div>
                    </div>
                    <h2>Gesundheitsbewertung</h2>
                    <div id="healthAssessment">
                        <p>Lade Gesundheitsbewertung...</p>
                    </div>
                </div>
            </div>
            @endisset
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars.forEach(bar => {
                const value = parseFloat(bar.getAttribute('aria-valuenow'));
                if (value >= 60) {
                    bar.classList.add('bg-danger');
                } else if (value >= 40) {
                    bar.classList.add('bg-warning');
                } else {
                    bar.classList.add('bg-success');
                }
            });

            @isset($productData)
                fetchHealthAssessment();
            @endisset
        });

        function fetchHealthAssessment() {
            const productData = @json($productData);
            fetch('{{ route("getHealthAssessment") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ productData })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('healthAssessment').innerText = data.healthAssessment;
            })
            .catch(error => {
                console.error('Error fetching health assessment:', error);
                document.getElementById('healthAssessment').innerText = 'Fehler beim Abrufen der Gesundheitsbewertung';
            });
        }
    </script>
</body>
</html>
