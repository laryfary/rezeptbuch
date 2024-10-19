<?php

$path_up = "";

require_once("engine/helper.php");

$urlparts = isset($_GET["url"]) ? explode("/", $_GET["url"]) : [""];

switch($urlparts[0]) {

    case "dashboard":
        // Lade die Rezepte für den eingeloggten Benutzer
        require_once("engine/rezepte_model.php"); // Modell für Rezepte einbinden
        
        $user_id = $_SESSION["user_id"];  // Benutzer-ID aus der Session
        $recipes = getRecipesByUserId($user_id, $dblink);  // Rezepte laden

        // Daten für das Dashboard (inkl. Rezepte und Benutzerdaten) übergeben
        $data = $_SESSION;
        $data["recipes"] = $recipes;  // Rezepte in die Daten hinzufügen

        // Lade Benutzer für das Dashboard (falls benötigt)
        $sql = "SELECT * FROM myTable";
        $result = $dblink->query($sql);
        $data["users"] = [];
        while($user = mysqli_fetch_assoc($result)) {
            array_push($data["users"], $user);
        }

        $html = get_template("dashboard", $data);  // Dashboard-Template rendern
        break;

    case "rezepte":
        // Anzeige aller Rezepte
        if (isset($urlparts[1]) && $urlparts[1] == "create") {
            $html = get_template("rezepte/create", $_GET);
        } elseif (isset($urlparts[1]) && $urlparts[1] == "update") {
            // Rezept aktualisieren (ID aus der URL)
            $recipe_id = $urlparts[2]; // Hier wird die ID des Rezepts angenommen
            $html = get_template("rezepte/update", ["id" => $recipe_id]);
        } else {
            // Alle Rezepte anzeigen
            $sql = "SELECT * FROM recipes WHERE user_id = ?";
            $stmt = $dblink->prepare($sql);
            $stmt->bind_param("i", $_SESSION["user_id"]);
            $stmt->execute();
            $result = $stmt->get_result();
            $data["recipes"] = $result->fetch_all(MYSQLI_ASSOC); // Alle Rezepte abrufen
            $html = get_template("rezepte/index", $data);
        }

        // Rezept speichern
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($urlparts[1] == "store") {
                // Rezept speichern
                $title = $_POST['title'];
                $description = $_POST['description'];
                $sql = "INSERT INTO recipes (title, description, user_id) VALUES (?, ?, ?)";
                $stmt = $dblink->prepare($sql);
                $stmt->bind_param("ssi", $title, $description, $_SESSION["user_id"]);
                $stmt->execute();
                header("Location: /rezeptbuch/rezepte");
            } elseif (isset($urlparts[1]) && $urlparts[1] == "update") {
                // Rezept aktualisieren
                $recipe_id = $urlparts[2];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $sql = "UPDATE recipes SET title = ?, description = ? WHERE id = ?";
                $stmt = $dblink->prepare($sql);
                $stmt->bind_param("ssi", $title, $description, $recipe_id);
                $stmt->execute();
                header("Location: /rezeptbuch/rezepte");
            }
        }

        // Rezept löschen
        if (isset($urlparts[1]) && $urlparts[1] == "delete") {
            // Rezept löschen
            $recipe_id = $urlparts[2];
            $sql = "DELETE FROM recipes WHERE id = ?";
            $stmt = $dblink->prepare($sql);
            $stmt->bind_param("i", $recipe_id);
            $stmt->execute();
            header("Location: /rezeptbuch/rezepte");
        }
        break;

    default:
        if (isset($_SESSION["name"])) {
            header("Location: /rezeptbuch/dashboard");
        } else {
            $html = get_template("index", $_GET);
        }
        break;
}

print $html;

?>
