CREATE TABLE departements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE types_conge (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    jours_max INTEGER NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE employes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    mot_de_passe TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'employe',
    departement_id INTEGER,
    actif INTEGER NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (departement_id) REFERENCES departements(id)
);

CREATE TABLE soldes_conge (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    annee INTEGER NOT NULL,
    jours_total INTEGER NOT NULL,
    jours_pris INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id)
);

CREATE TABLE demandes_conge (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    motif TEXT,
    statut TEXT NOT NULL DEFAULT 'en_attente',
    commentaire_rh TEXT,
    traite_par INTEGER,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id),
    FOREIGN KEY (traite_par) REFERENCES employes(id)
);

INSERT INTO departements (nom) VALUES
    ('Ressources Humaines'),
    ('Informatique'),
    ('Comptabilité'),
    ('Direction');

INSERT INTO types_conge (nom, jours_max) VALUES
    ('Congé annuel', 30),
    ('Congé maladie', 15),
    ('Congé maternité', 90),
    ('Congé sans solde', 30);

INSERT INTO employes (nom, email, mot_de_passe, role, departement_id) VALUES
    ('Admin RH', 'admin@techmada.mg', 'hashed_password', 'admin', 1),
    ('Responsable RH', 'rh@techmada.mg', 'hashed_password', 'rh', 1),
    ('Jean Dupont', 'jean.dupont@techmada.mg', 'hashed_password', 'employe', 2),
    ('Marie Claire', 'marie.claire@techmada.mg', 'hashed_password', 'employe', 3);

INSERT INTO soldes_conge (employe_id, type_conge_id, annee, jours_total, jours_pris) VALUES
    (3, 1, 2026, 30, 5),
    (3, 2, 2026, 15, 0),
    (4, 1, 2026, 30, 10),
    (4, 2, 2026, 15, 2);

INSERT INTO demandes_conge (employe_id, type_conge_id, date_debut, date_fin, motif, statut) VALUES
    (3, 1, '2026-06-01', '2026-06-05', 'Vacances en famille', 'approuvee'),
    (4, 2, '2026-05-20', '2026-05-22', 'Grippe', 'en_attente'),
    (3, 1, '2026-07-10', '2026-07-15', 'Voyage', 'en_attente');