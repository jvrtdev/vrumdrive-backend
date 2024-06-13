CREATE TABLE users (
  id_user INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  profile_img VARCHAR(100),
  nome VARCHAR(50) NOT NULL,
  data_nasc DATE NOT NULL,
  genero ENUM('F','M','O'),
  nome_mat VARCHAR(50) NOT NULL,
  cpf VARCHAR(15) UNIQUE,
  email VARCHAR(80) NOT NULL UNIQUE,
  celular VARCHAR(17) NOT NULL,
  telefone VARCHAR(16),
  tipo ENUM('Comum', 'Admin') DEFAULT'Comum',
  login VARCHAR(6) NOT NULL UNIQUE,
  senha VARCHAR(8) NOT NULL
);

CREATE TABLE address (
  id_address INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL UNIQUE,
  cep VARCHAR(9),
  estado VARCHAR(2),
  cidade VARCHAR(30),
  logradouro VARCHAR(40),
  numero INT,

  FOREIGN KEY(id_user) REFERENCES users(id_user)
);


CREATE TABLE logs (
  log_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  fator_autenticacao ENUM('CEP','Nome Materno', 'Data de Nascimento') NOT NULL,
  data_hora_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY(id_user) REFERENCES users(id_user)
);

CREATE TABLE vehicles_details (
  id_details INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  quilometragem VARCHAR(250) NOT NULL,
  motor VARCHAR(30) NOT NULL,
  cor VARCHAR(30) NOT NULL,
  titulo TEXT NOT NULL,
  detalhes TEXT NOT NULL,
  portas INT NOT NULL,
  mala INT NOT NULL,
  ar_condicionado BOOLEAN NOT NULL
);

CREATE TABLE vehicles (
  id_vehicle INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_details INT NOT NULL,
  img VARCHAR(100) NOT NULL,
  modelo VARCHAR(30) NOT NULL,
  marca VARCHAR(30) NOT NULL,
  categoria ENUM('SUV', 'Sedan', 'Hatchback', 'Pick-up', 'Esportivo', 'Luxo', 'Compacto') NOT NULL,
  ano VARCHAR(4) NOT NULL,
  status ENUM('disponivel', 'ocupado') NOT NULL,
  preco FLOAT NOT NULL,
  
  FOREIGN KEY(id_details) REFERENCES vehicles_details(id_details)
);

CREATE TABLE bookings (
  id_booking INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_vehicle INT NOT NULL,
  pickup_date_time DATETIME NOT NULL,
  return_date_time DATETIME NOT NULL,
  location VARCHAR(30) NOT NULL,
  total_price FLOAT NOT NULL,

  FOREIGN KEY(id_user) REFERENCES users(id_user),
  FOREIGN KEY(id_vehicle) REFERENCES vehicles(id_vehicle)
);

--adicionar informações nas tabelas

INSERT INTO vehicles_details (quilometragem, motor, cor, detalhes, portas, mala, ar_condicionado) VALUES
('10.000 km', '1.0 Firefly Flex', 'Branco', 'Este Fiat Argo possui motor 1.0 Firefly Flex, câmbio manual de 5 marchas, ar-condicionado, direção elétrica e sistema de áudio com conexão Bluetooth.', 4, 300, true),
('5.000 km', '1.8 Hybrid', 'Prata', 'O Toyota Corolla Hybrid é equipado com motor 1.8 Hybrid, transmissão CVT, sistema de segurança Toyota Safety Sense, e sistema multimídia com tela sensível ao toque de 8 polegadas.', 4, 470, true),
('8.000 km', '1.5 Turbo', 'Prata', 'O Honda Civic Touring vem com motor 1.5 Turbo de 173 cv, transmissão CVT, bancos em couro, teto solar elétrico, e sistema de áudio premium com 10 alto-falantes.', 4, 450, true),
('12.000 km', '1.4 Turbo', 'Prata', 'O Chevrolet Cruze Premier oferece motor 1.4 Turbo de 153 cv, transmissão automática de 6 marchas, bancos revestidos em couro, e sistema de conectividade MyLink com tela de 8 polegadas.', 4, 440, true),
('3.000 km', '2.0 Turbo', 'Azul Escuro', 'O BMW X3 é equipado com motor 2.0 Turbo de 252 cv, transmissão automática de 8 marchas, tração integral xDrive, teto solar panorâmico e sistema de navegação GPS.', 4, 550, true),
('20.000 km', '1.3 Firefly Flex', 'Prata', 'Este Fiat Cronos vem com motor 1.3 Firefly Flex, transmissão manual de 5 marchas, ar-condicionado, direção elétrica e sistema multimídia com tela de 7 polegadas.', 4, 525, true),
('6.000 km', '2.5 Hybrid', 'Branco', 'O Toyota RAV4 Hybrid é equipado com motor 2.5 Hybrid, transmissão CVT, tração integral AWD, sistema de segurança Toyota Safety Sense e sistema de entretenimento com tela de 8 polegadas.', 4, 580, true),
('9.000 km', '2.0 Turbo', 'Prata', 'O Honda Accord Sport possui motor 2.0 Turbo de 252 cv, transmissão automática de 10 marchas, bancos esportivos em couro, teto solar elétrico e sistema de áudio premium.', 4, 480, true);

INSERT INTO vehicles (id_details, img, modelo, marca, categoria, ano, status, preco) VALUES
(1, '/car8.png', 'Fiat Argo', 'Fiat', 'Hatchback', '2023', 'disponivel', 150.0),
(2, '/Car18.png', 'Toyota Corolla', 'Toyota', 'Sedan', '2024', 'disponivel', 300.0),
(3, '/car9.png', 'Honda Civic', 'Honda', 'Sedan', '2024', 'disponivel', 320.0),
(4, '/car50.png', 'Chevrolet Cruze', 'Chevrolet', 'Sedan', '2023', 'disponivel', 280.0),
(5, '/car11.png', 'BMW X3', 'BMW', 'SUV', '2024', 'disponivel', 600.0),
(6, '/Car19.png', 'Fiat Cronos', 'Fiat', 'Sedan', '2022', 'disponivel', 170.0),
(7, '/car13.png', 'Toyota RAV4', 'Toyota', 'SUV', '2023', 'disponivel', 450.0),
(8, '/car29.png', 'Honda Accord', 'Honda', 'Sedan', '2023', 'disponivel', 400.0);
