<?php

namespace Src;

require_once __DIR__ . '/Database.php';

use PDO;
use Src\Database;

class ProfileLogic
{
    private PDO $pdo;

    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    /**
     * Obtém os dados do usuário a partir do ID.
     *
     * @param int $userId
     * @return array|null
     */
    public function getUserProfileData(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, nome, email, criado_em FROM usuarios WHERE id = :id");
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Inicial do nome
            $user['initial'] = strtoupper(substr($user['nome'], 0, 1));
            // Compatibilidade com HTML
            $user['name'] = $user['nome'];
            unset($user['nome']);
        }

        return $user ?: null;
    }

    /**
     * Atualiza os dados do perfil do usuário no banco.
     *
     * @param int $userId
     * @param array $data ['name' => '...', 'email' => '...']
     * @return bool
     */
    public function updateProfile(int $userId, array $data): bool
    {
        $fields = [];
        $params = [':id' => $userId];

        if (isset($data['name'])) {
            $fields[] = 'nome = :name';
            $params[':name'] = $data['name'];
        }

        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params[':email'] = $data['email'];
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE usuarios SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}

?>