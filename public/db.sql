CREATE TABLE address (
  id_address INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cep VARCHAR(9) NOT NULL,
  estado VARCHAR(2) NOT NULL,
  cidade VARCHAR(30) NOT NULL,
  logradouro VARCHAR(40) NOT NULL,
  numero INT NOT NULL
);

CREATE TABLE users (
  id_user INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_address INT NOT NULL,
  nome VARCHAR(50) NOT NULL,
  data_nasc DATE NOT NULL,
  genero ENUM('F','M','O') NOT NULL,
  nome_mat VARCHAR(50) NOT NULL,
  cpf VARCHAR(15) NOT NULL UNIQUE,
  email VARCHAR(80) NOT NULL UNIQUE,
  celular VARCHAR(17) NOT NULL,
  telefone VARCHAR(16),
  tipo ENUM('Comum', 'Admin') DEFAULT'Comum',
  login VARCHAR(6) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,

  FOREIGN KEY(id_address) REFERENCES address(id_address)
);

CREATE TABLE vehicles_details (
  id_details INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  quilometragem VARCHAR(250) NOT NULL,
  motor VARCHAR(30) NOT NULL,
  cor VARCHAR(30) NOT NULL,
  detalhes TEXT NOT NULL,
  portas INT NOT NULL,
  mala INT NOT NULL,
  ar_condicionado BOOLEAN NOT NULL
);

CREATE TABLE vehicles (
  id_vehicle INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_details INT NOT NULL,
  img VARCHAR(30) NOT NULL,
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