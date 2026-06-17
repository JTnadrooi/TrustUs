<?php

declare(strict_types=1);

require_once 'config.php';

class User
{
    public int $id;
    public string $username;
    public string $role;
    public string $password;
    public string $createdAt;

    public function __construct(
        int $id,
        string $username,
        string $role,
        string $password,
        string $createdAt
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->role = $role;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }
}

// DB FUNC
class DB
{
    private static ?PDO $pdo = null;

    public static function getDB(): PDO
    {
        if (self::$pdo === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

            try {
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    public static function insertFile(
        string $token,
        string $originalName,
        string $mimeType,
        int $size,
        string $filePath,
        string $fileHash
    ): int {
        $pdo = self::getDB();

        $stmt = $pdo->prepare("
        INSERT INTO files (token, original_name, mime_type, size, file_path, file_hash)
        VALUES (:token, :original_name, :mime_type, :size, :file_path, :file_hash)
    ");

        $stmt->execute([
            ':token' => $token,
            ':original_name' => $originalName,
            ':mime_type' => $mimeType,
            ':size' => $size,
            ':file_path' => $filePath,
            ':file_hash' => $fileHash,
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function getFileByToken(string $token): ?array
    {
        $pdo = self::getDB();

        $stmt = $pdo->prepare("
            SELECT * FROM files WHERE token = :token
        ");

        $stmt->execute([
            ':token' => $token
        ]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    public static function getUser(string $username): ?User
    {
        $pdo = self::getDB();

        $stmt = $pdo->prepare("
            SELECT id, username, role, password, created_at
            FROM users
            WHERE username = :username
        ");

        $stmt->execute([
            ':username' => $username
        ]);

        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new User(
            (int)$row['id'],
            $row['username'],
            $row['role'],
            $row['password'],
            $row['created_at']
        );
    }
}

// HELPER FUNC

function generateToken(int $length = 32): string
{
    return bin2hex(random_bytes(intdiv($length, 2)));
}

function doLoginCheck(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function getMimeType(string $filePath): string
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filePath);
    finfo_close($finfo);
    return $mime ?: 'application/octet-stream';
}

function formatSize(int $bytes): string
{
    if ($bytes >= 1024 * 1024) {
        return number_format($bytes / (1024 * 1024), 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

function isPreviewable(string $mimeType): bool
{
    $previewable = ['image/', 'video/', 'audio/', 'text/plain'];
    foreach ($previewable as $prefix) {
        if (strpos($mimeType, $prefix) === 0) {
            return true;
        }
    }
    return false;
}

// USER STUFF

/**
 * Check if user is currently logged in via session.
 *
 * @return bool true if user has an active session, false otherwise
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/**
 * Get current logged-in user id.
 *
 * @return int|null user id from session or null if not logged in
 */
function currentUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

// SHA-256 is used for file integrity because it creates a fixed 64-character hash
// and is strong enough to detect accidental or manual file changes.
// This is not used for password hashing, only for checking whether a file changed.
function fileHash(string $filePath): string
{
    $hash = hash_file('sha256', $filePath);

    if ($hash === false) {
        throw new RuntimeException('Could not calculate file hash.');
    }

    return $hash;
}

function fileHashIsValid(string $filePath, ?string $expectedHash): bool
{
    if (!$expectedHash || strlen($expectedHash) !== 64 || !is_file($filePath)) {
        return false;
    }

    return hash_equals(strtolower($expectedHash), strtolower(fileHash($filePath)));
}