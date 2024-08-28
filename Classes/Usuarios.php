<?php
require_once __DIR__ . '/../Data/conexao.php'; 

class Usuarios {
    private $id;
    private $nome;
    private $matricula;
    private $email;
    private $senha;
    private $user_type;

    public function __construct($id, $nome, $matricula, $email, $senha, $user_type) {
        $this->id = $id;
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->email = $email;
        $this->senha = $senha;
        $this->user_type = $user_type;
    }

    // Métodos setters
    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setMatricula($matricula) {
        $this->matricula = $matricula;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setUserType($user_type) {
        $this->user_type = $user_type;
    }

    // Métodos getters
    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getUserType() {
        return $this->user_type;
    }

    public static function getUsers($search = '') {
        $conn = getConnection();

        if ($search) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE name LIKE ?");
            $searchTerm = "%{$search}%";
            $stmt->bind_param("s", $searchTerm);
        } else {
            $stmt = $conn->prepare("SELECT * FROM users");
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $user = new Usuarios($row['id'], $row['name'], $row['matricula'], $row['email'], $row['password'], $row['user_type']);
            $users[] = $user;
        }

        $stmt->close();
        $conn->close();

        return $users;
    }

    public static function existsByEmail($email) {
        $conn = getConnection();
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        $exists = $stmt->num_rows > 0;

        $stmt->close();
        $conn->close();

        return $exists;
    }

    public static function authenticate($email, $password) {
        $conn = getConnection();
        $stmt = $conn->prepare('SELECT id, name, email, password, user_type FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($id, $nome, $email, $senha, $user_type);

        if ($stmt->fetch()) {
            if ($password === $senha) {
                return new self($id, $nome, null, $email, $senha, $user_type);
            }
        }

        $stmt->close();
        $conn->close();
        return null;
    }

    public function save() {
        $conn = getConnection();

        if ($this->id) {
            $stmt = $conn->prepare('UPDATE users SET name = ?, matricula = ?, email = ?, password = ?, user_type = ? WHERE id = ?');
            $stmt->bind_param('sssssi', $this->nome, $this->matricula, $this->email, $this->senha, $this->user_type, $this->id);
        } else {
            $stmt = $conn->prepare('INSERT INTO users (name, matricula, email, password, user_type) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('sssss', $this->nome, $this->matricula, $this->email, $this->senha, $this->user_type);
        }

        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public static function getAll() {
        $conn = getConnection();
        $query = 'SELECT id, name, matricula, email, password, user_type FROM users';
        $result = $conn->query($query);
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = new self($row['id'], $row['name'], $row['matricula'], $row['email'], $row['password'], $row['user_type']);
        }

        $conn->close();
        return $users;
    }

    public static function getById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare('SELECT id, name, matricula, email, password, user_type FROM users WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->bind_result($id, $nome, $matricula, $email, $senha, $user_type);

        if ($stmt->fetch()) {
            $stmt->close();
            $conn->close();
            return new self($id, $nome, $matricula, $email, $senha, $user_type);
        }

        $stmt->close();
        $conn->close();
        return null;
    }

    public static function deleteById($id) {
        $conn = getConnection();
        $stmt = $conn->prepare('DELETE FROM users WHERE id = ?');
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            echo "Usuário excluído com sucesso.";
        } else {
            echo "Erro ao excluir usuário: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
