DROP DATABASE IF EXISTS IAM;
CREATE DATABASE IAM;
USE IAM;

DROP USER IF EXISTS 'student'@'%';
CREATE USER 'student'@'%' IDENTIFIED WITH mysql_native_password AS PASSWORD('test1234');

GRANT ALL ON IAM.* TO 'student'@'%';

CREATE TABLE roles
(
    idRole      INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title       VARCHAR(30)  NOT NULL COMMENT 'Unique name for role',
    description VARCHAR(200) NOT NULL,
    distinghuishedName VARCHAR(255) NOT NULL COMMENT 'Reference to existing group in LDAP tree'
) COMMENT 'Roles assignable to users';

CREATE UNIQUE INDEX UniqueRoleTitle ON roles (title);


CREATE TABLE application (
    idApplication INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL COMMENT 'Unique name for application',
    description VARCHAR(200) NOT NULL
) COMMENT 'Container for permissions';

CREATE UNIQUE INDEX UniqueApplicationTitle ON application (title);

CREATE TABLE permissions
(
    idPermission INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    code         VARCHAR(80)  NOT NULL COMMENT 'Unique code to be used in source code',
    title        VARCHAR(50)  NOT NULL COMMENT 'More descriptive role for management',
    description  VARCHAR(200) NOT NULL COMMENT 'Description like goal etc',
    fk_idApplication INT UNSIGNED NOT NULL,
    CONSTRAINT FOREIGN KEY idPermissionApplication (fk_idApplication) REFERENCES application (idApplication) ON DELETE CASCADE ON UPDATE RESTRICT
) COMMENT 'Possible permissions to protect transactions';

CREATE UNIQUE INDEX UniquePermissionCode ON permissions (code);
CREATE UNIQUE INDEX UniquePermissionTitle ON permissions (title);

CREATE TABLE role_permissions
(
    idRolePermission INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    fk_idPermission  INT UNSIGNED NOT NULL,
    fk_idRole        INT UNSIGNED NOT NULL,
    CONSTRAINT FOREIGN KEY idPermission (fk_idPermission) REFERENCES permissions (idPermission) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT FOREIGN KEY idRole (fk_idRole) REFERENCES roles (idRole) ON DELETE CASCADE ON UPDATE RESTRICT
) COMMENT 'Roles related to permissions';

CREATE UNIQUE INDEX UniqueRolePermission ON role_permissions (fk_idPermission, fk_idRole);


DELIMITER $$

