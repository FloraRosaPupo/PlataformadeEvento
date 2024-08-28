CREATE database labprog;
use labprog;
-- Criação da tabela "users"
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  matricula VARCHAR(20),
  email VARCHAR(255) UNIQUE,
  password VARCHAR(255),
  user_type VARCHAR(255)
);

-- Criação da tabela "categories"
CREATE TABLE categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255)
);

-- Criação da tabela "events"
CREATE TABLE events (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255),
  description TEXT,
  date DATE,
  time TIME,
  location VARCHAR(255),
  category_id INT,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Criação da tabela "registrations"
CREATE TABLE registrations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT,
  event_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (event_id) REFERENCES events(id)
);

INSERT INTO users (name, matricula, email, password, user_type) VALUES
('João Silva', '12345', 'joao.silva@example.com', 'senha123', 'admin'),
('Maria Oliveira', '67890', 'maria.oliveira@example.com', 'senha456', 'pessoal');
INSERT INTO categories (name) VALUES
('Tecnologia'),
('Educação'),
('Saúde'),
('Esportes');
INSERT INTO events (title, description, date, time, location, category_id) VALUES
('Workshop de Desenvolvimento', 'Um workshop sobre desenvolvimento de software.', '2024-09-15', '10:00:00', 'Auditório 1', 1),
('Seminário de Educação', 'Seminário sobre novas metodologias educacionais.', '2024-09-20', '14:00:00', 'Sala 301', 2),
('Caminhada na Natureza', 'Caminhada para promover a saúde e o bem-estar.', '2024-09-25', '08:00:00', 'Entrada do Parque', 3);
INSERT INTO registrations (user_id, event_id) VALUES
(1, 1),  -- João Silva se inscreveu no Workshop de Desenvolvimento
(2, 2);  -- Maria Oliveira se inscreveu no Seminário de Educação

SELECT * FROM registrations;