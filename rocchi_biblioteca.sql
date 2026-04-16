-- ==========================================================
-- DATABASE: Gestione Biblioteca
-- ==========================================================

-- 1. CREAZIONE TABELLE

-- Tabella Autori
CREATE TABLE Autori (
    id_autore INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    nazionalita VARCHAR(50),
    data_nascita DATE
);

-- Tabella Libri
CREATE TABLE Libri (
    id_libro INT PRIMARY KEY AUTO_INCREMENT,
    titolo VARCHAR(255) NOT NULL,
    anno_pubblicazione INT,
    isbn VARCHAR(20) UNIQUE,
    id_autore INT,
    FOREIGN KEY (id_autore) REFERENCES Autori(id_autore) ON DELETE SET NULL
);

-- Tabella Utenti
CREATE TABLE Utenti (
    id_utente INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    data_iscrizione DATE DEFAULT (CURRENT_DATE)
);

-- Tabella Prestiti (Relazione molti-a-molti tra Libri e Utenti)
CREATE TABLE Prestiti (
    id_prestito INT PRIMARY KEY AUTO_INCREMENT,
    id_libro INT,
    id_utente INT,
    data_inizio DATE NOT NULL,
    data_fine_prevista DATE NOT NULL,
    restituito BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_libro) REFERENCES Libri(id_libro) ON DELETE CASCADE,
    FOREIGN KEY (id_utente) REFERENCES Utenti(id_utente) ON DELETE CASCADE
);

-- ==========================================================
-- 2. INSERIMENTO TUPLE 
-- ==========================================================

-- Inserimento Autori
INSERT INTO Autori (nome, cognome, nazionalita, data_nascita) VALUES
('Umberto', 'Eco', 'Italiana', '1932-01-05'),
('George', 'Orwell', 'Britannica', '1903-06-25'),
('Gabriel', 'García Márquez', 'Colombiana', '1927-03-06'),
('Virginia', 'Woolf', 'Britannica', '1882-01-25'),
('Italo', 'Calvino', 'Italiana', '1923-10-15');

-- Inserimento Libri
INSERT INTO Libri (titolo, anno_pubblicazione, isbn, id_autore) VALUES
('Il nome della rosa', 1980, '978-8845278655', 1),
('1984', 1949, '978-0451524935', 2),
('Cent\'anni di solitudine', 1967, '978-8804702337', 3),
('Al faro', 1927, '978-8806219154', 4),
('Le città invisibili', 1972, '978-8804675761', 5);

-- Inserimento Utenti
INSERT INTO Utenti (nome, cognome, email, data_iscrizione) VALUES
('Mario', 'Rossi', 'mario.rossi@email.it', '2023-01-10'),
('Giulia', 'Bianchi', 'g.bianchi@email.it', '2023-02-15'),
('Luca', 'Verdi', 'luca.verdi@email.com', '2023-03-20'),
('Elena', 'Neri', 'elena.neri@provider.com', '2023-05-12'),
('Marco', 'Gialli', 'm.gialli@email.it', '2023-06-01');

-- Inserimento Prestiti
INSERT INTO Prestiti (id_libro, id_utente, data_inizio, data_fine_prevista, restituito) VALUES
(1, 1, '2023-10-01', '2023-10-15', TRUE),
(2, 2, '2023-10-05', '2023-10-20', FALSE),
(3, 3, '2023-10-10', '2023-10-25', FALSE),
(4, 4, '2023-10-12', '2023-10-27', TRUE),
(5, 5, '2023-10-15', '2023-10-30', FALSE);


-- Inserimento di altre 5 tuple nella tabella Prestiti
INSERT INTO Prestiti (id_libro, id_utente, data_inizio, data_fine_prevista, restituito) VALUES
(1, 3, '2023-11-01', '2023-11-15', TRUE),
(5, 1, '2023-11-05', '2023-11-20', FALSE),
(2, 4, '2023-11-10', '2023-11-25', TRUE),
(4, 2, '2023-11-12', '2023-11-27', FALSE),
(3, 5, '2023-11-20', '2023-12-05', FALSE);