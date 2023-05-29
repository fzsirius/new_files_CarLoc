<html>
<head>
    <title>Graphique du revenu</title>
    <link rel="stylesheet" type="text/css" href="styles/graphe.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- Inclusion de la bibliothèque Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php require_once('nav.php'); ?>
    
    <div class="container">
        <?php
            session_start();
            require_once('include.php'); // Charge le fichier include.php
            $db = new connexionDB();  // Création d'une instance de connexionDB
            $conn = $db->DB();
            
            // Vérifier si le panier existe déjà
            if (isset($_SESSION['panier'])) {
                // Récupérer les informations sur les voitures dans le panier
                $query = "SELECT voiture.Marque, SUM(panier.quantite * voiture.Prix_j) AS total_depense FROM panier INNER JOIN voiture ON panier.ID_voiture = voiture.ID_voiture WHERE panier.ID_voiture IN (".implode(",", array_keys($_SESSION['panier'])).") GROUP BY voiture.Marque";
                $result = $conn->query($query);
                $rows = $result->fetchAll(PDO::FETCH_ASSOC);
                
                // Préparer les données pour le graphique
                $labels = [];
                $data = [];
                foreach ($rows as $row) {
                    $labels[] = $row['Marque'];
                    $data[] = $row['total_depense'];
                }
        ?>

        <!-- Création du canvas pour le graphique -->
        <canvas id="graphe_depenses"></canvas>

        <?php
            // Afficher le graphique
            echo "<script>";
            echo "var graphe_depenses = document.getElementById('graphe_depenses').getContext('2d');";
            echo "var chart = new Chart(graphe_depenses, {";
            echo "    type: 'bar',";
            echo "    data: {";
            echo "        labels: " . json_encode($labels) . ",";
            echo "        datasets: [{";
            echo "            label: 'depense par marque',";
            echo "            data: " . json_encode($data) . ",";
            echo "            backgroundColor: 'lightblue',";
            echo "            borderColor: 'green',";
            echo "            borderWidth: 1";
            echo "        }]";
            echo "    },";
            echo "    options: {";
            echo "        scales: {";
            echo "            yAxes: [{";
            echo "                ticks: {";
            echo "                    beginAtZero: true";
            echo "                }";
            echo "            }]";
            echo "        }";
            echo "    }";
            echo "});";
            echo "</script>";
        ?>

        <?php
            } else {
                // Afficher un message si le panier est vide
                echo "<h2>Votre panier est vide.</h2>";
            }
        ?>
    </div>
</body>
</html>
