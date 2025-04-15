# Oefeningen

Als de Docker containers draaien is het tijd om een aantal opdrachten en oefeningen uit te voeren. Deze worden in
dit document verder uitgewerkt. De oplossingen / uitwerkingen staan aan het einde van het document.

Als je met onderstaande opdrachten aan de slag gaat dan kun je onderstaande tabel gebruiken voor de initiële login
gegevens.

| URL                       | rol       | username   |
|---------------------------|-----------|------------|
| http://marketing.docker/  | gebruiker | `fbos`     | 
| http://grades.docker/     | student   | `edeboer`  |
| http://grades.docker/     | teacher   | `gwillems` |
| http://admin.docker/      | admin     | `tvisser`  |
| http://sharepoint.docker/ | student   | `edeboer`  |
| http://sharepoint.docker/ | teacher   | `gwillems` |
| http://hrm.docker/        | HRM       | `kmulder`  |

Er verschijnt een popup waar je moet inloggen. Het wachtwoord is altijd `Test1234!`.

Later zul je ook op een andere manier gaan inloggen. De instructies daarvoor volgen in de opdrachten.

## Opdrachten

De volgende opdrachten en oefeningen zijn opgenomen:

1. In kaart brengen van beveiliging
2. Inzicht in account administratie
3. Accounts gebruiken om in te loggen
4. Handmatige aanpassing van toegangsrechten
5. Ongewenste toegangsrechten
6. Inzicht in rollen - gebruikers
7. Inzicht in rollen - permissies
8. Attestation
8. Aanmaken nieuwe rol
9. Aanmaken nieuwe gebruiker in LDAP
10. Automatische provisioning van gebruikers

# Opdracht 1 - In kaart brengen van beveiliging

De eerste opdracht betreft het in kaart brengen van de huidige stand van zaken over toegangsbeveiliging.

## Instructie

Er zijn enkele websites gemaakt die steeds bestemd zijn voor een ander doel. Zo is er een website voor marketing
campagnes,
een website voor de cijferadministratie en een soort Intranet pagina waar informatie gedeeld kan worden met medewerkers
en studenten.

## Opdracht

Login bij elke website en noteer welke mogelijkheden de gebruiker heeft. Gebruik onderstaande tabel voor de juiste
accounts. Vul bij 'Toegang' in welke functionele mogelijkheden je ziet (bijvoorbeeld in de menubalk).

| URL                       | rol       | username   | Toegang |
|---------------------------|-----------|------------|---------| 
| http://marketing.docker/  | gebruiker | `fbos`     |         | 
| http://grades.docker/     | student   | `edeboer`  |         |
| http://grades.docker/     | teacher   | `gwillems` |         |
| http://admin.docker/      | admin     | `tvisser`  |         |
| http://sharepoint.docker/ | student   | `edeboer`  |         |
| http://sharepoint.docker/ | teacher   | `gwillems` |         |

## Vragen

1. Is het verstandig of juist onverstandig vanuit een _security perspective_ om te werken met zoveel verschillende
   websites? Zou je niet liever zoveel mogelijk zaken combineren? Motiveer je antwoord.

# Opdracht 2 - Inzicht in account administratie

In deze opdracht gaan we in de account administratie kijken naar meer informatie. Deze is opgenomen in een zogenaamde
*LDAP-store*.

## Instructie

Start nu een LDAP-management tool zoals de _Apache Directory Studio_. Zie de [readme.MD](./README.md) voor instructies.

## Opdracht

Navigeer in de _Directory Information Tree_ (**DIT**) naar onderstaande locatie:

    dc=NHLStenden,dc=com > ou=Opleidingen > ou=Teachers

De zogenaamde **Distinguished Name** van deze locatie is

    ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com

Je ziet hier een aantal gebruikersaccounts. Zoek de account *'Guus Willems'* en klik hier op. Je krijgt de eigenschappen
te zien.

![opdracht 2 - 02 user details gwillems.png](images/excersises/opdracht%202%20-%2002%20user%20details%20gwillems.png)

## Vragen

1. Welke eigenschap van het account heb je gebruikt om mee in te loggen?

## Opdracht 3 - Accounts gebruiken om in te loggen

## Instructie

Zoek nu een andere _student_ of _teacher_ op in deze lijst met accounts. De studenten vind je op

    dc=NHLStenden,dc=com > ou=Opleidingen > ou=Teachers

## Opdracht

