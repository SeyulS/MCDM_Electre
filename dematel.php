<?php
$column_names = [
    'Usia',
    'Pengalaman',
    'Gender',
    'Softskill',
    'Format CV',
];

$dataset = [
    [
        "name" => "Kevin",
        "age" => "20 tahun",
        "experience" => "Pernah mengikuti kepanitiaan",
        "gender" => "Laki-laki",
        "softskill" => "Komunikasi",
        "format" => "Waktu pengiriman email"
    ],
    [
        "name" => "Samuel",
        "age" => "17 tahun",
        "experience" => "Tidak pernah bekerja di bidang EO dan kepanitiaan",
        "gender" => "Laki-laki",
        "softskill" => "Problem Solving",
        "format" => "Sopan"
    ],
    [
        "name" => "Jocelyn",
        "age" => "22 tahun",
        "experience" => "Pernah bekerja di bidang EO",
        "gender" => "Perempuan",
        "softskill" => "Komunikasi",
        "format" => "Format email (cover letter)"
    ],
    [
        "name" => "Estavanie",
        "age" => "24 tahun",
        "experience" => "Pernah mengikuti kepanitiaan",
        "gender" => "Perempuan",
        "softskill" => "Leadership",
        "format" => "Format email (cover letter)"
    ]
];

$identify_matrix = [
    [1, 0, 0, 0, 0],
    [0, 1, 0, 0, 0],
    [0, 0, 1, 0, 0],
    [0, 0, 0, 1, 0],
    [0, 0, 0, 0, 1],
];

$nilai_usia = [
    "17 tahun" => 0.4,
    "18 tahun" => 0.5,
    "19 tahun" => 0.6,
    "20 tahun" => 0.5,
    "21 tahun" => 0.4,
    "22 tahun" => 0.3,
    "23 tahun" => 0.2,
    "24 tahun" => 0.1
];

$nilai_pengalaman = [
    "Pernah bekerja di bidang EO" => 0.9,
    "Pernah mengikuti kepanitiaan" => 0.6,
    "Tidak pernah bekerja di bidang EO dan kepanitiaan" => 0.1
];

$nilai_gender = [
    "Laki-laki" => 0.5,
    "Perempuan" => 0.5
];

$nilai_softskill = [
    "Inisiatif" => 0.8,
    "Problem Solving" => 0.6,
    "Komunikasi" => 0.7,
    "Leadership" => 0.5
];

$nilai_format = [
    "Sopan" => 0.8,
    "Format email (cover letter)" => 0.7,
    "Waktu pengiriman email" => 0.4
];

$size = 5;

function calculate_score($data, $nilai_usia, $nilai_pengalaman, $nilai_gender, $nilai_softskill, $nilai_format)
{
    return [
        "name" => $data["name"],
        "Usia" => $nilai_usia[$data["age"]],
        "Pengalaman" => $nilai_pengalaman[$data["experience"]],
        "Gender" => $nilai_gender[$data["gender"]],
        "Softskill" => $nilai_softskill[$data["softskill"]],
        "Format CV" => $nilai_format[$data["format"]]
    ];
}
function find_highest_scores($new_dataset, $keys)
{
    // Inisialisasi variabel
    $highest_scores = [];
    $highest_values = array_fill_keys($keys, null);

    // Iterasi dataset untuk mencari nilai tertinggi
    foreach ($new_dataset as $data) {
        // Bandingkan dengan nilai tertinggi yang ada
        foreach ($keys as $key) {
            if ($highest_values[$key] === null || $data[$key] > $highest_values[$key]) {
                $highest_values[$key] = $data[$key];
                // Reset array untuk kriteria ini dan tambahkan data saat ini
                $highest_scores[$key] = [$data];
            } elseif ($data[$key] == $highest_values[$key]) {
                // Tambahkan data saat ini ke dalam array untuk kriteria ini
                $highest_scores[$key][] = $data;
            }
        }
    }

    // Gabungkan semua nilai tertinggi dari setiap kriteria menjadi satu array
    $final_highest_scores = [];
    foreach ($highest_scores as $key => $scores) {
        foreach ($scores as $score) {
            // Tambahkan informasi kriteria ke setiap data
            $score['criteria'] = $key;
            $final_highest_scores[] = $score;
        }
    }

    return $final_highest_scores;
}

