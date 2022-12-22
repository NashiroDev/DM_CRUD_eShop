<?php

include_once('/app/conf/mysql.php');

/**
 * Cherche un email dans la db, renvoi false si rien ne correspond sinon un array des info du user
 *
 * @param string $email
 * @return array|boolean
 */
function checkEmailExistance(string $email): array|bool
{
    global $db;

    $query = "SELECT * FROM users WHERE email = :email";
    $sqlStatement=$db->prepare($query);
    $sqlStatement->execute(['email' => $email]);

    return $sqlStatement->fetch();
}

/**
 * Insert a user from a form into the database
 *
 * @param string $nom
 * @param string $prenom
 * @param string $email
 * @param string $toHashPassword
 * @return boolean
 */
function insertUser(string $nom, string $prenom, string $email, string $toHashPassword): bool
{
    global $db;

    try {
        $query = 'INSERT INTO users (nom, prenom, email, password, roles) VALUE (:nom, :prenom, :email, :password, :roles)';
        $sqlStatement=$db->prepare($query);
        $sqlStatement->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => password_hash($toHashPassword, PASSWORD_ARGON2I),
            'roles' => json_encode(['CLASSIC_USER']),
        ]);
    } catch (PDOException $e) {
        return false;
    }

    return true;
}

/**
 * Récupère tout les utilisateurs de la table users
 *
 * @return array
 */
function getAllUsers(): array
{
    global $db;

    $query = 'SELECT * FROM users';
    $sqlStatement=$db->prepare($query);
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}

/**
 * Supprime un user en db avec son id
 *
 * @param integer $id
 * @return boolean
 */
function deleteUser(int $id): bool
{
    global $db;

    try {
        $query = 'DELETE FROM users WHERE id = :id';
        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute(['id' => $id]);

    } catch (PDOException $e) {
        return false;
    }
    return true;
}

/**
 * Récupère les données d'un utilisateur grace à l'id
 *
 * @param integer|null $id
 * @return array|boolean
 */
function getUserById(?int $id=0): array|bool
{
    global $db;

    try {
        $query = 'SELECT * FROM users WHERE id = :id';
        $sqlStatement=$db->prepare($query);
        $sqlStatement->execute(['id' => $id]);

    } catch (PDOException $e) {
        return false;
    }

    return $sqlStatement->fetch();
}


/**
 * Met a jour les données d'un utilisateur en db avec son id
 *
 * @return boolean
 */
function updateUser(string $nom, string $prenom, string $email, int $id, array $roles=["CLASSIC_USER"]) : bool
{
    global $db;

    try {
        $query = 'UPDATE users SET nom = :nom, prenom = :prenom, email = :email, roles = :roles WHERE id = :id';
        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'id' => $id,
            'roles' => json_encode($roles),
        ]);

    } catch (PDOException $e) {
        return false;
    }
    return true;
}