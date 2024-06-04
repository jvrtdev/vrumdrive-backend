CREATE TABLE users (
  id_user INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_address INT NOT NULL,
  name VARCHAR(50) NOT NULL,
  birth_date DATE NOT NULL,
  gender ENUM('F','M','O') NOT NULL,
  maternal_name VARCHAR(50) NOT NULL,
  cpf VARCHAR(15) NOT NULL,
  email VARCHAR(80) NOT NULL,
  cellphone VARCHAR(17) NOT NULL,
  login VARCHAR(6) NOT NULL,
  senha VARCHAR(255) NOT NULL,

  FOREIGN KEY(id_address) REFERENCES address(id)
);

CREATE TABLE cars (
  id_car INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  img VARCHAR(30) NOT NULL,
  model VARCHAR(30) NOT NULL,
  brand VARCHAR(30) NOT NULL,
  category ENUM('SUV', 'Sedan', 'Hatchback', 'Pick-up', 'Sport', 'Luxury', 'Sports car'),
  year VARCHAR(4) NOT NULL,
  status ENUM('spare', 'busy') NOT NULL,
  price FLOAT NOT NULL
);

CREATE TABLE bookings (
  id_booking INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_user INT NOT NULL,
  id_car INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  total_price FLOAT NOT NULL

  FOREIGN KEY(id_user) REFERENCES users(id),
  FOREIGN KEY(id_car) REFERENCES cars(id)
);

CREATE TABLE address (
  id_address INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cep VARCHAR(9) NOT NULL,
  state VARCHAR(2) NOT NULL,
  city VARCHAR(30) NOT NULL,
  address VARCHAR(40) NOT NULL,
  number INT
);

