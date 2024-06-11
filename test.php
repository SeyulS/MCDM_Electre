<?php
$cvs = [
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

$weight = [4,3,1,2,5];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV List</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function acceptCandidate(name, cardId) {
            // Menambahkan kandidat ke dalam tabel
            var table = document.getElementById("acceptedCandidates").getElementsByTagName('tbody')[0];
            var row = table.insertRow();
            row.insertCell(0).innerHTML = name;
            row.insertCell(1).innerHTML = '<input type="number" class="form-control" min="0" max="5">';
            row.insertCell(2).innerHTML = '<input type="number" class="form-control" min="0" max="5">';
            row.insertCell(3).innerHTML = '<input type="number" class="form-control" min="0" max="5">';
            row.insertCell(4).innerHTML = '<input type="number" class="form-control" min="0" max="5">';
            row.insertCell(5).innerHTML = '<input type="number" class="form-control" min="0" max="5">';

            // Menghapus kartu setelah di-accept
            var card = document.getElementById(cardId);
            card.parentNode.removeChild(card);
        }

        function submitTable() {
            var table = document.getElementById("acceptedCandidates").getElementsByTagName('tbody')[0];
            var data = [];
            for (var i = 0, row; row = table.rows[i]; i++) {
                var rowData = [];
                for (var j = 1, col; col = row.cells[j]; j++) { // Skip name column
                    var input = col.querySelector('input');
                    if (input) {
                        rowData.push(parseInt(input.value) || 0); // Ensure numbers, default to 0
                    }
                }
                data.push(rowData);
            }

            // Isi nilai input tersembunyi dengan data yang dikumpulkan
            document.getElementById('submittedData').value = JSON.stringify(data);

            // Submit form secara otomatis
            document.getElementById('myForm').submit();
        }
    </script>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Dynamic Cards for CVs -->
            <?php foreach ($cvs as $index => $cv) : ?>
                <div class="col-md-4" id="card-<?= $index ?>">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= $cv['name'] ?></h5>
                            <p class="card-text"><strong>Age:</strong> <?= $cv['age'] ?></p>
                            <p class="card-text"><strong>Experience:</strong> <?= $cv['experience'] ?></p>
                            <p class="card-text"><strong>Gender:</strong> <?= $cv['gender'] ?></p>
                            <p class="card-text"><strong>Soft Skill:</strong> <?= $cv['softskill'] ?></p>
                            <p class="card-text"><strong>Format:</strong> <?= $cv['format'] ?></p>
                            <button class="btn btn-primary" onclick="acceptCandidate('<?= $cv['name'] ?>', 'card-<?= $index ?>')">Accept</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- <h3>Weight the Criteria</h3>
        <form action="test.php" method="POST">
            <table class="table table-bordered" id="weightCriteria">
                <tbody>
                    <tr>
                        <td>Age</td>
                        <td><input type="number" name="weight_age" class="form-control" min="0" max="5"></td>
                    </tr>
                    <tr>
                        <td>Experience</td>
                        <td><input type="number" name="weight_experience" class="form-control" min="0" max="5"></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><input type="number" name="weight_gender" class="form-control" min="0" max="5"></td>
                    </tr>
                    <tr>
                        <td>Soft Skill</td>
                        <td><input type="number" name="weight_softskill" class="form-control" min="0" max="5"></td>
                    </tr>
                    <tr>
                        <td>Format</td>
                        <td><input type="number" name="weight_format" class="form-control" min="0" max="5"></td>
                    </tr>
                </tbody>
            </table>
            <button class="btn btn-success" name="weight">Update</button>
        </form> -->
        <?php
        if(isset($_POST['weight'])){
            array_push($weight,$_POST['weight_age']);
            array_push($weight,$_POST['weight_experience']);
            array_push($weight,$_POST['weight_gender']);
            array_push($weight,$_POST['weight_softskill']);
            array_push($weight,$_POST['weight_format']);

        }
        ?>


        <br>
        <h3>Accepted Candidates</h3>
        <table class="table table-bordered" id="acceptedCandidates">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Experience</th>
                    <th>Gender</th>
                    <th>Soft Skill</th>
                    <th>Format</th>
                </tr>
            </thead>
            <tbody>
                <!-- Accepted candidates will be appended here -->
            </tbody>
        </table>
        <br>
        <!-- Formulir untuk mengirimkan data -->
        <form id="myForm" method="post">
            <input type="hidden" name="submittedData" id="submittedData">
            <button type="button" class="btn btn-success" onclick="submitTable()">Submit</button>
        </form>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submittedData'])) {
            $submittedData = $_POST['submittedData'];
            var_dump($weight);

            $data = json_decode($submittedData);

            // Normalisasi
            $sum_of_squares_per_column = [];
            foreach ($data as $row) {
                foreach ($row as $column => $value) {
                    if (!isset($sum_of_squares_per_column[$column])) {
                        $sum_of_squares_per_column[$column] = 0;
                    }
                    $sum_of_squares_per_column[$column] += $value * $value;
                }
            }

            $sqrt_sum_of_squares_per_column = [];
            foreach ($sum_of_squares_per_column as $sum_of_squares) {
                $sqrt_sum_of_squares_per_column[] = sqrt($sum_of_squares);
            }

            $normalized_array = [];
            foreach ($data as $row) {
                $normalized_row = [];
                foreach ($row as $column => $value) {
                    $normalized_row[] = $value / $sqrt_sum_of_squares_per_column[$column];
                }
                $normalized_array[] = $normalized_row;
            }

            echo "Hasil Normalisasi:\n";
            echo "<br>";
            echo "<br>";

            foreach ($normalized_array as $row) {
                echo implode("\t", $row) . "\n ";
            }

            echo "<br>";
            echo "<br>";

            $weighted_array = [];
            foreach ($normalized_array as $row) {
                $weighted_row = [];
                foreach ($row as $column => $value) {
                    $weighted_row[] = $value * $weight[$column];
                }
                $weighted_array[] = $weighted_row;
            }

            echo "Hasil Perkalian:\n";
            echo "<br>";
            echo "<br>";

            foreach ($weighted_array as $row) {
                echo implode("\t", $row) . "\n ";
            }

            echo "<br>";
            echo "<br>";

            $concordance = [];

            // Loop untuk membandingkan setiap pasangan baris
            $num_rows = count($weighted_array);
            for ($i = 0; $i < $num_rows - 1; $i++) {
                for ($j = $i + 1; $j < $num_rows; $j++) {
                    // Mendapatkan panjang baris
                    $num_columns = count($weighted_array[$i]);

                    // Inisialisasi array untuk menyimpan indeks yang memenuhi kriteria
                    $matching_indices = [];

                    // Membandingkan setiap elemen dalam pasangan baris
                    for ($k = 0; $k < $num_columns; $k++) {
                        if ($weighted_array[$i][$k] >= $weighted_array[$j][$k]) {
                            // Jika elemen pada baris pertama lebih besar atau sama dengan elemen yang sesuai pada baris kedua
                            // Catat indeks elemen tersebut
                            $matching_indices[] = $k;
                        }
                    }

                    // Jika ada elemen yang memenuhi kriteria
                    if (!empty($matching_indices)) {
                        // Menambahkan hasil perbandingan ke dalam array concordance
                        $concordance[] = ["c" . ($i + 1) . ($j + 1), $matching_indices];

                        // Memeriksa hasil perbandingan yang berlawanan
                        $opposite_comparison = "c" . ($j + 1) . ($i + 1);
                        $opposite_matching_indices = [];

                        for ($k = 0; $k < $num_columns; $k++) {
                            if ($weighted_array[$j][$k] >= $weighted_array[$i][$k]) {
                                // Jika elemen pada baris kedua lebih besar atau sama dengan elemen yang sesuai pada baris pertama
                                // Catat indeks elemen tersebut
                                $opposite_matching_indices[] = $k;
                            }
                        }

                        // Jika ada elemen yang memenuhi kriteria untuk perbandingan yang berlawanan
                        if (!empty($opposite_matching_indices)) {
                            // Menambahkan hasil perbandingan yang berlawanan ke dalam array concordance
                            $concordance[] = [$opposite_comparison, $opposite_matching_indices];
                        }
                    }
                }
            }

            // Menampilkan hasil concordance
            echo "Concordance:\n";
            echo "<br>";
            foreach ($concordance as $comparison) {
                echo $comparison[0] . ": " . implode(" ", $comparison[1]) . "<br>";
            }

            echo "<br>";
            // Disordance
            $disordance = [];

            // Membuat array yang berisi semua indeks yang muncul dalam concordance
            $all_indices = [];
            foreach ($concordance as $comparison) {
                $all_indices = array_merge($all_indices, $comparison[1]);
            }
            $all_indices = array_unique($all_indices);

            // Membuat disordance berdasarkan concordance
            foreach ($concordance as $comparison) {
                $missing_indices = array_diff($all_indices, $comparison[1]);
                $disordance["d" . substr($comparison[0], 1)] = array_values($missing_indices);
            }

            // Menampilkan hasil disordance
            echo "Disordance:\n";
            echo "<br>";
            foreach ($disordance as $key => $indices) {
                echo $key . ": " . implode(" ", $indices) . "<br>";
            }

            echo "<br>";
            // Weighted Concordance
            $weighted_concordance = [];
            foreach ($concordance as $comparison) {
                $weighted_indices = $comparison[1];
                $weighted_sum = array_sum(array_intersect_key($weight, array_flip($weighted_indices)));
                $weighted_concordance[$comparison[0]] = $weighted_sum;
            }

            // Menampilkan hasil bobot untuk setiap elemen di concordance
            echo "Weighted Concordance:\n";
            echo "<br>";

            foreach ($weighted_concordance as $key => $value) {
                echo $key . ": " . $value . "<br>";
            }

            $num_rows = 0;
            $num_columns = 0;
            foreach ($weighted_concordance as $key => $value) {
                $row_index = intval(substr($key, 1, 1)) - 1;
                $column_index = intval(substr($key, 2, 1)) - 1;
                $matrix[$row_index][$column_index] = $value;
                $num_rows = max($num_rows, $row_index + 1);
                $num_columns = max($num_columns, $column_index + 1);
            }

            // Mengisi matriks dengan nilai 0 jika tidak ada hubungan yang ditentukan
            for ($i = 0; $i < $num_rows; $i++) {
                for ($j = 0; $j < $num_columns; $j++) {
                    if (!isset($matrix[$i][$j])) {
                        $matrix[$i][$j] = 0;
                    }
                }
            }

            // Menampilkan matriks
            echo "<br>";
            echo "Matrix Concordance:<br>";
            $count = 0;
            for ($i = 0; $i < $num_rows; $i++) {
                for ($j = 0; $j < $num_columns; $j++) {
                    echo $matrix[$i][$j] . "\t";
                }
                echo "<br>";
                $count++;
                if ($count % 4 == 0) {
                    echo "<br>";
                }
            }

            // Weighted Disordance

            // Menghitung nilai weighted disordance
            $weighted_disordance = [];
            foreach ($disordance as $key => $indices) {
                if (!empty($indices)) {
                    $max_diff = 0;

                    // Mengambil indeks baris pertama dan kedua dari kunci disordance
                    $i = intval(substr($key, 1, 1)) - 1; // Baris pertama
                    $j = intval(substr($key, 2, 1)) - 1; // Baris kedua

                    // Menghitung perbedaan absolut antara elemen-elemen yang relevan dan mencari nilai maksimumnya
                    foreach ($indices as $index) {
                        // Mengambil indeks kolom dari disordance
                        $k = $index;

                        // Menghitung perbedaan absolut antara elemen dari baris pertama dan kedua
                        $diff = abs($weighted_array[$i][$k] - $weighted_array[$j][$k]);

                        // Memperbarui nilai maksimum jika diperlukan
                        if ($diff > $max_diff) {
                            $max_diff = $diff;
                        }
                    }

                    // Menambahkan nilai weighted disordance ke dalam array
                    $weighted_disordance[$key] = $max_diff;
                } else {
                    // Jika tidak ada indeks yang ditemukan, nilai weighted disordance adalah 0
                    $weighted_disordance[$key] = 0;
                }
            }

            // Menampilkan hasil weighted disordance
            echo "<br>";
            echo "Weighted Disordance:<br>";
            foreach ($weighted_disordance as $key => $value) {
                echo $key . ": " . $value . "<br>";
            }

            $num_rows = 0;
            $num_columns = 0;
            foreach ($weighted_disordance as $key => $value) {
                $row_index = intval(substr($key, 1, 1)) - 1;
                $column_index = intval(substr($key, 2, 1)) - 1;
                $matrix[$row_index][$column_index] = $value;
                $num_rows = max($num_rows, $row_index + 1);
                $num_columns = max($num_columns, $column_index + 1);
            }

            // Mengisi matriks dengan nilai 0 jika tidak ada hubungan yang ditentukan
            for ($i = 0; $i < $num_rows; $i++) {
                for ($j = 0; $j < $num_columns; $j++) {
                    if (!isset($matrix[$i][$j])) {
                        $matrix[$i][$j] = 0;
                    }
                }
            }

            // Menampilkan matriks
            echo "<br>";
            echo "Matrix Disordance:<br>";
            $count = 0;
            for ($i = 0; $i < $num_rows; $i++) {
                for ($j = 0; $j < $num_columns; $j++) {
                    echo $matrix[$i][$j] . "\t";
                }
                echo "<br>";
                $count++;
                if ($count % 4 == 0) {
                    echo "<br>";
                }
            }

            // Menghitung total dari setiap baris matriks concordance
            $total = 0;
            $total2 = 0;
            //Concordance Dominan
            foreach ($weighted_concordance as $value) {
                $total += $value;
            }
            // Disordance Dominan
            foreach ($weighted_disordance as $value) {
                $total2 += $value;
            }

            $thresholdCon = $total / (sizeof($data) * (sizeof($data) - 1));
            $thresholdDis = $total2 / (sizeof($data) * (sizeof($data) - 1));

            echo "Total1: " . $total;
            echo "<br>";
            echo "Total2: " . $total2;
            echo "<br>";
            echo "Threshold " . $thresholdCon;
            echo "Threshold " . $thresholdDis;

            // Misalkan $thresholdCon adalah nilai ambang batas yang telah ditentukan

            foreach ($weighted_concordance as &$value) {
                if ($value >= $thresholdCon) {
                    $value = 1;
                } else {
                    $value = 0;
                }
            }


            foreach ($weighted_disordance as &$value) {
                if ($value >= $thresholdDis) {
                    $value = 1;
                } else {
                    $value = 0;
                }
            }
            // Jika Anda ingin menampilkan nilai array yang sudah diubah
            echo "Weighted Concordance:\n";
            echo "<br>";

            foreach ($weighted_concordance as $key => $value) {
                echo $key . ": " . $value . "<br>";
            }

            echo "Weighted Concordance:\n";
            echo "<br>";

            foreach ($weighted_disordance as $key => $value) {
                echo $key . ": " . $value . "<br>";
            }

            $aggregate_matrix = [];

            foreach ($weighted_concordance as $key_conc => $value_conc) {
                foreach ($weighted_disordance as $key_dis => $value_dis) {
                    // Mengekstrak nomor indeks dari kunci
                    $index_conc = substr($key_conc, 1);
                    $index_dis = substr($key_dis, 1);

                    // Jika nomor indeks sama, maka lakukan perkalian
                    if ($index_conc === $index_dis) {
                        $result_key = "c" . $index_conc . "d" . $index_dis;
                        $result_value = $value_conc * $value_dis;
                        $aggregate_matrix[$result_key] = $result_value;
                    }
                }
            }

            // Menampilkan hasil perkalian dalam array aggregate_matrix
            echo "Hasil Perkalian dalam Aggregate Matrix:<br>";
            foreach ($aggregate_matrix as $key => $value) {
                echo $key . ": " . $value . "<br>";
            }

            $num_rows = 0;
            $num_columns = 0;
            foreach ($aggregate_matrix as $key => $value) {
                $row_index = intval(substr($key, 1, 1)) - 1;
                $column_index = intval(substr($key, 2, 1)) - 1;
                $matrix[$row_index][$column_index] = $value;
                $num_rows = max($num_rows, $row_index + 1);
                $num_columns = max($num_columns, $column_index + 1);
            }

            // Mengisi matriks dengan nilai 0 jika tidak ada hubungan yang ditentukan
            for ($i = 0; $i < $num_rows; $i++) {
                for ($j = 0; $j < $num_columns; $j++) {
                    if (!isset($matrix[$i][$j])) {
                        $matrix[$i][$j] = 0;
                    }
                }
            }

            // Menampilkan matriks
            echo "<br>";
            echo "Matrix Disordance:<br>";
            $count = 0;
            for ($i = 0; $i < $num_rows; $i++) {
                for ($j = 0; $j < $num_columns; $j++) {
                    echo $matrix[$i][$j] . "\t";
                }
                echo "<br>";
                $count++;
                if ($count % 4 == 0) {
                    echo "<br>";
                }
            }
            $max_row_index = -1;
            $max_count = 0;

            // Menghitung jumlah kemunculan 1 di setiap baris matriks
            foreach ($matrix as $row_index => $row) {
                $count = array_sum($row);

                // Memperbarui baris dengan jumlah kemunculan 1 terbanyak jika ditemukan
                if ($count > $max_count) {
                    $max_count = $count;
                    $max_row_index = $row_index;
                }
            }

            // Menampilkan hasil
            // echo "Baris dengan Kemunculan 1 Terbanyak dalam Matriks: " . ($max_row_index + 1);
            echo "<br>";
            echo "Result: " . ($max_row_index + 1);
        }
        ?>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>