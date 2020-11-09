CREATE TABLE permissions (
  id int(11) NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  display_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  description varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO permissions (id, `name`, display_name, description) VALUES(1, 'permiso1', 'Permiso 1', '');
INSERT INTO permissions (id, `name`, display_name, description) VALUES(2, 'permiso2', 'Permiso 2', '');
INSERT INTO permissions (id, `name`, display_name, description) VALUES(3, 'permiso3', 'Permiso 3', '');

CREATE TABLE roles (
  id int(11) NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  display_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  description varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (id, `name`, display_name, description) VALUES(1, 'admin', 'Administrador', 'Somebody with access to the API administration features and all other features');
INSERT INTO roles (id, `name`, display_name, description) VALUES(2, 'user', 'Usuario', 'Somebody who can only manage their profile');

CREATE TABLE roles_permissions (
  role_id int(11) NOT NULL,
  permission_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles_permissions (role_id, permission_id) VALUES(1, 1);
INSERT INTO roles_permissions (role_id, permission_id) VALUES(1, 2);
INSERT INTO roles_permissions (role_id, permission_id) VALUES(1, 3);

CREATE TABLE users (
  id int(11) NOT NULL,
  username varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  email varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  password varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  verified tinyint(1) NOT NULL DEFAULT '0',
  active tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (id, username, email, `password`, verified, active) VALUES(1, 'admin', 'admin@yopmail.com', '$2y$10$wPoSyX.JmIVdhaF4tdBAi.oZzhYXKcrokFEI5x2CL76j2DY4Kd8.i', 1, 1);
INSERT INTO users (id, username, email, `password`, verified, active) VALUES(2, 'user1', 'user1@yopmail.com', '$2y$10$kUJJ.FaPebbea20gBUvFzuigzwDyHIO0SWsauNM7Bb2Fsy4QTsdZ.', 1, 0);

CREATE TABLE users_roles (
  user_id int(11) NOT NULL,
  role_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users_roles (user_id, role_id) VALUES(1, 1);
INSERT INTO users_roles (user_id, role_id) VALUES(2, 2);

ALTER TABLE permissions
  ADD PRIMARY KEY (id);

ALTER TABLE roles
  ADD PRIMARY KEY (id);

ALTER TABLE roles_permissions
  ADD PRIMARY KEY (role_id,permission_id);

ALTER TABLE users
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY email (email),
  ADD UNIQUE KEY username (username);

ALTER TABLE users_roles
  ADD PRIMARY KEY (user_id,role_id);