// convert database menjadi angka
$dataset_with_value = [];
foreach ($dataset as $data) {
    $dataset_with_value[] = calculate_score($data, $nilai_usia, $nilai_pengalaman, $nilai_gender, $nilai_softskill, $nilai_format);
}

function invertMatrix($matrix)
{
    $n = count($matrix);
    $identityMatrix = [];

    // Membuat matriks identitas
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            $identityMatrix[$i][$j] = ($i === $j) ? 1 : 0;
        }
    }

    // Membuat matriks gabungan dari matriks awal dan matriks identitas
    for ($i = 0; $i < $n; $i++) {
        $matrix[$i] = array_merge($matrix[$i], $identityMatrix[$i]);
    }

    // Menerapkan eliminasi Gauss-Jordan
    for ($i = 0; $i < $n; $i++) {
        // Pivoting
        $pivot = $matrix[$i][$i];
        if ($pivot == 0) {
            for ($j = $i + 1; $j < $n; $j++) {
                if ($matrix[$j][$i] != 0) {
                    $temp = $matrix[$i];
                    $matrix[$i] = $matrix[$j];
                    $matrix[$j] = $temp;
                    $pivot = $matrix[$i][$i];
                    break;
                }
            }
        }

        if ($pivot == 0) {
            throw new Exception('Matriks tidak memiliki invers.');
        }

        for ($j = 0; $j < $n * 2; $j++) {
            $matrix[$i][$j] /= $pivot;
        }

        for ($j = 0; $j < $n; $j++) {
            if ($i != $j) {
                $factor = $matrix[$j][$i];
                for ($k = 0; $k < $n * 2; $k++) {
                    $matrix[$j][$k] -= $factor * $matrix[$i][$k];
                }
            }
        }
    }

    // Ekstraksi matriks invers dari matriks gabungan
    $inverseMatrix = [];
    for ($i = 0; $i < $n; $i++) {
        $inverseMatrix[$i] = array_slice($matrix[$i], $n);
    }

    return $inverseMatrix;
}

function multiplyMatrices($matrix1, $matrix2)
{
    $rowsMatrix1 = count($matrix1);
    $colsMatrix1 = count($matrix1[0]);
    $colsMatrix2 = count($matrix2[0]);

    $result = array();

    for ($i = 0; $i < $rowsMatrix1; $i++) {
        for ($j = 0; $j < $colsMatrix2; $j++) {
            $result[$i][$j] = 0;
            for ($k = 0; $k < $colsMatrix1; $k++) {
                $result[$i][$j] += $matrix1[$i][$k] * $matrix2[$k][$j];
            }
        }
    }

    return $result;
}