CREATE OR REPLACE PROCEDURE InitAllRolesAndPermissions ()
    BEGIN
    INSERT INTO roles (title, description, distinghuishedName)
    VALUES ('admin', 'administrators', 'cn=admins,ou=roles,dc=NHLStenden,dc=com'),
           ('ICT Support', 'ICT Support', 'cn=ICT Support,ou=roles,dc=NHLStenden,dc=com'),
           ('All Personell', 'All Personell (Staff, teachers)', 'cn=All Personell,ou=roles,dc=NHLStenden,dc=com'),
           ('All Students', 'All Students ', 'cn=All Students,ou=roles,dc=NHLStenden,dc=com'),
           ('All Teachers', 'All Teachers', 'cn=All Teachers,ou=roles,dc=NHLStenden,dc=com'),

           ('Grades Students', 'Grades Students', 'cn=Grades Students,ou=roles,dc=NHLStenden,dc=com'),
           ('Grades Teachers', 'Grades Teachers', 'cn=Grades Teachers,ou=roles,dc=NHLStenden,dc=com'),

           ('SharePoint Students', 'SharePoint Students', 'cn=SharePoint Students,ou=roles,dc=NHLStenden,dc=com'),
           ('SharePoint Teachers', 'SharePoint Teachers', 'cn=SharePoint Teachers,ou=roles,dc=NHLStenden,dc=com'),

           ('Students ADCSS',   '', 'cn=Students ADCSS,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com'),
           ('Students HBO-ICT', '', 'cn=Students HBO-ICT,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com'),
           ('Teachers ADCSS',   '', 'cn=Teachers ADCSS,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com'),
           ('Teachers HBO-ICT', '', 'cn=Teachers HBO-ICT,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com'),

           ('Marketing', '', 'cn=Marketing,ou=roles,dc=NHLStenden,dc=com'),
           ('Marketing management', '', 'cn=Marketing managers,ou=roles,dc=NHLStenden,dc=com')
    ;

    SELECT roles.idRole INTO @var_Role_admin                FROM roles WHERE title = 'admin';
    SELECT roles.idRole INTO @var_Role_all_personell        FROM roles WHERE title = 'All Personell';

    SELECT roles.idRole INTO @var_Role_all_students        FROM roles WHERE title = 'All Students';
    SELECT roles.idRole INTO @var_Role_all_teachers        FROM roles WHERE title = 'All Teachers';

    SELECT roles.idRole INTO @var_Role_ICT_Support          FROM roles WHERE title = 'ICT Support';
    SELECT roles.idRole INTO @var_Role_Grades_Students      FROM roles WHERE title = 'Grades Students';
    SELECT roles.idRole INTO @var_Role_Grades_Teachers      FROM roles WHERE title = 'Grades Teachers';
    SELECT roles.idRole INTO @var_Role_Teachers_HBOICT      FROM roles WHERE title = 'Teachers ADCSS';
    SELECT roles.idRole INTO @var_Role_Teachers_ADCSS       FROM roles WHERE title = 'Teachers HBO-ICT';
    SELECT roles.idRole INTO @var_Role_Students_HBOICT      FROM roles WHERE title = 'Teachers ADCSS';
    SELECT roles.idRole INTO @var_Role_Students_ADCSS       FROM roles WHERE title = 'Teachers HBO-ICT';
    SELECT roles.idRole INTO @var_Role_SharePoint_Students  FROM roles WHERE title = 'SharePoint Students';
    SELECT roles.idRole INTO @var_Role_SharePoint_Teachers  FROM roles WHERE title = 'SharePoint Teachers';
    SELECT roles.idRole INTO @var_Role_Marketing            FROM roles WHERE title = 'Marketing';
    SELECT roles.idRole INTO @var_Role_Marketing_Management FROM roles WHERE title = 'Marketing management';

    INSERT INTO application (title, description)
    VALUES ('Admin Panel',''),
           ('SharePoint','' ),
           ('Marketing','' ),
           ('Grades','' ),
           ('Mail','' )
    ;

    SELECT idApplication INTO @var_App_AdminPanel FROM application WHERE title = 'Admin Panel';
    SELECT idApplication INTO @var_App_SharePoint FROM application WHERE title = 'SharePoint';
    SELECT idApplication INTO @var_App_Marketing  FROM application WHERE title = 'Marketing';
    SELECT idApplication INTO @var_App_Grades     FROM application WHERE title = 'Grades';
    SELECT idApplication INTO @var_App_Mail       FROM application WHERE title = 'Mail';

    INSERT INTO permissions (code, title, description,fk_idApplication)
    VALUES
           ('SharePoint_Basic_Access', 'Basic Access to SharePoint', '',@var_App_SharePoint),
           ('Grades_Basic_Access', 'Basic Access to Grades app', '',@var_App_Grades),
           ('Marketing_Basic_Access', 'Basic Access to Marketing app', '',@var_App_Marketing),
           ('Use_Mail', 'Use college e-mail', '',@var_App_Mail),
           ('AdminPanel', 'Use Admin Panel', '',@var_App_AdminPanel),

           ('SharePoint_News', 'Read news on SharePoint/Intranet', '',@var_App_SharePoint  ),
           ('SharePoint_HRM', 'Go to Human Resource Management', '',@var_App_SharePoint),
           ('SharePoint_StudentTools', 'Open student tools', '',@var_App_SharePoint),
           ('SharePoint_TeacherTools', 'Open teacher\'s tools', '',@var_App_SharePoint),

           ('Grades_Create_Gradelists', 'Create a new list of grades', '',@var_App_Grades),
           ('Grades_Approve_Gradeslist', 'Approve a list of grades', '',@var_App_Grades),
           ('Grades_Read_Own_Grades', 'Student can read own grades', '',@var_App_Grades),
           ('Grades_Read_StudentDetails', 'Get information on all students', '',@var_App_Grades),
           ('Grades_Show_Self', 'Show students own information', '',@var_App_Grades),
           ('Marketing_Create_Campaign', 'Create a new marketing campaign', '',@var_App_Marketing),
           ('Marketing_Read_Campaign', 'Read a marketing campaign', '',@var_App_Marketing),
           ('Marketing_Delete_Campaign', 'Delete a marketing campaign', '',@var_App_Marketing),
           ('Marketing_Update_Campaign', 'Update a marketing campaign', '',@var_App_Marketing),
           ('Marketing_Approve_Campaign', 'Approve a marketing campaign', '',@var_App_Marketing)
    ;

    SELECT permissions.idPermission INTO @var_permission_Use_Mail FROM permissions WHERE code = 'Use_Mail';
    SELECT permissions.idPermission INTO @var_permission_Admin_Panel FROM permissions WHERE code = 'AdminPanel';

    SELECT permissions.idPermission INTO @var_permission_SharePoint_Basic_Access FROM permissions WHERE code = 'SharePoint_Basic_Access';
    SELECT permissions.idPermission INTO @var_permission_SharePoint_News FROM permissions WHERE code = 'SharePoint_News';
    SELECT permissions.idPermission INTO @var_permission_SharePoint_HRM FROM permissions WHERE code = 'SharePoint_HRM';
    SELECT permissions.idPermission INTO @var_permission_SharePoint_StudentTools FROM permissions WHERE code = 'SharePoint_StudentTools';
    SELECT permissions.idPermission INTO @var_permission_SharePoint_TeacherTools FROM permissions WHERE code = 'SharePoint_TeacherTools';

    SELECT permissions.idPermission INTO @var_permission_Grades_Basic_Access FROM permissions WHERE code = 'Grades_Basic_Access';
    SELECT permissions.idPermission INTO @var_permission_Grades_Create_Gradelists FROM permissions WHERE code = 'Grades_Create_Gradelists';
    SELECT permissions.idPermission INTO @var_permission_Grades_Approve_Gradeslist FROM permissions WHERE code = 'Grades_Approve_Gradeslist';
    SELECT permissions.idPermission INTO @var_permission_Grades_Read_Own_Grades FROM permissions WHERE code = 'Grades_Read_Own_Grades';
    SELECT permissions.idPermission INTO @var_permission_Grades_Read_StudentDetails FROM permissions WHERE code = 'Grades_Read_StudentDetails';
    SELECT permissions.idPermission INTO @var_permission_Grades_Show_Self FROM permissions WHERE code = 'Grades_Show_Self';

    SELECT permissions.idPermission INTO @var_permission_Marketing_Basic_Access FROM permissions WHERE code = 'Marketing_Basic_Access';
    SELECT permissions.idPermission INTO @var_permission_Marketing_Create_Campaign FROM permissions WHERE code = 'Marketing_Create_Campaign';
    SELECT permissions.idPermission INTO @var_permission_Marketing_Read_Campaign FROM permissions WHERE code = 'Marketing_Read_Campaign';
    SELECT permissions.idPermission INTO @var_permission_Marketing_Delete_Campaign FROM permissions WHERE code = 'Marketing_Delete_Campaign';
    SELECT permissions.idPermission INTO @var_permission_Marketing_Update_Campaign FROM permissions WHERE code = 'Marketing_Update_Campaign';
    SELECT permissions.idPermission INTO @var_permission_Marketing_Approve_Campaign FROM permissions WHERE code = 'Marketing_Approve_Campaign';

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_all_personell, @var_permission_SharePoint_Basic_Access);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_all_personell, @var_permission_Use_Mail);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_all_personell, @var_permission_SharePoint_HRM);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_all_personell, @var_permission_SharePoint_News);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Marketing_Management, @var_permission_Marketing_Create_Campaign);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Marketing_Management, @var_permission_Marketing_Read_Campaign);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Marketing_Management, @var_permission_Marketing_Delete_Campaign);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Marketing_Management, @var_permission_Marketing_Update_Campaign);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Marketing_Management, @var_permission_Marketing_Approve_Campaign);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Marketing, @var_permission_Marketing_Basic_Access);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Marketing, @var_permission_Marketing_Create_Campaign);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Marketing, @var_permission_Marketing_Read_Campaign);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Marketing, @var_permission_Marketing_Update_Campaign);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_all_students, @var_permission_Use_Mail);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_All_Students, @var_permission_Grades_Basic_Access);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_All_Students, @var_permission_Grades_Read_Own_Grades);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_All_Students, @var_permission_Grades_Show_Self);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_All_Students, @var_permission_SharePoint_StudentTools);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_All_Students, @var_permission_SharePoint_News);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_All_Teachers, @var_permission_Grades_Basic_Access);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_All_Teachers, @var_permission_SharePoint_TeacherTools);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Grades_Teachers, @var_permission_Grades_Create_Gradelists);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Grades_Teachers, @var_permission_Grades_Approve_Gradeslist);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_Grades_Teachers, @var_permission_Grades_Read_StudentDetails);

    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_admin,       @var_permission_SharePoint_Basic_Access);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_admin,       @var_permission_Admin_Panel);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_ICT_Support, @var_permission_SharePoint_Basic_Access);
    INSERT INTO role_permissions(fk_idRole, fk_idPermission) VALUES ( @var_Role_All_Students, @var_permission_SharePoint_Basic_Access);


