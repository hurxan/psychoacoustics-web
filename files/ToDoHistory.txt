# Wep app per dipartimento di psicologia UniPd
attualmente in sviluppo il primo test 

glossario:
ISI: inter-stimulus interval
nAFC: number of Alternative Forced Choice

legenda:
 ✓ : fatto 
 ✗ : non da fare
 - : da fare
 ? : da chiedere

	--     APPUNTI     --

DEADLINES E TO-DO:

- domande prof Grassi
	- la funzione downloadAll non segue molto le norme di privacy
	- gli utenti devono poter essere informati su come vengono trattati i loro dati
	- norme GDPR e utilizzo dei cookie senza consenso (rischio multa)
	
-cose da fare con i tecnici
	- psychoacousticsWEB nell'url
	- capchta, serve https
	- settare smtp per inviare le email
	- copy to clipboard di userSettings richiede https (togliere overflow:scroll dopo aver risolto)

- nuove features (in ordine)
	- nuove feature per Mattia
		- sistemare click -> Mattia
		- test con rumore per amp e dur -> Mattia

	- roba facile e veloce
		✓ '.' come separatore dei decimali nei csv
		✓ cambio immagine homepage
		✓ etichette a tutti i valori (ricontrollare se vanno bene)
		✓ db->dB negli errori
		? link al manuale nella home page (da aggiornare dopo le modifiche)
		- ITI (interTrialInterval) va messo nel csv

	- nuove feature in ordine di importanza
		- save settings funziona anche senza fare il test
		- feedback scelto da chi manda il link -> va aggiunto al db
		✓ il feedback deve cancellarsi dopo un certo tempo <feedbackduration> di 0.5 secondi
		✓ nuovo suono parte dopo un certo tempo <interTrialInterval> di 1 secondo
		✓ reversal threshold solo per calcolo della soglia
		✓ con il link si mandano anche i settings salvati nell'account
			✓ aggiungere controllo in sound settings validation: tutti i dati devono essere uguali a quelli nel test $_SESSION['test'] (serve nel caso qualcuno togliesse il 'disabled' con ispeziona elemento)
		✓ quando si usa un link dopo demographicData esce una pagina di spiegazioni e non quella dei settings (c'è comunque la spunta feedback?)
		✓ demographicData: quando si è loggati escono i campi solo se c'è il referral
		✓ pulsante per mettere il proprio referral
		✓ responsive

	- altro da fare il prima possibile
		- lista errori/limiti dei valori nel manuale

- migliorie per il futuro
	- modificare la fase del suono
	- suoni suonabili componibili
	- migiorare la sicurezza
		- dati di accesso al db criptati
		- controlli sui dati passati con get
		- blocco accesso alle pagine tramite copia-incolla dell'url
	- save test settings funziona anche senza finire il test (si crea un apposito test dummy nel db che serve solo a quello)
	- possibilità di cancellare i test da yourTests
	- possibilità di scaricare un singolo test da yourTests
	- possibilità di togliere i SU (chi può farlo? Gli SU stessi? Se io metto un SU poi lui può togliermi. In molti ambiti funziona così)
	- pulsante "set test" in user settings per modificare le impostazioni del test mandato con il link/invite code

- documentazione entro il 10/06/2022
	- Diagramma architetturale del sistema
	- guida/documentazione
	- documento dei requisiti
	- tesi

- feedback testing:
	✓ file csv codifica utf 8
	✓ test ampiezza, varAmp diventa positivo (>0)
	✓ typo nella home "gues"
	✓ alert nella frequenza
	✓ something went wrong a fine test quando non registrati
	✓ se registrato e metti i dati da errore php
	✓ link referral "localhost" invece di "psychoacoustics.dpg.psy.unipd.it/sito"
	✓ "referral" invece di "invite link" in demographic data
	✓ registrato + demographic data in test della durata non ha salvato il test (non lo vede in your tests)
	✓ "some characters aren't allowed" anche con '
	✓ save data si può fare solo se sei loggato
	✓ il save sound settings sul delta è un problema da un test all'altro, meglio lasciarlo di default
	✓ "download data" non "datas"
	✓ Nel file dati csv breve sostituire "score" con "threshold"
	✓ typo in Pure tone intensity discrimination: L'etichetta di Delta è "db" va sostituita con "dB"
	✓ controllo su tutti e tre che nAFC non puo' essere minore di 2
	✓ A un certo punto "Pure tone intensity discrimination" e' crashato (provare a sbagliare risposta fin dall'inizio)
	✗ se uno sbaglia la prova con primo valore di delta, il programma si ferma e manda un pop-up "Are you sure you set a convenient Delta?" (nuova feature, facciamo se c'è tempo)
	✓ nel csv colonna "correct?" invertire i valori: 1==correct 0==incorrect
	✓ salva i dati in modo errato (scrive il delta dopo averlo modificato, quindi di fatto in corrispondenza di un trial scrive il delta successivo)
	✓ test fatto con link non ha salvato i dati (ho provato e a me (Andrea) funziona)
	? amptest: il suono variabile è sempre in prima posizione (risistemare quando mandiamo il sito al bando)
	✓ salva i dati degli ultimi <revarsal threshold> reversals (togliere reversal threshold)
	✓ risposta corretta/sbagliata da scrivere come 1(corretta) e 0(sbagliata)
	✓ link con referral non valido da errore 500 (bisogna gestire l'errore)
	✓ in soundSettings "Set the characteristics of the standard tone" nel primo blocco, poi "Set the characteristics of the experiment" e "Set the characteristics of the staircase"	
	✓ se si fa un test togliendo la spunta a "save data" esce l'errore something went wrong nella home

- test amp e freq
	✓ rivedere amp iniziale
	✓ inserire doppio factor (per freq)		
		✓ fattore <factor> usato per i primi <reversals> reversals (per freq)
		✓ fattore <second factor> usato per i successivi <second reversals> reversals (per freq)
		✓ totale reversals = <reversals> + <second reversals> (per freq)
		✓ reversals da salvare nel db = <revelsals threshold> (per freq)
	✓ limiti factor: il primo maggiore del secondo, il secondo maggiore di uno
		✓ 2 e sqrt(2) come placeholder
	✓ il parametro duration funziona (per freq)

- test durata + 2d1u
	✓ rivedere distanza tra i due suoni nel test sulla durata

- db (login) 30/04/2022
 	✓ revisionare schema er 
    	✓ implementare
    	✓ riempire
	✓ cambiare l'identificativo di test: usare fk_guest + un seriale (n-esimo test di un certo guest) 
	✓ età in guest deve essere sostituita con data di nascita (solo quando creiamo account?)
	✓ feedback se checkbox spuntata 
	✓ dati salvati solo se spunto la checkbox "save results"
	✓ aggiungere email al db 
	✓ inviamo solo gli ultimi reversals oppure tutti e poi ci pensano gli analisti 
	✓ salvataggio di blocco, trial, delta, posizioneVariabile, tastoPremuto, rispostaCorretta, reversal (in questo ordine)
	
- poi php entro 30/04/2022
	✓ quando un utente si registra va creato un nuovo account (e va controllato che l'username non esista già)
		✓ controlli js sugli input (required data + sql injections)
	✓ passaggio dati in sicurezza (demographicData, soundSettings, tipo di test)
	✓ richiedere al professore una spiegazione specifica su cosa dovrá fare chi ha l'account (invita gli altri in che modo e cosa ne facciamo dei dati)
	
	✓ salvataggio dei dati dei guest che hanno creato un account
	 
	✗ informativa sulla privacy?
	✓ quando registri va creato anche un guest, quindi vanno chieste tutte le info
	✓ quando un utente registrato fa un test va aggiunto il risultato del test e va collegato al guest dell'utente			
	✓ quando un guest fa un test va creato il guest e va aggiunto il risultato del test e va collegato al guest
	✓ quando un utente accede va controllato che esista e va creata una sessione
			
- captcha	entro 15/05/2022
	✓ inserire controllo login o sign in per non mettere il nome
	✓ come oscurare i dati di php username e psw
	✓ scrivi su file e i file php leggono il file
	✓ implementare struttura a tre livelli
	✓ creare la pagina per i risultati immediati del test
	✓ implementare download dati come CSV
	✓ creare pagina per lo storico dei test
	✓ modificare il php per inserimento dati

- modifiche al db (e quindi al sito) 	entro 22/05/2022?
	✓ account ha un type
		✓ corretto inserimento nel registering
		✓ super user deve poter creare altri super user
		✓ super user deve poter scaricare i dati di tutti i test nel db
		✓ i guest non collegati non possono scaricare i dati come csv
		✓ download di tutti i vecchi test dell'account da yourTests.php
	✓ account ha un referral
		✓ creazione nuovo referral
		✓ creazione link (demographicData.php?ref=####)
		✓ demographicData ha un input box per il referral
		✓ demographicData prende il valore del referral direttamente dal link, se c'è
	✓ guest ha una fk_account
		✓ se non si è fatto il log in:
			✓ se non c'è il nome: errore
				✓ gestione messaggio di errore
			✓ se c'è il nome:
				✓ se non c'è il referral: crea guest con fk_account nulla
				✓ se c'è il referral: crea guest collegato all'account del referral tramite fk_account
		✓se si è fatto il log in:
			✓ se non c'è il nome:
				✓ se non c'è il referral: il test si collega al guest dell'account loggato
				✓ se c'è il referral: errore
					✓ gestione messaggio di errore
			✓ se c'è il nome:
				✓ se non c'è il referral: crea guest collegato all'account loggato tramite fk_account
				✓ se c'è il referral: crea guest collegato all'account del referral tramite fk_account
	✓ account ha un test collegato dal quale prende i settings
		✓ checkbox "save settings" se è stato fatto il log in
	✓ account ha una data di nascita e il suo guest ha età nulla
		✓ sistemare registering.php e i vari download (la colonna età)

- altro da fare 	entro 22/05/2022?
	✓ nAFC funzionante (faccio nAFC-1 volte playStd e 1 playVar)
	✓ n. of blocks funzionante (ripeto n volte il test)
		✓ testare number of blocks 
	✓ nel risultato esce la media delle medie dei valori del delta prima e dopo gli ultimi <threshold> reversal
	✓ aggiungere download di un file ridotto con solo dati anagrafici, dati del test, blocco e risultato (ossia la media delle medie) (una riga per blocco)
	✓ gestione eccezioni nelle interazioni col db (un try catch per ogni $conn->query($sql);)
	✓ utilizzo sequence.next per simulare l'autoincrement (al posto di SELECT max(ID), serve per evitare collisioni)
	✓ fix degli altri download + svuotamento db (ora che i dati vengono salvati in modo diverso)
	✓ menù a tendina nel referral per scegliere il tipo di test
	✓ delta in db per test ampiezza
	✓ aggiungere isi (ordine: blocks-nafc-isi-delta e feedback insieme agli algoritmi)
	✓ feedback stampato a schermo senza alert
	✓ logo (ne ho abbozzato uno)
	✓ dopo ogni blocco esce il risultato e il tasto "continue"

- plus se riusciamo entro il 10/06/2022
	✗ Vedere se si puó modificare la fase del suono
	? Funzione (esponenziale fino a 1?) che permette di non avere brutte sensazioni a inizio e fine suono 
	✓ Predisporre piú di due suoni alla volta ( un suono viene ripetuto piú volte e l'altro una volta sola )
	? Recupero dati utente e psw ( difficile serve mailing list, quando ci si registra bisogna inserire email )
	✓ premi '1' e '2' da tastiera oltre ai pulsanti a schermo
		✓ limite nAFC: 9
	✓ Pagina per cambiare dati e impostazioni (nome, cognome, username, password), per creare altri SU (se tu sei SU) e per vedere e modificare referral e link
		✓ salvataggio dati da completare
	
- grafica entro il 10/06/2022
	✓ alert di errore di inserimento dati (migliorare)
	✓ tradurre tutto in inglese in ogni pagina
	✓ title delle pagine
	✓ responsive

- domande tecnici:
	✓ non funziona forticlient
	✓ come cambiare la landing page (creato un file che fa il redirect automatico)
	✓ come inviare i file 
	✓ é possibile fare in modo che le modifiche che faccio nella repository github vengano fatte anche sul server?
	✓ dati per recapchta : email futuro proprietario, nome dominio, non posso farlo ora perché servono questi dati e un collegamento https
	✓ download file da server (in locale funziona)
