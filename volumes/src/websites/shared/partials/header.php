<?php

include_once '../../shared/lib/RBACSupport.php';

enum Websites
{
  case WEBSITE_GRADES;
  case WEBSITE_SHAREPOINT;
  case WEBSITE_MARKETING;
  case WEBSITE_ADMIN;
}

function showheader(Websites $forWebsite, string $route, RBACSupport $rbac): string
{
  $navigationGrades     = [
    ['route' => 'my-grades.php', 'permission' => Permission_Grades_Read_Own_Grades, 'title' => 'Cijfers'],
    ['route' => 'my-data.php', 'permission' => Permission_Grades_Show_Self, 'title' => 'Mijn gegevens'],
    ['route' => 'new-list.php', 'permission' => Permission_Grades_Create_Gradelists, 'title' => 'Nieuwe cijferlijst'],
    ['route' => 'view-student.php', 'permission' => Permission_Grades_Read_StudentDetails, 'title' => 'Bekijk student'],
    ['route' => 'approve-list.php', 'permission' => Permission_Grades_Approve_Gradeslist, 'title' => 'Lijsten goedkeuren'],
  ];
  $navigationSharePoint = [
    ['route' => 'my-data.php', 'permission' => Permission_SharePoint_All_Users, 'title' => 'Mijn gegevens'],
    ['route' => 'http://grades.docker/intranet', 'permission' => Permission_Grades_BasicAccess, 'title' => 'Cijfers'],
    ['route' => 'http://marketing.docker/intranet', 'permission' => Permission_Marketing_Read_Campaign, 'title' => 'Marketing'],
    ['route' => 'http://admin.docker/intranet', 'permission' => Permission_Admin_Panel, 'title' => 'Admin Panel'],
  ];

  $navigationAdmin     = [
    ['route' => 'logging.php', 'permission' => Permission_Admin_Panel, 'title' => 'Apache Logfiles'],
    ['route' => 'attestation.php', 'permission' => Permission_Admin_Panel, 'title' => 'Attestation'],
  ];
  $navigationMarketing = [
    ['route' => 'new-campaign.php', 'permission' => Permission_Marketing_Create_Campaign, 'title' => 'Nieuwe campagne'],
    ['route' => 'read-campaign.php', 'permission' => Permission_Marketing_Read_Campaign, 'title' => 'Bekijk campagne'],
    ['route' => 'approve-campaign.php', 'permission' => Permission_Marketing_Approve_Campaign, 'title' => 'Campagne goedkeuren'],
    ['route' => 'delete-campaign.php', 'permission' => Permission_Marketing_Delete_Campaign, 'title' => 'Verwijder campagne'],
  ];

  $navHTML = '';

  $useNavigationTable = [];
  $sitename           = '';

  switch ($forWebsite) {
    case Websites::WEBSITE_ADMIN:
      $useNavigationTable = $navigationAdmin;
      $sitename           = 'Admin Panel';
      break;
    case Websites::WEBSITE_SHAREPOINT:
      $useNavigationTable = $navigationSharePoint;
      $sitename           = 'Sharepoint | Intranet';
      break;
    case Websites::WEBSITE_GRADES:
      $useNavigationTable = $navigationGrades;
      $sitename           = 'Cijferadministratie';
      break;
    case Websites::WEBSITE_MARKETING:
      $useNavigationTable = $navigationMarketing;
      $sitename           = 'Marketing';
      break;
  }

  $hasPermissions = false;

  foreach ($useNavigationTable as $nav) {
    if ($rbac->has($nav['permission'])) {
      $hasPermissions = true;
      $isActiveRoute  = $nav['route'] == $route;

      $html = '<a href="' . $nav['route'] . '" ';
      $html .= $isActiveRoute ? 'class="active" ' : '';
      $html .= '>';
      $html .= $nav['title'];
      $html .= '</a>';

      $navHTML .= $html;
    }
  }
  $fullname  = $rbac->userInfoLDAP['cn'];
  $jpegPhoto = base64_encode($rbac->userInfoLDAP['jpegphoto']);

  $host = $_SERVER['HTTP_HOST'];

  $result = <<< EOF_HEADER
<section class="navigation-header">
    <header>
        <h1>
            <a href="http://sharepoint.docker/intranet"><span class="home">&#127968;</span></a>
            <a href="/intranet">$sitename</a>
        </h1>
        <h2>Welkom $fullname</h2>
        
        <p class="right">
          <span class="logout"><a href="http://log:out@$host/intranet/logout">Logout</a></span>
          <img src="data:image/jpeg;base64,$jpegPhoto" alt="Gebruikersafbeelding">
        </p>
    </header>
EOF_HEADER;

  if ($hasPermissions) {
    $result .= <<< EOF_NAVIGATION
    <nav> $navHTML  </nav>
EOF_NAVIGATION;
  }
  $result .= <<< EOF_CLOSEHTML
    </section>
EOF_CLOSEHTML;
  return $result;
}

?>