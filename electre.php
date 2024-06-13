<?php
session_start();
function get_image_data($file)
{
    // Membaca file gambar
    $data = file_get_contents($file);

    // Meng-encode data gambar ke dalam format base64
    return base64_encode($data);
}

$cvs = [
    [
        "image" => get_image_data("assets/sadino.jpg"),
        "name" => "Kevin",
        "age" => "20 tahun",
        "experience" => "Pernah mengikuti kepanitiaan",
        "gender" => "Laki-laki",
        "softskill" => "Komunikasi",
        "format" => "Waktu pengiriman email"
    ],
    [
        "image" => get_image_data("assets/sam.jpg"),
        "name" => "Samuel",
        "age" => "17 tahun",
        "experience" => "Tidak pernah bekerja",
        "gender" => "Laki-laki",
        "softskill" => "Problem Solving",
        "format" => "Sopan"
    ],
    [
        "image" => get_image_data("assets/yoli.jpg"),
        "name" => "Jocelyn",
        "age" => "22 tahun",
        "experience" => "Pernah bekerja di bidang EO",
        "gender" => "Perempuan",
        "softskill" => "Komunikasi",
        "format" => "Format email (cover letter)"
    ],
    [
        "image" => get_image_data("assets/audrey.jpg"),
        "name" => "Estavanie",
        "age" => "24 tahun",
        "experience" => "Pernah mengikuti kepanitiaan",
        "gender" => "Perempuan",
        "softskill" => "Leadership",
        "format" => "Format email (cover letter)"
    ]
];

