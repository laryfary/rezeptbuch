<?php
// rezepte_model.php

// Funktion, um alle Rezepte eines Benutzers auszulesen
function getRecipesByUserId($user_id, $dblink) {
    $sql = "SELECT * FROM recipes WHERE user_id = ?";
    $stmt = $dblink->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Funktion, um ein neues Rezept anzulegen
function createRecipe($title, $description, $user_id, $dblink) {
    $sql = "INSERT INTO recipes (title, description, user_id) VALUES (?, ?, ?)";
    $stmt = $dblink->prepare($sql);
    $stmt->bind_param("ssi", $title, $description, $user_id);
    return $stmt->execute();
}

// Funktion, um ein Rezept zu aktualisieren
function updateRecipe($id, $title, $description, $user_id, $dblink) {
    $sql = "UPDATE recipes SET title = ?, description = ? WHERE id = ? AND user_id = ?";
    $stmt = $dblink->prepare($sql);
    $stmt->bind_param("ssii", $title, $description, $id, $user_id);
    return $stmt->execute();
}

// Funktion, um ein Rezept zu löschen
function deleteRecipe($id, $user_id, $dblink) {
    $sql = "DELETE FROM recipes WHERE id = ? AND user_id = ?";
    $stmt = $dblink->prepare($sql);
    $stmt->bind_param("ii", $id, $user_id);
    return $stmt->execute();
}
?>
