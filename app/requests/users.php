<?php

include_once('/app/conf/mysql.php');

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
