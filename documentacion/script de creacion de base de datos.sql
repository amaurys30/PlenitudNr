CREATE DATABASE ProduccionPanela;
USE ProduccionPanela;

CREATE TABLE Persona (
    id_persona INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    telefono VARCHAR(15),
    direccion VARCHAR(255),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Molienda (
    id_molienda INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255),
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa'
);

CREATE TABLE Fondada (
    id_fondada INT AUTO_INCREMENT PRIMARY KEY,
    id_molienda INT,
    cantidad_litros DECIMAL(10, 2) NOT NULL DEFAULT 100.00, -- Por defecto 100 litros
    fecha_agregada DATETIME NOT NULL,
    FOREIGN KEY (id_molienda) REFERENCES Molienda(id_molienda) ON DELETE CASCADE
);

CREATE TABLE Labor (
    id_labor INT AUTO_INCREMENT PRIMARY KEY,
    nombre_labor VARCHAR(100) NOT NULL,
    precio_por_fondada DECIMAL(10, 2) NOT NULL
);

CREATE TABLE Participacion (
    id_participacion INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT,
    id_molienda INT,
    id_labor INT,
    cantidad_fondadas INT NOT NULL,
    fecha_participacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_persona) REFERENCES Persona(id_persona) ON DELETE CASCADE,
    FOREIGN KEY (id_molienda) REFERENCES Molienda(id_molienda) ON DELETE CASCADE,
    FOREIGN KEY (id_labor) REFERENCES Labor(id_labor) ON DELETE CASCADE
);

CREATE TABLE ProduccionPanela (
    id_produccion INT AUTO_INCREMENT PRIMARY KEY,
    id_fondada INT,
    tipo_panela ENUM('grande', 'mediana', 'pequeña') NOT NULL,
    cantidad_panela INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_fondada) REFERENCES Fondada(id_fondada) ON DELETE CASCADE
);

CREATE TABLE Gasto (
    id_gasto INT AUTO_INCREMENT PRIMARY KEY,
    id_molienda INT,
    id_persona INT,
    id_labor INT,
    monto DECIMAL(10, 2) NOT NULL,
    fecha_gasto DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_molienda) REFERENCES Molienda(id_molienda) ON DELETE CASCADE,
    FOREIGN KEY (id_persona) REFERENCES Persona(id_persona) ON DELETE CASCADE,
    FOREIGN KEY (id_labor) REFERENCES Labor(id_labor) ON DELETE CASCADE
);

CREATE TABLE Pago (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT,
    id_molienda INT,
    monto_total DECIMAL(10, 2) NOT NULL,
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_persona) REFERENCES Persona(id_persona) ON DELETE CASCADE,
    FOREIGN KEY (id_molienda) REFERENCES Molienda(id_molienda) ON DELETE CASCADE
);
