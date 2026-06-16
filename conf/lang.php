<?php
/**
 * Internationalisation (i18n) — FR par défaut, + EN et ES.
 * La langue est mémorisée en session et se change via ?lang=xx.
 * Pour ajouter une langue : l'ajouter à $dispo et compléter $TRAD.
 */
$dispo = ['fr', 'en', 'es'];
if (isset($_GET['lang']) && in_array($_GET['lang'], $dispo, true)) {
    $_SESSION['lang'] = $_GET['lang'];
}
$GLOBALS['LANG'] = (isset($_SESSION['lang']) && in_array($_SESSION['lang'], $dispo, true)) ? $_SESSION['lang'] : 'fr';

$GLOBALS['TRAD'] = [
 'fr' => [
  'nav_accueil'=>"ACCUEIL", 'nav_concept'=>"CONCEPT", 'nav_salles'=>"LES SALLES",
  'nav_infos'=>"INFOS PRATIQUES", 'nav_regles'=>"RÈGLES", 'nav_contact'=>"CONTACT", 'nav_classement'=>"Classement",
  'btn_reserver'=>"RÉSERVER", 'btn_espace'=>"MON ESPACE", 'btn_connexion'=>"Connexion",
  'foot_tagline'=>"Escape game nocturne immersif.",
  'foot_quote'=>"«&nbsp;Vous n'auriez jamais dû trouver cet endroit.&nbsp;»",
  'foot_h_nav'=>"Navigation", 'foot_h_infos'=>"Infos pratiques", 'foot_h_pret'=>"Prêt·e&nbsp;?",
  'foot_resa_txt'=>"Réservez votre nuit dans les Backrooms.", 'foot_contact'=>"Nous contacter",
  'foot_jours'=>"Vendredi soir, samedi soir, jours fériés et vacances scolaires (sauf le lundi)",
  'foot_rights'=>"Tous droits réservés.", 'foot_by'=>"Réalisé par",
  'home_intro'=>"Inspiré de la légende urbaine des Backrooms, une dimension parallèle angoissante accessible uniquement par un « bug de la réalité », notre concept exploite la psychologie des espaces liminaux : des lieux de transition vides, répétitifs et vaguement familiers, qui génèrent un sentiment d'isolement et d'étrangeté. L'expérience se déroule du soir (19h-20h) au lendemain matin, avec 4 heures de jeu effectif pour tenter de « sortir des Backrooms ». Les décors immersifs et nos comédiens rendent cette expérience inoubliable !",
  'home_btn_reserver'=>"RÉSERVER UNE SESSION", 'home_btn_plus'=>"EN SAVOIR PLUS &gt;",
  'feat_entrer'=>"ENTRER DANS<br>LES BACKROOMS &gt;", 'feat_joueurs'=>"JOUEURS &gt;", 'feat_regles'=>"RÈGLES &gt;",
  'video_titre'=>"LA BANDE-ANNONCE", 'video_sous'=>"Plongez dans l'ambiance avant de franchir la porte.",
 ],
 'en' => [
  'nav_accueil'=>"HOME", 'nav_concept'=>"CONCEPT", 'nav_salles'=>"THE ROOMS",
  'nav_infos'=>"VISITOR INFO", 'nav_regles'=>"RULES", 'nav_contact'=>"CONTACT", 'nav_classement'=>"Leaderboard",
  'btn_reserver'=>"BOOK NOW", 'btn_espace'=>"MY ACCOUNT", 'btn_connexion'=>"Log in",
  'foot_tagline'=>"Immersive nighttime escape game.",
  'foot_quote'=>"«&nbsp;You should never have found this place.&nbsp;»",
  'foot_h_nav'=>"Navigation", 'foot_h_infos'=>"Visitor info", 'foot_h_pret'=>"Ready?",
  'foot_resa_txt'=>"Book your night in the Backrooms.", 'foot_contact'=>"Contact us",
  'foot_jours'=>"Friday & Saturday evenings, public holidays and school holidays (except Mondays)",
  'foot_rights'=>"All rights reserved.", 'foot_by'=>"Made by",
  'home_intro'=>"Inspired by the urban legend of the Backrooms — an unsettling parallel dimension reachable only through a « glitch in reality » — our concept plays on the psychology of liminal spaces: empty, repetitive, vaguely familiar in-between places that create a feeling of isolation and unease. The experience runs from the evening (7–8 pm) until the next morning, with 4 hours of actual gameplay to try to « escape the Backrooms ». Immersive sets and our live actors make it unforgettable!",
  'home_btn_reserver'=>"BOOK A SESSION", 'home_btn_plus'=>"LEARN MORE &gt;",
  'feat_entrer'=>"ENTER THE<br>BACKROOMS &gt;", 'feat_joueurs'=>"PLAYERS &gt;", 'feat_regles'=>"RULES &gt;",
  'video_titre'=>"THE TRAILER", 'video_sous'=>"Get a taste of the atmosphere before you step through the door.",
 ],
 'es' => [
  'nav_accueil'=>"INICIO", 'nav_concept'=>"CONCEPTO", 'nav_salles'=>"LAS SALAS",
  'nav_infos'=>"INFO PRÁCTICA", 'nav_regles'=>"REGLAS", 'nav_contact'=>"CONTACTO", 'nav_classement'=>"Clasificación",
  'btn_reserver'=>"RESERVAR", 'btn_espace'=>"MI ESPACIO", 'btn_connexion'=>"Iniciar sesión",
  'foot_tagline'=>"Escape game nocturno inmersivo.",
  'foot_quote'=>"«&nbsp;Nunca deberías haber encontrado este lugar.&nbsp;»",
  'foot_h_nav'=>"Navegación", 'foot_h_infos'=>"Info práctica", 'foot_h_pret'=>"¿Listo?",
  'foot_resa_txt'=>"Reserva tu noche en las Backrooms.", 'foot_contact'=>"Contáctanos",
  'foot_jours'=>"Viernes y sábados por la noche, festivos y vacaciones escolares (excepto lunes)",
  'foot_rights'=>"Todos los derechos reservados.", 'foot_by'=>"Hecho por",
  'home_intro'=>"Inspirado en la leyenda urbana de las Backrooms — una inquietante dimensión paralela a la que solo se accede por un « fallo de la realidad » — nuestro concepto juega con la psicología de los espacios liminales: lugares de paso vacíos, repetitivos y vagamente familiares que provocan una sensación de aislamiento y extrañeza. La experiencia transcurre desde la tarde (19-20 h) hasta la mañana siguiente, con 4 horas de juego efectivo para intentar « salir de las Backrooms ». ¡Los decorados inmersivos y nuestros actores la hacen inolvidable!",
  'home_btn_reserver'=>"RESERVAR UNA SESIÓN", 'home_btn_plus'=>"SABER MÁS &gt;",
  'feat_entrer'=>"ENTRAR EN<br>LAS BACKROOMS &gt;", 'feat_joueurs'=>"JUGADORES &gt;", 'feat_regles'=>"REGLAS &gt;",
  'video_titre'=>"EL TRÁILER", 'video_sous'=>"Sumérgete en el ambiente antes de cruzar la puerta.",
 ],
];

function t($k) {
    $l = $GLOBALS['LANG'];
    return $GLOBALS['TRAD'][$l][$k] ?? ($GLOBALS['TRAD']['fr'][$k] ?? $k);
}
function lang_courante() { return $GLOBALS['LANG']; }
