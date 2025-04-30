# Uitwerkingen

Hier vind je de uitwerkingen van de opgaven.



## Opdracht 1

Door in te loggen op de LDAP-administratie kan gekeken worden naar de structuur van de account administratie.
Het personeel van Marketing is geen onderwijzend personeel dus valt onder Staff/Marketing.

![opdracht 1 - 01.png](images/opdracht%201%20-%2001.png)

De aanname is dat je verwacht dat deze groep gebruikers inderdaad bij de applicatie voor Marketing website hoort. Dat
kun je alleen zeker weten door het aan de programmeur of een website beheer te vragen of in de documentatie te kijken.

In dit geval kun je het afleiden uit de configuratie van de webserver. Kijk op Github op de volgende locatie:

* [marketing.conf](../webserver/conf/marketing.conf) (webserver --> conf --> marketing.conf)

En zoek naar het onderstaande configuratie item:

```apacheconf
 Require ldap-group cn=Marketing,ou=roles,dc=NHLStenden,dc=com
```

Daar staat netjes dat het intranet alleen beschikbaar is voor de genoemde groep. 

## Opdracht 2

Het veld waarmee je probeert in te loggen is het veld *UID*. Dit veld moet uniek zijn in de administratie
maar in het geval van LDAP is daar geen bewaking op. 

Als je in de rest van de opdrachten dus gevraagd wordt om in te loggen dan kun je een gebruiker opzoeken in de
LDAP administratie en kijken naar het veld *UID* en daarmee inloggen.

## Opdracht 3

Als je werkt met toegangsrechten in applicaties dan is het fijn als je niet een webpagina kunt oproepen
waar je toch niks mee mag doen. Dus kun je de navigatie aanpassen op basis van de toegekende toegangsrechten. We
zien dat hier ook duidelijk terugkomen in de navigatiebalk. 

## Opdracht 4

Het is niet verstandig om dergelijke wijzigingen rechtstreeks toe te passen in een identity provider administratie. De
reden hierachter is dat het niet te achterhalen is waarom deze wijziging gedaan is en er is geen opbouw van historische
administratie. We komen hier verder op terug in opdracht 10.

## Opdracht 5 - Ongewenste toegangsrechten - Functiescheiding

Bij opdracht 5 horen de volgende vragen:

1. Wat zou een technische maatregel kunnen zijn om deze ongewenste situaties te voorkomen?
2. Wat zou een organisatorische maatregel kunnen zijn om deze ongewenste situatie te voorkomen?
3. Bij wie zou de verantwoordelijkheid moeten liggen om te zorgen dat dit niet kan gebeuren? Technisch of juist
   organisatorisch?

**Uitwerking vraag 1**

Je kunt proberen om tijdens het wijzigen van rollen en het toewijzen van de permissies per rol dit te 
controleren. Je kunt dan controleren of er niet een rol gemaakt wordt die ongewenste permissies combineert. 

Maar het probleem kan net zo goed ontstaan bij het toewijzen van een rol aan een gebruiker. Je moet dan 
telkens controleren of de tegenstrijdige permissies niet per ongeluk toch toegestaan worden.

**Uitwerking vraag 2**

Je kunt ook organisatorische maatregelen definiëren, maar dit vereist discipline. Bij het maken/wijzigen
van rollen en het toekennen van de rollen aan gebruikers zul je dan steeds met de hand zaken moeten controleren.
De vraag is of degene die dit werk verricht begrijpt hoe dat moet en wat de consequenties zijn bij het toestaan
van de ongewenste situatie. We komen hier in opdracht 8 (Attestation)  verder op terug.

**Uitwerking vraag 3**

