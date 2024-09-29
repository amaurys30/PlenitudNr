-- Crear la base de datos
CREATE DATABASE ProduccionPanela;
USE ProduccionPanela;

-- Tabla Persona: Registra las personas
CREATE TABLE Persona (
    id_persona INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    telefono VARCHAR(15),
    direccion VARCHAR(255),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla Molienda: Representa cada proceso de molienda
CREATE TABLE Molienda (
    id_molienda INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(255),
    fecha_inicio DATETIME NOT NULL,
    fecha_fin DATETIME,
    estado ENUM('activa', 'inactiva') DEFAULT 'activa'
);

-- Tabla Fondada: Registra las fondadas de jugo de caña (100 litros por defecto)
CREATE TABLE Fondada (
    id_fondada INT AUTO_INCREMENT PRIMARY KEY,
    id_molienda INT,
    cantidad_litros DECIMAL(10, 2) NOT NULL DEFAULT 100.00, -- Por defecto 100 litros
    fecha_agregada DATETIME NOT NULL,
    FOREIGN KEY (id_molienda) REFERENCES Molienda(id_molienda) ON DELETE CASCADE
);

-- Tabla Labor: Define las labores y sus precios por fondada
CREATE TABLE Labor (
    id_labor INT AUTO_INCREMENT PRIMARY KEY,
    nombre_labor VARCHAR(100) NOT NULL,
    precio_por_fondada DECIMAL(10, 2) NOT NULL
);

-- Tabla Participacion: Registra la participación de las personas en las labores de la molienda
CREATE TABLE Participacion (
    id_participacion INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT,
    id_molienda INT,
    id_labor INT,
    cantidad_fondadas INT NOT NULL,
    es_procesamiento BOOLEAN DEFAULT FALSE,  -- Indica si es para procesamiento de panela
    fecha_participacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_persona) REFERENCES Persona(id_persona) ON DELETE CASCADE,
    FOREIGN KEY (id_molienda) REFERENCES Molienda(id_molienda) ON DELETE CASCADE,
    FOREIGN KEY (id_labor) REFERENCES Labor(id_labor) ON DELETE CASCADE
);

-- Tabla ProduccionPanela: Registra la producción de panela de diferentes tipos
CREATE TABLE ProduccionPanela (
    id_produccion INT AUTO_INCREMENT PRIMARY KEY,
    id_fondada INT,
    tipo_panela ENUM('grande', 'mediana', 'pequeña') NOT NULL,
    cantidad_panela INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_fondada) REFERENCES Fondada(id_fondada) ON DELETE CASCADE
);

-- Tabla Gasto: Registra los gastos asociados a una molienda
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

-- Tabla Pago: Registra los pagos realizados a las personas por su participación en una molienda
CREATE TABLE Pago (
    id_pago INT AUTO_INCREMENT PRIMARY KEY,
    id_persona INT,
    id_molienda INT,
    monto_total DECIMAL(10, 2) NOT NULL,
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_persona) REFERENCES Persona(id_persona) ON DELETE CASCADE,
    FOREIGN KEY (id_molienda) REFERENCES Molienda(id_molienda) ON DELETE CASCADE
);

-- Tabla PagoProcesamiento: Registra los pagos a los participantes del procesamiento de panela
CREATE TABLE PagoProcesamiento (
    id_pago_procesamiento INT AUTO_INCREMENT PRIMARY KEY,
    id_molienda INT,
    id_persona INT,
    cantidad_fondadas INT,  -- Fondadas trabajadas en el procesamiento
    monto_a_pagar DECIMAL(10, 2) NOT NULL,  -- Pago calculado
    fecha_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_molienda) REFERENCES Molienda(id_molienda) ON DELETE CASCADE,
    FOREIGN KEY (id_persona) REFERENCES Persona(id_persona) ON DELETE CASCADE
);
