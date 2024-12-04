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

CREATE TABLE permissions
(
    idPermission INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    code         VARCHAR(80)  NOT NULL COMMENT 'Unique code to be used in source code',
    title        VARCHAR(50)  NOT NULL COMMENT 'More descriptive role for management',
    description  VARCHAR(200) NOT NULL COMMENT 'Description like goal etc'
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

           ('Students ADCSS', '', 'cn=Students ADCSS,ou=roles,dc=NHLStenden,dc=com'),
           ('Students HBO-ICT', '', 'cn=Students HBO-ICT,ou=roles,dc=NHLStenden,dc=com'),
           ('Teachers ADCSS', '', 'cn=Teachers ADCSS,ou=roles,dc=NHLStenden,dc=com'),
           ('Teachers HBO-ICT', '', 'cn=Teachers HBO-ICT,ou=roles,dc=NHLStenden,dc=com'),

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

    /*
    SELECT @var_Role_admin,
           @var_Role_all_personell,
           @var_Role_all_students,
           @var_Role_all_teachers,
           @var_Role_ICT_Support,
           @var_Role_Grades_Students,
           @var_Role_Grades_Teachers,
           @var_Role_Teachers_HBOICT,
           @var_Role_Teachers_ADCSS,
           @var_Role_Students_HBOICT,
           @var_Role_Students_ADCSS,
           @var_Role_SharePoint_Students,
           @var_Role_SharePoint_Teachers,
           @var_Role_Marketing,
           @var_Role_Marketing_Management
    ;*/




    INSERT INTO permissions (code, title, description)
    VALUES
           ('SharePoint_Basic_Access', 'Basic Access to SharePoint', ''),
           ('Grades_Basic_Access', 'Basic Access to Grades app', ''),
           ('Marketing_Basic_Access', 'Basic Access to Marketing app', ''),

           ('Use_Mail', 'Use college e-mail', ''),
           ('AdminPanel', 'Use Admin Panel', ''),

           ('SharePoint_News', 'Read news on SharePoint/Intranet', ''),
           ('SharePoint_HRM', 'Go to Human Resource Management', ''),
           ('SharePoint_StudentTools', 'Open student tools', ''),

           ('Grades_Create_Gradelists', 'Create a new list of grades', ''),
           ('Grades_Approve_Gradeslist', 'Approve a list of grades', ''),
           ('Grades_Read_Own_Grades', 'Student can read own grades', ''),
           ('Grades_Read_StudentDetails', 'Get information on all students', ''),
           ('Grades_Show_Self', 'Show students own information', ''),

           ('Marketing_Create_Campaign', 'Create a new marketing campaign', ''),
           ('Marketing_Read_Campaign', 'Read a marketing campaign', ''),
           ('Marketing_Delete_Campaign', 'Delete a marketing campaign', ''),
           ('Marketing_Update_Campaign', 'Update a marketing campaign', ''),
           ('Marketing_Approve_Campaign', 'Approve a marketing campaign', '')
    ;

    SELECT permissions.idPermission INTO @var_permission_Use_Mail FROM permissions WHERE code = 'Use_Mail';
    SELECT permissions.idPermission INTO @var_permission_Admin_Panel FROM permissions WHERE code = 'AdminPanel';

    SELECT permissions.idPermission INTO @var_permission_SharePoint_Basic_Access FROM permissions WHERE code = 'SharePoint_Basic_Access';
    SELECT permissions.idPermission INTO @var_permission_SharePoint_News FROM permissions WHERE code = 'SharePoint_News';
    SELECT permissions.idPermission INTO @var_permission_SharePoint_HRM FROM permissions WHERE code = 'SharePoint_HRM';
    SELECT permissions.idPermission INTO @var_permission_SharePoint_StudentTools FROM permissions WHERE code = 'SharePoint_StudentTools';

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

    -- @var_Role_admin
    -- @var_Role_all_personell
    -- @var_Role_all_students
    -- @var_Role_all_teachers
    -- @var_Role_ICT_Support
    -- @var_Role_Grades_Students
    -- @var_Role_Grades_Teachers
    -- @var_Role_Teachers_HBOICT
    -- @var_Role_Teachers_ADCSS
    -- @var_Role_Students_HBOICT
    -- @var_Role_Students_ADCSS
    -- @var_Role_SharePoint_Students
    -- @var_Role_SharePoint_Teachers
    -- @var_Role_Marketing
    -- @var_Role_Marketing_Managemen

    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_SharePoint_Basic_Access, @var_Role_admin);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_SharePoint_Basic_Access, @var_Role_ICT_Support);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_SharePoint_Basic_Access, @var_Role_All_Students);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_SharePoint_Basic_Access, @var_Role_all_personell);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Use_Mail, @var_Role_all_personell);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Use_Mail, @var_Role_all_students);

    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Marketing_Create_Campaign, @var_Role_Marketing_Management);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Marketing_Read_Campaign, @var_Role_Marketing_Management);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Marketing_Delete_Campaign, @var_Role_Marketing_Management);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Marketing_Update_Campaign, @var_Role_Marketing_Management);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Marketing_Approve_Campaign, @var_Role_Marketing_Management);

    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Marketing_Basic_Access, @var_Role_Marketing);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Marketing_Create_Campaign, @var_Role_Marketing);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Marketing_Read_Campaign, @var_Role_Marketing);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Marketing_Update_Campaign, @var_Role_Marketing);

    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Grades_Basic_Access, @var_Role_All_Students);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Grades_Basic_Access, @var_Role_All_Teachers);

    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Grades_Read_Own_Grades, @var_Role_All_Students);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Grades_Show_Self, @var_Role_All_Students);

    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Grades_Create_Gradelists, @var_Role_Grades_Teachers);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Grades_Approve_Gradeslist, @var_Role_Grades_Teachers);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Grades_Read_StudentDetails, @var_Role_Grades_Teachers);

    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_SharePoint_HRM, @var_Role_all_personell);
    INSERT INTO role_permissions(fk_idPermission, fk_idRole) VALUES (@var_permission_Admin_Panel, @var_Role_admin);

END $$
DELIMITER ;

CALL InitAllRolesAndPermissions();

CREATE OR REPLACE VIEW vw_Role_Permissions AS
SELECT idRolePermission,
       idRole,
       roles.title as role,
       roles.distinghuishedName as dn,
       idPermission,
       p.title as permission,
       p.code as permission_code
FROM roles JOIN role_permissions rp on roles.idRole = rp.fk_idRole
           JOIN permissions p on rp.fk_idPermission = p.idPermission
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

    SET sql_query = CONCAT('SELECT p.title AS Permission, ', @sql, '
                           FROM permissions p
                           LEFT JOIN role_permissions rp ON p.idPermission = rp.fk_idPermission
                           LEFT JOIN roles r ON rp.fk_idRole = r.idRole
                           GROUP BY p.title');

    RETURN sql_query;
END$$

CREATE OR REPLACE PROCEDURE ClearAllRolesAndPermissions()
BEGIN
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