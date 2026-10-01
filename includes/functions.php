<?php
require_once 'db.php';

class ComplaintSystem
{
    private $db;

    public function __construct()
    {
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }


    public function getUserById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }


    public function createComplaint($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO complaints (title, description, category, priority, student_id) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssi",
            $data['title'],
            $data['description'],
            $data['category'],
            $data['priority'],
            $_SESSION['user_id']
        );
        return $stmt->execute();
    }

    public function getComplaints($filters = [])
    {
        $sql = "SELECT c.*, u.full_name as student_name, t.full_name as teacher_name 
                FROM complaints c
                LEFT JOIN users u ON c.student_id = u.id
                LEFT JOIN users t ON c.assigned_teacher_id = t.id";

        $whereClauses = [];
        $params = [];
        $types = "";


        if ($_SESSION['user_role'] === 'student') {
            $whereClauses[] = "c.student_id = ?";
            $params[] = $_SESSION['user_id'];
            $types .= "i";
        } elseif ($_SESSION['user_role'] === 'teacher') {
            $whereClauses[] = "c.assigned_teacher_id = ?";
            $params[] = $_SESSION['user_id'];
            $types .= "i";
        }


        if (!empty($filters['status'])) {
            $whereClauses[] = "c.status = ?";
            $params[] = $filters['status'];
            $types .= "s";
        }
        if (!empty($filters['category'])) {
            $whereClauses[] = "c.category = ?";
            $params[] = $filters['category'];
            $types .= "s";
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $sql .= " ORDER BY c.created_at DESC";

        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getComplaintById($id)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.full_name as student_name, u.student_id, u.department,
                   t.full_name as teacher_name, t.email as teacher_email
            FROM complaints c
            LEFT JOIN users u ON c.student_id = u.id
            LEFT JOIN users t ON c.assigned_teacher_id = t.id
            WHERE c.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateComplaintStatus($id, $status, $teacherId = null)
    {
        $sql = "UPDATE complaints SET status = ?, updated_at = NOW()";
        $params = [$status];
        $types = "s";

        if ($teacherId && $status === 'reviewed') {
            $sql .= ", assigned_teacher_id = ?";
            $params[] = $teacherId;
            $types .= "i";
        }

        if ($status === 'resolved') {
            $sql .= ", resolved_at = NOW()";
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;
        $types .= "i";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

    public function addUpdate($complaintId, $updateText)
    {
        $stmt = $this->db->prepare("
            INSERT INTO complaint_updates (complaint_id, user_id, update_text) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iis", $complaintId, $_SESSION['user_id'], $updateText);
        return $stmt->execute();
    }

    public function getUpdates($complaintId)
    {
        $stmt = $this->db->prepare("
            SELECT cu.*, u.full_name, u.role 
            FROM complaint_updates cu
            JOIN users u ON cu.user_id = u.id
            WHERE cu.complaint_id = ?
            ORDER BY cu.created_at DESC
        ");
        $stmt->bind_param("i", $complaintId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    public function getStats()
    {
        $role = $_SESSION['user_role'];
        $userId = $_SESSION['user_id'];

        $stats = [];


        $sql = "SELECT COUNT(*) as total FROM complaints";
        if ($role === 'student') {
            $sql .= " WHERE student_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
        } elseif ($role === 'teacher') {
            $sql .= " WHERE assigned_teacher_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
        } else {
            $stmt = $this->db->prepare($sql);
        }
        $stmt->execute();
        $stats['total'] = $stmt->get_result()->fetch_assoc()['total'];


        $sql = "SELECT COUNT(*) as pending FROM complaints WHERE status IN ('submitted', 'reviewed')";
        if ($role === 'student') {
            $sql .= " AND student_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
        } elseif ($role === 'teacher') {
            $sql .= " AND assigned_teacher_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $userId);
        } else {
            $stmt = $this->db->prepare($sql);
        }
        $stmt->execute();
        $stats['pending'] = $stmt->get_result()->fetch_assoc()['pending'];


        if ($role === 'admin') {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as resolved_today 
                FROM complaints 
                WHERE DATE(resolved_at) = CURDATE()
            ");
            $stmt->execute();
            $stats['resolved_today'] = $stmt->get_result()->fetch_assoc()['resolved_today'];
        }

        return $stats;
    }


    public function getAllUsers()
    {
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function createUser($data)
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("
            INSERT INTO users (username, password, email, role, full_name, student_id, department) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssssss",
            $data['username'],
            $hashedPassword,
            $data['email'],
            $data['role'],
            $data['full_name'],
            $data['student_id'],
            $data['department']
        );
        return $stmt->execute();
    }
}