Hier zit een *addertje onder het gras*. Want wat is 'verantwoordelijk'? Natuurlijk moet degene die rollen bouwt
en toekent zijn werk goed doen. Als er gevraagd wordt om een autorisatie door te voeren die eigenlijk niet kan/mag,
dan moet deze niet uitgevoerd worden natuurlijk. Echter, als de procedure of inhoud niet duidelijk is (en dat
is zeker een risico bij complexe, _real-life_ situaties) dan is de manager of aanvrager uiteindelijk 'eind verantwoordelijk'.
Om dit beter inzichtelijk te krijgen gebruiken we ook wel het [RACI model](https://nl.wikipedia.org/wiki/RACI-model). 
Hierin wordt onderscheid gemaakt tussen 'Responsible' en 'Accountable'. De uitvoerende partij is 'Responsible'
(verantwoordelijk) en legt verantwoording af aan degene die 'Accountable' (Eindverwantwoordelijk) is.

## Opdracht 6 - Inzicht in rollen - gebruikers

Bij opdracht 6 zijn de volgende vragen geformuleerd.

1. Waarom kan het nuttig zijn om inzicht te krijgen in de toegangsrechten van één gebruiker?
2. Waarom kan het nuttig zijn om in bulk inzicht te krijgen in de toegangsrechten van veel gebruikers?
3. Welke doelgroepen kunnen we hier mee bedienen?

**Uitwerking vraag 1**

Vooral voor een helpdesk kan dit zeer nuttig zijn als 'iets niet werkt' of 'ik kan er niet bij' vragen langskomen.

**Uitwerking vraag 2**

We hebben bij opdracht 5 al gezien dat het lastig kan zijn om te bepalen of er geen ongewenste permissies gecombineerd
worden bij het onderhoud en toekennen van rollen. Maar ook wijzigingen in het personeelsbestand zorgen voor continue
wijzigingen in de benodigde autorisaties. Denk aan

* mensen gaan met pensioen
* er worden nieuwe mensen in dienst genomen
* er worden tijdelijke krachten ingehuurd
* medewerkers krijgen een andere functie
* medewerkers verhuizen naar een andere vestiging
* reorganisaties
* medewerkers stappen over naar een ander team

Bij al deze wijzigingen is het noodzakelijk dat er niet alleen bijgestuurd wordt, maar ook dat er gecontroleerd
wordt of de wijzigingen tijdig en juist doorgevoerd worden. Immers, wijzigingen zoals bovenstaande worden vaak
al tijden van tevoren bekendgemaakt, maar moeten pas doorgevoerd worden nadat ze daadwerkelijk zijn doorgevoerd.

Als een medewerker bijvoorbeeld overstapt naar een nieuw team, dan zal de oude manager willen dat de autorisaties
die bij zijn medewerkers horen voor deze specifieke medewerker ingetrokken worden. De nieuwe manager zal willen
dat de gebruiker tijdig zijn nieuwe autorisaties krijgt om snel aan de slag te gaan.

In de praktijk is er echter vaak sprake van een overgangsperiode waarbij de vertrekkende medewerker zijn/haar opvolger
nog kan ondersteunen en dus nog tijdelijk autorisaties nodig heeft. Dit maakt RBAC juist zo complex!

**Uitwerking vraag 3**

Zoals uit de bovenstaande antwoorden al blijkt kunnen we dus met dergelijke informatie de volgende doelgroepen
ondersteunen

* Managers van medewerkers
* De helpdek

Maar ook externe en interne *auditors* die zicht willen krijgen op de autorisaties van medewerkers.


## Opdracht 7 - Inzicht in rollen - permissies



## Opdracht 8 - Attestation

Bij opdracht 8 zijn de volgende vragen geformuleerd:

1. Stel jij bent beheerder van deze school. Je kunt deze rapportages gebruiken om de organisatie beter te beveiligen.
   Aan wie ga je deze rapportages sturen?
2. Wie zou er in actie moeten komen bij afwijkingen?


Als jij beheerder bent

1. Stel jij bent beheerder van deze school. Je kunt deze rapportages gebruiken om de organisatie beter te beveiligen.
   Aan wie ga je deze rapportages sturen?
2. Wie zou er in actie moeten komen bij afwijkingen?

## # Opdracht 9 - Aanmaken nieuwe rol


## Opdracht 10 - Automatische provisioning van gebruikers

Bij het veranderen van de functie zal het User Provisioning proces netjes de oude lidmaatschappen eerst opruimen, de 
account verplaatsen naar de juiste plaats en dan de juiste default rollen weer toekennen. Ze verliest hierdoor dus bepaalde
rollen die ze mogelijk extra had. We hadden net zo arbitrair kunnen kiezen om de oude rollen te behouden, maar dat strookt 
niet altijd met wat logisch is. Immers vanuit haar rol had ze recht op vooraf gedefinieerde toegang. Eventueel later
toegekende rollen moeten dus eigenlijk opnieuw bekeken worden.

Stel dat je eerst bij HRM werkte en nu docent wordt bij een opleiding. Daarnaast zat je in een HRM-project. Het ligt 
voor de hand dat je de toegang tot specifieke HRM-systemen verliest. Het is onwenselijk dat je bijvoorbeeld als docent
nog bij HRM-systemen kunt voor opvragen en wijzigen van collega's. Echter, geldt dat ook voor het project? Wellicht
is het verstandig dat de projectleider dit opnieuw bekijkt. 

Mogelijk is er ook een overgangsregeling. Dus je blijft nog een tijdje in een dubbel rol: je ondersteunt de collega
die jouw baantje bij HRM overneemt nog een tijdje en daarom moet je nog toegang houden.

Zoals je ziet is het schrijven van een User Provisioning proces nog niet zo eenvoudig. Het begint dan ook vooral bij het
vooraf opstellen van procedures en een exacte definitie van wat je precies wilt!

## Opdracht 11 - Audit trails

Bij opdracht 11 zijn de volgende vragen geformuleerd.

1. Van welke handelingen zou jij zelf een audit trail bij willen houden als het gaat om security, IAM en dergelijke?
   Denk aan rollen, gebruikers, permissies en de relaties daar tussen.
2. De informatie over "wat is er gebeurd met een gebruiker" is behoorlijk verspreid over het systeem. Hoe zou je daar
   toch inzicht in kunnen krijgen? Welk gegeven verbind al deze verspreidde informatie?
3. Wat is het effect op de **gebruiker** die gekoppeld was aan de **medewerker** die verwijderd is?
4. Wat zijn mogelijke redenen dat de gebruiker van de verwijderde medewerker niet weggegooid wordt?
5. Wat zou een redelijke bewaartermijn kunnen zijn van accounts van vertrokken medewerkers?

**Uitwerking vraag 1**

Je kunt het zo uitgebreid of simpel maken als je wilt. Let wel op welke informatie je in het audit-trail opneemt. Er kunnen
persoonsgegevens in het spel zijn die in het kader van de AVG goed afgeschermd moeten worden!

* Rollen
  * toewijzen en intrekken van rollen aan gebruikers
  * wijzigingen in de permissies van een rol
* gebruikers
  * aanmaken nieuwe gebruiker
  * verwijderen gebruiker
  * wachtwoord wijzigen
* foutieve login pogingen
* ophogen teller "aantal foute login pogingen"
* uitschakelen gebruiker (+reden waarom)
* ....

**Uitwerking vraag 2**

Meestal zorg je dat óf het personeelsnummer óf een gebruikersnaam (uid) in elk audittrail zit. Zorg ook voor datum en tijd
zodat je een tijdslijn kunt opbouwen van wat er met een gebruiker gebeurd is.

**Uitwerking vraag 3 + 4**

Vaak blijven zowel medewerkers als gebruikers langer in het systeem dan dat ze in dienst zijn bij de werkgever. Redenen
hiervoor kunnen zijn:

* het niet meteen willen weggooien van allerlei bestanden; een medewerker wil persoonlijke bestanden misschien nog archiveren
  of nog uitzoeken voor de andere collega's.
* afhandeling van salaris en pensioen
* Gebruikers die "per ongeluk" verwijderd zouden worden; stel dat je een procedurele fout maakt, dan wil je niet meteen
  alle bestanden, mail, cloud-opslag, licenties etc. definitief verwijderen. 

Daarom zijn er voor gebruikers die geen toegang meer moeten krijgen in verband met "uit dienst" geblokkeerd op zo'n manier
dat ze niet meer meedoen met User Provisioning (processen) en dergelijke. Soms wordt ook een "datum uit dienst + 30 dagen"
gehanteerd voordat zaken definitief worden opgeruimd.

**Uitwerking vraag 5**

De bewaartermijn hangt heel erg af van de situatie. Gebruikers bestaan vaak in vele systemen en kunnen soms ook helemaal
niet echt verwijderd worden. In verband met wettelijke eisen of audits kan het nodig zijn om gebruikersaccounts nog jaren
te moeten bewaren.



