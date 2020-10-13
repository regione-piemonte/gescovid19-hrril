# Project title: GESCOVID19

La piattaforma **GESCOVID19** è un composito di applicazioni create per gestire tutta la fase dell'emergenza pandemica del virus covid-19, dal momento della segnalazione di un sospetto positivo, monitorando tutto il decorso del paziente covid-positivo.  
E' stata realizzata dal CSI-Piemonte con tencologie opensource, commissionata e finanziata dalla Regione Piemonte.  
  
# Project description 
  
**HR Rilevazione** Sistema di raccolta delle informazioni legate alle attività svolte dal personale delle ASL sul territorio come richiesto dagli indicatori ministeriali

  
# Prerequisites  

## Software  

- [PHP 7.1](https://www.php.net)  
- [PostgreSQL 9.6](https://www.postgresql.org/download/)  
- [Apache 2.4](https://www.apache.org)  
  
  
## Front-end web:  
  
- [PHPRunner](https://xlinesoft.com/phprunner/index.htm)  
  
  
# Configurations  

Il file del progetto è `40.CovidRL.1.1.9.Testing.PROD.phpr`.
Per ragioni di riservatezza sono stati eliminati tutti i riferimenti ai server, agli utenti e alle password del database. 
Tuttavia è necessario inizializzarli per poter aprire il file .phpr con il RAD PHPRunner.

- utilizzando un editor di testo aprire il file `40.CovidRL.1.1.9.Testing.PROD.phpr`
- sostituire `<schemaname>` con il nome dello schema PostgreSQL
- sostituire `<hostname>` con il nome del server del db
- sostituire `<password>` con la password del db
- sostituire `covid_rw` con l'utente dello schema di test/produzione

NOTA. Le sostituzioni devono avvenire nel tag xml e non nelle select.  

- posizionarsi nella cartella `/subversion/PAGES.sqllite`
- sostituire schemaname con il nome dello schema

Una volta effettuate le sostituzioni uscire e salvare il file .phpr.

Avendo precedentemente installato il DB, eseguire doppio click sul file .phpr. 
Si apre un'istanza di PHPRunner con il progetto caricato.

# Installing  

- Dopo aver aperto il progetto con PHPRunner compilare utilizzando il pulsante `build`
- Nella cartella principale viene prodotto un file `<nome progetto>.zip` contentente il codice PHP 'compilato' a partire dal progetto .phpr
- Tramite la funzione `Publish via FPT` traferire il file compilato nella cartella dell'Apache corrispondente al contesto applicativo
- Nel file `config.php` modificare i riferimenti al database introducendo username/password/server di produzione
  
# Versioning  

Per la gestione del codice sorgente può essere utilizzata qualsiasi piattaforma di source versioning (p.es GIT, Subversion, ...).
Per il versionamento del codice sono utilizzate le regole del [Semantic Versioning](http://semver.org/).

