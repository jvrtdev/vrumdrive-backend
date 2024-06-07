CREATE TABLE users (
  id_user INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_address INT NOT NULL,
  nome VARCHAR(50) NOT NULL,
  data_nasc DATE NOT NULL,
  genero ENUM('F','M','O') NOT NULL,
  nome_mat VARCHAR(50) NOT NULL,
  cpf VARCHAR(15) NOT NULL,
  email VARCHAR(80) NOT NULL,
  celular VARCHAR(17) NOT NULL,
  telefone VARCHAR(16) NOT NULL,
  login VARCHAR(6) NOT NULL,
  senha VARCHAR(255) NOT NULL,

  FOREIGN KEY(id_address) REFERENCES address(id_address)
);

CREATE TABLE address (
  id_address INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cep VARCHAR(9) NOT NULL,
  state VARCHAR(2) NOT NULL,
  city VARCHAR(30) NOT NULL,
  address VARCHAR(40) NOT NULL,
  number INT NOT NULL
);

CREATE TABLE vehicles (
  id_vehicle INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  img VARCHAR(30) NOT NULL,
  modelo VARCHAR(30) NOT NULL,
  marca VARCHAR(30) NOT NULL,
  categoria ENUM('SUV', 'Sedan', 'Hatchback', 'Pick-up', 'Esportivo', 'Luxo', 'Compacto'),
  ano VARCHAR(4) NOT NULL,
  status ENUM('disponivel', 'ocupado') NOT NULL,
  preco FLOAT NOT NULL
);

CREATE TABLE vehicles_details (
  id_details INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_vehicle INT NOT NULL,
  quilometragem VARCHAR(250) NOT NULL,
  motor VARCHAR(30) NOT NULL,
  cor VARCHAR(30) NOT NULL,
  detalhes TEXT NOT NULL,
  portas INT NOT NULL,
  mala INT NOT NULL,
  ar_condicionado BOOLEAN NOT NULL
  
  FOREIGN KEY(id_vehicle) REFERENCES vehicles(id_vehicle)
);

CREATE TABLE bookings (
  id_booking INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_vehicle INT NOT NULL,
  pickup_date_time DATETIME NOT NULL,
  return_date_time DATETIME NOT NULL,
  total_price FLOAT NOT NULL,

  FOREIGN KEY(id_user) REFERENCES users(id_user),
  FOREIGN KEY(id_vehicle) REFERENCES vehicles(id_vehicle)
);