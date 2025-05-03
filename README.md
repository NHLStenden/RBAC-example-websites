# Welkom

Welkom bij de repository voor de oefeningen rondom het thema Identity & Access Management (IAM). In deze handleiding
wordt uitgelegd hoe je de Docker Containers aan de praat kunt krijgen. Onderaan deze handleiding vind je veel
verwijzingen naar websites (**Referenties**), een verantwoording van het tot stand komen van de gegevens en afbeeldingen
en tips hoe je wél een veilige website kunt (laten) bouwen.

**Disclaimer**

De code in dit voorbeeld vormt geen goed voorbeeld voor het opzetten van een veilige website! De focus ligt voornamelijk
op het kunnen spelen met autorisaties op basis van een Role Based Access (RBAC) model met gebruik van permissies.

# Installatie

Een uitgebreide installatiehandleiding kun je [hier](./Install/README.md) vinden.

# Eisen aan de werkplek

De volgende eisen worden gesteld aan de werkplek (jou computer / laptop):

1. Een Command Line Interface zoals Powershell, Xterm, cmd.exe voor het uitvoeren van Docker commando's
2. Een werkende Docker installatie die gebruik kan maken van `docker compose`.
3. Een werkende recente JAVA versie.
    1. Voor Windows: Te downloaden via [Java.com](https://www.java.com/nl/download/)
    2. Debian Linux: `sudo apt install openjdk-21-jre`
    3. MacOS:te installeren via [Oracle](https://www.oracle.com/java/technologies/downloads/)
4. Geïnstalleerde versie van [Apache Directory Studie](https://directory.apache.org/studio/). Zie ook de
   [handleiding](./Install/InstallApacheDirectoryStudio.md).
5. Een recente Browser zoals Google Chrome, Brave, Firefox, Chromium, Vivaldi of Microsoft Edge. Lees voor gebruik
   van Microsoft Edge goed de benodigde aanpassingen in de installatie handleiding van deze repository.

Eventueel handig om te hebben:

* Docker Desktop voor het managen van de containers (maar dit kan ook volledig vanaf de command line).
* Een PDF lezer voor het geval je gegevens exporteert naar PDF
* Een tekst editor die broncode begrijpt zoals [Notepad++](https://notepad-plus-plus.org/) voor het bekijken van
  broncode  (dit kan ook gewoon in Kladblok, Vim, Emacs, nano of online bij Github! )

# Oefeningen

In de map [Assignments](./Assignments) vind je de bestanden om mee te oefenen en de uitwerkingen. Zie hiervoor de
[readme](./Assignments/README.MD).

# Verantwoording

Een toelichting op de gemaakte keuzes kun je vinden in de [verantwoording](./Documentation/Verantwoording.md).

# Authenticatie en Autorisatie

Elke website maakt gebruik van beveiliging. De gebruikte authenticatie en autorisatie flow
wordt [hier](./Documentation/Authentication%20and%20Autorisation.MD) beschreven.

# Colofon

Martin Molema, ing MSc

Docent bij NHL Stenden, opleidingen Bachelor HBO-ICT en Associate Degree Cyber Safety & Security.

[martin.molema@nhlstenden.com](mailto:martin.molema@nhlstenden.com)

April 2025