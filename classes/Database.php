<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

class Database {
  private $host; // Host
  private $db_name; // DB Name
  private $username; // DB Username
  private $password; // DB Password


  private static $instance = null; // Instanca klase
  public $connection = null; // Konekcija

  private function __construct() {
    if ($_SERVER['SERVER_NAME'] == "localhost") {
      $this->host = "localhost"; // Host
      $this->db_name = "karagaca"; // DB Name
      $this->username = "root"; // DB Username
      $this->password = ""; // DB Password
    } else {
      $this->host = "localhost"; // Host
      $this->db_name = "tatamatars_main"; // DB Name
      $this->username = "tatamatars_admin"; // DB Username
      $this->password = "ilovemath123"; // DB Password
    }
    try {
      $this->connection = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8', $this->username, $this->password);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit();
    }

    // $this->connection = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
  }

  public function getConnection() {
    return $this->connection;
  }

  public static function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new Database();
    }

    return self::$instance;
  }

  public function takenEmail($email) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT email FROM user WHERE email = :email");
      $query->execute(array(
        ':email' => $email,
      ));

      $user = $query->fetch();

      if ($user) {
        return true;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function takenUsername($username) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT username FROM user WHERE username = :username");
      $query->execute(array(
        ':username' => $username,
      ));

      $user = $query->fetch();

      if ($user) {
        return true;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function registerUser($data) {
    try {
      $data = (object) $data;
      $data->country = strtolower($data->country);
      $data->password = md5(md5($data->password)); // Hashovanje sifre sa md5
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO user (email, password, firstname, lastname, phone_number, school_type_id, country) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $result = $query->execute([
        $data->email,
        $data->password,
        $data->firstname,
        $data->lastname,
        $data->phone_number,
        $data->school,
        $data->country,
      ]);

      $data->id = Database::getInstance()->getConnection()->lastInsertId();
      $data->is_admin = false;

      if ($result) { // Ukoliko je query uspesan pravimo sesiju i vracamo true
        $_SESSION['user'] = $data;
        Database::getInstance()->insertLoginDetails($data, $data->id);

        return true;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function warnUser($user_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `user` SET warned = 1 WHERE id = ?");
      $result = $query->execute([$user_id]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function unWarnUser($user_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `user` SET warned = 0 WHERE id = ?");
      $result = $query->execute([$user_id]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function blockUser($user_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `user` SET blocked = 1 WHERE id = ?");
      $result = $query->execute([$user_id]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function isUserBlocked($user_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT id FROM `user` WHERE id = :id AND blocked = 1");
      $query->execute(array(
        ':id' => $user_id,
      ));

      $user = $query->fetch();

      if ($user) {
        return true;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function unBlockUser($user_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `user` SET blocked = 0 WHERE id = ?");
      $result = $query->execute([$user_id]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function loginUser($data) {
    try {
      $data = (object) $data;
      $data->password = md5(md5($data->password));
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE email = :email AND password = :password");
      $query->execute(array(
        ':email' => $data->email,
        ':password' => $data->password,
      ));

      $user = $query->fetch();

      if ($user) {
        $user = (object) $user;
        $_SESSION['user'] = $user;
        Database::getInstance()->insertLoginDetails($data, $user->id);

        return true;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function isUserLoggedIn() {
    if (isset($_SESSION['user'])) {
      return true;
    }

    return false;
  }

  public function logoutUser() {
    unset($_SESSION['user']);
  }

  public function getUserByID($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE id = :id");
      $query->execute(array(
        ':id' => $id,
      ));

      $user = $query->fetch();

      return $user;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function getUserByEmail($email) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM user WHERE email = :email");
      $query->execute(array(
        ':email' => $email,
      ));

      $user = $query->fetch();

      return $user;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function getPasswordForUser($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT password FROM user WHERE id = :id");
      $query->execute(array(
        ':id' => $id,
      ));

      $password = $query->fetch()['password'];

      return $password;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function updateUserPassword($id, $password) {
    try {
      $password = md5(md5($password)); // Hashovanje sifre sa md5
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `user` SET password = :password WHERE id = :id");
      $result = $query->execute([
        ':password' => $password,
        ':id' => $id,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }
  public function updateUserProfile($schoolTypeID, $user_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `user` SET school_type_id = :school_type_id WHERE id = :id");
      $result = $query->execute([
        ':school_type_id' => $schoolTypeID == "NULL" ? NULL : $schoolTypeID,
        ':id' => $user_id,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function getAllUsers() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM user");
      $query->execute();
      $users = $query->fetchAll();

      return $users;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  // COURESES =====================================================================================================================================

  public function addCourse($data) {
    try {
      $data = (object) $data;
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO course (name, description, price, price2, school_type_id, grade_id, img, trailer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $result = $query->execute([
        $data->name,
        $data->description,
        $data->price,
        $data->price2,
        $data->schoolTypeID,
        $data->gradeID,
        $data->img,
        $data->trailer,
      ]);

      $lastInsertedID = Database::getInstance()->getConnection()->lastInsertId();

      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO chapter (name, course_id, number) VALUES ('_global', $lastInsertedID, -1)");
      $result = $query->execute();

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return false;
    }
  }

  public function updateCourse($data, $id) {
    try {
      $data = (object) $data;
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `course` SET price2 = :price2, trailer = :trailer,  name = :name, description = :description, price = :price, school_type_id = :school_type_id, grade_id = :grade_id, img = :img, live = :live WHERE id = :id");
      $result = $query->execute([
        ':name' => $data->name_edit,
        ':description' => $data->description_edit,
        ':price' => $data->price_edit,
        ':id' => $id,
        ':school_type_id' => $data->schoolTypeIDEdit,
        ':grade_id' => $data->gradeIDEdit,
        ':img' => $data->img_edit,
        ':live' => $data->live_edit,
        ':trailer' => $data->trailer_edit,
        ':price2' => $data->price2_edit,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function deleteCourse($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM course WHERE id = :id LIMIT 1");
      $result = $query->execute(array(':id' => $id));

      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM user_course WHERE course_id = :id");
      $result = $query->execute(array(':id' => $id));

      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `clip` WHERE course_id = :id");
      $result = $query->execute(array(':id' => $id));

      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `chapter` WHERE course_id = :id");
      $result = $query->execute(array(':id' => $id));

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function getAllCourses() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM course c LEFT JOIN school_type st ON c.school_type_id = st.school_type_id LEFT JOIN grade g ON g.grade_id = c.grade_id");
      $query->execute();
      $courses = $query->fetchAll();

      return $courses;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  public function getCourse($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM course c JOIN grade g ON g.grade_id = c.grade_id WHERE id = :id");
      $query->execute(array(
        ':id' => $id,
      ));

      $course = $query->fetch();

      return $course;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return null;
    }
  }

  public function buyCourse($data) {
    try {
      $data = (object) $data;
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO user_course (user_id, course_id) VALUES (?, ?)");
      $result = $query->execute([
        $data->user_id,
        $data->course_id,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function getUser_Course($user_id, $course_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM user_course WHERE user_id = :user_id AND course_id = :course_id");
      $query->execute(array(
        ':user_id' => $user_id,
        ':course_id' => $course_id,
      ));

      $course = $query->fetch();

      return $course;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return null;
    }
  }

  public function searchCourses($searchText) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM course c WHERE c.name LIKE '%$searchText%'");
      $query->execute();
      $courses = $query->fetchAll();

      return $courses;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  // USER_COURSE =================================================================================================================================================

  public function getAllUser_Courses() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT *, c.name AS course_name, u.id AS user_id, uc.id AS uc_id, uc.created_at as uc_created_at FROM user_course uc JOIN user u ON u.id = uc.user_id JOIN course c ON c.id = uc.course_id");
      $query->execute();
      $courses = $query->fetchAll();

      return $courses;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  public function getAllCoursesForUser($user_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT *, uc.created_at AS date, c.id AS course_id, uc.id AS uc_id, c.name AS course_name FROM user_course uc JOIN user u ON u.id = uc.user_id JOIN course c ON c.id = uc.course_id WHERE user_id = :user_id ORDER BY confirmed DESC");
      $query->execute(array(
        ':user_id' => $user_id,
      ));
      $courses = $query->fetchAll();

      return $courses;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  public function insertUserCourseConfirmed($data) {
    try {
      $data = (object) $data;
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO user_course (user_id, course_id, confirmed) VALUES (?, ?, ?)");
      $result = $query->execute([
        $data->user_id,
        $data->course_id,
        1
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function userCourseExists($data) {
    try {
      $data = (object) $data;
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM user_course WHERE user_id = ? AND course_id = ?");
      $result = $query->execute([
        $data->user_id,
        $data->course_id,
      ]);

      $userCourse = $query->fetch();

      if ($userCourse) {
        return true;
      }

      return false;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function confirmCoursePurchase($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `user_course` SET confirmed = 1 WHERE id = :id");
      $result = $query->execute(array(
        ':id' => $id,
      ));

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function unconfirmCourse($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `user_course` SET confirmed = 0 WHERE id = :id");
      $result = $query->execute(array(
        ':id' => $id,
      ));

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function deleteCoursePurchase($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `user_course` WHERE id = :id LIMIT 1");
      $result = $query->execute(array(
        ':id' => $id,
      ));

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  // CLIPS ===========================================================================================================================

  public function addClip($data) {
    try {
      $data = (object) $data;
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO `clip` (name, number, description, link, course_id, chapter_id) VALUES (?, ?, ?, ?, ?, ?)");
      $result = $query->execute([
        $data->name,
        $data->number,
        $data->description,
        $data->link,
        $data->course_id,
        $data->chapter_id,
      ]);

      $lastInsertedID = Database::getInstance()->getConnection()->lastInsertId();

      if ($result) {
        $query = Database::getInstance()->getConnection()->prepare("UPDATE `clip` SET number = number + 1 WHERE number >= $data->number AND id != $lastInsertedID AND course_id = $data->course_id AND chapter_id = $data->chapter_id");
        $result = $query->execute();
      }

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function getAllClips() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT *, c.description AS clip_description, c.chapter_id AS current_chapter, c.number AS clip_number, cu.name AS course_name, ch.name AS chapter_name, c.name AS c_name, c.description AS c_description, c.id AS c_id FROM `clip` c JOIN course cu ON cu.id = c.course_id JOIN chapter ch ON ch.id = c.chapter_id ORDER BY cu.name, ch.name DESC, c.number");
      $query->execute();
      $clips = $query->fetchAll();

      return $clips;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  public function getClipNameFromID($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT name FROM `clip` WHERE id = :id");
      $query->execute([':id' => $id]);
      $clip = $query->fetch();

      return $clip['name'];
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return null;
    }
  }

  public function getAllClipsForCourse($course_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT *, c.description AS clip_description, c.chapter_id AS current_chapter, c.number AS clip_number, cu.name AS course_name, ch.name AS chapter_name, c.name AS c_name, c.description AS c_description, c.id AS c_id FROM `clip` c JOIN course cu ON cu.id = c.course_id JOIN chapter ch ON ch.id = c.chapter_id WHERE c.course_id = :course_id ORDER BY cu.name, ch.name DESC, c.number");
      $query->execute(['course_id' => $course_id]);
      $clips = $query->fetchAll();

      return $clips;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  public function getAllClipsForChapterAndCourse($chapter_id, $course_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT *, c.chapter_id AS clip_chapter_id, c.description AS clip_description, c.number AS clip_number, cu.name AS course_name, ch.name AS chapter_name, c.name AS c_name, c.description AS c_description, c.id AS c_id FROM `clip` c JOIN course cu ON cu.id = c.course_id JOIN chapter ch ON ch.id = c.chapter_id WHERE c.chapter_id = $chapter_id AND c.course_id = $course_id ORDER BY cu.name, ch.name DESC, c.number");
      $query->execute();
      $clips = $query->fetchAll();

      return $clips;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  public function updateClip($data, $id) {
    try {
      $data = (object) $data;

      // Uzmem stari chapter i broj koji je bio po redu u starom chapteru
      $query = Database::getInstance()->getConnection()->prepare("SELECT number, chapter_id, course_id FROM `clip` WHERE id = :id");
      $query->execute([
        ':id' => $id,
      ]);

      $row = $query->fetch();
      $oldNumber = $row['number'];
      $oldChapter = $row['chapter_id'];
      $oldCourse = $row['course_id'];

      if ($data->number_edit < 1) {
        return false;
      }

      // Ubacim ga u novi chapter i stavim mu novi broj
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `clip` SET course_id = :course_id, chapter_id = :chapter_id, name = :name, link = :link, description = :description, number = :number WHERE id = :id");
      $result = $query->execute([
        ':course_id' => $data->course_id_edit,
        ':chapter_id' => $data->chapter_id_edit,
        ':name' => $data->name_edit,
        ':link' => $data->link_edit,
        ':description' => $data->description_edit,
        ':number' => $data->number_edit,
        ':id' => $id,
      ]);

      // Sredi novi chapter, tako sto ces sve klipove koji su veci ili jednaki od njega uvecati za jedan, znaci kao drag and drop gde guras listu na dole
      $query = Database::getInstance()->getConnection()->prepare("UPDATE `clip` SET number = number + 1 WHERE number >= $data->number_edit AND id != $id AND course_id = $data->course_id_edit AND chapter_id = $data->chapter_id_edit");
      $result = $query->execute();

      // Sredi stari chapter, tako sto ces sve posled izbacenog smanjiti za 1
      if ($oldChapter != $data->chapter_id_edit) {
        $query = Database::getInstance()->getConnection()->prepare("UPDATE `clip` SET number = number - 1 WHERE number > $oldNumber AND id != $id AND course_id = $oldCourse AND chapter_id = $oldChapter");
        $result = $query->execute();
      }

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . $e->getLine() . $oldChapter . "</p>";

      exit;
    }
  }

  public function deleteClip($id) {
    try {

      $query = Database::getInstance()->getConnection()->prepare("SELECT number, course_id FROM `clip` WHERE id = :id");
      $query->execute([
        ':id' => $id,
      ]);

      $row = $query->fetch();
      $oldNumber = $row['number'];
      $course_id = $row['course_id'];
      $chapter_id = $row['course_id'];

      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `clip` WHERE id = :id LIMIT 1");
      $result = $query->execute(array(':id' => $id));

      $query = Database::getInstance()->getConnection()->prepare("UPDATE `clip` SET number = number -1  WHERE number > $oldNumber AND course_id = $course_id AND chapter_id = $chapter_id");
      $result = $query->execute();

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function searchClips($searchText, $cid) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `clip` c WHERE c.name LIKE '%$searchText%' OR c.description LIKE '%$searchText%' AND c.course_id = $cid");
      $query->execute();
      $clips = $query->fetchAll();

      return $clips;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  // CHAPTERS ===========================================================================================================================

  public function addChapter($data) {
    try {
      $data = (object) $data;
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO `chapter` (name, number, course_id) VALUES (?, ?, ?)");
      $result = $query->execute([
        $data->name,
        $data->number,
        $data->course_id,
      ]);

      $lastInsertedID = Database::getInstance()->getConnection()->lastInsertId();

      if ($result) {
        $query = Database::getInstance()->getConnection()->prepare("UPDATE `chapter` SET number = number + 1 WHERE number >= $data->number AND id != $lastInsertedID AND course_id = $data->course_id");
        $result = $query->execute();
      }

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function getAllChapters() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT *, c.name AS chapter_name, c.number AS chapter_number, c.id AS chapter_id FROM `chapter` c JOIN course cu ON cu.id = c.course_id ORDER BY cu.name, c.number");
      $query->execute();
      $chapters = $query->fetchAll();

      return $chapters;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  public function getChapter($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM chapter c WHERE id = :id");
      $query->execute(array(
        ':id' => $id,
      ));

      $course = $query->fetch();

      return $course;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return null;
    }
  }

  public function getAllChaptersForCourse($course_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT *, c.name AS chapter_name, c.number AS chapter_number, c.id AS chapter_id FROM `chapter` c JOIN course cu ON cu.id = c.course_id WHERE c.course_id = $course_id ORDER BY cu.name, c.number");
      $query->execute();
      $chapters = $query->fetchAll();

      return $chapters;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      return [];
    }
  }

  public function updateChapter($data, $id) {
    try {
      $data = (object) $data;
      $query = Database::getInstance()->getConnection()->prepare("SELECT number FROM `chapter` WHERE id = :id");
      $query->execute([
        ':id' => $id,
      ]);

      $oldNumber = $query->fetch()['number'];

      $query = Database::getInstance()->getConnection()->prepare("UPDATE `chapter` SET course_id = :course_id, name = :name, number = :number WHERE id = :id");
      $result = $query->execute([
        ':course_id' => $data->course_id_edit,
        ':name' => $data->name_edit,
        ':number' => $data->number_edit,
        ':id' => $id,
      ]);

      if ($oldNumber > $data->number_edit) {
        if ($result) {
          $query = Database::getInstance()->getConnection()->prepare("UPDATE `chapter` SET number = number + 1 WHERE number >= $data->number_edit AND number < $oldNumber + 1 AND id != $id AND course_id = $data->course_id_edit");
          $result = $query->execute();
        }
      } else {
        if ($result) {
          $query = Database::getInstance()->getConnection()->prepare("UPDATE `chapter` SET number = number - 1 WHERE number >= $oldNumber AND number < $data->number_edit + 1 AND id != $id AND course_id = $data->course_id_edit");
          $result = $query->execute();
        }
      }



      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  public function deleteChapter($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT number, course_id FROM `chapter` WHERE id = :id");
      $query->execute([
        ':id' => $id,
      ]);

      $row = $query->fetch();
      $oldNumber = $row['number'];
      $course_id = $row['course_id'];

      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `chapter` WHERE id = :id LIMIT 1");
      $result = $query->execute(array(':id' => $id));

      $query = Database::getInstance()->getConnection()->prepare("UPDATE `chapter` SET number = number - 1 WHERE number > $oldNumber AND course_id = $course_id");
      $result = $query->execute();

      // DELETE ALL CLIPS FOR THAT CHAPTER

      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `clip` WHERE chapter_id = :id");
      $result = $query->execute(array(':id' => $id));

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      exit;
    }
  }

  // PASSWORD RESET ================================================================================================================================================

  public function getPasswordReset($selector, $expires) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `password_reset` WHERE selector = :selector AND expires >= :expires");
      $query->execute([
        ":selector" => $selector,
        ":expires" => $expires
      ]);

      $password_reset = $query->fetch();

      return $password_reset;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      die();
    }
  }

  public function deletePasswordReset($email) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM password_reset WHERE email = :email");
      $result = $query->execute([
        ':email' => $email,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
      echo $e->getLine();

      exit;
    }
  }

  public function insertPasswordReset($data) {
    try {
      $data = (object) $data;
      $hashedToken = password_hash($data->token, PASSWORD_DEFAULT);
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO password_reset (email, selector, token, expires) VALUES (?, ?, ?, ?)");
      $result = $query->execute([
        $data->email,
        $data->selector,
        $hashedToken,
        $data->expires,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
      echo $e->getLine();

      exit;
    }
  }

  // LOGIN DETAILS ================================================================================================================================================

  public function getAllLoginDetails() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT *, u.id AS user_id, ld.id AS ld_id FROM `login_details` ld JOIN user u ON u.id = ld.user_id");
      $query->execute();

      $login_details = $query->fetchAll();

      return $login_details;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      die();
    }
  }

  public function getAllLoginDetailsSortedByUsers() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT *, u.id AS user_id, ld.id AS ld_id FROM `login_details` ld JOIN user u ON u.id = ld.user_id ORDER BY u.firstname, u.lastname, ld.date DESC");
      $query->execute();

      $login_details = $query->fetchAll();

      return $login_details;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      die();
    }
  }

  public function getAllLoginDetailsSortedByDate() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT *, u.id AS user_id, ld.id AS ld_id FROM `login_details` ld JOIN user u ON u.id = ld.user_id ORDER BY ld.date DESC");
      $query->execute();

      $login_details = $query->fetchAll();

      return $login_details;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      die();
    }
  }

  // public function deletePasswordReset($email) {
  //   try {
  //     $query = Database::getInstance()->getConnection()->prepare("DELETE FROM password_reset WHERE email = :email");
  //     $result = $query->execute([
  //       ':email' => $email,
  //     ]);

  //     return $result;
  //   } catch (PDOException $e) {
  //     echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
  //     echo $e->getLine();

  //     exit;
  //   }
  // }

  public function insertLoginDetails($data, $user_id) {
    try {
      $data = (object) $data;
      if ($data->cookies_enabled == 'true') {
        $data->cookies_enabled = 1;
      }
      $query = Database::getInstance()->getConnection()->prepare("INSERT INTO login_details (user_id, gpu, ram, cpu_cores, os, screen_resolution, timezone, color_depth, cookies_enabled, fonts) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $result = $query->execute([
        $user_id,
        $data->gpu,
        $data->ram,
        $data->cpu_cores,
        $data->os,
        $data->screen_resolution,
        $data->timezone,
        $data->color_depth,
        $data->cookies_enabled,
        $data->fonts,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
      echo $e->getLine();

      exit;
    }
  }

  public function getLoginInfoForUser($user_id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `login_details` WHERE user_id = ? ORDER BY date DESC");
      $result = $query->execute([
        $user_id,
      ]);

      $login_details = $query->fetchAll();

      return $login_details;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
      echo $e->getLine();

      exit;
    }
  }

  public function deleteLoginInfo($id) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `login_details` WHERE id= ?");
      $result = $query->execute([
        $id,
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
      echo $e->getLine();

      exit;
    }
  }

  public function deleteMultipleLoginInfo($data) {
    try {
      $data = (object) $data;
      $query = Database::getInstance()->getConnection()->prepare("DELETE FROM `login_details` WHERE user_id = ? AND $data->column_name = ?");
      $result = $query->execute([
        $data->user_id,
        $data->column_value
      ]);

      return $result;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";
      echo $e->getLine();

      exit;
    }
  }

  // GRADES ========================================================================================================================================================

  public function getAllGrades() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `grade` ORDER BY grade_school_type_id, grade_id DESC");
      $query->execute();

      $grades = $query->fetchAll();

      return $grades;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      die();
    }
  }

  public function getAllGradesForSchoolType(int $schoolTypeID) {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `grade` WHERE grade_school_type_id = :id");
      $query->execute([
        ":id" => $schoolTypeID
      ]);

      $grades = $query->fetchAll();

      return $grades;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      die();
    }
  }

  // SCHOOL TYPES ========================================================================================================================================================

  public function getAllSchoolTypes() {
    try {
      $query = Database::getInstance()->getConnection()->prepare("SELECT * FROM `school_type`");
      $query->execute();

      $types = $query->fetchAll();

      return $types;
    } catch (PDOException $e) {
      echo "<p class='alert mb-0 alert-danger'>PDO EXCEPTION: " . $e->getMessage() . "</p>";

      die();
    }
  }
}
