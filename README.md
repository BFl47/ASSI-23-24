# GymGenius - Web application per la gestione di una palestra

Progetto per il laboratorio di Architetture Software e Sicurezza Informatica - Sapienza Università di Roma

GymGenius è un'applicazione progettata per supportare la gestione di una palestra, fornendo
funzionalità specifiche per utenti (clienti), trainer e l'admin (proprietario della palestra).

## Collaboratori
Andrea Di Vito, Benedetta Fiorillo, Simone Giudici

## Dettagli di implementazione
### LINGUAGGIO
PHP

### RUOLI
- L'admin può aggiungere e gestire i corsi, che diventano visibili sul calendario integrato
dell'app. Ciò gli permette di gestire ed organizzare l'offerta formativa della sua palestra.
Inoltre, l'admin ha il potere di licenziare i trainer, eliminandoli dall'applicazione, in modo da
mantenere aggiornato il team di trainer.

- I trainer, una volta registrati, hanno la possibilità di aggiungere le loro disponibilità nel
calendario, rendendosi prenotabili per sessioni private con i clienti. Inoltre, possono
eliminare lezioni già programmate. Quando questo accade, tutti i clienti prenotati per quella
lezione ricevono una notifica automatica, assicurando una comunicazione tempestiva e
riducendo al minimo gli inconvenienti.

- I clienti possono registrarsi sull'app, iscriversi ai corsi disponibili e aggiungerli ai preferiti per
un accesso più rapido. Possono anche visualizzare i profili dei trainer per scegliere il più
adatto alle loro esigenze e prenotare sessioni private. Inoltre, i clienti hanno la possibilità di
recensire i corsi frequentati, in modo da lasciare il loro feedback.

- Nella homepage dell'app, ogni utente (anche non registrato) può visualizzare un calendario
con tutti i corsi disponibili, una breve descrizione e la media delle recensioni degli utenti
rappresentata tramite il classico star rating.

### INTERAZIONE CON SERVIZI ESTERNI 
API: Google Calendar, OAuth
 
### AUTENTICAZIONE  
 Sono previste due modalità di accesso al sito: 
•	Locale -> l’utente può registrarsi direttamente sul sito inserendo i propri dati 
•	OAuth -> l’utente può accedere al sito direttamente tramite il proprio account GitHub 

### PIANO DEI TEST: 
Da definire