$weight = isset($_SESSION['weight']) ? $_SESSION['weight'] : [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['weight_age'])) {
    $weight = [
        $_POST['weight_age'],
        $_POST['weight_experience'],
        $_POST['weight_gender'],
        $_POST['weight_softskill'],
        $_POST['weight_format']
    ];
    $_SESSION['weight'] = $weight;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV List</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
            event.preventDefault();
            var table = document.getElementById("acceptedCandidates").getElementsByTagName('tbody')[0];
            var data = [];
            var names = [];
            for (var i = 0, row; row = table.rows[i]; i++) {
                var rowData = [];
                names.push(row.cells[0].innerText); // Collect candidate names
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
            document.getElementById('submittedNames').value = JSON.stringify(names); // Send names as well

            // Submit form secara otomatis
            document.getElementById('myForm').submit();
        }
    </script>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12" style="text-align: center;">
                <h2>List of Candidates</h2> <!-- Judul "List of Candidates" -->
            </div>
            <!-- Dynamic Cards for CVs -->
            <?php foreach ($cvs as $index => $cv) : ?>
                <div class="col-md-6" id="card-<?= $index ?>">
                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img class="img-fluid rounded-start" src="data:image/jpeg;base64,<?php echo $cv['image']; ?>" />
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $cv['name'] ?></h5>
                                    <p class="card-text"><strong>Age:</strong> <?= $cv['age'] ?></p>
                                    <p class="card-text"><strong>Experience:</strong> <?= $cv['experience'] ?></p>
                                    <p class="card-text"><strong>Gender:</strong> <?= $cv['gender'] ?></p>
                                    <p class="card-text"><strong>Soft Skill:</strong> <?= $cv['softskill'] ?></p>
                                    <p class="card-text"><strong>Format:</strong> <?= $cv['format'] ?></p>
                                    <button class="btn btn-success" onclick="acceptCandidate('<?= $cv['name'] ?>', 'card-<?= $index ?>')">Accept</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>
        <h3 style="text-align: center;">Weight the Criteria</h3>
        <br>
        <button class="btn btn-secondary d-block mx-auto" data-toggle="modal" data-target="#weightModal">Click to Weight</button>
        <br>
        <hr>
        <!-- Modal -->
        <div class="modal fade" id="weightModal" tabindex="-1" role="dialog" aria-labelledby="weightModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="weightModalLabel">Weight the Criteria</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['weight'])) {
            $_SESSION['weight'] = [
                $_POST['weight_age'],
                $_POST['weight_experience'],
                $_POST['weight_gender'],
                $_POST['weight_softskill'],
                $_POST['weight_format']
            ];
        }
        ?>
        <h3 style="text-align: center;">Accepted Candidates</h3>
        <br>
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
        <form id="myForm" method="post" class="text-center">
            <input type="hidden" name="submittedData" id="submittedData">
            <input type="hidden" name="submittedNames" id="submittedNames"> <!-- Hidden input for names -->
            <div class="mt-3">
                <button type="button" class="btn btn-secondary" onclick="submitTable()">Submit</button>
            </div>
        </form>

        <hr>


        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submittedData'])) {

            $weight = $_SESSION['weight'];
            var_dump($weight);

            echo "<h2>Calculation: </h2><br>";
            $submittedData = $_POST['submittedData'];
            $submittedCandidateNames = json_decode($_POST['submittedNames']);

            // var_dump($submittedCandidateNames);  
            // var_dump($weight);

            $data = json_decode($submittedData);

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


            echo "<h3>Hasil Normalisasi</h3>\n";
            echo "<style>";  // Add inline styling
            echo "table {
                    border-collapse: collapse;
                    width: 100%;  /* Adjust width as needed */
                    margin: 0 auto;  /* Center the table */
                }
                th, td {
                    border: 1px solid black;
                    padding: 5px;  /* Add some padding for better readability */
                }
                </style>";
            echo "<table>";
            echo "<tr>";
            // Extract column names from the first row (assuming consistent structure)
            $column_names = array_keys($normalized_array[0]);
            foreach ($column_names as $column) {
                echo "<th>$column</th>";
            }

            echo "</tr>";

            // Loop through normalized array and display rows
            foreach ($normalized_array as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }
            echo "</table>";

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


            echo "<h3>Hasil Perkalian</h3>\n";
            echo "<style>";  // Add inline styling
            echo "table {
                    border-collapse: collapse;
                    width: 100%;  /* Adjust width as needed */
                    margin: 0 auto;  /* Center the table */
                }
                th, td {
                    border: 1px solid black;
                    padding: 5px;  /* Add some padding for better readability */
                }
                </style>";
            echo "<table>";
            echo "<tr>";

            // Extract column names from weight keys
            foreach (array_keys($weight) as $column) {
                echo "<th>$column</th>";
            }

            echo "</tr>";

            // Loop through weighted array and display rows
            foreach ($weighted_array as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }

            echo "</table>";

            echo "<br>";
            echo "<br>";

            // Create a copy of $weighted_array to avoid modifying the original
            $incremented_array = $weighted_array;

            // Increment numeric values by 1
            foreach ($incremented_array as &$row) {
                foreach ($row as &$value) {
                    if (is_numeric($value)) {
                        $value += 1;
                    }
                }
            }

            $concordance = [];

            // Loop to compare each pair of rows
            $num_rows = count($weighted_array);
            for ($i = 0; $i < $num_rows - 1; $i++) {
                for ($j = $i + 1; $j < $num_rows; $j++) {
                    // Get the number of columns
                    $num_columns = count($weighted_array[$i]);

                    // Initialize array to store matching indices
                    $matching_indices = [];

                    // Compare each element in the pair of rows
                    for ($k = 0; $k < $num_columns; $k++) {
                        if ($weighted_array[$i][$k] >= $weighted_array[$j][$k]) {
                            // If element in row 1 is greater or equal to the corresponding element in row 2
                            // Record the index of that element
                            $matching_indices[] = $k;
                        }
                    }

                    // If there are matching indices
                    if (!empty($matching_indices)) {
                        // Add the comparison result to the concordance array
                        $concordance[] = ["c" . ($i + 1) . ($j + 1), $matching_indices];

                        // Check the opposite comparison
                        $opposite_comparison = "c" . ($j + 1) . ($i + 1);
                        $opposite_matching_indices = [];

                        for ($k = 0; $k < $num_columns; $k++) {
                            if ($weighted_array[$j][$k] >= $weighted_array[$i][$k]) {
                                // If element in row 2 is greater or equal to the corresponding element in row 1
                                // Record the index of that element
                                $opposite_matching_indices[] = $k;
                            }
                        }

                        // If there are matching indices for the opposite comparison
                        if (!empty($opposite_matching_indices)) {
                            // Add the opposite comparison result to the concordance array
                            $concordance[] = [$opposite_comparison, $opposite_matching_indices];
                        }
                    }
                }
            }

            // Modify concordance indices to start from 1
            foreach ($concordance as &$comparison) {
                foreach ($comparison[1] as &$index) {
                    $index++; // Increment each index by 1
                }
            }

            // Display the concordance table
            echo "<h3>Concordance</h3>\n";
            echo "<table>";
            echo "<tr>";
            echo "<th>Keys</th>";
            echo "<th>Concordance</th>";
            echo "</tr>";

            foreach ($concordance as $comparison) {
                echo "<tr>";
                echo "<td>" . $comparison[0] . "</td>";
                echo "<td>" . implode(" ", $comparison[1]) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
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
            echo "<br>";

            echo "<h3>Disordance</h3>\n";
            echo "<table>";
            echo "<tr>";
            echo "<th>Keys</th>";
            echo "<th>Missing Indices</th>";
            echo "</tr>";

            foreach ($disordance as $key => $indices) {
                echo "<tr>";
                echo "<td>" . $key . "</td>";
                echo "<td>" . implode(" ", $indices) . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            echo "<br>";

            // Weighted Concordance
            $weighted_concordance = [];
            foreach ($concordance as $comparison) {
                $weighted_indices = $comparison[1];
                $weighted_sum = array_sum(array_intersect_key($weight, array_flip($weighted_indices)));
                $weighted_concordance[$comparison[0]] = $weighted_sum;
            }


            echo "<h3>Weighted Concordance</h3>\n";
            echo "<table>";
            echo "<tr>";
            echo "<th>Key</th>";
            echo "<th>Value</th>";
            echo "</tr>";

            foreach ($weighted_concordance as $key => $value) {
                echo "<tr>";
                echo "<td>$key</td>";
                echo "<td>$value</td>";
                echo "</tr>";
            }

            echo "</table>";

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

            // Assuming your matrix is pre-defined as $matrix
            echo "<caption><h3>Matrix Concordance</h3></caption>";
            echo "<table border='1'>";  // Tambahkan border agar tabel terlihat

            for ($i = 0; $i < $num_rows; $i++) {
                echo "<tr>";
                for ($j = 0; $j < $num_columns; $j++) {
                    echo "<td>" . $matrix[$i][$j] . "</td>";
                }
                echo "</tr>";
            }

            echo "</table>";

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
                        // echo $index;
                        $k = $index;

                        // Menghitung perbedaan absolut antara elemen dari baris pertama dan kedua
                        $diff = abs($weighted_array[$i][$k - 1] - $weighted_array[$j][$k - 1]);

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

            echo "<br>";
            echo "<table>";
            echo "<h3>Weighted Disordance</h3>";  // Table caption

            echo "<tr>";  // Table header row
            echo "<th>Key</th>";
            echo "<th>Value</th>";
            echo "</tr>";

            foreach ($weighted_disordance as $key => $value) {
                echo "<tr>";
                echo "<td>" . $key . "</td>";
                echo "<td>" . $value . "</td>";
                echo "</tr>";
            }
            echo "</table>";

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

            // Assuming your matrix is pre-defined as $matrix
            echo "<br>";
            echo "<caption><h3>Matrix Disordance</h3></caption>";
            echo "<table border='1'>";  // Tambahkan border agar tabel terlihat

            for ($i = 0; $i < $num_rows; $i++) {
                echo "<tr>";
                for ($j = 0; $j < $num_columns; $j++) {
                    echo "<td>" . $matrix[$i][$j] . "</td>";
                }
                echo "</tr>";
            }

            echo "</table>";

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

            echo "<br>";
            echo "<caption><h3>Total Matriks and Threshold</h3></caption>";  // Table caption
            echo "<table border='1'>";  // Tambahkan border agar tabel terlihat
            echo "<tr><td><b>Total Matriks Concordance:</b></td><td>$total</td></tr>";
            echo "<tr><td><b>Total Matriks Disordance:</b></td><td>$total2</td></tr>";
            echo "<tr><td><b>Threshold Concordance:</b></td><td>$thresholdCon</td></tr>";
            echo "<tr><td><b>Threshold Disordance:</b></td><td>$thresholdDis</td></tr>";

            echo "</table>";

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
            echo "<br>";

            echo "<br>";
            echo "<h3>Dominan Concordance</h3>";

            echo "<table>";
            echo "<tr>
                    <th>Term</th>
                    <th>Weight</th>
                </tr>";

            foreach ($weighted_concordance as $key => $value) {
                echo "<tr>
                        <td>$key</td>
                        <td>$value</td>
                    </tr>";
            }

            echo "</table>";

            $num_rows = 0;
            $num_columns = 0;
            foreach ($weighted_concordance as $key => $value) {
                $row_index = intval(substr($key, 1, 1)) - 1;
                $column_index = intval(substr($key, 2, 1)) - 1;
                $matrix[$row_index][$column_index] = $value;
                $num_rows = max($num_rows, $row_index + 1);
                $num_columns = max($num_columns, $column_index + 1);
            }

            echo "<br>";
            echo "<caption><h3>Matrix Dominan Concordance</h3></caption>";
            echo "<table border='1'>";  // Tambahkan border agar tabel terlihat

            for ($i = 0; $i < $num_rows; $i++) {
                echo "<tr>";
                for ($j = 0; $j < $num_columns; $j++) {
                    echo "<td>" . $matrix[$i][$j] . "</td>";
                }
                echo "</tr>";
            }

            echo "</table>";
            echo "<br>";


            echo "<br>";
            echo "<h3>Dominan Disordance</h3>";

            echo "<table>";
            echo "<tr>
                    <th>Term</th>
                    <th>Weight</th>
                </tr>";

            foreach ($weighted_disordance as $key => $value) {
                echo "<tr>
                        <td>$key</td>
                        <td>$value</td>
                    </tr>";
            }

            echo "</table>";

            $num_rows = 0;
            $num_columns = 0;
            foreach ($weighted_disordance as $key => $value) {
                $row_index = intval(substr($key, 1, 1)) - 1;
                $column_index = intval(substr($key, 2, 1)) - 1;
                $matrix[$row_index][$column_index] = $value;
                $num_rows = max($num_rows, $row_index + 1);
                $num_columns = max($num_columns, $column_index + 1);
            }

            echo "<br>";
            echo "<caption><h3>Matrix Dominan Concordance</h3></caption>";
            echo "<table border='1'>";

            for ($i = 0; $i < $num_rows; $i++) {
                echo "<tr>";
                for ($j = 0; $j < $num_columns; $j++) {
                    echo "<td>" . $matrix[$i][$j] . "</td>";
                }
                echo "</tr>";
            }

            echo "</table>";

            echo "<br>";



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

            echo "<br>";
            echo "<h3>Aggregate Dominance</h3>";

            echo "<table>";
            echo "<tr>
                    <th>Term</th>
                    <th>Weight</th>
                </tr>";

            foreach ($aggregate_matrix as $key => $value) {
                echo "<tr>
                        <td>$key</td>
                        <td>$value</td>
                    </tr>";
            }

            echo "</table>";

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


            echo "<br>";
            echo "<h3>Matrix Aggregate</h3>";
            echo "<table>";

            for ($i = 0; $i < $num_rows; $i++) {
                echo "<tr>";
                for ($j = 0; $j < $num_columns; $j++) {
                    echo "<td>" . $matrix[$i][$j] . "</td>";
                }
                echo "</tr>";
            }

            echo "</table>";

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
            echo "<h1>Chosen Candidate: $submittedCandidateNames[$max_row_index] </h1>";
        }
        ?>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<style>
    .img-fluid.rounded-start {
        display: flex;
        /* Menggunakan flexbox */
        justify-content: flex-start;
        /* Posisi tengah kiri */
        align-items: center;
        /* Posisi tengah secara vertikal */
        padding: 20px;
        /* Padding di pinggir */
        overflow: hidden;
        /* Memastikan gambar tidak melebihi batas container */
    }

    .img-container img {
        width: auto;
        /* Lebar gambar otomatis berdasarkan tinggi */
        height: 150%;
        /* Tinggi gambar diperbesar */
        object-fit: cover;
        /* Crop gambar agar sesuai dengan container */
    }

    hr {
        border: none;
        height: 1px;
        /* Ubah tinggi garis sesuai keinginan */
        background-color: #333;
        /* Ubah warna garis sesuai keinginan */
    }
</style>

</html>