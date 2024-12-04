# Welkom

Welkom bij de repository voor de oefeningen rondom het thema Identity & Access Management (IAM).

**Disclaimer**
De code in dit voorbeeld vormt geen goed voorbeeld voor het opzetten van een veilige website! De focus ligt voornamelijk
op het kunnen spelen met autorisaties op basis van een Role Based Access (RBAC) model met gebruik van permissies.

# Installatie en activeren

Hier wordt uitgelegd hoe je deze repository kunt installeren en de software kunt starten om de oefeningen uit te voeren.

## Randvoorwaarden

Om deze oefeningen uit te kunnen voeren heb je de volgende zaken nodig:
* Een werkende Docker installatie
* Toegang tot je `hosts`-file voor het toevoegen van hostnames.
* De applicatie Apache Directory Studio

1. Gebruik een GIT-client of download deze repository als een ZIP file.
2. Als je deze repository op je computer hebt staan ga je naar  de map en open je een Command Prompt (bash, cmd, Powershell)
3. Bouw en start de container
4. Voeg in je hosts-file de URL's van de website toe


## Ophalen repository


## Open Command Prompt


## Bouw en start de containers

```cmd
  docker compose up --build
```

Als de containers eenmaal zijn gebouwd, kunnen ze ook eenvoudig beheerd worden via bijvoorbeeld een plug-in in je IDE of 
via de Docker Desktop applicatie (niet beschikbaar op Linux).

## Aanpassingen in de hosts file



# Testen van de websites

De volgende websites zijn beschikbaar:

| Beschrijving                                     | URL                       | Rol in Identity Server                                                                                           | 
|--------------------------------------------------|---------------------------|------------------------------------------------------------------------------------------------------------------| 
| De website voor Marketing:                       | http://marketing.docker/  | `cn=marketing,ou=roles,dc=NHLStenden,dc=com`                                                                     |
| De Cijfer Administratie                          | http://grades.docker/     | `cn=Grades Students,ou=roles,dc=NHLStenden,dc=com` of `cn=Blackboard Teachers,ou=roles,dc=NHLStenden,dc=com`     |
| Het Admin panel van de beheerder                 | http://admin.docker/      | `cn=admins,ou=roles,dc=NHLStenden,dc=com`                                                                        |
| Het SharePoint platform voor gedeelde informatie | http://sharepoint.docker/ | `cn=SharePoint Students,ou=roles,dc=NHLStenden,dc=com` of `cn=SharePoint Teachers,ou=roles,dc=NHLStenden,dc=com` |

Je kunt hierbij inloggen met de volgende gebruikers. Het wachtwoord is altijd  `Test1234!`. Je kunt met Apache Directory Studio
ook kijken in de aangegeven rollen in de Identity Server. De onderstaande gebruikersaccounts zijn willekeurig gekozen uit
die rollen.

* http://marketing.docker/
  * username : `fbos`
* http://grades.docker/
  * student username : `edeboer`  
  * teacher username : `gwillems` 
* http://admin.docker/
  * username: `tvisser`
* http://sharepoint.docker/
  * student username : `edeboer`  
  * teacher username : `gwillems` 

Let op: in Microsoft Edge kan het zijn dat een policy niet langer BasicAuthentication (`basic`) toestaat. Zie https://answers.microsoft.com/en-us/microsoftedge/forum/all/latest-version-of-edge-no-longer-shows-basic/3601252b-e56b-46c0-a088-0f6084eabe47 
en `edge://policy/` (zoek naar AuthSchemes) en check of `basic` daar bij staat. Zo niet, gebruik dan een andere browser 
(Brave, Firefox, Chromium, Opera, Vivaldi of Google Chrome). 

# Aanpassen van autorisaties

Er zijn nu veel autorisaties toegekend. Deze zijn te wijzigen door gebruik te maken van een programma als Apache 
Directory Studio. 

## Verbinding maken met de Identity Server


## Autorisaties aanpassen

## Testen van aangepaste autorisaties



# Verantwoording testgegevens

In deze repository zijn grote hoeveelheden gebruikers opgenomen om te kunnen testen. De reden voor deze grote aantallen is om
ook beter de attestation te kunnen demonstreren. 

De grote aantallen gebruikers zijn tot stand gekomen door middel van generatieve AI (Chat GPT). Daardoor is geen grip  op
de kwaliteit van de namen. Zo zijn er vooral nederlandstalige namen gekozen en is er bijvoorbeeld geen rekening gehouden met
demografische spreiding op geslacht etc.

# Referenties / bronnen

* [Apache Directory Studio](https://directory.apache.org/studio/)
* [Docker Install](https://docs.docker.com/engine/install/)