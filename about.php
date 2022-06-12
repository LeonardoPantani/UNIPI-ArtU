<?php
$title = "📕 Manuale Utente";
$description = "Manuale utente e spiegazione del servizio";
$tags = "user manual, about us";
require_once("config/config.php");
require_once($folder_include . "/functions.php");
require_once($folder_include . "/dbconn.php");
// da qui in poi viene aggiunto output alla pagina HTML...
require_once($folder_include . "/head.php");
require_once($folder_include . "/navbar.php");
?>
<main class="main_content">
    <div class="flex_container">
        <div class="flex_item bgcolor_primary">
            <img src="./<?php echo $folder_media; ?>/logocomplete.png"  alt="<?php echo $service_name; ?> logo"/>
        </div>
    </div>

    <div class="flex_container">
        <div class="flex_item bgcolor_primary color_on_primary textalign_start">
            <h1><?php echo $title; ?></h1>
            <h2>Spiegazione generale</h2>
            <p>
                <b><?php echo $service_name; ?></b> è un sito per artisti (amatoriali e professionisti) volto a permettere a
                questi ultimi di pubblicare le loro creazioni e ricevere feedback dagli altri utenti. Il vantaggio di
                questo servizio è la gamma di contenuti che possono essere pubblicati, come:
                <strong>foto, video, dipinti, musica, testi e poesie</strong>. Questo progetto implementa anche un sistema
                stile community come i "mi piace" / "non mi piace", commenti e amicizie.
            </p>
            <h2>Spiegazione sezioni</h2>
            <h3>🌍 Esplora (<small>Visibile a tutti 🔓</small>)</h3>
            <p>
                Permette di visualizzare i contenuti degli altri membri della community in modo semplice e
                rapido. Contiene un'anteprima del contenuto (se disponibile), il titolo, descrizione e tags. Da qui si
                può: aprire il contenuto e aggiungere like/dislike.
            </p>
            <h3>➕ Crea contenuto (<small>Login richiesto 🔒</small>)</h3>
            <p>
                E' la sezione dalla quale gli utenti registrati aggiungono le proprie creazioni alla piattaforma. I vari
                step (passaggi) vengono mostrati passo passo mentre l'artista compila i campi richiesti.
            </p>
            <h3>🔮 Generatore di idee (<small>Visibile a tutti 🔓</small>)</h3>
            <p>
                Genera una permutazione casuale di concetti (nomi e aggettivi) che possono aiutare l'artista a trovare un'ispirazione.
            </p>
            <!-- -->
            <h3>🧑 Account utente (<small>Login richiesto 🔒</small>)</h3>
            <p>
                Cliccando sulla immagine profilo, si accede al proprio profilo. Al suo interno si può:
            </p>
            <ul>
                <li>Visualizzare i propri amici</li>
                <li>Visualizzare info generali sul proprio profilo</li>
                <li>Visitare e aggiornare la propria pagina pubblica (vedere sotto per altri dettagli)</li>
                <li>Cambiare la propria password</li>
                <li>Cambiare il numero di elementi visibili in una singola pagina nella home</li>
                <li>Cambiare la visibilità della propria pagina</li>
                <li>Eliminare il proprio account</li>
            </ul>
            <!-- -->
            <h3>👥 Amici  (<small>Login richiesto 🔒</small>)</h3>
            <p>
                Si può inviare la richiesta di amicizia ad un utente dalla sua pagina. L'altro potrà accettarla (o
                rifiutarla) dalla 'scheda amici' nel proprio profilo. Gli utenti amici tra di loro potranno vedere la
                pagina profilo dell'altro anche se questi l'ha impostata su 'privata'. Possono anche vedere i contenuti
                impostati su 'privati'.
            </p>
            <h3>💻 Pagina pubblica  (<small>Login richiesto 🔒</small>)</h3>
            <p>
                Ogni utente possiede una pagina pubblica dove può scrivere quello che desidera, rispettando i Termini di
                Servizio. Questa schermata può essere pubblica o privata in base a se l'utente ha scelto
                di mantenerla pubblica o metterla privata. Questa preferenza è specificabile dal proprio profilo. E' possibile
                anche vedere i contenuti pubblici e privati (se si è amici) dell'utente di cui si sta vedendo la pagina.
                Infine, si può decidere di mettere "mi piace" / "non mi piace" per dare feedback all'utente.
            </p>
            <h3>📃 Pagina del contenuto (<small>Visibile a tutti 🔓</small>)</h3>
            <p>
                Visibile cliccando su un contenuto dalla sezione 'esplora'. Mostra maggiori dettagli sulle foto o dipinti,
                e mostra le risorse di altro tipo (come video o testi). Da qui si può vedere le note del contenuto,
                la data di pubblicazione e altre informazioni. E' presente inoltre il tasto 'scarica' che permette di
                fare il download del contenuto attuale.
            </p>
        </div>
    </div>
</main>
<?php require_once($folder_include . "/footer.php"); ?>