Log in bij de website [http://grades.docker/](http://grades.docker/) met diverse rollen (student, teacher).

## Vragen

## Opdracht 4 - Handmatige aanpassing van toegangsrechten

## Introductie

Kijk in de LDAP-administratie bij de administratie van de opleidingen. Er zijn twee opleidingen:

* HBO-ICT
* AD CS&S

Elke opleiding is opgedeeld in twee soorten gebruikers:

* Student
* Teacher

Er zijn veel manieren om je gebruikers in te delen in deze (LDAP-) administratie. In deze situatie is gekozen om voor
elke
combinatie van *opleiding* en *soort gebruiker* een **rol** aan te maken. Dat levert dus vier rollen op:

* Student bij HBO-ICT
* Student bij AD CS&S
* Teacher bij HBO-ICT
* Teacher bij AD CS&S

Deze vind je op de locatie

> cn=Teachers ADCSS,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com

en

> cn=Teachers HBO-ICT,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com

Er zijn twee docenten die bij beide opleidingen lesgeven. Dit zijn onderstaande docenten.

* Isabel Dekker
* Liam de Koning

Het is dus mogelijk om meerdere rollen toe te wijzen aan één account (persoon). Dit is vaak in de echte wereld ook
gebruikelijk.

## Opdracht

1. Verwijder de rol "Teacher HBO-ICT" bij Isabel Dekker.
    1. Ga naar "cn=Teachers HBO-ICT,ou=opleidingen,ou=roles,dc=NHLStenden,dc=com"
    2. Open het attribuut  "Unique Members"
    3. Selecteer "cn=Isabel Dekker,ou=Teachers,ou=Opleidingen,dc=NHLStenden,dc=com"
    4. verwijder deze (Rechter muisknop 'delete *value*') of de delete-knop op je toetsenbord
2. Ververs de pagina in de website. Wat is er veranderd?

We hebben nu dus in de administratie van de accounts een rol handmatig ingetrokken.

## Vragen

1. Is het verstandig om dergelijke aanpassingen handmatig te doen?
2. Wat zou de bron moeten zijn voor deze informatie?
3. In welke situatie zou een handmatige actie nodig kunnen zijn?

# Opdracht 5 - Ongewenste toegangsrechten

## Introductie

## Instructie

## Opdracht

## Vragen

# Opdracht 6 - Inzicht in rollen - gebruikers

## Introductie

De applicatie Apache Directory Studio helpt niet goed bij het inzichtelijk krijgen van de toegangsrechten van een
gebruiker. We kunnen opzoeken welke gebruikers in een groep zitten, en dan vanuit de groepen zo een beeld opbouwen.

Mocht je dus willen nazoeken welke rollen een gebruiker uiteindelijk allemaal heeft, dan is dat een flinke uitzoek klus.

## Instructie

We gaan eerst kijken naar welke rechten één gebruiker heeft. Dit kan op twee manieren:

1. we gaan naar http://sharepoint.docker/intranet/my-data.php om onze eigen gegevens in te zien
2. de administrator kan via http://admin.docker/intranet een gebruiker opzoeken

## Opdracht

1. Login met een normale gebruiker op "SharePoint" en navigeer naar de "Mijn gegevens" pagina
2. Login met een admin-gebruiker op het Admin Panel en zoek een gebruiker op via de knop 'Zoeken...'

## Vragen

1. Waarom kan het nuttig zijn om inzicht te krijgen in de toegangsrechten van één gebruiker?
2. Waarom kan het nuttig zijn om in bulk inzicht te krijgen in de toegangsrechten van veel gebruikers?

# Opdracht 7 - Inzicht in rollen - permissies

## Introductie

Als een centrale oplossing zoals LDAP of Active Directory wordt gebruikt om **rollen** te administreren, dan zal een
applicatie
zelf moeten bepalen welke permissies er voor die applicatie gelden. Dit _kan_ ook in een centrale oplossing maar in ons
voorbeeld is dat geregeld in een database.

De admin-pagina stelt ons in staat om de permissies bij een applicatie te veranderen.

## Opdracht

1. Navigeer naar http://admin.docker/intranet en login met een admin gebruiker
2. Gebruik de knop 'Rollen' om naar de administratie van de rollen te gaan. Er volgt een lijst met beschikbare rollen.
3. Klik op de knop 'Edit' achter de rol "All Students". Je kunt nu permissies toevoegen aan deze rol.
4. Selecteer uit de lijst achter 'Permissie' het item 'Use Admin Panel' en druk op de knop Toevoegen.
5. Klik op de knop 'Uitloggen' rechtsboven; je krijgt nu een popup om in te loggen. Sluit deze (Annuleren) en sluit het
   tabblad.
6. Open een nieuw tabblad en navigeer naar http://sharepoint.docker/intranet/my-data.php
7. Login met een student account
8. Wat valt op? Welke functionaliteit hebben we nu onbedoeld beschikbaar gesteld?

## Vragen

Momenteel zijn de volgende permissies beschikbaar:

| Permissie                  | Titel                            |
|----------------------------|----------------------------------|
| SharePoint_Basic_Access    | Basic Access to SharePoint       |
| Grades_Basic_Access        | Basic Access to Grades app       |
| Marketing_Basic_Access     | Basic Access to Marketing app    |
| Use_Mail                   | Use college e-mail               |
| AdminPanel                 | Use Admin Panel                  |
| SharePoint_News            | Read news on SharePoint/Intranet |
| SharePoint_HRM             | Go to Human Resource Management  |
| SharePoint_StudentTools    | Open student tools               |
| SharePoint_TeacherTools    | Open teacher's tools             |
| Grades_Create_Gradelists   | Create a new list of grades      |
| Grades_Approve_Gradeslist  | Approve a list of grades         |
| Grades_Read_Own_Grades     | Student can read own grades      |
| Grades_Read_StudentDetails | Get information on all students  |
| Grades_Show_Self           | Show students own information    |
| Marketing_Create_Campaign  | Create a new marketing campaign  |
| Marketing_Read_Campaign    | Read a marketing campaign        |
| Marketing_Delete_Campaign  | Delete a marketing campaign      |
| Marketing_Update_Campaign  | Update a marketing campaign      |
| Marketing_Approve_Campaign | Approve a marketing campaign     |
| HRM_Manage_Employees       | Manage Employees                 |

1. Welke permissies zijn er beschikbaar voor het admin panel?
2. Welke problemen kunnen hierdoor ontstaan? Kijk naar het aantal functionaliteiten.
3. Wat zou kunnen helpen om dit probleem op te lossen?

# Opdracht 8 - Attestation

## Introductie

Uit de twee vorige opdracht wordt wel duidelijk dat het belangrijk is om inzicht te hebben in toegangsrechten. Dat valt
uiteen in twee onderdelen:

1. Hoe rollen leiden tot permissies in applicaties
2. Hoe rollen gebruikt worden door gebruikers

Dat inzicht kan niet alleen nuttig maar ook noodzakelijk zijn. Zo kan een externe auditor vragen om hier inzicht in te
geven.

## Instructie

1. Login bij het admin panel
2. Gebruik de menu knop 'Attestation - Rollen'.
2. Maak een PDF van dit bestand (Afdrukken en dan opslaan als PDF)
3. Gebruik de knop 'Download' bovenaan om een export te maken; sla deze op
4. Gebruik de menu knop 'Attestation - Gebruikers'.
5. Maak een PDF van dit bestand (Afdrukken en dan opslaan als PDF)
6. Gebruik de knop 'Download' bovenaan om een export te maken; sla deze op

We hebben nu een momentopname ('snapshot') van de huidige rollen en het gebruik er van.

We willen nu inzicht krijgen in mogelijke afwijkingen. Dat kan door de filters en sorteermogelijkheden op de web pagina
te gebruiken of de geëxporteerde lijst te importeren in Excel en daar te filteren en te sorteren.

## Opdracht

1. Ga op zoek naar afwijkingen. Kijk bijvoorbeeld of een student geen rechten heeft die er niet thuishoren.
2. Maak bewust afwijkingen via het scherm "Rollen": voeg bijvoorbeeld "Teacher" of "Marketing" rechten toe.
3. Ga op zoek naar de afwijkingen

## Vragen

1. Stel jij bent beheerder van deze school. Je kunt deze rapportages gebruiken om de organisatie beter te beveiligen.
   Aan wie ga je deze rapportages sturen?
2. Wie zou er in actie moeten komen bij afwijkingen?

# Reflectie

# Opdracht 9 - Aanmaken nieuwe rol

Hier gaan we onderzoeken hoe we een nieuwe rol kunnen maken om deze in te zetten voor ons personeel.

## Introductie

Stel er is een nieuw team opgezet dat marketing campagnes moet monitoren op voortgang. We noemen dit team de
"Campagne Monitors".

## Opdracht

1. Maak via LDAP een nieuwe "GroupOfUniqueNames" aan in de DN `ou=roles,dc=NHLStenden,dc=com`.
2. Gebruik de naam "Campagne Monitors" voor de CN.
3. Login bij het Admin Panel
4. Ga naar 'Rollen' en start de synchronisatie.
    1. Als het goed is wordt de nieuwe rol uit LDAP overgenomen in de database
5. Ga terug naar de rollen
6. Zoek de regel in de tabel met 'Campagne Monitors' op en druk op de `Edit` knop om deze te wijzigen
7. Voeg de permissie 'Read a Marketing Campaign' toe

Nu gaan we een gebruiker autoriseren voor deze rol

1. Login bij het Admin Panel
2. Ga naar 'Autorisatie Aanvraag'
3. Kies de nieuwe rol 'Campagne Monitors' bij de rol
4. Kies een stafmedewerker (niet een medewerker die al bij de afdeling Marketing zit!) bijv. Sophie Bakker van HRM
5. Druk op Autoriseer

We gaan nu kijken of de nieuwe medewerker ook daadwerkelijk naar de Marketing Website mag

1. Ga naar de SharePoint website
2. Login als de gebruiker die je net geautoriseerd hebt
3. Zie dat er in de menu balk nu een button staat voor Marketing
4. Druk op de button Marketing
5. Login in bij de website voor Marketing (let op: als je al ingelogd was eerder moet je mogelijk daar eerst uitloggen).

Het blijkt dat we mogelijk nog niet de juiste autorisaties hebben.

1. Waarom lukt het maar niet om in te loggen op de marketing website? Welke autorisatie mist er (zie de tabel bij
   opdracht 7)
2. Ken de missende autorisaties toe aan de rol 'Campagne Monitors'
3. Probeer opnieuw in te loggen.

Nog steeds zal het niet lukken om in te loggen op de website van Marketing. Dit heeft te maken met de beveiliging op
applicatie
niveau. Om gebruik te maken van de website moet je de rol 'Marketing' hebben. Het is dus niet genoeg om allerlei
Marketing-*permissies*
te hebben. Je moet ook de rol "Marketing" hebben.

Echter aan de rol van Marketing zitten de nodige permissies waardoor je meteen ook bijna alles mag. Deze rol is dus
eigenlijk veel
te ruim opgezet: als je de rol Marketing heb mag je meteen teveel. We kunnen minder autorisaties in die rol opnemen en
een nieuwe
rol maken voor de standaard permissies van een echte Marketing medewerker. Echter, dan moeten we wel meteen alle
gebruikers
toewijzen aan die nieuwe rol.

# Opdracht 10 - Gebruiker aanmaken in LDAP

## Introductie

## Instructie

## Opdracht

## Vragen

----------------------------------------------------------------------------

# Opdracht 11 - Automatische provisioning van gebruikers

## Instructie

Het proces van "User Provisioning" zorgt voor het overbrengen van identiteitsinformatie van bronsystemen naar
doelsystemen.
In ons voorbeeld gaat het om het overbrengen van medewerkersinformatie naar de LDAP-administratie.

## Verdieping

Lees de achtergronden rondom automatische User Provisioning: [User Provisioning](./User%20Provisioning.MD).

Er is een achtergrond proces gemaakt dat elke 3 minuten een synchronisatie doet tussen de HRM-database en de LDAP-store.

## Opdracht

Voor deze opdracht moet je iets meer weten over hoe je kunt nagaan of een gebruiker lid is van een bepaalde groep.
Hieronder vind je meer informatie:

De rollen zijn ondergebracht op de volgende locatie: `ou=roles,dc=NHLStenden,dc=com`

| Rol                                             | Distinguished Name                                   |
|-------------------------------------------------|------------------------------------------------------|
| ICT Administrators                              | cn=admins,ou=roles,dc=NHLStenden,dc=com              | 
| Al het personeel                                | cn=All Personell,ou=roles,dc=NHLStenden,dc=com       | 
| Alle studenten                                  | cn=All Students,ou=roles,dc=NHLStenden,dc=com        | 
| Alle docenten                                   | cn=All Teachers,ou=roles,dc=NHLStenden,dc=com        | 
| Toegang tot de cijfer administratie als student | cn=Grades Students,ou=roles,dc=NHLStenden,dc=com     | 
| Toegang tot de cijfer administratie als docent  | cn=Grades Teachers,ou=roles,dc=NHLStenden,dc=com     | 
| Human Resource Management                       | cn=HRM,ou=roles,dc=NHLStenden,dc=com                 | 
| Medewerkers ICT Support                         | cn=ICT Support,ou=roles,dc=NHLStenden,dc=com         | 
| Medewerkers Marketing                           | cn=Marketing,ou=roles,dc=NHLStenden,dc=com           | 
| Managers van de afdeling Marketing              | cn=Marketing managers,ou=roles,dc=NHLStenden,dc=com  | 
| Toegang tot sharepoint voor studenten           | cn=SharePoint Students,ou=roles,dc=NHLStenden,dc=com | 
| Toegang tot sharepoint voor docenten            | cn=SharePoint Teachers,ou=roles,dc=NHLStenden,dc=com | 

1. Login op de HRM website
2. Kijk welke medewerkers er zijn. Wijzig de functie van een medewerker. Onthoudt even de gebruikersnaam.
3. Wacht op de synchronisatie. Je kunt dit volgen via een logfile in de map `{ROOT}/volumes/src/user-provisioning`.
4. Kijk in de LDAP-store naar de medewerker.
    1. staat de gebruiker nog op de juiste plek?
    2. heeft de gebruiker de juiste rechten (groepslidmaatschappen, zie tabel hier boven) ?
5. Kijk via het Admin-panel naar de eigenschappen van de gebruiker (via de button `Zoeken`).
6. Lukt het nu om in te loggen op de website die bij deze rol hoort? 

# Verwerking van kennis in de eigen werkomgeving ("Weekopdracht")

We hebben de theorie behandeld en via de oefeningen inzicht gekregen in de werking van Role Based Access Control op
basis van permissies. Ga op onderzoek uit en reflecteer op de volgende vragen en neem deze op in je portfolio.

## Betreffende User Provisioning

1. Onderzoek hoe het proces van "Hire to Retire" impact heeft op de gebruikersadministratie. Dit kan veel werk zijn,
   maar verdiep eens op één belangrijke applicatie in jouw bedrijf.
    1. gaat het vanzelf? moet je lang wachten? hoe kom je aan je wachtwoord(en)?
    2. wanneer en hoe krijgt een nieuwe medewerker accounts (let op: meervoud! Er zijn vast vele applicaties)
    3. Krijgt een nieuwe medewerker meteen de juiste rechten ("permissies" of "rollen")?
2. Is er beleid (of procedures) geformuleerd rondom toegang, RBAC, aanmaken accounts? Is HR bijvoorbeeld betrokken? Geef
   een korte
   samenvatting.
    1. Zo niet, wat zou jouw advies zijn? Probeer het zo concreet mogelijk te maken.
3. Is er een User Provisioning proces? Geef een korte samenvatting eventueel ondersteund met een diagram.
    1. Is dat proces (in enige mate) geautomatiseerd?
    2. Zijn er bron- en doelsystemen gedefinieerd?
    3. Wat is de frequentie van het proces ("hoe vaak draait het")
    3. Wat zijn de regels om informatie uit bronsystemen naar doelsystemen te transformeren? Gebruik bijvoorbeeld
       één bron- en doelsysteem
4. Welke applicaties regelen hun eigen account-administratie?
5. Indien er geen User Provisioning proces is:
    1. Welke mogelijke bronsystemen zouden ingezet kunnen worden?
    2. Welke informatie in het bronsysteem is bruikbaar voor RBAC?
    3. Welke doelsystemen zouden aangemerkt kunnen worden voor User Provisioning?
    4. Welke regels stel je voor als je kijkt naar de transformatie van bron- naar doelsystemen.

## Governance van toegang

1. Worden managers (afdeling/projecten) periodiek op de hoogte gehouden van de stand van zaken ("Attestation")?
    1. Welke procedure
2. Wat is jouw advies aan de organisatie op het gebied van een centrale opslag van accounts en gebruik in applicaties?

## Over applicaties

1. Kijk eens naar een applicatie waar toegang voor geregeld moet worden.
    1. Is er een toegangsbeheerder aangesteld die deze aanvragen regelt
    2. In hoeverre kan de beheerder zelf nieuwe permissies (géén rollen) instellen als er bijvoorbeeld een nieuw team
       opgericht wordt dat afwijkende autorisaties/toegang nodig heeft?

In je verslag neem je de lezer mee in de context van je verkenning. Je beschrijft kort het bedrijf en de betrokken
afdelingen. Als je experts spreekt dan is het voldoende om de rol binnen de organisatie te benoemen en eventueel kort
toe te lichten. Onderbouw je antwoorden met argumenten uit de aangeboden theorie. Een simpel antwoord "Ja er is ..." is
niet voldoende. Geef dan een korte beschrijving of samenvatting, eventueel ondersteund met een diagram. 

Betrek daar waar mogelijk de bedrijfscontext in je verslag: veel rondom RBAC en User Provisioning moet passen bij hoe
er binnen het bedrijf gewerkt wordt, de mate van verandering van de samenstelling van het personeel (instroom , 
doorstroom, uitstroom), financiële middelen, uitbesteding van ICT-taken etcetera. 