END $$
DELIMITER ;

CALL InitAllRolesAndPermissions();

CREATE OR REPLACE VIEW vw_Role_Permissions AS
SELECT idRolePermission,
       idRole,
       a.title as application,
       roles.title as role,
       roles.distinghuishedName as dn,
       idPermission,
       p.title as permission,
       p.code as permission_code
FROM roles JOIN role_permissions rp on roles.idRole = rp.fk_idRole
           JOIN permissions p on rp.fk_idPermission = p.idPermission
           JOIN application a on a.idApplication = p.fk_idApplication
;


/**
  Create a stored function to get all roles and permissions in a pivot table
  */

DELIMITER $$

CREATE FUNCTION GenerateRolePermissionCrossTable()
    RETURNS TEXT
BEGIN
    DECLARE sql_query TEXT;
    DECLARE header TEXT;
    DECLARE result TEXT;

    SET @sql = NULL;
    SELECT
        GROUP_CONCAT(
                DISTINCT
                CONCAT(
                        'MAX(CASE WHEN r.title = ''',
                        r.title,
                        ''' THEN 1 ELSE 0 END) AS `',
                        r.title,
                        '`'
                )
        ) INTO @sql
    FROM roles r;

    SET sql_query = CONCAT('SELECT a.title as Application, p.title AS Permission, ', @sql, '
                           FROM permissions p
                           LEFT JOIN role_permissions rp ON p.idPermission = rp.fk_idPermission
                           LEFT JOIN roles r ON rp.fk_idRole = r.idRole
                           LEFT JOIN application a ON p.fk_idApplication = a.idApplication
                           GROUP BY a.title, p.title');

    RETURN sql_query;
END $$

CREATE OR REPLACE PROCEDURE ClearAllRolesAndPermissions()
BEGIN
    DELETE FROM application;
    DELETE FROM role_permissions;
    DELETE FROM permissions;
    DELETE FROM roles;
END $$


CREATE OR REPLACE PROCEDURE ResetAllRolesAndPermissions()
BEGIN
    CALL ClearAllRolesAndPermissions();
    CALL InitAllRolesAndPermissions();
END $$



DELIMITER ;