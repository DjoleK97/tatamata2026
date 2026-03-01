<?php require_once './header_admin.php'; ?>

<?php

$courses = Database::getInstance()->getAllUser_Courses();
$totalMoneyEarned = 0;

foreach ($courses as $course) {
  if ($course['confirmed']) {
    $totalMoneyEarned += $course['price'];
  }
}

$totalCourses = Database::getInstance()->getAllCourses();
$user_courses = Database::getInstance()->getAllUser_Courses();
$totalCoursesSold = 0;
$totalCoursesPending = 0;

foreach ($user_courses as $user_course) {
  if ($user_course['confirmed']) {
    $totalCoursesSold++;
  } else {
    $totalCoursesPending++;
  }
}

?>

<div class="app-wrapper">

  <div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">

      <h1 class="app-page-title">Overview</h1>

      <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Total Courses</h4>
              <div class="stats-figure"><?php echo sizeof($totalCourses); ?></div>
            </div>
            <!--//app-card-body-->
          </div>
          <!--//app-card-->
        </div>
        <!--//col-->
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Total Chapters</h4>
              <div class="stats-figure"><?php echo sizeof(Database::getInstance()->getAllChapters()); ?></div>
            </div>
          </div>
          <!--//app-card-->
        </div>
        <!--//col-->
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Total Clips</h4>
              <div class="stats-figure"><?php echo sizeof(Database::getInstance()->getAllClips()); ?></div>
            </div>
          </div>
          <!--//app-card-->
        </div>
        <!--//col-->
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Total Users</h4>
              <div class="stats-figure"><?php echo sizeof(Database::getInstance()->getAllUsers()); ?></div>
            </div>
          </div>
          <!--//app-card-->
        </div>
        <!--//col-->
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Total Courses Pending</h4>
              <div class="stats-figure"><?php echo $totalCoursesPending; ?></div>
            </div>
          </div>
          <!--//app-card-->
        </div>
        <!--//col-->
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Total Courses Sold</h4>
              <div class="stats-figure"><?php echo $totalCoursesSold; ?></div>
            </div>
          </div>
          <!--//app-card-->
        </div>
        <!--//col-->
        <div class="col-6 col-lg-3">
          <div class="app-card app-card-stat shadow-sm h-100">
            <div class="app-card-body p-3 p-lg-4">
              <h4 class="stats-type mb-1">Total Earnings</h4>
              <div class="stats-figure"><?php echo $totalMoneyEarned; ?> RSD</div>
            </div>
          </div>
          <!--//app-card-->
        </div>
        <!--//col-->
      </div>
    </div>
    <!--//container-fluid-->
  </div>
  <!--//app-content-->

</div>
<!--//app-wrapper-->


<?php require_once './footer_admin.php'; ?>