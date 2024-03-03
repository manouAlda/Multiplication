<?php
session_start();

$tab_data = array();
$lignes_actuelles = array();
$lignes_mod = array();
$modification = array();
$tmp_a = 0;
$tmp_b = 0;
$tmp_x = 0;
$ligne_modifier = 0;

//getdata
$a = isset($_GET["a"]) ? $_GET["a"] : 0;
$b = isset($_GET["b"]) ? $_GET["b"] : 0;
$ligne_suppr = isset($_GET["ligne"]) ? $_GET["ligne"] : null;
$ligne_modifier = isset($_GET['modifier']) ? $_GET['modifier'] : null;
$mod = isset($_GET['modifier_ligne']) ? $_GET['modifier_ligne'] : null;
$tmp_a = isset($_GET["cur_a"]) ? $_GET["cur_a"] : 0;
$tmp_b = isset($_GET["cur_b"]) ? $_GET["cur_b"] : 0;
$tmp_x = isset($_GET["cur_x"]) ? $_GET["cur_x"] : 0;
$mod_a = isset($_GET["mod_a"]) ? $_GET["mod_a"] : $a;
$mod_b = isset($_GET["mod_b"]) ? $_GET["mod_b"] : 0;
$mod_x = isset($_GET["mod_x"]) ? $_GET["mod_x"] : 0;

// Verifier si a ou b a change pour vider la session
if ($_SESSION['a_val'] != $a || $_SESSION['b_val'] != $b) {
    $_SESSION['ligne_suppr_val'] = array();
    $_SESSION['ligne_modifier_val'] = array();
    $_SESSION['modification'] = array();

}

$_SESSION['a_val'] = $a;
$_SESSION['b_val'] = $b;

// Obtenir les lignes a supprimer ou modifier
$lignes_actuelles = $_SESSION['ligne_suppr_val'];
$lignes_mod = $_SESSION['ligne_modifier_val'];
$modification = $_SESSION['modification'];

if (!is_null($mod)) {
    
        $modification[] = array(
            'mod_a' => $mod_a,
            'mod_b' => $mod_b,
            'mod_x' => $mod_x
        );
        $_SESSION['modification'] = $modification;

        $lignes_mod[] = $mod;
        $_SESSION['ligne_modifier_val'] = $lignes_mod;
    
}

// Obtenir le tableau de multiplication
for ($i = 0; $i <= $b; $i++) {
    if (in_array($i, $lignes_mod)) {
        $mod_index = array_search($i, $lignes_mod);
        $mod_row = $modification[$mod_index];

        $tab_data[$i] = array(
            'a' => $mod_row['mod_a'],
            'b' => $mod_row['mod_b'],
            'x' => $mod_row['mod_x']
        );
    } else {
        $tab_data[$i] = array(
            'a' => $a,
            'b' => $i,
            'x' => $i * $a
        );
    }
}


// Ajouter la ligne suppr a la session de lignes a supprimer
if (!is_null($ligne_suppr)) {
    if (!in_array($ligne_suppr, $lignes_actuelles)) {
        $lignes_actuelles[] = $ligne_suppr;
        $_SESSION['ligne_suppr_val'] = $lignes_actuelles;
    }
}

// Supprimer les lignes du tableau
foreach ($lignes_actuelles as $index) {
    unset($tab_data[$index]);
}

echo "<html>
        <head>
            <title>Table de multiplication</title>
            <link rel=\"shortcut icon\" href=\"Img/design.svg\">
            <link rel=\"stylesheet\" href=\"multiplication_style.css\">
        </head>
        <body>";

if ($ligne_modifier == null) {
    echo "<div>
                <table>
                    <caption>Table de Multiplication</caption>
                    <thead>
                        <tr> 
                            <th> a </th>
                            <th> b </th>
                            <th> a * b </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>";

    foreach ($tab_data as $i => $row) {
        if (!empty($row['a']) ) {
            $tmp_a = $row['a'];
            $tmp_b = $row['b'];
            $tmp_x = $row['x'];
            echo "<tr>
                            <td> {$row['a']} </td>
                            <td> {$row['b']} </td>   
                            <td> {$row['x']} </td>
                            <td>
                                <a href=\"http://www.manou.mg/multiplication.php?a=$a&b=$b&modifier=$i&cur_a=$tmp_a&cur_b=$tmp_b&cur_x=$tmp_x\" > <button> Modifier </button> </a>  
                                <a href=\"http://www.manou.mg/multiplication.php?a=$a&b=$b&ligne=$i\" > Supprimer </a> 
                            </td>
                        </tr>";
        }
    }

    echo "</tbody>
                </table>
            </div>";
} else{
    echo "<div>
            <h1><center>Modification</center></h1>
            <form action=\"multiplication.php\" method=\"get\">
                <input type=\"hidden\" name=\"a\" value=\"$a\">
                <input type=\"hidden\" name=\"b\" value=\"$b\">
                <label style=\"color: #8597a3;\">Enter a </label>
                <input type=\"number\" name=\"mod_a\" value=\"$tmp_a\" required>
                <label style=\"color: #8597a3;\">Enter b </label>
                <input type=\"number\" name=\"mod_b\" value=\"$tmp_b\" required>
                <label style=\"color: #8597a3;\">Enter a * b </label>
                <input type=\"number\" name=\"mod_x\" value=\"$tmp_x\" required>
                <button type=\"submit\" name=\"modifier_ligne\" value=\"$ligne_modifier\">Submit</button>
            </form> 
        </div>";
}

echo "</body>
</html>";
?>
