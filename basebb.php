<!DOCTYPE html>
<link rel="stylesheet" href="base22.css">
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Requête SQL personnalisée</title>
    <style>
        body {
            text-align: center;
        }
        form {
            display: inline-block;
            text-align: left;
            margin: 0 auto;
        }
        table {
            margin: 20px auto;
        }
    </style>
</head>
<header>
        <h1 class="test1"> Portail Cartographique du Conseil Départemental de Goudomp<br></h1>
    </header>
<body>

<?php
// Vérifier si le formulaire n'a pas été soumis
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo '<h2>Requête SQL personnalisée</h2>';
    echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
    echo '<label for="region">Nom de la région :</label>';
    echo '<input type="text" name="region" id="region"><br>';
    echo '<label for="departement">Nom du département :</label>';
    echo '<input type="text" name="departement" id="departement"><br>';
    echo '<label for="nbr_hommes">Nombre d\'hommes :</label>';
    echo '<select name="op_hommes" id="op_hommes">';
    echo '<option value="=">Égal à</option>';
    echo '<option value=">">Supérieur à</option>';
    echo '<option value="<">Inférieur à</option>';
    echo '<option value=">=">Supérieur ou égal à</option>';
    echo '<option value="<=">Inférieur ou égal à</option>';
    echo '</select>';
    echo '<input type="number" name="nbr_hommes" id="nbr_hommes"><br>';
    echo '<label for="nbr_femmes">Nombre de femmes :</label>';
    echo '<select name="op_femmes" id="op_femmes">';
    echo '<option value="=">Égal à</option>';
    echo '<option value=">">Supérieur à</option>';
    echo '<option value="<">Inférieur à</option>';
    echo '<option value=">=">Supérieur ou égal à</option>';
    echo '<option value="<=">Inférieur ou égal à</option>';
    echo '</select>';
    echo '<input type="number" name="nbr_femmes" id="nbr_femmes"><br>';
    echo '<input type="checkbox" id="afficher_tout" name="afficher_tout" value="1">';
    echo '<label for="afficher_tout">Afficher toutes les informations</label><br>';
    echo '<input type="submit" value="Rechercher">';
    echo '</form>';
}
?>

<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données PostgreSQL
    $connexion = pg_connect("host=localhost port=5432 dbname=Rendu_BB user=postgres password=Sane99");

  // Vérifier si la connexion a réussi
if (!$connexion) {
    echo "Erreur lors de la connexion à la base de données.";
    exit;
}


    // Récupérer les valeurs saisies par l'utilisateur
    $region = $_POST['region'];
    $departement = $_POST['departement'];
    $nbr_hommes = $_POST['nbr_hommes'];
    $op_hommes = $_POST['op_hommes'];
    $nbr_femmes = $_POST['nbr_femmes'];
    $op_femmes = $_POST['op_femmes'];
    $afficher_tout = isset($_POST['afficher_tout']) ? true : false;

    // Construire la requête SQL en fonction des valeurs saisies
    $query = "SELECT * FROM \"POP\".pop2013 WHERE 1=1";

    if ($region !== '') {
        $query .= " AND \"REGION\" = '{$region}'";
    }

    if ($departement !== '') {
        $query .= " AND \"DEPARTEM_1\" = '{$departement}'";
    }

    if ($nbr_hommes !== '') {
        $query .= " AND \"Homme\" {$op_hommes} '{$nbr_hommes}'";
    }

    if ($nbr_femmes !== '') {
        $query .= " AND \"Femme\" {$op_femmes} '{$nbr_femmes}'";
    }

    if ($afficher_tout) {
        $query = "SELECT * FROM \"POP\".pop2013";
    }

    // Exécuter la requête SQL
    $result = pg_query($connexion, $query);

    // Vérifier si la requête a réussi
    if (!$result) {
        echo "Erreur lors de l'exécution de la requête : " . pg_last_error($connexion);
        exit;
    }

    // Affichage des données récupérées dans un tableau HTML
    echo "<h2>Résultats de la recherche :</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Région</th><th>Département</th><th>Hommes</th><th>Femmes</th></tr>";
    while ($row = pg_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['REGION'] . "</td>";
        echo "<td>" . $row['DEPARTEM_1'] . "</td>";
        echo "<td>" . $row['Homme'] . "</td>";
        echo "<td>" . $row['Femme'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Fermer la connexion à la base de données
    pg_close($connexion);
}
?>
</body>
<br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br>
<br><br>
<footer>
        <p> &copy;<span class="blue-text">Conseil Départemental de Goudomp</span><span class="noir-text"> .2024. Réalisé par </span> <span class="blue-text">YatouGeom</span></p>

    </footer>
</html>
