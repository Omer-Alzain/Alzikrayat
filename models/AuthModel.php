<?php
require_once __DIR__ . '/../core/Model.php';
class AuthModel extends Model
{
    public function getUserById(int $id):array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function createUser(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO Users (first_name, last_name, email, password_hash, location, description, occupation)
             VALUES (:firstName, :lastName, :email, :passwordHash, :location, :description, :occupation)"
        );
        $stmt->execute([
            ':firstName' => $data['first_name'],
            ':lastName' => $data['last_name'],
            ':email' => $data['email'],
            ':passwordHash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':location' => $data['location'],
            ':description' => $data['description'],
            ':occupation' => $data['occupation']
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function getUserByEmail($email):array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM Users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM Users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        // fetch() returns false when no row matches.
        return $stmt->fetch() !== false;
    }

}