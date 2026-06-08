-- =====================================================================
--  Base de données du site événementiel BACKROOMS  (sae202_event)
--  Noms de tables explicites (exigé par le cahier des charges).
-- =====================================================================
SET NAMES utf8mb4;

-- --- Équipes (on s'inscrit à l'escape game EN ÉQUIPE) ---
CREATE TABLE IF NOT EXISTS equipes (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom          VARCHAR(80)  NOT NULL UNIQUE,
    code_invite  CHAR(6)      NOT NULL UNIQUE,      -- code pour rejoindre l'équipe
    createur_id  INT UNSIGNED NULL,
    date_creation DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --- Utilisateurs (participants + administrateurs) ---
CREATE TABLE IF NOT EXISTS utilisateurs (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom           VARCHAR(60)  NOT NULL,
    prenom        VARCHAR(60)  NOT NULL,
    pseudo        VARCHAR(60)  NOT NULL,
    email         VARCHAR(160) NOT NULL UNIQUE,
    telephone     VARCHAR(20)  NULL,
    mot_de_passe  VARCHAR(255) NOT NULL,            -- haché avec password_hash()
    role          ENUM('membre','admin') NOT NULL DEFAULT 'membre',
    equipe_id     INT UNSIGNED NULL,
    date_inscription DATETIME  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_equipe FOREIGN KEY (equipe_id) REFERENCES equipes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE equipes
    ADD CONSTRAINT fk_equipe_createur FOREIGN KEY (createur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL;

-- --- Réservations (une équipe réserve une session) ---
CREATE TABLE IF NOT EXISTS reservations (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    equipe_id   INT UNSIGNED NOT NULL,
    salle       ENUM('facile','standard','hardcore') NOT NULL,
    date_session DATETIME    NOT NULL,
    nb_joueurs  TINYINT UNSIGNED NOT NULL,
    statut      ENUM('en_attente','confirmee','annulee') NOT NULL DEFAULT 'en_attente',
    date_reservation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_resa_equipe FOREIGN KEY (equipe_id) REFERENCES equipes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --- Scores (résultat d'une équipe après la partie) ---
CREATE TABLE IF NOT EXISTS scores (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    equipe_id   INT UNSIGNED NOT NULL,
    points      INT NOT NULL DEFAULT 0,
    temps_secondes INT UNSIGNED NULL,               -- temps de sortie
    reussi      TINYINT(1) NOT NULL DEFAULT 0,
    date_partie DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_score_equipe FOREIGN KEY (equipe_id) REFERENCES equipes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --- Commentaires / avis (publiés après modération admin) ---
CREATE TABLE IF NOT EXISTS commentaires (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT UNSIGNED NOT NULL,
    note          TINYINT UNSIGNED NOT NULL,        -- 1 à 5
    texte         TEXT NOT NULL,
    statut        ENUM('en_attente','approuve','refuse') NOT NULL DEFAULT 'en_attente',
    date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_com_user FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