function sumColumns($matrix)
{
    $numRows = count($matrix);
    $numCols = count($matrix[0]);
    $columnSums = array_fill(0, $numCols, 0);

    for ($j = 0; $j < $numCols; $j++) {
        for ($i = 0; $i < $numRows; $i++) {
            $columnSums[$j] += $matrix[$i][$j];
        }
    }

    return $columnSums;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pairwise_matrix = $_POST['matrix'];

    $total_sum = [];
    foreach ($pairwise_matrix as $row) {
        $total = array_sum($row);
        $total_sum[] = $total;
    }

    $angka_terbesar = max($total_sum);

    // Normalisasi Matriks Pairwise
    $normalization_matrix = [];
    foreach ($pairwise_matrix as $row) {
        $normalized_row = [];
        foreach ($row as $element) {
            $normalized_row[] = $element / $angka_terbesar;
        }
        $normalization_matrix[] = $normalized_row;
    }

    $resultMatrix = [];
    for ($i = 0; $i < 5; $i++) {
        for ($j = 0; $j < 5; $j++) {
            $resultMatrix[$i][$j] = $identify_matrix[$i][$j] - $normalization_matrix[$i][$j];
        }
    }

    // Matriks Invers dari Hasil Pengurangan
    $matrix_invers = invertMatrix($resultMatrix);

    // Matrix T
    $matrix_t = multiplyMatrices($normalization_matrix, $matrix_invers);

    // Matriks Ri
    $ri = [];

    foreach ($matrix_t as $row) {
        $total = array_sum($row); // Menghitung total di setiap baris
        $ri[] = $total; // Menyimpan total ke dalam matriks baru
    }

    // Menghitung total kolom dari matriks T
    $ci = sumColumns($matrix_t);


    // Menghitung total Ri + Ci
    $total_ri_ci = [];
    for ($i = 0; $i < count($ri); $i++) {
        $total_ri_ci[] = $ri[$i] + $ci[$i];
    }


    // Menghitung selisih Ri - Ci
    $selisih_ri_ci = [];
    for ($i = 0; $i < count($ri); $i++) {
        $selisih_ri_ci[] = $ri[$i] - $ci[$i];
    }


    // Menghitung rata-rata elemen matriks T
    $totalSum = 0;
    $totalElements = 0;

    foreach ($matrix_t as $row) {
        foreach ($row as $element) {
            $totalSum += $element;
            $totalElements++;
        }
    }

    $average = $totalSum / $totalElements;


    // Find indices of elements larger than average
    // echo "<h3>Indices of Elements Larger Than Average</h3>";
    $larger_indices = [];

    for ($row = 0; $row < count($matrix_t); $row++) {
        for ($col = 0; $col < count($matrix_t[0]); $col++) {
            if ($matrix_t[$row][$col] > $average) {
                $larger_indices[] = [$row, $col];
            }
        }
    }

    // Tampilkan matriks dengan nilai lebih besar dari rata-rata

    $count_usia = 0;
    $count_pengalaman = 0;
    $count_cv = 0;
    $count_gender = 0;
    $count_softskill = 0;
    foreach ($larger_indices as $data) {
        if ($data[0] != $data[1]) {

            if ($data[0] == 0) {
                $data[0] = 'Usia';
            } elseif ($data[0] == 1) {
                $data[0] = 'Pengalaman';
            } elseif ($data[0] == 2) {
                $data[0] = 'Gender';
            } elseif ($data[0] == 3) {
                $data[0] = 'Softskill';
            } elseif ($data[0] == 4) {
                $data[0] = 'Format CV';
            }

            if ($data[1] == 0) {
                $data[1] = 'Usia';
                $count_usia += 1;
            } elseif ($data[1] == 1) {
                $data[1] = 'Pengalaman';
                $count_pengalaman += 1;
            } elseif ($data[1] == 2) {
                $data[1] = 'Gender';
                $count_gender += 1;
            } elseif ($data[1] == 3) {
                $data[1] = 'Softskill';
                $count_softskill += 1;
            } elseif ($data[1] == 4) {
                $data[1] = 'Format CV';
                $count_cv += 1;
            }
        }
    }

    // Menentukan atribut dengan count terbanyak
    $counts = [
        'Usia' => $count_usia,
        'Pengalaman' => $count_pengalaman,
        'Gender' => $count_gender,
        'Softskill' => $count_softskill,
        'Format CV' => $count_cv,
    ];

    // Temukan atribut dengan count tertinggi
    $max_count = max($counts);
    $max_attributes = array_keys($counts, $max_count);

    $highest_score = find_highest_scores($dataset_with_value, $max_attributes);
    $highlight_names = [];
    $highlight_names = array_map(function ($data) {
        return $data['name'];
    }, $highest_score);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrix Calculation</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .matrix-table {
            margin-bottom: 20px;
        }

        /* .card:hover {
            transform: scale(1.05);
            transition: transform 0.3s;
        } */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            /* Jarak antar card */
            margin-top: 1rem;
        }

        .card {
            flex: 1 1 calc(25% - 1rem);
            /* Card responsif dengan lebar 25% minus gap */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Memberikan bayangan pada card */
        }

        .card-body {
            padding: 1rem;
            /* Padding dalam card */
        }

        .card h5 {
            margin-top: 0.5rem;
            /* Margin atas untuk judul card */
        }

        .highlight-card {
            background-color: #d4edda;
            /* Warna latar hijau */
            border-color: #c3e6cb;
            /* Warna border hijau */
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn.btn-outline-ocean {
            color: #fff;
            background-color: #0B6977;
            border: 3px solid #0B6977;
            padding: 8px 16px;
            font-weight: 500;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s, border-color 0.3s;
        }

        .btn.btn-outline-ocean:hover {
            color: #0B6977;
            background-color: #fff;
            border-color: #0B6977;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row mt-3 mb-3">
            <form id="matrix-form" action="" method="post" onsubmit="hideTable(event)">
                <h3>Input Matrix Pairwise</h3>
                <table class="table table-bordered matrix-table" id="inputTable">
                    <thead>
                        <tr>
                            <th></th> <!-- Empty top-left cell -->
                            <?php
                            foreach ($column_names as $name) {
                                echo "<th>$name</th>"; // Menggunakan indeks sebagai judul kolom
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < $size; $i++) {
                            echo "<tr>";
                            // Judul baris
                            echo "<th>" . $column_names[$i] . "</th>";
                            for ($j = 0; $j < $size; $j++) {
                                if ($i == $j) {
                                    echo '<td><input type="text" name="matrix[' . $i . '][' . $j . ']" class="form-control" value="0" readonly></td>';
                                } else {
                                    echo '<td><input type="text" name="matrix[' . $i . '][' . $j . ']" class="form-control" required></td>';
                                }
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <button type="submit" class="btn btn-outline-ocean" id="submitButton">Submit</button>
            </form>
        </div>

        <h2>Candidates</h2>
        <div class="container mt-4 mb-3">
            <div class="row">
                <?php
                if (isset($highlight_names)) {
                    echo '<div class="row">';
                    foreach ($dataset as $data) {
                        $highlight_class = in_array($data["name"], $highlight_names) ? 'highlight-card' : '';
                        echo '<div class="col-md-3 mb-3">';
                        echo '    <div class="card ' . $highlight_class . '">';
                        echo '        <div class="card-body">';
                        echo '            <h5 class="card-title">' . htmlspecialchars($data["name"]) . '</h5>';
                        echo '            <p class="card-text"><strong>Age:</strong> ' . htmlspecialchars($data["age"]) . '</p>';
                        echo '            <p class="card-text"><strong>Experience:</strong> ' . htmlspecialchars($data["experience"]) . '</p>';
                        echo '            <p class="card-text"><strong>Gender:</strong> ' . htmlspecialchars($data["gender"]) . '</p>';
                        echo '            <p class="card-text"><strong>Softskill:</strong> ' . htmlspecialchars($data["softskill"]) . '</p>';
                        echo '            <p class="card-text"><strong>Format:</strong> ' . htmlspecialchars($data["format"]) . '</p>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</div>';
                    }
                    echo '</div>';

                    // tabel matrix pairwise
                    echo "<h3 class='mt-5'>Matriks Pairwise</h3>";
                    echo '<table class="table table-bordered matrix-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th></th>'; // Kolom kosong untuk judul baris
                    foreach ($column_names as $names) {
                        echo "<th>$names</th>"; // Menggunakan indeks sebagai judul kolom
                    }
                    echo '<th>Total</th>'; // Judul kolom untuk total penjumlahan baris
                    echo '</tr>';
                    echo '</thead>';
                    // Isi tabel
                    echo '<tbody>';
                    foreach ($pairwise_matrix as $row_index => $row) {
                        echo '<tr>';
                        // Judul baris
                        echo "<th>{$column_names[$row_index]}</th>";
                        foreach ($row as $element) {
                            echo "<td>$element</td>";
                        }
                        // Kolom total penjumlahan baris
                        echo "<td><strong>{$total_sum[$row_index]}</strong></td>";
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';

                    echo "<h3 class='mt-3'>Hasil Normalisasi Matriks Pairwise</h3>";
                    echo '<table class="table table-bordered matrix-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th></th>'; // Kolom kosong untuk judul baris
                    foreach ($column_names as $names) {
                        echo "<th>$names</th>"; // Menggunakan indeks sebagai judul kolom
                    }
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach ($normalization_matrix as $row_index => $row) {
                        echo '<tr>';
                        echo "<th>{$column_names[$row_index]}</th>";
                        foreach ($row as $element) {
                            echo "<td>$element</td>";
                        }
                        echo '</tr>';
                    }
                    echo '</table>';

                    echo "<h3>Matriks Hasil Pengurangan</h3>";
                    echo '<table class="table table-bordered matrix-table">';
                    foreach ($resultMatrix as $row) {
                        echo '<tr>';
                        foreach ($row as $element) {
                            echo "<td>$element</td>";
                        }
                        echo '</tr>';
                    }
                    echo '</table>';

                    echo "<h3>Matriks Invers Hasil Pengurangan</h3>";
                    echo '<table class="table table-bordered matrix-table">';
                    foreach ($matrix_invers as $row) {
                        echo '<tr>';
                        foreach ($row as $element) {
                            echo "<td>$element</td>";
                        }
                        echo '</tr>';
                    }
                    echo '</table>';

                    echo "<h3>Matrix T</h3>";
                    echo '<table class="table table-bordered matrix-table">';
                    foreach ($matrix_t as $row) {
                        echo '<tr>';
                        foreach ($row as $element) {
                            echo "<td>$element</td>";
                        }
                        echo '</tr>';
                    }
                    echo '</table>';

                    echo "<h3>Ri</h3>";
                    echo '<table class="table table-bordered matrix-table">';
                    echo '<tr><th>Ri</th></tr>';
                    foreach ($ri as $value) {
                        echo "<tr><td>$value</td></tr>";
                    }
                    echo '</table>';

                    echo "<h3>Ci</h3>";
                    echo '<table class="table table-bordered matrix-table">';
                    echo '<tr><th>Ci</th></tr>';
                    foreach ($ci as $value) {
                        echo "<tr><td>$value</td></tr>";
                    }
                    echo '</table>';

                    echo "<h3>Ri + Ci</h3>";
                    echo '<table class="table table-bordered matrix-table">';
                    echo '<tr><th>Ri + Ci</th></tr>';
                    foreach ($total_ri_ci as $value) {
                        echo "<tr><td>$value</td></tr>";
                    }
                    echo '</table>';

                    echo "<h3>Ri - Ci</h3>";
                    echo '<table class="table table-bordered matrix-table">';
                    echo '<tr><th>Ri - Ci</th></tr>';
                    foreach ($selisih_ri_ci as $value) {
                        echo "<tr><td>$value</td></tr>";
                    }
                    echo '</table>';

                    $attributes_to_display = [];
                    echo "<br>";
                    echo "<h3>Most Influence Criteria</h3>";
                    echo '<ul>';
                    foreach ($max_attributes as $attribute) {
                        $attributes_to_display = $attribute;
                        echo "<li>$attribute</li>";
                    }
                    echo '</ul>';

                    echo "<h3 class='mt-3'>Final Matrix T</h3>";
                    echo '<table id="matrixTable" class="table table-bordered matrix-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th></th>'; // Kolom kosong untuk judul baris
                    foreach ($column_names as $names) {
                        echo "<th>$names</th>";
                    }
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    foreach ($matrix_t as $row_index => $row) {
                        echo '<tr>';
                        echo "<th>{$column_names[$row_index]}</th>";
                        foreach ($row as $element) {
                            echo "<td>$element</td>";
                        }
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';

                    echo "<h3 class='mt-3'>Candidate with The Most Influence Criteria's Score</h3>";
                    echo '<div class="card-container mt-3" style="margin-bottom: 10px;">';
                    foreach ($highest_score as $data) {
                        echo '<div class="card">';
                        echo '<div class="card-body">';
                        echo '<p class="card-text"><strong>Highest score in :</strong> ' . htmlspecialchars($data["criteria"]) . '</p>';
                        echo '<h5 class="card-title">' . htmlspecialchars($data["name"]) . '</h5>';
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                    foreach ($dataset as $data) {
                        echo '<div class="col-md-3 mb-3">';
                        echo '    <div class="card">';
                        echo '        <div class="card-body">';
                        echo '            <h5 class="card-title">' . htmlspecialchars($data["name"]) . '</h5>';
                        echo '            <p class="card-text"><strong>Age:</strong> ' . htmlspecialchars($data["age"]) . '</p>';
                        echo '            <p class="card-text"><strong>Experience:</strong> ' . htmlspecialchars($data["experience"]) . '</p>';
                        echo '            <p class="card-text"><strong>Gender:</strong> ' . htmlspecialchars($data["gender"]) . '</p>';
                        echo '            <p class="card-text"><strong>Softskill:</strong> ' . htmlspecialchars($data["softskill"]) . '</p>';
                        echo '            <p class="card-text"><strong>Format:</strong> ' . htmlspecialchars($data["format"]) . '</p>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
<script>
    function hideTable(event) {
        // event.preventDefault(); // Prevent form submission
        document.getElementById('inputTable').style.display = 'none';
        document.getElementById('submitButton').style.display = 'none';
        // Optionally, you can submit the form via AJAX here if needed
    }

    var average = <?php echo $average; ?>;

    // Ambil tabel dari HTML
    var table = document.getElementById("matrixTable");

    // Bandingkan setiap nilai dengan average dan ubah warna kolom jika perlu
    for (var i = 0; i < table.rows.length; i++) {
        for (var j = 0; j < table.rows[i].cells.length; j++) {
            var cellValue = parseFloat(table.rows[i].cells[j].innerText);
            if (cellValue > average) {
                table.rows[i].cells[j].style.backgroundColor = "red";
            }
        }
    }
</script>

</html>