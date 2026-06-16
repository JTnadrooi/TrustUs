<?php

declare(strict_types=1);

require_once 'config.php';

// DB FUNC

class DB
{
    private static ?mysqli $conn = null;

    private static function getDB(): mysqli
    {
        if (self::$conn === null) {
            self::$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if (self::$conn->connect_error) {
                die('Database connection failed: ' . self::$conn->connect_error);
            }
            self::$conn->set_charset('utf8mb4');
        }
        return self::$conn;
    }

    public static function insertFile(string $token, string $originalName, string $mimeType, int $size, string $filePath): int
    {
        $conn = self::getDB();
        $stmt = $conn->prepare("INSERT INTO files (token, original_name, mime_type, size, file_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssis', $token, $originalName, $mimeType, $size, $filePath);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public static function getFileByToken(string $token): ?array
    {
        $conn = self::getDB();
        $stmt = $conn->prepare("SELECT * FROM files WHERE token = ?");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row;
    }
}

// HELPER FUNC

function generateToken(int $length = 32): string
{
    return bin2hex(random_bytes($length / 2));
}

function getMimeType(string $filePath): string
{
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filePath);
    finfo_close($finfo);
    return $mime;
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
