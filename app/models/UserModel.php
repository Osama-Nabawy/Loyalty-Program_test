<?php
require_once ROOT_PATH . "/app/config/database.php";

class UserModel
{
    private PDO $conn;
    private string $table;

    public function __construct(string $userType)
    {
        $db = new Database();
        $this->conn = $db->connect();

        // اختيار الجدول بناءً على نوع المستخدم
        $this->table = match ($userType) {
            'customer' => 'customers',
            'merchant' => 'merchants',
            'admin' => 'admins',
            default => throw new Exception("Invalid user type: $userType"),
        };
    }

    // =========================
    // تسجيل مستخدم جديد
    // =========================
    public function register(string $name, string $email, string $password): bool
    {
        try {
            $check = $this->conn->prepare(
                "SELECT 1 FROM {$this->table} WHERE email = :email LIMIT 1"
            );
            $check->execute([":email" => $email]);

            if ($check->fetch()) {
                return false;
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->conn->prepare(
                "INSERT INTO {$this->table} (name, email, password_hash)
                 VALUES (:name, :email, :password_hash)"
            );

            return $stmt->execute([
                ":name" => htmlspecialchars($name),
                ":email" => htmlspecialchars($email),
                ":password_hash" => $hash,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // =========================
    // تسجيل الدخول
    // =========================
    public function login(string $email, string $password)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1"
            );
            $stmt->execute([":email" => $email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                return $user;
            }

            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    // =========================
    // دالة عامة لتحديث أي مستخدم
    // =========================
    private function updateUser(int $id, array $data, string $idField): bool
    {
        $fields = [];
        $params = [":id" => $id];

        foreach ($data as $key => $value) {

            // التعامل مع كلمة المرور
            if ($key === 'password') {
                if ($value === null || $value === '') {
                    continue;
                }

                $fields[] = "password_hash = :password_hash";
                $params[':password_hash'] = password_hash($value, PASSWORD_DEFAULT);
                continue;
            }

            // باقي الحقول
            $fields[] = "$key = :$key";
            $params[":$key"] = htmlspecialchars($value);
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE {$this->table}
                SET " . implode(", ", $fields) . "
                WHERE $idField = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    // =========================
    // تحديث بيانات العملاء
    // =========================
    public function updateCustomer(
        int $customerId,
        string $name,
        string $email,
        ?string $password = null
    ): bool {
        if ($this->table !== 'customers') {
            throw new Exception("updateCustomer can only be used with customers table.");
        }

        return $this->updateUser($customerId, [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ], 'customer_id');
    }

    // =========================
    // تحديث بيانات التجار
    // =========================
    public function updateMerchant(
        int $merchantId,
        string $name,
        string $email,
        ?string $password = null
    ): bool {
        if ($this->table !== 'merchants') {
            throw new Exception("updateMerchant can only be used with merchants table.");
        }

        return $this->updateUser($merchantId, [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ], 'merchant_id');
    }
    public function getCustomerPoints(int $customerId): int
    {
        $stmt = $this->conn->prepare(
            "SELECT points FROM customers WHERE customer_id = ?"
        );
        $stmt->execute([$customerId]);
        $row = $stmt->fetch();

        return $row ? (int) $row['points'] : 0;
    }



    public function addPoints(int $customerId, int $points): bool
    {
        // نتأكد إننا شغالين على customers
        if ($this->table !== 'customers') {
            throw new Exception("addPoints can only be used with customers table.");
        }

        $stmt = $this->conn->prepare(
            "UPDATE customers 
         SET points = points + :points 
         WHERE customer_id = :id"
        );

        return $stmt->execute([
            ":points" => $points,
            ":id" => $customerId
        ]);
    }
}
